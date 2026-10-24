<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Core\Models\Role;

class PermissionPageRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return Gate::allows('viewPermissions', Role::class);
    }

    public function rules(): array
    {
        return [
            'role' => ['bail', 'nullable', 'integer', 'min:1', $this->existsIn('roles')->where('guard_name', 'web')],
            'role_search' => ['nullable', 'string', 'max:80'],
        ];
    }
}
