<?php

namespace Modules\Core\Http\Requests;

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
        }

        return $values;
    }

    public function rules(): array
    {
        $setting = $this->record();

        $fields = config('settings.rules.'.$setting->group);
        $credentials = config('settings.credentials.'.$setting->group, []);
        $configured = $setting->configuredCredentials();
        $enabled = in_array($this->input('data.enabled'), [1, '1', true], true);
        $rules = [
            'group' => ['missing'],
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

        foreach ($credentials as $field => $definition) {
            $rules['credentials.'.$field] = [Rule::requiredIf($enabled && ! ($configured[$field] ?? false)), 'nullable', 'string', 'max:'.$definition['max']];

            if (isset($definition['min'])) {
                $rules['credentials.'.$field][] = 'min:'.$definition['min'];
            }
        }

        return $rules;
    }
}
