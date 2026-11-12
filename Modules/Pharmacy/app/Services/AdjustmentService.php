<?php

namespace Modules\Pharmacy\Services;

use Brick\Math\BigInteger;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Models\Adjustment;
use Modules\Pharmacy\Models\StockEntry;

class AdjustmentService extends StockEntryService
{
    protected string $model = Adjustment::class;

    protected function prepareEntry(Account $actor, StockEntry $entry, array $values, array $settings, Collection $previousLines): array
    {
        $entry->reason = $values['reason'] ?? null;
        $entry->stock_method = $settings['stock_method'];
        if (blank($entry->reason)) {
            throw ValidationException::withMessages(['reason' => 'Enter a reason for this stock adjustment.']);
        }
        $inputs = $values['lines'] ?? [];
        $lines = $this->items->prepareQuantities($entry, $inputs);
        foreach ($lines as $index => $line) {
            $input = $inputs[$index];
            $line['direction'] = $input['direction'] ?? null;
            if (! in_array($line['direction'], ['increase', 'decrease'], true)) {
                throw ValidationException::withMessages(["lines.$index.direction" => 'Choose Increase or Decrease.']);
            }
            if ($line['direction'] === 'increase' && $entry->product_type === 'medicine' && blank($input['expiry_date'] ?? null)) {
                throw ValidationException::withMessages(["lines.$index.expiry_date" => 'Enter the medicine expiry date.']);
            }
            $price = $line['direction'] === 'increase' ? Money::minor($input['unit_price'] ?? '0') : 0;
            $line['unit_price'] = $price;
            $line['cost'] = $this->items->bounded(BigInteger::of($price)->multipliedBy($line['quantity']));
            $line['batch_no'] = $input['batch_no'] ?? null;
            $line['expiry_date'] = $input['expiry_date'] ?? null;
            $line['stock_lot_id'] = $line['direction'] === 'decrease' ? ($input['stock_lot_id'] ?? null) : null;
            $lines[$index] = $line;
        }

        return $lines;
    }

    protected function postStock(StockEntry $entry): int
    {
        return $this->stock->adjust($entry);
    }

    protected function postJournal(Account $actor, StockEntry $entry, bool $created): void
    {
        $increase = (int) $entry->lines()->where('direction', 'increase')->sum('cost');
        $decrease = (int) $entry->lines()->where('direction', 'decrease')->sum('cost');

        $this->journals->sync(
            source: $entry,
            feature: 'pharmacy.'.$entry->product_type.'.adjustment',
            entryDate: $entry->entry_date->toDateString(),
            branchId: $entry->branch_id,
            amounts: [
                'inventory_in' => $increase,
                'gain' => $increase,
                'loss' => $decrease,
                'inventory_out' => $decrease,
            ],
            created: $created,
            actor: $actor,
            reference: $entry->number,
        );
    }
}
