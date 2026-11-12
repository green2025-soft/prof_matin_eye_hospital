<?php

namespace Modules\Accounting\Services;

use Brick\Math\BigInteger;
use Generator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Accounting\Models\AccountHead;
use Modules\Accounting\Models\JournalEntry;
use Modules\Accounting\Models\JournalEntryDetail;

class CashReportService
{
    public function __construct(private ReportService $reports) {}

    public function accounts(): Collection
    {
        return AccountHead::orderBy('code')
            ->get(['id', 'parent_id', 'code', 'name', 'type', 'depth', 'system_key', 'cash_flow_activity'])
            ->keyBy('id');
    }

    // Historical cash ledgers keep their inherited role even after being disabled.
    public function cashAccounts(?Collection $heads = null): Collection
    {
        $heads ??= $this->accounts();
        $accounts = collect();

        foreach ($heads->where('depth', 5)->where('type', 'asset') as $account) {
            $ancestor = $account;
            while ($ancestor !== null && $ancestor->system_key === null) {
                $ancestor = $heads->get($ancestor->parent_id);
            }

            $kind = ['cash_in_hand' => 'cash', 'cash_at_bank' => 'bank'][$ancestor?->system_key] ?? null;
            if ($kind !== null) {
                $account->setAttribute('cash_kind', $kind);
                $accounts->put($account->id, $account);
            }
        }

        return $accounts;
    }

    public function cashLines(int $branchId, array $cashLedgerIds): Builder
    {
        return JournalEntryDetail::postedInBranch($branchId)
            ->whereIn('account_head_id', $cashLedgerIds);
    }

    public function pageOpening(Builder $ordered, int $offset, array $opening, array $groups): array
    {
        if ($offset === 0) {
            return $opening;
        }

        $previous = (clone $ordered)->withoutEagerLoads()
            ->select(['account_head_id', 'debit', 'credit'])
            ->limit($offset);
        $previousLines = JournalEntryDetail::fromSub($previous->toBase(), 'previous_cash_lines');

        foreach ($groups as $kind => $ids) {
            $lines = (clone $previousLines)->whereIn('account_head_id', $ids);
            $opening[$kind] = BigInteger::of($opening[$kind])
                ->plus((clone $lines)->sum('debit'))
                ->minus((clone $lines)->sum('credit'));
        }

        return $opening;
    }

    public function activity(AccountHead $account, Collection $heads): string
    {
        while ($account->cash_flow_activity === null && $account->parent_id !== null) {
            $account = $heads->get($account->parent_id);
        }

        return $account->cash_flow_activity ?? 'unclassified';
    }

    public function addToPeriods(array &$row, array $periods, string $date, BigInteger $amount): void
    {
        foreach ($periods as $key => $period) {
            if ($date >= $period['from_date'] && $date <= $period['to_date']) {
                $row[$key] = $row[$key]->plus($amount);
            }
        }
    }

    // Both statements reconcile the same cash balances for their displayed periods.
    public function periodBalances(int $branchId, Collection $cashAccounts, Collection $heads, array $periods, int $depth): array
    {
        $balances = [];

        foreach ($periods as $key => $period) {
            $ledgerBalances = $this->reports->accounts($branchId, $period['from_date'], $period['to_date'])
                ->whereIn('id', $cashAccounts->keys())
                ->get()
                ->keyBy('id');
            $rows = [];
            $openingTotal = $closingTotal = BigInteger::zero();

            foreach ($cashAccounts as $account) {
                $group = $this->reports->groupAccount($account, $heads, $depth);
                $ledger = $ledgerBalances->get($account->id);
                $opening = BigInteger::of($ledger?->opening_debit ?? 0)
                    ->minus($ledger?->opening_credit ?? 0);
                $closing = $opening->plus($ledger?->period_debit ?? 0)
                    ->minus($ledger?->period_credit ?? 0);
                $rows[$group->id] ??= [
                    'account' => $group,
                    'opening' => BigInteger::zero(),
                    'closing' => BigInteger::zero(),
                ];
                $rows[$group->id]['opening'] = $rows[$group->id]['opening']->plus($opening);
                $rows[$group->id]['closing'] = $rows[$group->id]['closing']->plus($closing);
                $openingTotal = $openingTotal->plus($opening);
                $closingTotal = $closingTotal->plus($closing);
            }

            $balances[$key] = [
                'rows' => collect($rows)->sortBy('account.code'),
                'opening' => $openingTotal,
                'closing' => $closingTotal,
            ];
        }

        return $balances;
    }

    // A single journal's cash net and opposite account movements stay together.
    public function movements(int $branchId, array $cashLedgerIds, string $from, string $to): Generator
    {
        $cashIds = array_fill_keys($cashLedgerIds, true);
        $entries = JournalEntry::active()
            ->where('branch_id', $branchId)
            ->whereBetween('entry_date', [$from, $to])
            ->whereHas('lines', fn (Builder $lines) => $lines->whereIn('account_head_id', $cashLedgerIds))
            ->with('lines:id,journal_entry_id,account_head_id,debit,credit')
            ->lazyById(100);

        foreach ($entries as $entry) {
            $cash = BigInteger::zero();
            $counterparts = [];

            foreach ($entry->lines as $line) {
                $net = BigInteger::of($line->debit)->minus($line->credit);
                if (isset($cashIds[$line->account_head_id])) {
                    $cash = $cash->plus($net);
                } else {
                    $counterparts[$line->account_head_id] = ($counterparts[$line->account_head_id] ?? BigInteger::zero())
                        ->minus($net);
                }
            }

            // Internal transfers and non-cash vouchers have no external cash movement.
            if ($cash->isZero()) {
                continue;
            }

            yield [
                'entry' => $entry,
                'cash' => $cash,
                'counterparts' => array_filter($counterparts, fn (BigInteger $amount) => ! $amount->isZero()),
            ];
        }
    }
}
