<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Inventory\Models\Adjustment;
use Modules\Inventory\Models\Document;

class AdjustmentService extends StockEntryService
{
    protected string $model = Adjustment::class;

    protected function prepareEntry(Account $actor, Document $document, array $values, array $settings, Collection $previousLines): array
    {
        if (blank($document->reason)) {
            throw ValidationException::withMessages(['reason' => 'Enter a reason for this stock adjustment.']);
        }
        $lines = $this->items->prepareLines($document, $values['lines'] ?? []);
        foreach ($lines as $index => $line) {
            if (! in_array($line['direction'], ['increase', 'decrease'], true)) {
                throw ValidationException::withMessages(["lines.$index.direction" => 'Choose Increase or Decrease.']);
            }
            if ($line['direction'] === 'increase') {
                if ($document->product_type === 'medicine' && blank($line['expiry_date'])) {
                    throw ValidationException::withMessages(["lines.$index.expiry_date" => 'Enter the medicine expiry date.']);
                }
                $lines[$index]['stock_lot_id'] = null;
            }
        }

        $document->vat_mode = 'none';
        $document->vat_percent = '0.00';
        $document->subtotal_minor = $this->items->sum(array_column($lines, 'subtotal_minor'));
        $document->total_minor = $document->subtotal_minor;

        return $lines;
    }

    protected function postStock(Document $document): int
    {
        return $this->stock->adjust($document);
    }
}
