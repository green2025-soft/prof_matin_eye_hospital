<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;

class SettingRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        return parent::authorize() && $this->record()->group === 'hospital';
    }

    public function rules(): array
    {
        $sections = config('settings.hospital_sections');
        $fields = Arr::only(config('settings.rules.hospital'), $sections[$this->hospitalSection()]);
        $rules = [
            'group' => ['missing'],
            'section' => ['required', Rule::in(array_keys($sections))],
            'secrets' => ['missing'],
            'credentials' => ['missing'],
            'logo_path' => ['missing'],
            'favicon_path' => ['missing'],
            'logo' => ['missing'],
            'favicon' => ['missing'],
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
        $switches = [
            'consultation_serial_enabled',
            'consultation_invoice_enabled',
            'reference_defaults_enabled',
            'reference_percentages_enabled',
            'reference_consultation_percent_enabled',
            'reference_investigation_percent_enabled',
            'reference_operation_percent_enabled',
            'consultation_reference_enabled',
            'investigation_reference_enabled',
            'operation_reference_enabled',
        ];

        // Normalize posted switches without changing values in another section.
        foreach ($switches as $field) {
            if (array_key_exists($field, $values['data'])) {
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
