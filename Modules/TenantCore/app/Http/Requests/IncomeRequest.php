<?php

namespace Modules\TenantCore\Http\Requests;

use Illuminate\Validation\Validator;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\TenantCore\Models\IncomeHead;

class IncomeRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*' => ['required', 'array:head_id,amount'],
            'items.*.head_id' => ['bail', 'required', 'integer', 'min:1'],
            'items.*.amount' => ['required', 'string', 'decimal:0,2', 'between:0.01,9999999999.99'],
            'payment_type_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('payment_types')],
            'entry_date' => ['required', 'date_format:Y-m-d'],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function after(): array
    {
        return [fn (Validator $validator) => $this->validateRelatedRows($validator, 'items', 'head_id', IncomeHead::class)];
    }
}
