<?php

namespace Modules\Accounting\Services;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Builder;
use Modules\Accounting\Models\AccountHead;

class IncomeExpenseReportService
{
    public function __construct(private ReportService $reports) {}

    public function statement(int $branchId, array $periods, int $depth): array
    {
        $fiscal = fn (Builder $lines) => $lines
            ->postedInBranch($branchId)
            ->duringDates($periods['fiscal']['from_date'], $periods['fiscal']['to_date']);

        $heads = AccountHead::orderBy('code')
            ->get(['id', 'code', 'name', 'type', 'parent_id', 'depth'])
            ->keyBy('id');
        $records = $this->reports->accounts($branchId, $periods['current']['from_date'], $periods['current']['to_date'])
            ->whereIn('type', ['income', 'expense'])
            ->withSum(['journalLines as fiscal_debit' => $fiscal], 'debit')
            ->withSum(['journalLines as fiscal_credit' => $fiscal], 'credit')
            ->get();

        // Income is credit-normal; expenditure is debit-normal in both stated periods.
        foreach ($records as $account) {
            $normal = $account->type === 'income' ? -1 : 1;
            $account->current_amount = BigInteger::of($account->period_debit ?? 0)
                ->minus($account->period_credit ?? 0)
                ->multipliedBy($normal);
            $account->comparison_amount = BigInteger::of($account->fiscal_debit ?? 0)
                ->minus($account->fiscal_credit ?? 0)
                ->multipliedBy($normal);
        }

        $incomeAccounts = $records->where('type', 'income');
        $expenseAccounts = $records->where('type', 'expense');
        $income = $this->reports->total($incomeAccounts, 'current_amount');
        $expense = $this->reports->total($expenseAccounts, 'current_amount');
        $fiscalIncome = $this->reports->total($incomeAccounts, 'comparison_amount');
        $fiscalExpense = $this->reports->total($expenseAccounts, 'comparison_amount');

        return [
            'rows' => $this->reports->statementRows($records, $heads, $depth),
            'sections' => [
                'income' => [
                    'label' => 'A. Income',
                    'current' => $income,
                    'comparison' => $fiscalIncome,
                ],
                'expense' => [
                    'label' => 'B. Expenditure',
                    'current' => $expense,
                    'comparison' => $fiscalExpense,
                ],
            ],
            'income' => $income,
            'expense' => $expense,
            'profit' => $income->minus($expense),
            'fiscalProfit' => $fiscalIncome->minus($fiscalExpense),
        ];
    }
}
