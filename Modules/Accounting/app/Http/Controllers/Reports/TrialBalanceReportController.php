<?php

namespace Modules\Accounting\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Accounting\Http\Requests\MonthReportRequest;
use Modules\Accounting\Models\AccountHead;
use Modules\Accounting\Services\ReportService;
use Modules\Core\Http\Controllers\BaseController;

class TrialBalanceReportController extends BaseController
{
    public function index(MonthReportRequest $request, ReportService $reports): View
    {
        $filters = $request->filters();
        $period = $request->periods()['current'];
        $ledgers = $reports->accounts((int) $request->attributes->get('branch_id'), $period['from_date'], $period['to_date'])
            ->get();
        $heads = AccountHead::orderBy('code')
            ->get(['id', 'code', 'name', 'parent_id', 'type', 'depth'])
            ->keyBy('id');
        $records = $reports->trialRows($ledgers, $heads, (int) $filters['depth']);

        $data = [
            'pageTitle' => 'Trial Balance',
            'filters' => $filters,
            'period' => $period,
            'records' => $records,
            'periodDebit' => $reports->total($records, 'period_debit'),
            'periodCredit' => $reports->total($records, 'period_credit'),
            'openingDebit' => $reports->total($records, 'opening_debit'),
            'openingCredit' => $reports->total($records, 'opening_credit'),
            'totalDebit' => $reports->total($records, 'closing_debit'),
            'totalCredit' => $reports->total($records, 'closing_credit'),
        ];

        return view('accounting::reports.trial-balance', $data);
    }
}
