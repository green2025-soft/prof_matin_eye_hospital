<?php

namespace Modules\Inventory\Http\Requests;

class WastageRequest extends StockEntryRequest
{
    public function rules(): array
    {
        return array_replace($this->commonRules(), $this->lineRules([
            'variant_id', 'product_unit_id', 'quantity', 'stock_lot_id',
        ]), [
            'reason' => ['required', 'string', 'max:500'],
            'lines.*.stock_lot_id' => ['nullable', 'integer', 'min:1'],
        ]);
    }
}
