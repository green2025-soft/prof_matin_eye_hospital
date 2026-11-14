<?php

namespace Modules\TenantCore\Http\Controllers\Reports;

use Carbon\CarbonImmutable;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\TenantCore\Http\Requests\CashReportRequest;
use Modules\TenantCore\Services\CashReportService;

class DailyReportController extends BaseController
{
    public function index(CashReportRequest $request, CashReportService $reports): View
    {
        $today          = CarbonImmutable::today(config('app.timezone'));
        $selectedFrom   = $request->validated('from_date') ?? $request->validated('date');
        $from           = $selectedFrom ?? $today->startOfMonth()->toDateString();
        $to             = $request->validated('to_date') ?? $selectedFrom ?? $today->toDateString();
        $module         = $request->validated('module') ?? 'all';

        $data = $reports->report($from, $to, $module === 'all' ? [] : [$module], includeOpening: false);
        $data += [
            'pageTitle' => 'Income / Expense report',
            'filters' => [
                'from_date' => $from,
                'to_date' => $to,
                'module' => $module,
            ],
        ];

        return view('tenantcore::reports.daily', $data);
    }
}
