<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\OpeningStock;

class OpeningStockService extends StockEntryService
{
    protected string $model = OpeningStock::class;

    protected function prepareEntry(Account $actor, Document $document, array $values, array $settings, Collection $previousLines): array
    {
        $lines = $this->items->prepareLines($document, $values['lines'] ?? []);
        foreach ($lines as $index => $line) {
            if ($document->product_type === 'medicine' && blank($line['expiry_date'])) {
                throw ValidationException::withMessages(["lines.$index.expiry_date" => 'Enter the medicine expiry date.']);
            }
            $lines[$index]['stock_lot_id'] = null;
            $lines[$index]['direction'] = null;
        }

        $document->vat_mode = 'none';
        $document->vat_percent = '0.00';
        $document->subtotal_minor = $this->items->sum(array_column($lines, 'subtotal_minor'));
        $document->total_minor = $document->subtotal_minor;

        return $lines;
    }

    protected function postStock(Document $document): int
    {
        return $this->stock->receive($document);
    }
}
