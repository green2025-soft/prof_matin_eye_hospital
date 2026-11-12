<?php

namespace Modules\Accounting\Services;

use Brick\Math\BigInteger;
use Modules\Accounting\Models\AccountHead;

class ChangesInEquityReportService
{
    public function __construct(private ReportService $reports) {}

    public function statement(int $branchId, array $periods, int $depth): array
    {
        $heads = AccountHead::where('type', 'equity')
            ->get(['id', 'code', 'name', 'parent_id', 'depth'])
            ->keyBy('id');
        $records = collect();
        $balances = [];

        foreach ($periods as $key => $period) {
            $accounts = $this->reports->accounts($branchId, $period['from_date'], $period['to_date'])
                ->whereIn('type', ['equity', 'income', 'expense'])
                ->get();
            $equity = $accounts->where('type', 'equity');
            $operating = $accounts->whereIn('type', ['income', 'expense']);
            $movements = collect();

            foreach ($equity as $account) {
                $group = $this->reports->groupAccount($account, $heads, $depth);
                $movement = BigInteger::of($account->period_credit ?? 0)->minus($account->period_debit ?? 0);
                $movements->put($group->id, ($movements->get($group->id) ?? BigInteger::zero())->plus($movement));
                $records->put($group->id, $group);
            }

            // Unclosed income and expense belong to equity until an explicit closing journal moves them.
            $opening = $this->reports->total($accounts, 'opening_credit')->minus($this->reports->total($accounts, 'opening_debit'));
            $unclosed = $this->reports->total($operating, 'period_credit')->minus($this->reports->total($operating, 'period_debit'));
            $equityMovement = $this->reports->total($equity, 'period_credit')->minus($this->reports->total($equity, 'period_debit'));
            $balances[$key] = [
                'opening' => $opening,
                'movements' => $movements,
                'unclosed' => $unclosed,
                'closing' => $opening->plus($equityMovement)->plus($unclosed),
            ];
        }

        return [
            'records' => $records->sortBy('code'),
            'balances' => $balances,
        ];
    }
}
