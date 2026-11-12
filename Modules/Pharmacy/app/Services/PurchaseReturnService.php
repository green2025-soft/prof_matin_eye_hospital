<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Models\ProductUnit;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\PurchaseReturn;
use Modules\Pharmacy\Models\StockEntry;

class PurchaseReturnService extends ReturnEntryService
{
    protected string $model = PurchaseReturn::class;

    public function invoiceData(Account $actor, StockEntry $record): array
    {
        $data = parent::invoiceData($actor, $record);
        $data['entry']->load(['supplier:id,name,contact_no,address', 'purchase:id,number', 'paymentType:id,name']);

        return $data;
    }

    protected function prepareForWrite(Account $actor, StockEntry $entry, array $values): Collection
    {
        if ($entry->exists) {
            $this->authorizeReturnUpdate($actor, $entry, $values);
            if ($entry->purchase_id !== (int) $values['purchase_id']) {
                throw ValidationException::withMessages(['purchase_id' => 'A return must retain its original invoice.']);
            }
            $this->reverseStock($entry);
            $entry->lines()->update(['superseded_at' => now(config('app.timezone'))]);
            $entry->unsetRelation('lines');
        }
        $entry->forceFill([
            'payment_type_id' => $values['payment_type_id'] ?? null,
            'total' => 0,
            'initial_paid' => 0,
        ]);

        return collect();
    }

    protected function prepareEntry(Account $actor, StockEntry $entry, array $values, array $settings, Collection $previousLines): array
    {
        $purchase = Purchase::whereKey($values['purchase_id'] ?? 0)
            ->lockForUpdate()
            ->first();
        if ($purchase === null || $purchase->branch_id !== $entry->branch_id
            || $purchase->product_type !== $entry->product_type || $purchase->voided_at !== null || $purchase->posted_at === null) {
            throw ValidationException::withMessages(['purchase_id' => 'Choose an active original invoice from this branch.']);
        }
        $entry->purchase_id = $purchase->id;
        $entry->supplier_id = $purchase->supplier_id;
        $inputs = $this->returnInputs($values['lines'] ?? []);
        if (count(array_unique(array_column($inputs, 'purchase_item_id'))) !== count($inputs)) {
            throw ValidationException::withMessages(['lines' => 'Add each original invoice line once, up to 100 lines.']);
        }
        $originals = $purchase->lines()
            ->with('variant:id,product_id')
            ->whereIn('id', array_column($inputs, 'purchase_item_id'))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        $units = ProductUnit::whereIn('id', array_column($inputs, 'product_unit_id'))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        $returnedAmounts = $this->returnedAmounts($purchase);
        $lines = [];
        foreach ($inputs as $index => $input) {
            $original = $originals->get((int) ($input['purchase_item_id'] ?? 0));
            if ($original === null) {
                throw ValidationException::withMessages(["lines.$index.purchase_item_id" => 'Choose a line from the original invoice.']);
            }
            $unitId = (int) ($input['product_unit_id'] ?? $original->product_unit_id);
            $line = $this->returnLine($original, $units->get($unitId), $input, $returnedAmounts->get($original->id), $index);
            $line['purchase_item_id'] = $original->id;
            $lines[] = $line;
        }
        $this->applyReturnTotals($entry, $purchase, $lines);
        $this->validatePaymentType($entry, $entry->initial_paid);

        return $lines;
    }

    protected function reverseStock(StockEntry $entry): void
    {
        $purchase = $entry->purchase()
            ->lockForUpdate()
            ->firstOrFail();
        $this->reverseReturnBalance($entry, $purchase);
        $originals = $purchase->lines()
            ->whereIn('id', $entry->lines()->select('purchase_item_id'))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        $returned = $entry->lines()
            ->orderBy('id')
            ->lockForUpdate()
            ->get();
        $this->stock->reverse($entry);
        foreach ($returned as $line) {
            $this->reverseReturnQuantity($originals->get($line->purchase_item_id), $line);
        }
    }

    public function returnedAmounts(Purchase $purchase, ?PurchaseReturn $excluding = null): Collection
    {
        $activeReturns = function ($query) use ($purchase, $excluding): void {
            $query->whereNull('superseded_at')
                ->whereHas('purchaseReturn', function ($query) use ($purchase, $excluding): void {
                    $query->where('purchase_id', $purchase->id)
                        ->where('branch_id', $purchase->branch_id)
                        ->where('product_type', $purchase->product_type)
                        ->whereNull('voided_at')
                        ->whereNull('deleted_at');
                    if ($excluding?->exists) {
                        $query->whereKeyNot($excluding->id);
                    }
                });
        };

        return $purchase->lines()
            ->select('id')
            ->withSum(['returns as discount' => $activeReturns], 'discount')
            ->withSum(['returns as invoice_discount' => $activeReturns], 'invoice_discount')
            ->withSum(['returns as vat' => $activeReturns], 'vat')
            ->withSum(['returns as total' => $activeReturns], 'total')
            ->get()
            ->keyBy('id');
    }

    protected function postStock(StockEntry $entry): int
    {
        return $this->stock->returnPurchase($entry);
    }

    protected function postJournal(Account $actor, StockEntry $entry, bool $created): void
    {
        $this->journals->sync(
            source: $entry,
            feature: 'pharmacy.'.$entry->product_type.'.purchase-return',
            entryDate: $entry->entry_date->toDateString(),
            branchId: $entry->branch_id,
            amounts: [
                'settlement' => $entry->initial_paid,
                'payable' => $entry->total - $entry->initial_paid,
                'inventory' => $entry->cost,
                'variance_loss' => max(0, $entry->cost - $entry->total),
                'variance_gain' => max(0, $entry->total - $entry->cost),
            ],
            created: $created,
            paymentTypeId: $entry->payment_type_id,
            actor: $actor,
            reference: $entry->number,
        );
    }
}
