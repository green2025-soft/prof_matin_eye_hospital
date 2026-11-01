<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Validation\Rule;

class AdjustmentRequest extends StockEntryRequest
{
    public function rules(): array
    {
        return array_replace($this->commonRules(), $this->lineRules([
            'variant_id', 'product_unit_id', 'quantity', 'unit_price', 'batch_no', 'expiry_date', 'direction', 'stock_lot_id',
        ]), [
            'reason' => ['required', 'string', 'max:500'],
            'lines.*.unit_price' => $this->moneyRules(),
            'lines.*.batch_no' => ['nullable', 'string', 'max:80'],
            'lines.*.expiry_date' => ['nullable', 'date_format:Y-m-d'],
            'lines.*.direction' => ['required', Rule::in(['increase', 'decrease'])],
            'lines.*.stock_lot_id' => ['nullable', 'integer', 'min:1'],
        ]);
    }
}
