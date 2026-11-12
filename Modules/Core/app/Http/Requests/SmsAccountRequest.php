<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Modules\Core\Models\SmsAccount;
use Modules\Core\Models\Tenant;

class SmsAccountRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        return Gate::allows('manageAccounts', $this->record() ?? SmsAccount::class);
    }

    public function rules(): array
    {
        $rules = [
            'tenant_id' => $this->onCreate(['required', 'integer', Rule::exists(Tenant::class, 'id')], ['missing']),
            'enabled' => ['required', 'boolean'],
            'rate' => ['required', 'string', 'decimal:0,2', 'between:0,9999999999.99'],
            'monthly_limit' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'allowed_features' => ['sometimes', 'array'],
            'allowed_features.*' => ['string', 'distinct', Rule::in(array_keys(config('settings.sms_features', [])))],
            'gateway_enabled' => ['required', 'boolean'],
            'gateway' => ['nullable', 'array:api_url,method,body_format,sender_id,api_key,username,password,headers,parameters,success_field,success_value,failure_values'],
            'gateway.api_url' => ['required_if:gateway_enabled,1', 'nullable', 'url:http,https', 'max:2048'],
            'gateway.method' => ['required_if:gateway_enabled,1', Rule::in(['GET', 'POST'])],
            'gateway.body_format' => ['required_if:gateway_enabled,1', Rule::in(['json', 'form'])],
            'gateway.sender_id' => ['nullable', 'string', 'max:100'],
            'gateway.success_field' => ['nullable', 'string', 'max:255'],
            'gateway.success_value' => ['required_with:gateway.success_field', 'nullable', 'string', 'max:255'],
            'gateway.failure_values' => ['nullable', 'string', 'max:1000'],
        ];

        foreach (config('settings.credentials.sms') as $field => $definition) {
            $rules['gateway.'.$field] = ['bail', 'nullable', 'string', 'max:'.$definition['max']];
            if ($definition['json'] ?? false) {
                $rules['gateway.'.$field][] = 'json';
            }
        }

        $rules['gateway.headers'][] = function (string $attribute, string $value, \Closure $fail): void {
            $headers = json_decode($value, true);
            if (! is_array($headers) || ! str_starts_with(ltrim($value), '{')) {
                $fail('Enter a JSON object for headers.');

                return;
            }
            foreach ($headers as $name => $header) {
                if (! preg_match('/\A[!#$%&\x27*+.^_`|~0-9a-z-]+\z/i', (string) $name) || ! is_string($header) || strpbrk($header, "\r\n") !== false) {
                    $fail('Use valid header names and single-line string values.');

                    return;
                }
            }
        };
        $rules['gateway.parameters'][] = function (string $attribute, string $value, \Closure $fail): void {
            $parameters = json_decode($value, true);
            if (! is_array($parameters) || ! str_starts_with(ltrim($value), '{')) {
                $fail('Enter a JSON object for parameters.');

                return;
            }
            $text = implode("\n", array_filter(Arr::flatten($parameters), 'is_string'));
            if (! str_contains($text, '{to}') || ! str_contains($text, '{message}')) {
                $fail('Parameter values must include {to} and {message}.');
            }
        };

        return $rules;
    }

    public function payload(): array
    {
        $values = $this->validated();
        $values['enabled'] = $this->boolean('enabled');
        $values['gateway_enabled'] = $this->boolean('gateway_enabled');
        $values['gateway'] = $values['gateway'] ?? [];

        return $values;
    }
}
