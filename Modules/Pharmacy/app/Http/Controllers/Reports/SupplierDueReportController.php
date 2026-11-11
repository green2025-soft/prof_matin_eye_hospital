<?php

namespace Modules\Pharmacy\Http\Controllers\Reports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Pharmacy\Http\Requests\ReportRequest;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\Supplier;
use Modules\Pharmacy\Services\ReportService;

class SupplierDueReportController extends BaseController
{
    public function index(ReportRequest $request, ReportService $reports): View
    {
        $permissions = $request->attributes->get('navigation_permissions', []);
        $filters = $request->filters();
        $invoices = Purchase::outstanding()
            ->where('product_type', $filters['product_type'])
            ->whereNotNull('supplier_id')
            ->when($filters['supplier_id'] ?? null, fn (Builder $query, int $id) => $query->where('supplier_id', $id));
        $parties = (clone $invoices)->select('supplier_id as party_id')
            ->groupBy('supplier_id')
            ->orderBy('supplier_id');
        $records = $this->paginateQuery($parties, $filters);
        $reports->dueBalances($invoices, 'supplier_id', $records);
        $suppliers = Supplier::whereIn('id', $records->pluck('party_id'))
            ->get(['id', 'name', 'contact_no'])
            ->keyBy('id');
        $summary = $reports->dueSummary($invoices, 'supplier_id');
        $selectedSupplier = $suppliers->get($filters['supplier_id'] ?? 0);
        $canCreatePayment = isset($permissions['pharmacy-payments.create']);

        $data = [
            'pageTitle' => 'Supplier Dues',
            'filters' => $filters,
            'records' => $records,
            'summary' => $summary,
            'suppliers' => $suppliers,
            'selectedSupplier' => $selectedSupplier,
            'canCreatePayment' => $canCreatePayment,
        ];

        return view('pharmacy::reports.supplier-dues', $data);
    }
}
