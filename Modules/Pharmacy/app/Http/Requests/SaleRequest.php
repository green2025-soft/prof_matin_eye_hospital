<?php

namespace Modules\Pharmacy\Http\Requests;

use Modules\Core\Models\Setting;
use Modules\Pharmacy\Services\SaleService;

class SaleRequest extends StockEntryRequest
{
    public function rules(): array
    {
        $fields = SaleService::itemFields(Setting::values('pharmacy'), $this->productType());

        return array_replace($this->commonRules(), $this->lineRules([
            'variant_id', 'product_unit_id', 'original_line_id', 'quantity', 'unit_price', 'discount',
        ]), [
            'customer_id' => ['required', 'integer', 'min:1'],
            'discount' => $this->moneyRules(),
            'paid_amount' => $this->moneyRules(),
            'payment_type_id' => ['nullable', 'integer', 'min:1'],
            'lines.*.original_line_id' => $this->isUpdate() ? ['nullable', 'integer', 'min:1', 'distinct'] : ['prohibited'],
            'lines.*.unit_price' => $this->moneyRules('required'),
            'lines.*.discount' => $fields['discount'] ? $this->moneyRules() : ['prohibited'],
        ]);
    }
}
