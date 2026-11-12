<?php

namespace Modules\TenantCore\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\TenantCore\Http\Requests\CashReportRequest;
use Modules\TenantCore\Services\CashReportService;

class CashReportController extends BaseController
{
    public function index(CashReportRequest $request, CashReportService $reports): View
    {
        $from = $request->validated('from_date') ?? now(config('app.timezone'))->toDateString();
        $to = $request->validated('to_date') ?? $from;
        $module = $request->validated('module') ?? 'all';

        $data = $reports->report($from, $to, $module === 'all' ? [] : [$module]) + [
            'pageTitle' => 'Cash report',
            'filters' => [
                'from_date' => $from,
                'to_date' => $to,
                'module' => $module,
            ],
        ];

        return view('tenantcore::reports.cash', $data);
    }
}
