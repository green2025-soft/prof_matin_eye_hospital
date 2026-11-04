<?php

namespace Modules\Inventory\Http\Controllers\Reports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Inventory\Http\Requests\ReportRequest;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Services\InventoryAccess;
use Modules\Inventory\Services\ReportService;

class SupplierDueReportController extends BaseController
{
    public function index(ReportRequest $request, ReportService $reports): View
    {
        $filters = $request->filters();
        $invoices = Purchase::query()
            ->outstanding()
            ->where('product_type', $filters['product_type'])
            ->whereNotNull('supplier_id')
            ->when($filters['supplier_id'] ?? null, fn (Builder $query, int $id) => $query->where('supplier_id', $id));
        $parties = (clone $invoices)->select('supplier_id as party_id')
            ->groupBy('supplier_id')
            ->orderBy('supplier_id');
        $records = $this->paginateQuery($parties, $filters);
        $reports->dueBalances($invoices, 'supplier_id', $records);
        $suppliers = Supplier::query()
            ->whereIn('id', $records->pluck('party_id'))
            ->get(['id', 'name', 'contact_no'])
            ->keyBy('id');

        $data = [
            'pageTitle' => 'Supplier Dues',
            'filters' => $filters,
            'records' => $records,
            'summary' => $reports->dueSummary($invoices, 'supplier_id'),
            'suppliers' => $suppliers,
            'selectedSupplier' => $suppliers->get($filters['supplier_id'] ?? 0),
            'canCreatePayment' => app(InventoryAccess::class)
                ->allows($request->user('web'), 'inventory-payments.create'),
        ];

        return view('inventory::reports.supplier-dues', $data);
    }
}
