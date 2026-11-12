<?php

namespace Modules\Accounting\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Accounting\Http\Requests\MonthReportRequest;
use Modules\Accounting\Services\ChangesInEquityReportService;
use Modules\Core\Http\Controllers\BaseController;

class ChangesInEquityReportController extends BaseController
{
    public function index(MonthReportRequest $request, ChangesInEquityReportService $reports): View
    {
        $filters = $request->filters();
        $periods = $request->periods();
        $statement = $reports->statement((int) $request->attributes->get('branch_id'), $periods, (int) $filters['depth']);

        $data = [
            'pageTitle' => 'Changes in Equity',
            'filters' => $filters,
            'periods' => $periods,
            'records' => $statement['records'],
            'balances' => $statement['balances'],
        ];

        return view('accounting::reports.changes-in-equity', $data);
    }
}
