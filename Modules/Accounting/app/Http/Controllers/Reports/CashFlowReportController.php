<?php

namespace Modules\Accounting\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Accounting\Http\Requests\MonthReportRequest;
use Modules\Accounting\Services\CashFlowReportService;
use Modules\Core\Http\Controllers\BaseController;

class CashFlowReportController extends BaseController
{
    public function index(MonthReportRequest $request, CashFlowReportService $reports): View
    {
        $filters = $request->filters();
        $periods = array_intersect_key($request->periods(), array_flip(['current', 'fiscal']));
        $statement = $reports->statement((int) $request->attributes->get('branch_id'), $periods, (int) $filters['depth']);

        $data = [
            'pageTitle' => 'Cash Flow Statement',
            'filters' => $filters,
            'periods' => $periods,
        ] + $statement;

        return view('accounting::reports.cash-flow', $data);
    }
}
