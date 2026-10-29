<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;

class OperationRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', $this->unique('name')],
            'category_id' => ['required', 'integer', 'min:1', $this->existsIn('operation_categories')],
            'single_rate' => ['required', 'string', 'regex:/\A\d{1,10}(?:\.\d{1,2})?\z/'],
            'both_rate' => ['required', 'string', 'regex:/\A\d{1,10}(?:\.\d{1,2})?\z/'],
            'commission_type' => ['required', 'string', Rule::in(array_keys(commissionTypes()))],
            'commission_percent' => ['required', 'numeric', 'regex:/\A\d{1,3}(?:\.\d{1,2})?\z/', 'between:0,100'],
            'commission_amount' => ['required', 'string', 'regex:/\A\d{1,10}(?:\.\d{1,2})?\z/'],
            'ordering' => ['required', 'integer', 'between:0,65535'],
        ];
    }
}
