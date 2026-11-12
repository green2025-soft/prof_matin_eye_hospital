<?php

namespace Modules\Pharmacy\Http\Controllers\Reports;

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
        $module = $request->validated('module') ?? 'all';
        $modules = in_array($module, ['medicine', 'optic'], true) ? [$module] : ['medicine', 'optic'];

        $data = $reports->report($from, $to, $modules) + [
            'pageTitle' => 'Pharmacy cash balance',
            'filters' => [
                'from_date' => $from,
                'to_date' => $to,
                'module' => count($modules) === 1 ? $module : 'all',
            ],
        ];

        return view('pharmacy::reports.cash-balance', $data);
    }
}
