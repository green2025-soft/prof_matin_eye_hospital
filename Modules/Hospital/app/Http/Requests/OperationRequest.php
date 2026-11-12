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
            'single_rate' => ['required', 'string', 'decimal:0,2', 'between:0,9999999999.99'],
            'both_rate' => ['required', 'string', 'decimal:0,2', 'between:0,9999999999.99'],
            'commission_type' => ['required', 'string', Rule::in(array_keys(commissionTypes()))],
            'commission_percent' => ['required', 'decimal:0,2', 'between:0,100'],
            'commission_amount' => ['required', 'string', 'decimal:0,2', 'between:0,9999999999.99'],
            'ordering' => ['required', 'integer', 'between:0,65535'],
        ];
    }
}
