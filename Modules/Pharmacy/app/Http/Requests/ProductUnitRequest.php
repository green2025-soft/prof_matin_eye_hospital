<?php

namespace Modules\Pharmacy\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;

class ProductUnitRequest extends BaseRequest
{
    public function rules(): array
    {
        $record = $this->record();
        $used = $record !== null && $record->hasReferences();

        return [
            'product_id' => [
                'required', 'integer', $this->existsIn('phr_products'),
                Rule::when($record !== null, fn () => Rule::in([$record->product_id])),
            ],
            'unit_id' => [
                'required', 'integer', $this->existsIn('phr_units'),
                $this->unique('unit_id')->where('product_id', $record?->product_id ?? $this->input('product_id'))
                    ->where('factor', $this->input('factor', $record?->factor)),
                Rule::when($record !== null, fn () => Rule::in([$record->unit_id])),
            ],
            'factor' => [
                'required', 'integer', 'between:1,1000000',
                Rule::when($used, fn () => Rule::in([$record->factor])),
            ],
        ];
    }
}
