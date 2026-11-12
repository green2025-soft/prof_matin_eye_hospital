<?php

namespace Modules\Pharmacy\Http\Requests;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Money;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Core\Models\SmsAccount;

class SettingRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        return parent::authorize() && $this->record()->group === 'pharmacy'
            && ($this->input('section') !== 'sms'
                || array_intersect(array_column(config('settings.pharmacy_sms_notifications'), 'feature'), array_keys(SmsAccount::availableFeatures())) !== []);
    }

    public function rules(): array
    {
        $section = $this->pharmacySection();
        $sections = config('settings.pharmacy_sections');
        $sectionFields = $sections[$section];

        if ($section === 'sms') {
            $smsFeatures = SmsAccount::availableFeatures();
            $notifications = array_filter(config('settings.pharmacy_sms_notifications'), fn (array $notification): bool => isset($smsFeatures[$notification['feature']]));
            $sectionFields = array_merge(array_column($notifications, 'enabled'), array_column($notifications, 'template'));
        }

        $fields = Arr::only(config('settings.rules.pharmacy'), $sectionFields);

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

        if ($section === 'numbering') {
            foreach (array_keys($fields) as $field) {
                $rules['data.'.$field][] = function (string $attribute, mixed $value, Closure $fail) use ($field): void {
                    $this->validatePharmacyNumberPattern($value, $field === 'payment_invoice_pattern' ? 40 : 80, $fail);
                };
            }
        }

        return $rules;
    }

    public function payload(): array
    {
        $values = $this->validated('data');
        $defaults = config('settings.defaults.pharmacy');

        // Only this submitted section changes; unposted switches retain their saved values.
        foreach ($values as $field => $value) {
            if (is_bool($defaults[$field])) {
                $values[$field] = $this->boolean('data.'.$field);
            }
        }

        if (array_key_exists('vat_percent', $values)) {
            $values['vat_percent'] = Money::decimal(Money::minor((string) $values['vat_percent']));
        }

        return ['data' => $values];
    }

    protected function getRedirectUrl(): string
    {
        return route('pharmacy.settings.index', ['section' => $this->pharmacySection()]);
    }

    private function pharmacySection(): string
    {
        $section = $this->input('section', 'general');

        return is_string($section) && array_key_exists($section, config('settings.pharmacy_sections')) ? $section : 'general';
    }

    private function validatePharmacyNumberPattern(string $pattern, int $maximumLength, Closure $fail): void
    {
        if (! preg_match('/\A(?:[\p{L}\p{N} ._\/-]|\{(?:YYYY|MM|DD|BRANCH|PRODUCT|SEQ(?::(?:[1-9]|1[0-2]))?)\})+\z/u', $pattern)) {
            $fail('Use letters, numbers, spaces, . _ / -, and supported numbering tokens only.');

            return;
        }
        if (preg_match_all('/\{SEQ(?::(?:[1-9]|1[0-2]))?\}/', $pattern) !== 1) {
            $fail('Include exactly one {SEQ} or {SEQ:1} to {SEQ:12} token.');

            return;
        }
        if (! str_contains($pattern, '{BRANCH}')) {
            $fail('Include {BRANCH} in the invoice pattern.');

            return;
        }

        $rendered = str_replace(
            ['{YYYY}', '{MM}', '{DD}', '{BRANCH}', '{PRODUCT}'],
            ['9999', '99', '99', str_repeat('9', 20), 'MED'],
            $pattern,
        );
        $rendered = preg_replace('/\{SEQ(?::(?:[1-9]|1[0-2]))?\}/', str_repeat('9', 12), $rendered);

        if (mb_strlen($rendered) > $maximumLength) {
            $fail('The generated number must fit within '.$maximumLength.' characters. Shorten the pattern.');
        }
    }
}
