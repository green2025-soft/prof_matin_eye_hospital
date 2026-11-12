<?php

namespace Modules\Accounting\Services;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Accounting\Models\AccountHead;

class ReportService
{
    // The full chart is already loaded; grouping never triggers parent queries.
    public function groupAccount(AccountHead $account, Collection $heads, int $depth): AccountHead
    {
        while ($account->depth > $depth) {
            $account = $heads->get($account->parent_id);
        }

        return $account;
    }

    // Both statements provide signed amounts; this rolls them up through the loaded chart.
    public function statementRows(Collection $accounts, Collection $heads, int $depth): Collection
    {
        $rows = collect();
        $note = 0;

        for ($level = 2; $level <= $depth; $level++) {
            $groups = $accounts->groupBy(fn (AccountHead $account): int => $this->groupAccount($account, $heads, $level)->id);

            foreach ($groups as $members) {
                $rows->push([
                    'account' => $this->groupAccount($members->first(), $heads, $level),
                    'accounts' => $members,
                    'current' => $this->total($members, 'current_amount'),
                    'comparison' => $this->total($members, 'comparison_amount'),
                    'note' => $level === $depth ? str_pad((string) ++$note, 2, '0', STR_PAD_LEFT) : null,
                ]);
            }
        }

        return $rows->sortBy('account.code')->values();
    }

    // Historical ledger activity remains visible when an account is later disabled.
    public function accounts(int $branchId, string $from, string $to): Builder
    {
        $posted = fn (Builder $lines) => $lines->postedInBranch($branchId);
        $opening = fn (Builder $lines) => $posted($lines)->beforeDate($from);
        $period = fn (Builder $lines) => $posted($lines)->duringDates($from, $to);

        return AccountHead::select(['id', 'code', 'name', 'type', 'parent_id', 'depth'])
            ->where('depth', 5)
            ->whereHas('journalLines', fn (Builder $lines) => $posted($lines)->throughDate($to))
            ->withSum(['journalLines as opening_debit' => $opening], 'debit')
            ->withSum(['journalLines as opening_credit' => $opening], 'credit')
            ->withSum(['journalLines as period_debit' => $period], 'debit')
            ->withSum(['journalLines as period_credit' => $period], 'credit')
            ->orderBy('code')
            ->orderBy('id');
    }

    public function total(Collection $accounts, string $column): BigInteger
    {
        return BigInteger::sum(0, ...$accounts->pluck($column)->filter()->all());
    }

    public function balance(Collection $accounts): BigInteger
    {
        return $this->total($accounts, 'opening_debit')
            ->plus($this->total($accounts, 'period_debit'))
            ->minus($this->total($accounts, 'opening_credit'))
            ->minus($this->total($accounts, 'period_credit'));
    }

    // Split each posting ledger before grouping so opposite balances never cancel.
    public function trialRows(Collection $ledgers, Collection $heads, int $depth): Collection
    {
        $rows = [];

        foreach ($ledgers as $ledger) {
            $group = $this->groupAccount($ledger, $heads, $depth);
            $opening = BigInteger::of($ledger->opening_debit ?? 0)
                ->minus($ledger->opening_credit ?? 0);
            $closing = $opening->plus($ledger->period_debit ?? 0)
                ->minus($ledger->period_credit ?? 0);
            $values = [
                'opening_debit' => $opening->isNegative() ? '0' : (string) $opening,
                'opening_credit' => $opening->isNegative() ? (string) $opening->abs() : '0',
                'period_debit' => $ledger->period_debit ?? '0',
                'period_credit' => $ledger->period_credit ?? '0',
                'closing_debit' => $closing->isNegative() ? '0' : (string) $closing,
                'closing_credit' => $closing->isNegative() ? (string) $closing->abs() : '0',
            ];
            $rows[$group->id] ??= (clone $group)->forceFill(array_fill_keys(array_keys($values), '0'));

            foreach ($values as $field => $amount) {
                $rows[$group->id]->setAttribute($field, (string) BigInteger::of($rows[$group->id]->getAttribute($field) ?? 0)
                    ->plus($amount));
            }
        }

        return collect($rows)->sortBy('code')->values();
    }
}
