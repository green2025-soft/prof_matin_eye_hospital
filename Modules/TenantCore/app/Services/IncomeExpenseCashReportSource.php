<?php

namespace Modules\TenantCore\Services;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Helpers\Money;
use Modules\TenantCore\Contracts\CashReportSource;
use Modules\TenantCore\Models\ExpenseItem;
use Modules\TenantCore\Models\IncomeItem;

class IncomeExpenseCashReportSource implements CashReportSource
{
    public function modules(): array
    {
        return ['general'];
    }

    public function heads(): array
    {
        return [
            'General Income' => 'received',
            'General Expense' => 'paid',
        ];
    }

    public function collect(string $through, Closure $record, ?string $from = null): void
    {
        IncomeItem::select(['id', 'income_id', 'head_id', 'amount'])
            ->with(['income:id,entry_date', 'head:id,name'])
            ->whereHas('income', fn (Builder $query) => $query
                ->where('entry_date', '<=', $through)
                ->when($from, fn (Builder $query) => $query->where('entry_date', '>=', $from)))
            ->eachById(function (IncomeItem $item) use ($record): void {
                $record([
                    'date' => $item->income->entry_date->toDateString(),
                    'module' => 'general',
                    'label' => 'General Income',
                    'reference' => 'INC-'.$item->income_id,
                    'description' => $item->head->name,
                    'received' => Money::minor($item->amount),
                    'paid' => 0,
                    'receivable' => 0,
                    'payable' => 0,
                ]);
            }, 200);

        ExpenseItem::select(['id', 'expense_id', 'head_id', 'amount'])
            ->with(['expense:id,entry_date', 'head:id,name'])
            ->whereHas('expense', fn (Builder $query) => $query
                ->where('entry_date', '<=', $through)
                ->when($from, fn (Builder $query) => $query->where('entry_date', '>=', $from)))
            ->eachById(function (ExpenseItem $item) use ($record): void {
                $record([
                    'date' => $item->expense->entry_date->toDateString(),
                    'module' => 'general',
                    'label' => 'General Expense',
                    'reference' => 'EXP-'.$item->expense_id,
                    'description' => $item->head->name,
                    'received' => 0,
                    'paid' => Money::minor($item->amount),
                    'receivable' => 0,
                    'payable' => 0,
                ]);
            }, 200);
    }
}
