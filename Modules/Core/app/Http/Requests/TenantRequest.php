<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Models\Tenant;
use Modules\Core\Validation\TenantRules;

class TenantRequest extends BaseRequest
{
    public function rules(): array
    {
        $tenant = $this->record();
        $rules = TenantRules::metadata($tenant);
        $createRules = TenantRules::owner() + TenantRules::credentials();
        $createRules['password'][] = 'confirmed';

        foreach ($createRules as $field => $fieldRules) {
            $rules[$field] = $this->onCreate($fieldRules);
        }

        $rules['password_confirmation'] = $this->onCreate(['required', 'string']);
        $rules['logo'] = TenantRules::logo();
        $rules['logo_path'] = ['prohibited'];
        $rules['database'] = ['prohibited'];
        $rules['database_username'] = ['prohibited'];
        $rules['database_password'] = ['prohibited'];
        $rules['database_token'] = ['prohibited'];
        $rules['status'] = $this->onCreate(['prohibited'], ['sometimes', 'string', Rule::in(array_keys(($tenant ?? new Tenant)->statusOptions()))]);

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $isUpdate = $this->isUpdate();
        $values = TenantRules::normalize($this->only(['name', 'slug', 'domains', 'owner_name', 'owner_email', 'address', 'phone']));
        $credentials = $this->input('credentials');

        if (is_array($credentials)) {
            $credentials['database'] = is_string($credentials['database'] ?? null) ? trim($credentials['database']) : null;
            $credentials['username'] = is_string($credentials['username'] ?? null) ? trim($credentials['username']) : null;
            $values['credentials'] = $credentials;
        }

        if (! $isUpdate) {
            $name = is_string($values['name'] ?? null) ? $values['name'] : '';
            $values['slug'] = TenantRules::availableSlug($name);

            if (is_array($values['domains'] ?? null)
                && ($this->boolean('domains_auto') || $values['domains'] === [TenantRules::defaultDomain(TenantRules::slugFromName($name))])) {
                $values['domains'][0] = TenantRules::defaultDomain($values['slug']);
            }
        }

        $domains = $values['domains'] ?? [];
        $slug = $values['slug'] ?? ($isUpdate ? $this->record()?->slug : null);
        if ($slug !== null && (! $isUpdate || array_key_exists('domains', $values)) && is_array($domains)
            && array_filter($domains, fn ($domain): bool => $domain !== null && $domain !== '') === []) {
            $values['domains'] = [TenantRules::defaultDomain($slug)];
        }

        $this->merge($values);
    }
}
