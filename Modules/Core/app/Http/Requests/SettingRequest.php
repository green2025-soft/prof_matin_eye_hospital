<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Money;
use Nwidart\Modules\Facades\Module;

class SettingRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        return parent::authorize()
            && ! in_array($this->record()->group, config('settings.tenant_only_groups'), true);
    }

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
        $credentials = config('settings.credentials.'.$setting->group, []);
        $configured = $setting->configuredCredentials();
        $enabled = in_array($this->input('data.enabled'), [1, '1', true], true);
        $rules = [
            'logo' => $setting->group === 'branding'
                ? [
                    'nullable', 'image',
                    'mimes:png,jpg,jpeg,webp,gif',
                    'extensions:png,jpg,jpeg,webp,gif',
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
        ] + Arr::prependKeysWith($fields, 'data.');

        if ($setting->group === 'general') {
            $moduleRules = $setting->getConnection()->getName() === 'central'
                ? ['missing']
                : ['sometimes', 'array:'.implode(',', array_keys(Module::getOrdered('desc')))];

            $rules['data.module_order'] = $moduleRules;
            $rules['data.module_order.*'] = ['nullable', 'integer', 'between:0,65535'];
            $rules['data.module_names'] = $moduleRules;
            $rules['data.module_names.*'] = ['nullable', 'string', 'max:100'];
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
}
