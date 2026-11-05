<?php

namespace Modules\Pharmacy\Http\Controllers\Reports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Pharmacy\Http\Requests\ReportRequest;
use Modules\Pharmacy\Models\ProductVariant;
use Modules\Pharmacy\Models\StockLot;
use Modules\Pharmacy\Services\ReportService;

class ExpiryReportController extends BaseController
{
    public function index(ReportRequest $request, ReportService $reports): View
    {
        $filters = $request->filters([
            'expiry_before' => now()
                ->addDays(90)
                ->toDateString(),
        ]);
        $filters['product_type'] = 'medicine';

        $request->attributes->set('pharmacy_product_type', 'medicine');

        $query = StockLot::query()
            ->with('variant.product:id,name')
            ->whereNull('reversed_at')
            ->where('remaining_quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', $filters['expiry_before'])
            ->orderBy('expiry_date')
            ->orderBy('id');

        $reports->filterStockProducts(
            $query,
            'medicine',
            $filters['variant_id'] ?? null,
            $filters['search'] ?? null,
        );

        $records = $this->paginateQuery($query, $filters);

        $productQuery = ProductVariant::query()
            ->with('product:id,name,type')
            ->whereHas('product', fn (Builder $products) => $products->where('type', 'medicine'));

        $selectedProduct = $this->selectedOption(
            $productQuery,
            $filters['variant_id'] ?? null,
            ['id', 'product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl'],
        );

        $data = [
            'pageTitle' => 'Expiry Report',
            'filters' => $filters,
            'records' => $records,
            'selectedProduct' => $selectedProduct,
        ];

        return view('pharmacy::reports.expiry', $data);
    }
}
