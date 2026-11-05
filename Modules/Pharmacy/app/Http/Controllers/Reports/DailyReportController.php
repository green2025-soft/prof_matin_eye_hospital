<?php

namespace Modules\Pharmacy\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Pharmacy\Http\Requests\DateRangeReportRequest;
use Modules\Pharmacy\Models\Adjustment;
use Modules\Pharmacy\Models\OpeningStock;
use Modules\Pharmacy\Models\Payment;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\PurchaseReturn;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\SaleReturn;
use Modules\Pharmacy\Models\Transfer;
use Modules\Pharmacy\Models\Wastage;
use Modules\Pharmacy\Services\ReportService;

class DailyReportController extends BaseController
{
    public function index(DateRangeReportRequest $request, ReportService $reports): View
    {
        $filters = $request->filters();
        $productType = $filters['product_type'];
        $dateRange = [$filters['from_date'], $filters['to_date']];
        $purchases = Purchase::query()
            ->whereNull('voided_at')
            ->where('product_type', $productType)
            ->whereBetween('entry_date', $dateRange);
        $sales = Sale::query()
            ->whereNull('voided_at')
            ->where('product_type', $productType)
            ->whereBetween('entry_date', $dateRange);
        $purchaseReturns = PurchaseReturn::query()
            ->whereNull('voided_at')
            ->where('product_type', $productType)
            ->whereBetween('entry_date', $dateRange);
        $saleReturns = SaleReturn::query()
            ->whereNull('voided_at')
            ->where('product_type', $productType)
            ->whereBetween('entry_date', $dateRange);
        $openings = OpeningStock::query()
            ->whereNull('voided_at')
            ->where('product_type', $productType)
            ->whereBetween('entry_date', $dateRange);
        $adjustments = Adjustment::query()
            ->whereNull('voided_at')
            ->where('product_type', $productType)
            ->whereBetween('entry_date', $dateRange);
        $wastages = Wastage::query()
            ->whereNull('voided_at')
            ->where('product_type', $productType)
            ->whereBetween('entry_date', $dateRange);
        $transfers = Transfer::query()
            ->where('branch_id', (int) $request->attributes->get('branch_id'))
            ->whereNull('voided_at')
            ->where('product_type', $productType)
            ->whereBetween('entry_date', $dateRange);
        $payments = Payment::query()
            ->whereNull('voided_at')
            ->where('product_type', $productType)
            ->whereBetween('payment_date', $dateRange);

        // Paginate dates before loading activity so every activity for a day stays together.
        $dates = (clone $purchases)->select('entry_date as activity_date')
            ->toBase()
            ->union(
                (clone $sales)->select('entry_date as activity_date')
                    ->toBase()
            )
            ->union(
                (clone $purchaseReturns)->select('entry_date as activity_date')
                    ->toBase()
            )
            ->union(
                (clone $saleReturns)->select('entry_date as activity_date')
                    ->toBase()
            )
            ->union(
                (clone $openings)->select('entry_date as activity_date')
                    ->toBase()
            )
            ->union(
                (clone $adjustments)->select('entry_date as activity_date')
                    ->toBase()
            )
            ->union(
                (clone $wastages)->select('entry_date as activity_date')
                    ->toBase()
            )
            ->union(
                (clone $transfers)->select('entry_date as activity_date')
                    ->toBase()
            )
            ->union(
                (clone $payments)->select('payment_date as activity_date')
                    ->toBase()
            );
        $query = (new Payment)->getConnection()
            ->query()
            ->fromSub($dates, 'activity_dates')
            ->orderByDesc('activity_date');
        $records = $this->paginateQuery($query, $filters);
        $pageDates = $records->pluck('activity_date')
            ->all();

        $activities = $reports->financialActivities($purchases, $pageDates, 'purchase', -1)
            ->concat($reports->financialActivities($sales, $pageDates, 'sale', 1))
            ->concat($reports->financialActivities($purchaseReturns, $pageDates, 'purchase_return', 1))
            ->concat($reports->financialActivities($saleReturns, $pageDates, 'sale_return', -1))
            ->concat($reports->stockActivities($openings, $pageDates, 'opening'))
            ->concat($reports->stockActivities($adjustments, $pageDates, 'adjustment'))
            ->concat($reports->stockActivities($wastages, $pageDates, 'damage'))
            ->concat($reports->stockActivities($transfers, $pageDates, 'transfer'))
            ->concat($reports->paymentActivities((clone $payments)->where('direction', 'customer'), $pageDates, 'customer_collection', 1))
            ->concat($reports->paymentActivities((clone $payments)->where('direction', 'supplier'), $pageDates, 'supplier_payment', -1));
        $records->setCollection(
            $activities->sortBy([['activity_date', 'desc'], ['activity', 'asc']])
                ->values()
        );

        $data = [
            'pageTitle' => 'Daily Report',
            'filters' => $filters,
            'records' => $records,
        ];

        return view('pharmacy::reports.daily', $data);
    }
}
