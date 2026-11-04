<?php

namespace Modules\Inventory\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Inventory\Http\Requests\DateRangeReportRequest;
use Modules\Inventory\Models\Purchase;

class PurchaseDiscountReportController extends BaseController
{
    public function index(DateRangeReportRequest $request): View
    {
        $filters = $request->filters();
        $query = Purchase::query()
            ->with('supplier:id,name')
            ->whereNull('voided_at')
            ->where('product_type', $filters['product_type'])
            ->whereBetween('entry_date', [$filters['from_date'], $filters['to_date']])
            ->withSum('lines as line_discount', 'discount');

        $data = [
            'pageTitle' => 'Purchase Discounts',
            'filters' => $filters,
            'records' => $this->paginateQuery(
                $query->orderByDesc('entry_date')
                    ->orderByDesc('id'),
                $filters
            ),
        ];

        return view('inventory::reports.purchase-discounts', $data);
    }
}
