<?php

namespace Modules\TenantCore\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class BranchRequest extends BaseRequest
{
    protected array $rules = [
        'name' => ['required', 'string', 'max:255'],
        'address' => ['required', 'string', 'max:2000'],
        'mobile_no' => ['nullable', 'string', 'max:30'],
        'telephone_no' => ['nullable', 'string', 'max:30'],
        'email' => ['nullable', 'string', 'email', 'max:255'],
        'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,gif', 'extensions:png,jpg,jpeg,webp,gif', 'max:2048'],
        'logo_path' => ['missing'],
    ];

    public function payload(): array
    {
        return $this->safe()->except('logo');
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if (is_string($this->input('email'))) {
            $this->merge(['email' => normalizeEmail($this->input('email'))]);
        }
    }
}
