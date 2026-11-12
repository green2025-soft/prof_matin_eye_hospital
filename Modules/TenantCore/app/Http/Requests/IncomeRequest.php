<?php

namespace Modules\TenantCore\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class IncomeRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function rules(): array
    {
        return [
            'head_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('income_heads')],
            'payment_type_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('payment_types')],
            'entry_date' => ['required', 'date_format:Y-m-d'],
            'amount' => ['required', 'string', 'decimal:0,2', 'between:0.01,9999999999.99'],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
