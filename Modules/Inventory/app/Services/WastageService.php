<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Inventory\Models\StockEntry;
use Modules\Inventory\Models\Wastage;

class WastageService extends StockEntryService
{
    protected string $model = Wastage::class;

    protected function prepareEntry(Account $actor, StockEntry $entry, array $values, array $settings, Collection $previousLines): array
    {
        $entry->reason = $values['reason'] ?? null;
        $entry->stock_method = $settings['stock_method'];
        if (blank($entry->reason)) {
            throw ValidationException::withMessages(['reason' => 'Enter a reason for this damage or wastage.']);
        }
        $inputs = $values['lines'] ?? [];
        $lines = $this->items->prepareQuantities($entry, $inputs);
        foreach ($lines as $index => $line) {
            $line['stock_lot_id'] = $inputs[$index]['stock_lot_id'] ?? null;
            $line['cost'] = 0;
            $lines[$index] = $line;
        }

        return $lines;
    }

    protected function postStock(StockEntry $entry): int
    {
        return $this->stock->issueWastage($entry);
    }
}
