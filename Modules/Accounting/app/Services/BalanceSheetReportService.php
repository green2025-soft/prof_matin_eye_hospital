<?php

namespace Modules\Accounting\Services;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Builder;
use Modules\Accounting\Models\AccountHead;

class BalanceSheetReportService
{
    public function __construct(private ReportService $reports) {}

    public function statement(int $branchId, array $periods, int $depth): array
    {
        $date = $periods['current']['to_date'];
        $comparisonDate = $periods['previous_fiscal']['to_date'];
        $comparison = fn (Builder $lines) => $lines
            ->postedInBranch($branchId)
            ->throughDate($comparisonDate);

        $heads = AccountHead::orderBy('code')
            ->get(['id', 'code', 'name', 'type', 'parent_id', 'depth'])
            ->keyBy('id');
        $records = $this->reports->accounts($branchId, $date, $date)
            ->withSum(['journalLines as comparison_debit' => $comparison], 'debit')
            ->withSum(['journalLines as comparison_credit' => $comparison], 'credit')
            ->get();

        // Every balance is cumulative. Unclosed income and expense remain part of equity.
        foreach ($records as $account) {
            $normal = in_array($account->type, ['asset', 'expense'], true) ? 1 : -1;
            $account->current_amount = BigInteger::of($account->opening_debit ?? 0)
                ->plus($account->period_debit ?? 0)
                ->minus($account->opening_credit ?? 0)
                ->minus($account->period_credit ?? 0)
                ->multipliedBy($normal);
            $account->comparison_amount = BigInteger::of($account->comparison_debit ?? 0)
                ->minus($account->comparison_credit ?? 0)
                ->multipliedBy($normal);
        }

        $assets = $this->reports->total($records->where('type', 'asset'), 'current_amount');
        $liabilities = $this->reports->total($records->where('type', 'liability'), 'current_amount');
        $equity = $this->reports->total($records->where('type', 'equity'), 'current_amount');
        $earnings = $this->reports->total($records->where('type', 'income'), 'current_amount')
            ->minus($this->reports->total($records->where('type', 'expense'), 'current_amount'));
        $comparisonAssets = $this->reports->total($records->where('type', 'asset'), 'comparison_amount');
        $comparisonLiabilities = $this->reports->total($records->where('type', 'liability'), 'comparison_amount');
        $comparisonEquity = $this->reports->total($records->where('type', 'equity'), 'comparison_amount');
        $comparisonEarnings = $this->reports->total($records->where('type', 'income'), 'comparison_amount')
            ->minus($this->reports->total($records->where('type', 'expense'), 'comparison_amount'));
        $liabilitiesAndEquity = $liabilities->plus($equity)->plus($earnings);
        $comparisonLiabilitiesAndEquity = $comparisonLiabilities->plus($comparisonEquity)->plus($comparisonEarnings);

        return [
            'rows' => $this->reports->statementRows($records->whereIn('type', ['asset', 'liability', 'equity']), $heads, $depth),
            'sections' => [
                'asset' => [
                    'label' => 'Assets',
                    'current' => $assets,
                    'comparison' => $comparisonAssets,
                ],
                'liability' => [
                    'label' => 'Liabilities',
                    'current' => $liabilities,
                    'comparison' => $comparisonLiabilities,
                ],
                'equity' => [
                    'label' => 'Equity',
                    'current' => $equity->plus($earnings),
                    'comparison' => $comparisonEquity->plus($comparisonEarnings),
                ],
            ],
            'assets' => $assets,
            'earnings' => $earnings,
            'comparisonEarnings' => $comparisonEarnings,
            'liabilitiesAndEquity' => $liabilitiesAndEquity,
            'comparisonLiabilitiesAndEquity' => $comparisonLiabilitiesAndEquity,
            'difference' => $assets->minus($liabilitiesAndEquity),
            'comparisonDifference' => $comparisonAssets->minus($comparisonLiabilitiesAndEquity),
        ];
    }
}
