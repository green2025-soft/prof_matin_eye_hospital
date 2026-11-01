<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\Wastage;

class WastageService extends StockEntryService
{
    protected string $model = Wastage::class;

    protected function prepareEntry(Account $actor, Document $document, array $values, array $settings, Collection $previousLines): array
    {
        if (blank($document->reason)) {
            throw ValidationException::withMessages(['reason' => 'Enter a reason for this damage or wastage.']);
        }
        $lines = $this->items->prepareLines($document, $values['lines'] ?? []);
        foreach ($lines as $index => $line) {
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
        return $this->stock->issue($document, null, true);
    }
}
