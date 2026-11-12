<?php

namespace Modules\Accounting\Services;

use Brick\Math\BigInteger;

class ReceiptsPaymentsReportService
{
    public function __construct(private CashReportService $cash, private ReportService $reports) {}

    public function statement(int $branchId, array $periods, int $depth): array
    {
        $heads = $this->cash->accounts();
        $cashAccounts = $this->cash->cashAccounts($heads);
        $balances = $this->cash->periodBalances($branchId, $cashAccounts, $heads, $periods, $depth);
        $groups = ['receipts' => [], 'payments' => []];
        $movements = $this->cash->movements($branchId, $cashAccounts->keys()->all(), $periods['fiscal']['from_date'], $periods['current']['to_date']);

        foreach ($movements as $movement) {
            $side = $movement['cash']->isPositive() ? 'receipts' : 'payments';
            $date = $movement['entry']->entry_date->toDateString();

            foreach ($movement['counterparts'] as $accountId => $amount) {
                $account = $this->reports->groupAccount($heads->get($accountId), $heads, $depth);
                $groups[$side][$account->id] ??= [
                    'account' => $account,
                ] + array_fill_keys(array_keys($periods), BigInteger::zero());

                // Opposite-side legs remain signed deductions instead of overstating cash.
                $amount = $movement['cash']->isPositive() ? $amount : $amount->negated();
                $this->cash->addToPeriods($groups[$side][$account->id], $periods, $date, $amount);
            }
        }

        $totals = [];
        foreach ($periods as $key => $period) {
            $receipts = BigInteger::sum(0, ...array_column($groups['receipts'], $key));
            $payments = BigInteger::sum(0, ...array_column($groups['payments'], $key));
            $totals[$key] = [
                'receipts' => $receipts,
                'payments' => $payments,
                'total_receipts' => $balances[$key]['opening']->plus($receipts),
                'total_payments' => $payments->plus($balances[$key]['closing']),
                'difference' => $balances[$key]['opening']->plus($receipts)
                    ->minus($payments)
                    ->minus($balances[$key]['closing']),
            ];
        }

        return [
            'cashAccounts' => $cashAccounts,
            'balances' => $balances,
            'groups' => $groups,
            'totals' => $totals,
        ];
    }
}
