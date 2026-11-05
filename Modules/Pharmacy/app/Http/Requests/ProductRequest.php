<?php

namespace Modules\Pharmacy\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Pharmacy\Models\Product;

class ProductRequest extends BaseRequest
{
    public function rules(): array
    {
        $record = $this->record();
        $type = $record?->type ?? $this->query('type', 'medicine');
        if (! is_string($type) || ! array_key_exists($type, Product::types())) {
            $type = 'medicine';
        }

        return [
            'type' => ['bail', 'required', 'string', Rule::in([$type])],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['prohibited'],
            'barcode' => ['bail', 'nullable', 'string', 'max:100', 'not_regex:/\p{Cc}/u', $this->unique('barcode')],
            'category_id' => ['nullable', 'integer', $this->existsIn('phr_product_categories')->where('type', $type)],
            'dosage_id' => ['nullable', 'integer', $this->existsIn('phr_dosages')],
            'supplier_id' => ['nullable', 'integer', $this->existsIn('phr_suppliers')->where('type', $type)],
            'shelf_id' => ['nullable', 'integer', $this->existsIn('phr_shelves')->where('type', $type)],
            'strength' => ['nullable', 'string', 'max:255'],
            'purchase_price' => ['required', 'decimal:0,2', 'numeric', 'min:0', 'max:9999999999.99'],
            'sale_price' => ['required', 'decimal:0,2', 'numeric', 'min:0', 'max:9999999999.99'],
            'reorder_level' => ['required', 'integer', 'between:0,1000000000'],
            'needs_setup' => ['prohibited', 'exclude'],
            'active' => ['prohibited', 'exclude'],
        ];
    }
}
