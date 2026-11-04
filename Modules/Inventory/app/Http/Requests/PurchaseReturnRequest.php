<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Validation\Rule;

class PurchaseReturnRequest extends StockEntryRequest
{
    public function rules(): array
    {
        return array_replace($this->commonRules(), $this->lineRules([
            'variant_id', 'product_unit_id', 'purchase_item_id', 'quantity', 'unit_price',
        ]), [
            'purchase_id' => $this->isUpdate()
                ? ['required', 'integer', Rule::in([$this->record()->purchase_id])]
                : ['required', 'integer', 'min:1'],
            'payment_type_id' => ['nullable', 'integer', 'min:1'],
            'lines.*.purchase_item_id' => ['required', 'integer', 'min:1', 'distinct'],
            'lines.*.unit_price' => $this->moneyRules(),
        ]);
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $lines = $this->input('lines');
        if (is_array($lines) && count($lines) <= 100) {
            $this->merge(['lines' => array_filter($lines, static fn ($line): bool => ! is_array($line)
                || ! in_array($line['quantity'] ?? null, [0, '0'], true))]);
        }
    }

    public function messages(): array
    {
        return ['lines.required' => 'Enter a return quantity for at least one item.', 'lines.min' => 'Enter a return quantity for at least one item.'];
    }
}
