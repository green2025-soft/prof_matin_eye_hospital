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
        $ownerRules = TenantRules::owner();
        $ownerRules['password'][] = 'confirmed';

        foreach ($ownerRules as $field => $fieldRules) {
            $rules[$field] = $this->onCreate($fieldRules);
        }

        $rules['password_confirmation'] = $this->onCreate(['required', 'string', 'min:'.config('auth.password_min_length', 5), 'max:4096']);
        $rules['logo'] = TenantRules::logo();
        $rules['logo_path'] = ['prohibited'];
        $rules['database'] = ['prohibited'];
        $rules['database_token'] = ['prohibited'];
        $rules['status'] = $this->onCreate(['prohibited'], ['sometimes', 'string', Rule::in(array_keys(($tenant ?? new Tenant)->statusOptions()))]);

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $isUpdate = $this->isUpdate();
        $values = TenantRules::normalize($this->only(['name', 'slug', 'domains', 'owner_name', 'owner_email', 'address', 'phone']));

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
