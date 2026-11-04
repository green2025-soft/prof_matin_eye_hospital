<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;

abstract class StockEntryRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    protected function productType(): string
    {
        $type = $this->isUpdate() ? $this->record()?->product_type : $this->query('product_type', 'medicine');

        return in_array($type, ['medicine', 'optic'], true) ? $type : 'medicine';
    }

    protected function commonRules(): array
    {
        return [
            'entry_date' => ['required', 'date_format:Y-m-d'],
            'product_type' => ['required', Rule::in([$this->productType()])],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function lineRules(array $allowedFields): array
    {
        return [
            'lines' => ['required', 'array', 'min:1', 'max:100'],
            'lines.*' => ['required', 'array:'.implode(',', $allowedFields)],
            'lines.*.variant_id' => ['required', 'integer', 'min:1'],
            'lines.*.product_unit_id' => ['required', 'integer', 'min:1'],
            'lines.*.quantity' => ['required', 'integer', 'between:1,1000000000'],
        ];
    }

    protected function moneyRules(string $presence = 'nullable'): array
    {
        return [$presence, 'regex:/^\d{1,10}(?:\.\d{1,2})?$/D'];
    }
}
