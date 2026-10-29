<?php

namespace Modules\Hospital\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class DepartmentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', $this->unique('name')],
            'description' => ['nullable', 'string', 'max:2000'],
            'ordering' => ['required', 'integer', 'between:0,65535'],
        ];
    }
}
