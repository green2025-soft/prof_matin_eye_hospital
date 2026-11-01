<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\Transfer;

class TransferService extends StockEntryService
{
    protected string $model = Transfer::class;

    protected function prepareEntry(Account $actor, Document $document, array $values, array $settings, Collection $previousLines): array
    {
        $destinationId = (int) ($values['destination_branch_id'] ?? 0);
        $destination = $document->getConnection()->table('branches')
            ->where('id', $destinationId)->lockForUpdate()->first(['id']);
        if ($destinationId === $document->branch_id || $destination === null) {
            throw ValidationException::withMessages(['destination_branch_id' => 'Choose another branch.']);
        }
        if (blank($document->reason)) {
            throw ValidationException::withMessages(['reason' => 'Enter a reason for this branch transfer.']);
        }
        $document->destination_branch_id = $destinationId;
        $lines = $this->items->prepareLines($document, $values['lines'] ?? []);
        foreach ($lines as $index => $line) {
            $lines[$index]['batch_no'] = null;
            $lines[$index]['expiry_date'] = null;
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
        return $this->stock->transfer($document);
    }
}
