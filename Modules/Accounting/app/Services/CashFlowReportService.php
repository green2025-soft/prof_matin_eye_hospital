<?php

namespace Modules\Accounting\Services;

use Brick\Math\BigInteger;

class CashFlowReportService
{
    public function __construct(private CashReportService $cash, private ReportService $reports) {}

    public function statement(int $branchId, array $periods, int $depth): array
    {
        $heads = $this->cash->accounts();
        $cashAccounts = $this->cash->cashAccounts($heads);
        $balances = $this->cash->periodBalances($branchId, $cashAccounts, $heads, $periods, $depth);
        $groups = ['operating' => [], 'investing' => [], 'financing' => [], 'unclassified' => []];
        $movements = $this->cash->movements($branchId, $cashAccounts->keys()->all(), $periods['fiscal']['from_date'], $periods['current']['to_date']);

        foreach ($movements as $movement) {
            $entry = $movement['entry'];
            $date = $entry->entry_date->toDateString();
            $activities = [];
            $opposite = false;

            foreach ($movement['counterparts'] as $accountId => $amount) {
                $activities[$accountId] = $this->cash->activity($heads->get($accountId), $heads);
                $opposite = $opposite || $amount->isNegative() !== $movement['cash']->isNegative();
            }

            // A mixed, offsetting voucher does not identify which activity owns its cash.
            if ($opposite && count(array_unique($activities)) > 1) {
                $id = 'journal-'.$entry->id;
                $groups['unclassified'][$id] = [
                    'account' => null,
                    'entry' => $entry,
                ] + array_fill_keys(array_keys($periods), BigInteger::zero());
                $this->cash->addToPeriods($groups['unclassified'][$id], $periods, $date, $movement['cash']);

                continue;
            }

            foreach ($movement['counterparts'] as $accountId => $amount) {
                $account = $this->reports->groupAccount($heads->get($accountId), $heads, $depth);
                $activity = $activities[$accountId];
                $groups[$activity][$account->id] ??= [
                    'account' => $account,
                    'entry' => null,
                ] + array_fill_keys(array_keys($periods), BigInteger::zero());
                $this->cash->addToPeriods($groups[$activity][$account->id], $periods, $date, $amount);
            }
        }

        $totals = [];
        foreach ($periods as $key => $period) {
            foreach ($groups as $activity => $rows) {
                $totals[$key][$activity] = BigInteger::sum(0, ...array_column($rows, $key));
            }
            $totals[$key]['net_change'] = BigInteger::sum(...array_values($totals[$key]));
            $totals[$key]['difference'] = $balances[$key]['opening']
                ->plus($totals[$key]['net_change'])
                ->minus($balances[$key]['closing']);
        }

        return [
            'cashAccounts' => $cashAccounts,
            'balances' => $balances,
            'groups' => $groups,
            'totals' => $totals,
        ];
    }
}
