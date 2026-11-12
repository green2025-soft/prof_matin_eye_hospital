<?php

namespace Modules\Hospital\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\TenantCore\Http\Requests\CashReportRequest;
use Modules\TenantCore\Services\CashReportService;

class CashBalanceReportController extends BaseController
{
    public function index(CashReportRequest $request, CashReportService $reports): View
    {
        $from = $request->validated('from_date') ?? now(config('app.timezone'))->toDateString();
        $to = $request->validated('to_date') ?? $from;

        $data = $reports->report($from, $to, ['hospital']) + [
            'pageTitle' => 'Hospital cash balance',
            'filters' => [
                'from_date' => $from,
                'to_date' => $to,
            ],
        ];

        return view('hospital::reports.cash-balance', $data);
    }
}
