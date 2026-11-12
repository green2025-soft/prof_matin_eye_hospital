<?php

namespace Modules\Accounting\Http\Controllers\Reports;

use Brick\Math\BigInteger;
use Illuminate\View\View;
use Modules\Accounting\Http\Requests\LedgerReportRequest;
use Modules\Accounting\Models\AccountHead;
use Modules\Accounting\Models\JournalEntry;
use Modules\Accounting\Models\JournalEntryDetail;
use Modules\Core\Http\Controllers\BaseController;

class LedgerReportController extends BaseController
{
    public function index(LedgerReportRequest $request): View
    {
        $filters = $request->filters();
        $selectedAccount = isset($filters['account_head_id']) ? AccountHead::findOrFail($filters['account_head_id']) : null;
        $records = null;
        $opening = $periodDebit = $periodCredit = $closing = $pageOpening = BigInteger::zero();

        if ($selectedAccount !== null) {
            $lines = JournalEntryDetail::postedInBranch((int) $request->attributes->get('branch_id'))
                ->where('account_head_id', $selectedAccount->id);
            $prior = (clone $lines)->beforeDate($filters['from_date']);
            $opening = BigInteger::of((clone $prior)->sum('debit'))
                ->minus((clone $prior)->sum('credit'));
            $period = (clone $lines)->duringDates($filters['from_date'], $filters['to_date']);
            $periodDebit = BigInteger::of((clone $period)->sum('debit'));
            $periodCredit = BigInteger::of((clone $period)->sum('credit'));
            $closing = $opening->plus($periodDebit)->minus($periodCredit);

            $ordered = $period
                ->with(['journalEntry:id,branch_id,number,entry_date,reference,narration,voucher_type_id', 'journalEntry.voucherType:id,name'])
                ->orderBy(JournalEntry::select('entry_date')
                    ->whereColumn('id', 'acc_journal_entry_details.journal_entry_id'))
                ->orderBy('journal_entry_id')
                ->orderBy('ordering')
                ->orderBy('id');
            $records = $this->paginateQuery(clone $ordered, $filters);
            $pageOpening = $opening;

            // Previous pages affect the running balance without loading their journal lines.
            $offset = ($records->currentPage() - 1) * $records->perPage();
            if ($offset > 0) {
                $previous = (clone $ordered)
                    ->withoutEagerLoads()
                    ->select(['debit', 'credit'])
                    ->limit($offset);
                $previousRows = JournalEntryDetail::fromSub($previous->toBase(), 'previous_lines');
                $pageOpening = $opening
                    ->plus((clone $previousRows)->sum('debit'))
                    ->minus((clone $previousRows)->sum('credit'));
            }
        }

        $data = [
            'pageTitle' => 'Account Ledger',
            'filters' => $filters,
            'selectedAccount' => $selectedAccount,
            'records' => $records,
            'opening' => $opening,
            'periodDebit' => $periodDebit,
            'periodCredit' => $periodCredit,
            'closing' => $closing,
            'pageOpening' => $pageOpening,
        ];

        return view('accounting::reports.ledger', $data);
    }
}
