<?php

namespace Modules\Core\Http\Requests;

class AccountRequest extends BaseRequest
{
    protected array $checkboxes = ['roles' => 'roles_present'];

    protected array $fieldAbilities = ['roles' => 'assignRoles'];

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $this->unique('email')],
            'password' => [$this->onCreate('required', 'nullable'), 'string', 'min:12', 'max:4096', 'confirmed'],
            'password_confirmation' => [$this->onCreate('required', 'nullable'), 'string', 'max:4096'],
            'roles_present' => ['required_with:roles', 'in:1'],
            'roles' => ['sometimes', 'array', 'max:50', $this->existsIn('roles')->where('guard_name', 'web')],
            'roles.*' => ['required', 'integer', 'min:1', 'distinct'],
        ];
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if ($this->exists('email')) {
            $this->merge(['email' => normalizeEmail($this->input('email'))]);
        }
    }
}
