<?php

namespace Modules\Inventory\Http\Controllers\Reports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Hospital\Contracts\InventoryCustomers;
use Modules\Inventory\Http\Requests\ReportRequest;
use Modules\Inventory\Models\Sale;
use Modules\Inventory\Services\InventoryAccess;
use Modules\Inventory\Services\ReportService;

class CustomerDueReportController extends BaseController
{
    public function index(ReportRequest $request, InventoryCustomers $customers, ReportService $reports): View
    {
        $filters = $request->filters();
        $invoices = Sale::query()
            ->outstanding()
            ->where('product_type', $filters['product_type'])
            ->whereNotNull('customer_id')
            ->when($filters['customer_id'] ?? null, fn (Builder $query, int $id) => $query->where('customer_id', $id));
        $parties = (clone $invoices)->select('customer_id as party_id')
            ->groupBy('customer_id')
            ->orderBy('customer_id');
        $records = $this->paginateQuery($parties, $filters);
        $reports->dueBalances($invoices, 'customer_id', $records);
        $actor = $request->user('web');
        $customers = $customers->findMany(
            $actor,
            $records->pluck('party_id')
                ->all()
        );

        $data = [
            'pageTitle' => 'Customer Dues',
            'filters' => $filters,
            'records' => $records,
            'summary' => $reports->dueSummary($invoices, 'customer_id'),
            'customers' => $customers,
            'selectedCustomer' => $customers[$filters['customer_id'] ?? 0] ?? null,
            'canCreatePayment' => app(InventoryAccess::class)
                ->allows($actor, 'inventory-payments.create'),
        ];

        return view('inventory::reports.customer-dues', $data);
    }
}
