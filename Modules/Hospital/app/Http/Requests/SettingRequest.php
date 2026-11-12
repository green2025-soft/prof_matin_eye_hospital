<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Core\Models\SmsAccount;

class SettingRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        return parent::authorize() && $this->record()->group === 'hospital'
            && ($this->input('section') !== 'sms'
                || array_intersect_key(SmsAccount::availableFeatures(), config('settings.hospital_sms_notifications')) !== []);
    }

    public function rules(): array
    {
        $sections = config('settings.hospital_sections');
        $section = $this->hospitalSection();
        $sectionFields = $sections[$section];

        if ($section === 'sms') {
            $notifications = Arr::only(config('settings.hospital_sms_notifications'), array_keys(SmsAccount::availableFeatures()));
            $sectionFields = array_merge(array_column($notifications, 'enabled'), array_column($notifications, 'template'));
        }

        $fields = Arr::only(config('settings.rules.hospital'), $sectionFields);
        $rules = [
            'section' => ['required', Rule::in(array_keys($sections))],
            'data' => ['required', 'array:'.implode(',', array_keys($fields))],
        ] + Arr::prependKeysWith($fields, 'data.');

        $numberingPrefixes = [
            'patient_id', 'patient_branch_id',
            'doctor_id', 'doctor_branch_id',
            'reference_id', 'reference_branch_id',
            'appointment_serial', 'appointment_invoice',
            'consultation_serial', 'consultation_invoice',
            'investigation_invoice', 'operation_invoice',
            'refund_invoice', 'due_collection_invoice',
        ];

        foreach ($numberingPrefixes as $prefix) {
            if (! isset($rules['data.'.$prefix.'_pattern'])) {
                continue;
            }

            $rules['data.'.$prefix.'_pattern'][] = function (string $attribute, mixed $value, \Closure $fail) use ($prefix): void {
                $this->validateHospitalNumberPattern($prefix, $value, $fail);
            };
        }

        return $rules;
    }

    public function payload(): array
    {
        $values = $this->safe()->only(['data']);
        $defaults = config('settings.defaults.hospital');

        // Normalize posted switches without changing values in another section.
        foreach ($values['data'] as $field => $value) {
            if (is_bool($defaults[$field])) {
                $values['data'][$field] = $this->boolean('data.'.$field);
            }
        }

        return $values;
    }

    protected function getRedirectUrl(): string
    {
        return route('hospital.settings.index', [
            'section' => $this->hospitalSection(),
        ]);
    }

    private function hospitalSection(): string
    {
        $section = $this->input('section', 'patient');

        return is_string($section) && array_key_exists($section, config('settings.hospital_sections')) ? $section : 'patient';
    }

    private function validateHospitalNumberPattern(string $prefix, string $pattern, \Closure $fail): void
    {
        $identityPattern = in_array($prefix, ['patient_id', 'patient_branch_id', 'doctor_id', 'doctor_branch_id', 'reference_id', 'reference_branch_id'], true);
        if (! preg_match('/\A(?:[\p{L}\p{N} ._\/-]|\{(?:YYYY|MM|DD|BRANCH|DOCTOR|SEQ(?::(?:[1-9]|1[0-2]))?)\})+\z/u', $pattern)) {
            $fail('Use letters, numbers, spaces, . _ / -, and supported numbering tokens only.');

            return;
        }

        $rendered = strtr($pattern, [
            '{YYYY}' => '9999',
            '{MM}' => '99',
            '{DD}' => '99',
            '{BRANCH}' => str_repeat('9', 20),
            '{DOCTOR}' => str_repeat('9', 20),
        ]);
        $rendered = preg_replace('/\{SEQ(?::(?:[1-9]|1[0-2]))?\}/', str_repeat('9', 12), $rendered, -1, $sequenceCount);
        if ($sequenceCount !== 1) {
            $fail('Include exactly one {SEQ} or {SEQ:1} to {SEQ:12} token.');

            return;
        }

        if ($identityPattern && str_contains($pattern, '{DOCTOR}')) {
            $fail('ID patterns use {SEQ}, not a Doctor token.');

            return;
        }
        if (in_array($prefix, ['patient_branch_id', 'doctor_branch_id', 'reference_branch_id'], true) && ! str_contains($pattern, '{BRANCH}')) {
            $fail('Include {BRANCH} in the branch ID pattern.');

            return;
        }

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
