<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;

class ProductVariantRequest extends BaseRequest
{
    public function rules(): array
    {
        $record = $this->record();

        return [
            'product_id' => [
                'required', 'integer', $this->existsIn('inventory_products'),
                Rule::when($record !== null, fn () => Rule::in([$record->product_id])),
            ],
            'sku' => ['required', 'string', 'max:100', $this->unique('sku')],
            'name' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'max:100'],
            'sph' => ['nullable', 'decimal:0,2', 'numeric', 'between:-9999.99,9999.99'],
            'cyl' => ['nullable', 'decimal:0,2', 'numeric', 'between:-9999.99,9999.99'],
            'active' => ['required', 'boolean'],
        ];
    }
}
