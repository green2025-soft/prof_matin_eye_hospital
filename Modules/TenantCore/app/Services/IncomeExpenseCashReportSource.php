<?php

namespace Modules\TenantCore\Services;

use Closure;
use Modules\Core\Helpers\Money;
use Modules\TenantCore\Contracts\CashReportSource;
use Modules\TenantCore\Models\Expense;
use Modules\TenantCore\Models\Income;

class IncomeExpenseCashReportSource implements CashReportSource
{
    public function modules(): array
    {
        return ['general'];
    }

    public function collect(string $through, Closure $record): void
    {
        Income::with('head:id,name')
            ->where('entry_date', '<=', $through)
            ->chunkById(200, function ($entries) use ($record): void {
                foreach ($entries as $entry) {
                    $record([
                        'date' => $entry->entry_date->toDateString(),
                        'module' => 'general',
                        'label' => 'Income: '.$entry->head->name,
                        'received' => Money::minor($entry->amount),
                        'paid' => 0,
                        'receivable' => 0,
                        'payable' => 0,
                    ]);
                }
            });
        Expense::with('head:id,name')
            ->where('entry_date', '<=', $through)
            ->chunkById(200, function ($entries) use ($record): void {
                foreach ($entries as $entry) {
                    $record([
                        'date' => $entry->entry_date->toDateString(),
                        'module' => 'general',
                        'label' => 'Expense: '.$entry->head->name,
                        'received' => 0,
                        'paid' => Money::minor($entry->amount),
                        'receivable' => 0,
                        'payable' => 0,
                    ]);
                }
            });
    }
}
