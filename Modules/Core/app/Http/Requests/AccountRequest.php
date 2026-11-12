<?php

namespace Modules\Core\Http\Requests;

use Modules\Core\Models\Tenant;

class AccountRequest extends BaseRequest
{
    protected array $checkboxes = ['roles' => 'roles_present'];

    protected array $fieldAbilities = ['roles' => 'assignRoles'];

    public function rules(): array
    {
        $tenantAccount = $this->attributes->get('application_provider') === 'users';
        $hasBranches = $tenantAccount && (bool) Tenant::current()?->has_branches;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', $this->unique('email')],
            'password' => [$this->onCreate('required', 'nullable'), 'string', 'min:'.config('auth.password_min_length', 5), 'max:4096', 'confirmed'],
            'password_confirmation' => [$this->onCreate('required', 'nullable'), 'string'],
            'roles_present' => ['required_with:roles', 'in:1'],
            'roles' => ['sometimes', 'array', 'max:50', $this->existsIn('roles')->where('guard_name', 'web')],
            'roles.*' => ['required', 'integer', 'min:1', 'distinct'],
            'branch_id' => $hasBranches ? ['nullable', 'integer', 'min:1', $this->existsIn('branches')->where('is_default', false)] : ['missing'],
            'redirect_url' => $tenantAccount ? ['nullable', 'string', 'max:2048', 'regex:~\A/(?!/)[^\s\p{C}\p{Z}\x5c]*\z~u'] : ['missing'],
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
