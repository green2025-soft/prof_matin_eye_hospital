<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Inventory\Services\InventoryAccess;

class ReportRequest extends BaseRequest
{
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();
        if ($this->routeIs('inventory.reports.index') && in_array($this->route('report', 'stock'), ['stock', 'profit-loss'], true)) {
            $this->merge([
                'from_date' => $this->input('from_date') ?? now()->startOfMonth()->toDateString(),
                'to_date' => $this->input('to_date') ?? now()->toDateString(),
            ]);
        }
    }

    public function authorize(): bool
    {
        return app(InventoryAccess::class)->allows($this->user('web'), 'inventory-reports.view');
    }

    public function rules(): array
    {
        return [
            'from_date' => ['nullable', 'date_format:Y-m-d'], 'to_date' => ['nullable', 'date_format:Y-m-d', Rule::when($this->filled('from_date'), ['after_or_equal:from_date'])],
            'product_type' => ['nullable', 'in:medicine,optic'], 'variant_id' => ['nullable', 'integer', 'min:1'],
            'category_id' => ['nullable', 'integer', 'min:1'], 'shelf_id' => ['nullable', 'integer', 'min:1'],
            'stock_status' => ['nullable', Rule::in(['all', 'available', 'out', 'low', 'expired', 'future', 'negative'])],
            'customer_id' => ['nullable', 'integer', 'min:1'], 'supplier_id' => ['nullable', 'integer', 'min:1'],
            'search' => ['nullable', 'string', 'max:80'], 'expiry_before' => ['nullable', 'date_format:Y-m-d'],
            'low_stock' => ['nullable', 'boolean'], 'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['nullable', Rule::in(config('pagination.options'))],
        ];
    }
}
