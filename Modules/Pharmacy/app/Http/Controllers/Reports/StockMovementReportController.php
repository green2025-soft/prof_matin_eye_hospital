<?php

namespace Modules\Pharmacy\Http\Controllers\Reports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Pharmacy\Http\Requests\DateRangeReportRequest;
use Modules\Pharmacy\Models\ProductVariant;
use Modules\Pharmacy\Models\StockMovement;
use Modules\Pharmacy\Services\ReportService;

class StockMovementReportController extends BaseController
{
    public function index(DateRangeReportRequest $request, ReportService $reports): View
    {
        $filters = $request->filters();
        $query = StockMovement::where('movement_date', '<=', $filters['to_date']);
        $reports->filterStockProducts($query, $filters['product_type'], $filters['variant_id'] ?? null, $filters['search'] ?? null);
        $summary = (object) [
            'opening_quantity' => (clone $query)->where('movement_date', '<', $filters['from_date'])
                ->sum('quantity'),
            'in_quantity' => (clone $query)->where('movement_date', '>=', $filters['from_date'])
                ->where('quantity', '>', 0)
                ->sum('quantity'),
            'out_quantity' => -(int) (clone $query)->where('movement_date', '>=', $filters['from_date'])
                ->where('quantity', '<', 0)
                ->sum('quantity'),
            'closing_quantity' => (clone $query)->sum('quantity'),
        ];
        $records = $this->paginateQuery(
            $query->with(['variant.product:id,name', 'stockEntry:id,number'])
                ->where('movement_date', '>=', $filters['from_date'])
                ->orderByDesc('movement_date')
                ->orderByDesc('id'),
            $filters
        );
        $selectedProduct = $this->selectedOption(
            ProductVariant::with('product:id,name,type')
                ->whereHas('product', fn (Builder $products) => $products->where('type', $filters['product_type'])),
            $filters['variant_id'] ?? null,
            ['id', 'product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl']
        );

        $data = [
            'pageTitle' => 'Stock Movements',
            'filters' => $filters,
            'summary' => $summary,
            'records' => $records,
            'selectedProduct' => $selectedProduct,
        ];

        return view('pharmacy::reports.movements', $data);
    }
}
