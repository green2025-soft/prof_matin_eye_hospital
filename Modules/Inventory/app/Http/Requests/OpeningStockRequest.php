<?php

namespace Modules\Inventory\Http\Requests;

class OpeningStockRequest extends StockEntryRequest
{
    public function rules(): array
    {
        return array_replace($this->commonRules(), $this->lineRules([
            'variant_id', 'product_unit_id', 'quantity', 'unit_price', 'batch_no', 'expiry_date',
        ]), [
            'lines.*.unit_price' => $this->moneyRules(),
            'lines.*.batch_no' => ['nullable', 'string', 'max:80'],
            'lines.*.expiry_date' => ['nullable', 'date_format:Y-m-d'],
        ]);
    }
}
