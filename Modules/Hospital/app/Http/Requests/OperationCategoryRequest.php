<?php

namespace Modules\Hospital\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class OperationCategoryRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', $this->unique('name')],
            'ordering' => ['required', 'integer', 'between:0,65535'],
        ];
    }
}
