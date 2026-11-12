<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Models\StockEntry;
use Modules\Pharmacy\Models\Transfer;
use Modules\TenantCore\Models\Branch;

class TransferService extends StockEntryService
{
    protected string $model = Transfer::class;

    public function invoiceData(Account $actor, StockEntry $record): array
    {
        $data = parent::invoiceData($actor, $record);
        $data['entry']->load('destinationBranch:id,name');

        return $data;
    }

    protected function prepareEntry(Account $actor, StockEntry $entry, array $values, array $settings, Collection $previousLines): array
    {
        $entry->reason = $values['reason'] ?? null;
        $entry->stock_method = $settings['stock_method'];
        $destinationId = (int) ($values['destination_branch_id'] ?? 0);
        $destination = Branch::whereKey($destinationId)
            ->where('is_default', false)
            ->lockForUpdate()
            ->first(['id']);
        if ($destinationId === $entry->branch_id || $destination === null) {
            throw ValidationException::withMessages(['destination_branch_id' => 'Choose another branch.']);
        }
        if (blank($entry->reason)) {
            throw ValidationException::withMessages(['reason' => 'Enter a reason for this branch transfer.']);
        }
        $entry->destination_branch_id = $destinationId;

        return $this->items->prepareQuantities($entry, $values['lines'] ?? []);
    }

    protected function postStock(StockEntry $entry): int
    {
        return $this->stock->transfer($entry);
    }

    protected function postJournal(Account $actor, StockEntry $entry, bool $created): void
    {
        $this->journals->sync(
            source: $entry,
            feature: 'pharmacy.'.$entry->product_type.'.transfer-out',
            entryDate: $entry->entry_date->toDateString(),
            branchId: $entry->branch_id,
            amounts: [
                'clearing' => $entry->cost,
                'inventory' => $entry->cost,
            ],
            created: $created,
            actor: $actor,
            reference: $entry->number,
        );

        $this->journals->sync(
            source: $entry,
            feature: 'pharmacy.'.$entry->product_type.'.transfer-in',
            entryDate: $entry->entry_date->toDateString(),
            branchId: $entry->destination_branch_id,
            amounts: [
                'inventory' => $entry->cost,
                'clearing' => $entry->cost,
            ],
            created: $created,
            actor: $actor,
            reference: $entry->number,
        );
    }
}
