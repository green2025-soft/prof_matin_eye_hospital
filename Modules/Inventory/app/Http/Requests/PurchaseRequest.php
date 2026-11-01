<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Models\Setting;
use Modules\Inventory\Models\Document;

class PurchaseRequest extends StockEntryRequest
{
    public function rules(): array
    {
        $productType = $this->productType();
        $fields = Document::visibleItemFields(Setting::values('inventory'), 'purchase', $productType);

        return array_replace($this->commonRules(), $this->lineRules([
            'variant_id', 'product_unit_id', 'unit_id', 'unit_factor', 'original_line_id', 'quantity', 'unit_price',
            'sale_price_per_piece', 'discount', 'discount_input', 'batch_no', 'expiry_date',
            'vat_mode', 'vat_percent', 'vat_amount', 'vat_input',
        ]), [
            'supplier_id' => ['required', 'integer', 'min:1'],
            'supplier_invoice_no' => ['nullable', 'string', 'max:80'],
            'discount' => $this->moneyRules(),
            'paid_amount' => $this->moneyRules(),
            'payment_type_id' => ['nullable', 'integer', 'min:1'],
            'vat_mode' => $fields['vat'] ? ['nullable', Rule::in(['none', 'inclusive', 'exclusive'])] : ['prohibited'],
            'vat_percent' => $fields['vat'] && $productType === 'optic'
                ? ['nullable', 'numeric', 'between:0,100', 'decimal:0,2'] : ['prohibited'],
            'lines.*.original_line_id' => $this->isUpdate() ? ['nullable', 'integer', 'min:1', 'distinct'] : ['prohibited'],
            'lines.*.product_unit_id' => ['required_without:lines.*.unit_id', 'nullable', 'integer', 'min:1'],
            'lines.*.unit_id' => [
                'nullable', 'required_without:lines.*.product_unit_id', 'prohibits:lines.*.product_unit_id', 'integer', 'min:1',
                $this->existsIn('inventory_units')->where(function ($query) use ($productType): void {
                    if ($productType === 'optic') {
                        $query->whereIn('symbol', ['Pcs', 'Pair', 'Box']);
                    }
                }),
            ],
            'lines.*.unit_factor' => ['nullable', 'required_with:lines.*.unit_id', 'integer', 'between:1,1000000'],
            'lines.*.unit_price' => $this->moneyRules('required'),
            'lines.*.sale_price_per_piece' => $fields['sale_price'] ? $this->moneyRules() : ['prohibited'],
            'lines.*.discount' => $fields['discount'] ? $this->moneyRules() : ['prohibited'],
            'lines.*.discount_input' => $fields['discount'] ? ['nullable', Rule::in(['percent', 'unit', 'amount'])] : ['prohibited'],
            'lines.*.vat_mode' => $fields['vat'] ? ['required', Rule::in(['none', 'inclusive', 'exclusive'])] : ['prohibited'],
            'lines.*.vat_percent' => $fields['vat'] ? ['nullable', 'numeric', 'between:0,100', 'decimal:0,2'] : ['prohibited'],
            'lines.*.vat_amount' => $fields['vat'] ? [...$this->moneyRules(), 'required_if:lines.*.vat_input,amount,unit'] : ['prohibited'],
            'lines.*.vat_input' => $fields['vat'] ? ['nullable', Rule::in(['percent', 'amount', 'unit'])] : ['prohibited'],
            'lines.*.batch_no' => ['nullable', 'string', 'max:80'],
            'lines.*.expiry_date' => $fields['expiry'] ? ['nullable', 'date_format:Y-m-d'] : ['prohibited'],
        ]);
    }
}
