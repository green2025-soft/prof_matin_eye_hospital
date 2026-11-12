<?php

namespace Modules\Accounting\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Accounting\Http\Requests\MonthReportRequest;
use Modules\Accounting\Services\IncomeExpenseReportService;
use Modules\Core\Http\Controllers\BaseController;

class IncomeExpenseReportController extends BaseController
{
    public function index(MonthReportRequest $request, IncomeExpenseReportService $reports): View
    {
        $filters = $request->filters();
        $periods = $request->periods();
        $statement = $reports->statement((int) $request->attributes->get('branch_id'), $periods, (int) $filters['depth']);

        $data = [
            'pageTitle' => 'Income & Expenditure',
            'filters' => $filters,
            'periods' => $periods,
        ] + $statement;

        return view('accounting::reports.income-expense', $data);
    }
}
