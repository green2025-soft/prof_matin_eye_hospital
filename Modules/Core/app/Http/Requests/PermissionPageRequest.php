<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Modules\Core\Models\Role;

class PermissionPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('viewPermissions', Role::class);
    }

    public function rules(): array
    {
        return [
            'role' => ['bail', 'nullable', 'integer', 'min:1', Rule::exists($this->user('web')->getConnection()->getName().'.roles', 'id')->where('guard_name', 'web')],
            'role_search' => ['nullable', 'string', 'max:80'],
        ];
    }
}
