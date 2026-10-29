<?php

namespace Modules\TenantCore\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class DivisionRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', $this->unique('name')],
            'bn_name' => ['required', 'string', 'max:255'],
        ];
    }
}
