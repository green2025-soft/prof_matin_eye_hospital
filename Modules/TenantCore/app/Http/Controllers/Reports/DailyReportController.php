<?php

namespace Modules\TenantCore\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\TenantCore\Http\Requests\CashReportRequest;
use Modules\TenantCore\Services\CashReportService;

class DailyReportController extends BaseController
{
    public function index(CashReportRequest $request, CashReportService $reports): View
    {
        $date = $request->validated('date') ?? now(config('app.timezone'))->toDateString();
        $module = $request->validated('module') ?? 'all';

        $data = $reports->report($date, $date, $module === 'all' ? [] : [$module]) + [
            'pageTitle' => 'Daily report',
            'filters' => [
                'date' => $date,
                'module' => $module,
            ],
        ];

        return view('tenantcore::reports.daily', $data);
    }
}
