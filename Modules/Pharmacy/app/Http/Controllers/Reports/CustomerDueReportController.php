<?php

namespace Modules\Pharmacy\Http\Controllers\Reports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Pharmacy\Http\Requests\ReportRequest;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Services\ReportService;

class CustomerDueReportController extends BaseController
{
    public function index(ReportRequest $request, PharmacyCustomers $customers, ReportService $reports): View
    {
        $permissions = $request->attributes->get('navigation_permissions', []);
        $filters = $request->filters();
        $invoices = Sale::outstanding()
            ->where('product_type', $filters['product_type'])
            ->whereNotNull('customer_id')
            ->when($filters['customer_id'] ?? null, fn (Builder $query, int $id) => $query->where('customer_id', $id));
        $parties = (clone $invoices)->select('customer_id as party_id')
            ->groupBy('customer_id')
            ->orderBy('customer_id');
        $records = $this->paginateQuery($parties, $filters);
        $reports->dueBalances($invoices, 'customer_id', $records);
        $actor = $request->user('web');
        $customerRecords = $customers->findMany(
            $actor,
            $records->pluck('party_id')
                ->all()
        );
        $summary = $reports->dueSummary($invoices, 'customer_id');
        $selectedCustomer = $customerRecords[$filters['customer_id'] ?? 0] ?? null;
        $canCreatePayment = isset($permissions['pharmacy-payments.create']);

        $data = [
            'pageTitle' => 'Customer Dues',
            'filters' => $filters,
            'records' => $records,
            'summary' => $summary,
            'customers' => $customerRecords,
            'selectedCustomer' => $selectedCustomer,
            'canCreatePayment' => $canCreatePayment,
        ];

        return view('pharmacy::reports.customer-dues', $data);
    }
}
