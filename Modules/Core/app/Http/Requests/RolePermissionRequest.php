<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Core\Models\Role;

class RolePermissionRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        $role = $this->record();

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
}
