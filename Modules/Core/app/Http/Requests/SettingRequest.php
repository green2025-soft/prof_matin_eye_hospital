<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Money;

class SettingRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    protected function getRedirectUrl(): string
    {
        $group = $this->record()->group;
        $isPayment = in_array($group, config('settings.payment_groups'), true);

        return route(str_replace('.update', '.index', $this->route()->getName()), [
            'group' => $isPayment ? 'payments' : $group,
            'provider' => $isPayment ? $group : null,
            'section' => $group === 'hospital' ? $this->hospitalSection() : null,
        ]);
    }

    public function payload(): array
    {
        $values = $this->safe()->only(['data', 'credentials']);
        $group = $this->record()->group;
        if (in_array($group, config('settings.payment_groups'), true)) {
            $values['data']['enabled'] = $this->boolean('data.enabled');
            $values['data']['extra_charge'] = Money::decimal(Money::minor($values['data']['extra_charge']));

            if ($group === 'manual') {
                $values['data']['instructions'] = $values['data']['instructions'] ?? '';
            } else {
                $values['data']['sandbox'] = $this->boolean('data.sandbox');
            }
        } elseif ($group === 'billing') {
            $values['data']['currency'] = strtoupper($values['data']['currency']);
            $values['data']['invoice_prefix'] = strtoupper($values['data']['invoice_prefix']);
        } elseif ($group === 'company' && array_key_exists('email', $values['data'])) {
            $values['data']['email'] = normalizeEmail($values['data']['email']) ?? '';
        } elseif ($group === 'hospital') {
            foreach (['consultation_serial_enabled', 'consultation_invoice_enabled', 'reference_defaults_enabled', 'reference_percentages_enabled', 'reference_consultation_percent_enabled', 'reference_investigation_percent_enabled', 'reference_operation_percent_enabled', 'consultation_reference_enabled', 'investigation_reference_enabled', 'operation_reference_enabled'] as $field) {
                if (array_key_exists($field, $values['data'])) {
                    $values['data'][$field] = $this->boolean('data.'.$field);
                }
            }
        } elseif (in_array($group, ['email', 'sms'], true)) {
            $values['data']['enabled'] = $this->boolean('data.enabled');
            $values['data'] = array_map(fn ($value) => $value ?? '', $values['data']);

            if ($group === 'email') {
                $values['data']['port'] = (string) $values['data']['port'];
                $values['data']['from_address'] = normalizeEmail($values['data']['from_address'] ?? '') ?? '';
            }
        }

        return $values;
    }

    public function rules(): array
    {
        $setting = $this->record();

        $fields = config('settings.rules.'.$setting->group);
        if ($setting->group === 'hospital') {
            $fields = Arr::only($fields, config('settings.hospital_sections')[$this->hospitalSection()]);
        }
        $credentials = config('settings.credentials.'.$setting->group, []);
        $configured = $setting->configuredCredentials();
        $enabled = in_array($this->input('data.enabled'), [1, '1', true], true);
        $rules = [
            'group' => ['missing'],
            'section' => $setting->group === 'hospital' ? ['required', Rule::in(array_keys(config('settings.hospital_sections')))] : ['missing'],
            'secrets' => ['missing'],
            'logo_path' => ['missing'],
            'favicon_path' => ['missing'],
            'logo' => $setting->group === 'branding'
                ? [
                    'nullable', 'image',
                    'mimes:png,jpg,jpeg,webp,gif',
                    'extensions:png,jpg,jpeg,webp,gif',
                    'mimetypes:image/png,image/jpeg,image/webp,image/gif',
                    'max:2048',
                ]
                : ['missing'],
            'favicon' => $setting->group === 'branding'
                ? [
                    'nullable', 'file',
                    'mimes:png,ico', 'extensions:png,ico',
                    'mimetypes:image/png,image/x-icon,image/vnd.microsoft.icon',
                    'max:512',
                ]
                : ['missing'],
            'data' => ['required', 'array:'.implode(',', array_keys($fields))],
            'credentials' => $credentials === [] ? ['missing'] : ['sometimes', 'array:'.implode(',', array_keys($credentials))],
        ];

        foreach ($fields as $field => $fieldRules) {
            $rules['data.'.$field] = $fieldRules;
        }

        if ($setting->group === 'hospital') {
            foreach (['patient_id', 'patient_branch_id', 'doctor_id', 'doctor_branch_id', 'reference_id', 'reference_branch_id', 'appointment_serial', 'appointment_invoice', 'consultation_serial', 'consultation_invoice', 'investigation_invoice', 'operation_invoice', 'refund_invoice', 'due_collection_invoice'] as $prefix) {
                if (! isset($rules['data.'.$prefix.'_pattern'])) {
                    continue;
                }
                $rules['data.'.$prefix.'_pattern'][] = function (string $attribute, mixed $value, \Closure $fail) use ($prefix): void {
                    $this->validateHospitalNumberPattern($prefix, $value, $fail);
                };
            }
        }

        foreach ($credentials as $field => $definition) {
            $rules['credentials.'.$field] = ['bail', Rule::requiredIf($enabled && ! ($definition['optional'] ?? false) && ! ($configured[$field] ?? false)), 'nullable', 'string', 'max:'.$definition['max']];

            if (isset($definition['min'])) {
                $rules['credentials.'.$field][] = 'min:'.$definition['min'];
            }

            if ($definition['json'] ?? false) {
                $rules['credentials.'.$field][] = function (string $attribute, mixed $value, \Closure $fail) use ($field): void {
                    $parameters = json_decode($value, true);

                    if (! is_array($parameters) || ! str_starts_with(ltrim($value), '{')) {
                        $fail('Enter a valid JSON object.');
                    } elseif ($field === 'headers') {
                        foreach ($parameters as $name => $header) {
                            if (! preg_match('/\A[!#$%&\x27*+.^_`|~0-9a-z-]+\z/i', (string) $name) || ! is_string($header) || strpbrk($header, "\r\n") !== false) {
                                $fail('Headers must use valid header names and single-line string values.');
                                break;
                            }
                        }
                    } else {
                        $text = implode("\n", array_filter(Arr::flatten($parameters), 'is_string'));

                        if (! str_contains($text, '{to}') || ! str_contains($text, '{message}')) {
                            $fail('Parameter values must include {to} and {message} placeholders.');
                        }
                    }
                };
            }
        }

        return $rules;
    }

    private function hospitalSection(): string
    {
        $section = $this->input('section', 'patient');

        return is_string($section) && array_key_exists($section, config('settings.hospital_sections')) ? $section : 'patient';
    }

    private function validateHospitalNumberPattern(string $prefix, string $pattern, \Closure $fail): void
    {
        $identityPattern = in_array($prefix, ['patient_id', 'patient_branch_id', 'doctor_id', 'doctor_branch_id', 'reference_id', 'reference_branch_id'], true);
        $identityLabel = match (true) {
            str_starts_with($prefix, 'doctor') => 'Doctor',
            str_starts_with($prefix, 'reference') => 'Reference',
            default => 'Patient',
        };
        if (! preg_match('/\A(?:[\p{L}\p{N} ._\/-]|\{(?:YYYY|MM|DD|BRANCH|DOCTOR|SEQ(?::(?:[1-9]|1[0-2]))?)\})+\z/u', $pattern)) {
            $fail('Use letters, numbers, spaces, . _ / -, and supported numbering tokens only.');

            return;
        }

        if (preg_match_all('/\{SEQ(?::(?:[1-9]|1[0-2]))?\}/', $pattern) !== 1) {
            $fail('Include exactly one {SEQ} or {SEQ:1} to {SEQ:12} token.');

            return;
        }

        if ($identityPattern && str_contains($pattern, '{DOCTOR}')) {
            $fail($identityLabel.' ID patterns use {SEQ}, not a Doctor token.');

            return;
        }
        if (in_array($prefix, ['patient_branch_id', 'doctor_branch_id', 'reference_branch_id'], true) && ! str_contains($pattern, '{BRANCH}')) {
            $fail('Include {BRANCH} in the branch '.$identityLabel.' ID pattern.');

            return;
        }

        $rendered = str_replace(
            ['{YYYY}', '{MM}', '{DD}', '{BRANCH}', '{DOCTOR}'],
            ['9999', '99', '99', str_repeat('9', 20), str_repeat('9', 20)],
            $pattern,
        );
        $rendered = preg_replace('/\{SEQ(?::(?:[1-9]|1[0-2]))?\}/', str_repeat('9', 12), $rendered);
        $maximumLength = $identityPattern ? 40 : 120;
        if (mb_strlen($rendered) > $maximumLength) {
            $fail('The generated number must fit within '.$maximumLength.' characters. Shorten the pattern.');

            return;
        }

        if (! str_ends_with($prefix, '_invoice')
            || ($prefix === 'consultation_invoice' && ! $this->boolean('data.consultation_invoice_enabled'))) {
            return;
        }

        $requiredTokens = match ($this->input('data.'.$prefix.'_reset')) {
            'year' => ['{YYYY}'],
            'month' => ['{YYYY}', '{MM}'],
            'day' => ['{YYYY}', '{MM}', '{DD}'],
            default => [],
        };
        if ($this->input('data.'.$prefix.'_scope') === 'branch') {
            $requiredTokens[] = '{BRANCH}';
        }
        $missingTokens = array_filter($requiredTokens, fn (string $token): bool => ! str_contains($pattern, $token));
        if ($missingTokens !== []) {
            $fail('Include '.implode(', ', $missingTokens).' in the invoice pattern for the selected scope and reset.');
        }
    }
}
