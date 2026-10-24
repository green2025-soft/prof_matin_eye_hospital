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

        $rules['password_confirmation'] = $this->onCreate(['required', 'string', 'max:4096']);
        $rules['database'] = ['prohibited'];
        $rules['database_token'] = ['prohibited'];
        $rules['status'] = $this->onCreate(['prohibited'], ['sometimes', 'string', Rule::in(array_keys(($tenant ?? new Tenant)->statusOptions()))]);

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->merge(TenantRules::normalize($this->only(['name', 'slug', 'domains', 'owner_name', 'owner_email'])));
    }
}
