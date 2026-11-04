<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Inventory\Services\InventoryAccess;

class ReportRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return app(InventoryAccess::class)->allows($this->user('web'), 'inventory-reports.view');
    }

    public function rules(): array
    {
        return [
            'product_type' => ['nullable', 'in:medicine,optic'], 'variant_id' => ['nullable', 'integer', 'min:1'],
            'category_id' => ['nullable', 'integer', 'min:1'], 'shelf_id' => ['nullable', 'integer', 'min:1'],
            'stock_status' => ['nullable', Rule::in(['all', 'available', 'out', 'low', 'expired', 'future', 'negative'])],
            'customer_id' => ['nullable', 'integer', 'min:1'], 'supplier_id' => ['nullable', 'integer', 'min:1'],
            'search' => ['nullable', 'string', 'max:80'], 'expiry_before' => ['nullable', 'date_format:Y-m-d'],
            'low_stock' => ['nullable', 'boolean'], 'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['nullable', Rule::in(config('pagination.options'))],
        ];
    }

    public function filters(array $defaults = []): array
    {
        $filters = array_filter($this->validated(), fn ($value): bool => $value !== null && $value !== '')
            + $defaults + ['product_type' => 'medicine'];

        $this->attributes->set('inventory_product_type', $filters['product_type']);

        return $filters;
    }
}
