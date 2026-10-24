<?php

namespace Modules\Core\Http\Requests;

use Modules\Core\Models\Role;

class RoleRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:5', 'max:255', $this->unique('name')->where('guard_name', 'web'), 'not_regex:/\A'.preg_quote(Role::OWNER_NAME, '/').'\z/i'],
            'guard_name' => ['missing'],
            'permissions_present' => ['missing'],
            'permissions' => ['missing'],
        ];
    }
}
