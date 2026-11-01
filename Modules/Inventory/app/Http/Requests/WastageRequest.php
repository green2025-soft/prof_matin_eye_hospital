<?php

namespace Modules\Inventory\Http\Requests;

class WastageRequest extends StockEntryRequest
{
    public function rules(): array
    {
        return array_replace($this->commonRules(), $this->lineRules([
            'variant_id', 'product_unit_id', 'quantity', 'unit_price', 'stock_lot_id', 'batch_no', 'expiry_date',
        ]), [
            'reason' => ['required', 'string', 'max:500'],
            'lines.*.unit_price' => $this->moneyRules(),
            'lines.*.stock_lot_id' => ['nullable', 'integer', 'min:1'],
            'lines.*.batch_no' => ['nullable', 'string', 'max:80'],
            'lines.*.expiry_date' => ['nullable', 'date_format:Y-m-d'],
        ]);
    }
}
