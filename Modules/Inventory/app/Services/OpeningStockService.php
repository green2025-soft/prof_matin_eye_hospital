<?php

namespace Modules\Inventory\Services;

use Brick\Math\BigInteger;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Inventory\Models\OpeningStock;
use Modules\Inventory\Models\StockEntry;

class OpeningStockService extends StockEntryService
{
    protected string $model = OpeningStock::class;

    protected function prepareEntry(Account $actor, StockEntry $entry, array $values, array $settings, Collection $previousLines): array
    {
        $inputs = $values['lines'] ?? [];
        $lines = $this->items->prepareQuantities($entry, $inputs);
        foreach ($lines as $index => $line) {
            $input = $inputs[$index];
            if ($entry->product_type === 'medicine' && blank($input['expiry_date'] ?? null)) {
                throw ValidationException::withMessages(["lines.$index.expiry_date" => 'Enter the medicine expiry date.']);
            }
            $price = Money::minor($input['unit_price'] ?? '0');
            $line['unit_price'] = $price;
            $line['cost'] = $this->items->bounded(BigInteger::of($price)->multipliedBy($line['quantity']));
            $line['batch_no'] = $input['batch_no'] ?? null;
            $line['expiry_date'] = $input['expiry_date'] ?? null;
            $lines[$index] = $line;
        }

        return $lines;
    }

    protected function postStock(StockEntry $entry): int
    {
        return $this->stock->receive($entry);
    }
}
