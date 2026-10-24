<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Modules\Core\Models\Role;

class RolePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = $this->route('role');

        return $role instanceof Role && $role->exists && Gate::allows('managePermissions', $role);
    }

    public function rules(): array
    {
        return [
            'permissions_present' => ['required', 'in:1'],
            'permissions' => ['present', 'array', 'max:500'],
            'permissions.*' => ['required', 'integer', 'min:1', 'distinct'],
        ];
    }

    public function validationData(): array
    {
        return parent::validationData() + ['permissions' => []];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $data = $validator->getData();
            $exists = ValidatorFacade::make(['permissions' => $data['permissions']], [
                'permissions' => [Rule::exists($this->user('web')->getConnection()->getName().'.permissions', 'id')->where('guard_name', 'web')],
            ]);

            if ($exists->fails()) {
                $validator->errors()->add('permissions', 'Select permissions from the current application.');
            }
        }];
    }
}
