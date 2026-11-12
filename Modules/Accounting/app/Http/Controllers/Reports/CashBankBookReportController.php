<?php

namespace Modules\Accounting\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Accounting\Http\Requests\DateRangeReportRequest;
use Modules\Accounting\Models\JournalEntry;
use Modules\Accounting\Services\CashReportService;
use Modules\Accounting\Services\ReportService;
use Modules\Core\Http\Controllers\BaseController;

class CashBankBookReportController extends BaseController
{
    public function index(DateRangeReportRequest $request, CashReportService $cashReports, ReportService $reports): View
    {
        $filters = $request->filters();
        $branchId = (int) $request->attributes->get('branch_id');
        $cashAccounts = $cashReports->cashAccounts()->keyBy('id');
        $cashIds = $cashAccounts->where('cash_kind', 'cash')->pluck('id')->all();
        $bankIds = $cashAccounts->where('cash_kind', 'bank')->pluck('id')->all();
        $totals = $reports->accounts($branchId, $filters['from_date'], $filters['to_date'])
            ->whereIn('id', $cashAccounts->keys())
            ->get();
        $cashTotals = $totals->whereIn('id', $cashIds);
        $bankTotals = $totals->whereIn('id', $bankIds);
        $opening = [
            'cash' => $reports->total($cashTotals, 'opening_debit')->minus($reports->total($cashTotals, 'opening_credit')),
            'bank' => $reports->total($bankTotals, 'opening_debit')->minus($reports->total($bankTotals, 'opening_credit')),
        ];
        $closing = [
            'cash' => $reports->balance($cashTotals),
            'bank' => $reports->balance($bankTotals),
        ];

        // A book retains transfers between cash and bank; neither side is an external receipt or payment.
        $ordered = $cashReports->cashLines($branchId, $cashAccounts->keys()->all())
            ->duringDates($filters['from_date'], $filters['to_date'])
            ->with('journalEntry:id,branch_id,entry_date,number,reference,narration')
            ->orderBy(JournalEntry::select('entry_date')
                ->whereColumn('id', 'acc_journal_entry_details.journal_entry_id'))
            ->orderBy('journal_entry_id')
            ->orderBy('ordering')
            ->orderBy('id');
        $records = $this->paginateQuery(clone $ordered, $filters);
        $offset = ($records->currentPage() - 1) * $records->perPage();
        $pageOpening = $cashReports->pageOpening($ordered, $offset, $opening, ['cash' => $cashIds, 'bank' => $bankIds]);

        $data = [
            'pageTitle' => 'Cash & Bank Book',
            'filters' => $filters,
            'records' => $records,
            'cashAccounts' => $cashAccounts,
            'opening' => $opening,
            'closing' => $closing,
            'pageOpening' => $pageOpening,
            'periodDebit' => $reports->total($totals, 'period_debit'),
            'periodCredit' => $reports->total($totals, 'period_credit'),
        ];

        return view('accounting::reports.cash-bank-book', $data);
    }
}
