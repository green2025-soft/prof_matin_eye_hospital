<?php

namespace Modules\Inventory\Http\Requests;

class TransferRequest extends StockEntryRequest
{
    public function rules(): array
    {
        return array_replace($this->commonRules(), $this->lineRules([
            'variant_id', 'product_unit_id', 'quantity', 'unit_price',
        ]), [
            'destination_branch_id' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:500'],
            'lines.*.unit_price' => $this->moneyRules(),
        ]);
    }
}
