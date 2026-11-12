<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Accounting\Models\AccountHead;
use Modules\Accounting\Models\JournalEntry;
use Modules\Core\Http\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index(Request $request): View
    {
        $permissions = $request->attributes->get('navigation_permissions', []);
        $branchId = (int) $request->attributes->get('branch_id');
        $today = now(config('app.timezone'));
        $canViewJournals = isset($permissions['accounting-journals.view']);
        $canViewAccounts = isset($permissions['accounting-accounts.view']);
        $todayCount = null;
        $todayTotal = null;
        $ledgerCount = null;
        $recentEntries = collect();

        if ($canViewJournals) {
            $todayEntries = JournalEntry::active()->where('branch_id', $branchId)
                ->where('entry_date', $today->toDateString());
            $todayCount = (clone $todayEntries)->count();
            $todayTotal = $todayEntries->sum('total');
            $recentEntries = JournalEntry::where('branch_id', $branchId)
                ->with('voucherType:id,name')->orderByDesc('id')->limit(5)
                ->get(['id', 'voucher_type_id', 'number', 'entry_date', 'total', 'voided_at']);
        }

        if ($canViewAccounts) {
            $ledgerCount = AccountHead::postable()->count();
        }

        $data = [
            'pageTitle' => 'Accounting Dashboard',
            'todayIso' => $today->toDateString(),
            'todayLabel' => $today->format('l, d F Y'),
            'canViewJournals' => $canViewJournals,
            'canCreateJournal' => isset($permissions['accounting-journals.create']),
            'canViewAccounts' => $canViewAccounts,
            'canViewMappings' => isset($permissions['accounting-mappings.view']),
            'canViewReports' => isset($permissions['accounting-reports.view']),
            'todayCount' => $todayCount,
            'todayTotal' => $todayTotal,
            'ledgerCount' => $ledgerCount,
            'recentEntries' => $recentEntries,
        ];

        return view('accounting::dashboard.index', $data);
    }
}
