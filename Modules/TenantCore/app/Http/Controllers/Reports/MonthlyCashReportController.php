<?php

namespace Modules\TenantCore\Http\Controllers\Reports;

use Carbon\CarbonImmutable;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\TenantCore\Http\Requests\CashReportRequest;
use Modules\TenantCore\Services\CashReportService;

class MonthlyCashReportController extends BaseController
{
    public function index(CashReportRequest $request, CashReportService $reports): View
    {
        $today = CarbonImmutable::now(config('app.timezone'));
        $year = (int) ($request->validated('year') ?? $today->year);
        $month = (int) ($request->validated('month') ?? $today->month);
        $start = CarbonImmutable::create($year, $month, 1, 0, 0, 0, config('app.timezone'));
        $module = $request->validated('module') ?? 'all';

        $data = $reports->report($start->toDateString(), $start->endOfMonth()->toDateString(), $module === 'all' ? [] : [$module], includeOpening: false);
        $data += [
            'pageTitle' => 'Monthly Cash Report',
            'filters' => [
                'year' => $year,
                'month' => $month,
                'module' => $module,
            ],
        ];

        return view('tenantcore::reports.monthly-cash', $data);
    }
}
