<?php

namespace Modules\Accounting\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Accounting\Http\Requests\MonthReportRequest;
use Modules\Accounting\Services\BalanceSheetReportService;
use Modules\Core\Http\Controllers\BaseController;

class BalanceSheetReportController extends BaseController
{
    public function index(MonthReportRequest $request, BalanceSheetReportService $reports): View
    {
        $filters = $request->filters();
        $periods = $request->periods();
        $statement = $reports->statement((int) $request->attributes->get('branch_id'), $periods, (int) $filters['depth']);

        $data = [
            'pageTitle' => 'Balance Sheet',
            'filters' => $filters,
            'periods' => $periods,
        ] + $statement;

        return view('accounting::reports.balance-sheet', $data);
    }
}
