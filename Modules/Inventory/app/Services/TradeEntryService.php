<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Inventory\Models\StockEntry;

abstract class TradeEntryService extends StockEntryService
{
    protected function reverseStock(StockEntry $entry): void
    {
        $hasReturns = $entry->returns()->whereNull('voided_at')->exists();
        $hasPayments = $entry->paymentAllocations()
            ->whereHas('payment', fn ($query) => $query->whereNull('voided_at'))->exists();
        if ($hasReturns || $hasPayments) {
            throw ValidationException::withMessages(['record' => 'Void linked payments and returns before changing this invoice.']);
        }

        parent::reverseStock($entry);
    }

    protected function prepareForWrite(Account $actor, StockEntry $entry, array $values): Collection
    {
        $previousLines = collect();
        if ($entry->exists) {
            $this->inventory->authorize($actor, $entry->crudPermission().'.update');
            Gate::forUser($actor)->authorize('update', $entry);
            if ($entry->product_type !== $values['product_type']) {
                throw ValidationException::withMessages(['product_type' => 'An invoice must retain its original product type.']);
            }
            $previousLines = $entry->lines()->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        }
        $this->validateOriginalLines($values['lines'] ?? [], $previousLines);
        if ($entry->exists) {
            $this->reverseStock($entry);
            $entry->lines()->update(['superseded_at' => now(config('app.timezone'))]);
            $entry->unsetRelation('lines');
        }
        $entry->forceFill([
            'payment_type_id' => $values['payment_type_id'] ?? null,
            'subtotal' => 0, 'discount' => 0, 'vat' => 0, 'total' => 0,
            'paid' => 0, 'initial_paid' => 0, 'returned' => 0, 'refunded' => 0,
            'settlement_discount' => 0, 'outstanding' => 0,
        ]);

        return $previousLines;
    }

    private function validateOriginalLines(array $inputs, Collection $previousLines): void
    {
        $usedIds = [];
        foreach ($inputs as $index => $input) {
            if (blank($input['original_line_id'] ?? null)) {
                continue;
            }
            $id = (int) $input['original_line_id'];
            $previous = $previousLines->get($id);
            if ($previous === null || isset($usedIds[$id]) || $previous->variant_id !== (int) ($input['variant_id'] ?? 0)) {
                throw ValidationException::withMessages(["lines.$index.original_line_id" => 'Choose each original line once from the current invoice, keeping the same product.']);
            }
            $usedIds[$id] = true;
        }
    }

    protected function calculateTotals(StockEntry $entry, array $lines, array $values): array
    {
        $discount = Money::minor($values['discount'] ?? '0');
        $this->setDiscountTotals($entry, $lines, $discount);
        [$lines, $netAmounts] = $this->allocateInvoiceDiscount($lines, $discount);
        $lines = $this->applyVat($entry, $lines, $netAmounts);
        $lines = $this->completeLineAmounts($lines, $netAmounts);

        $entry->vat = $this->items->sum(array_column($lines, 'vat'));
        $entry->total = $this->items->sum(array_column($lines, 'total'));
        $this->applyPayment($entry, Money::minor($values['paid_amount'] ?? '0'));

        return $lines;
    }

    abstract protected function applyVat(StockEntry $entry, array $lines, array $weights): array;

    private function setDiscountTotals(StockEntry $entry, array $lines, int $discount): void
    {
        $subtotal = $this->items->sum(array_column($lines, 'subtotal'));
        $lineDiscount = $this->items->sum(array_column($lines, 'discount'));
        if ($discount > $subtotal - $lineDiscount) {
            throw ValidationException::withMessages(['discount' => 'Discount cannot exceed the remaining invoice amount.']);
        }

        $entry->subtotal = $subtotal;
        $entry->discount = $lineDiscount + $discount;
    }

    private function allocateInvoiceDiscount(array $lines, int $discount): array
    {
        // Keep submitted row keys so allocation stays aligned after a row is removed.
        $netAmounts = array_map(static fn (array $line): int => $line['total'], $lines);
        $discounts = $this->items->allocate($discount, $netAmounts);
        foreach ($lines as $index => $line) {
            $line['invoice_discount'] = $discounts[$index];
            $netAmounts[$index] -= $discounts[$index];
            $lines[$index] = $line;
        }

        return [$lines, $netAmounts];
    }

    private function completeLineAmounts(array $lines, array $netAmounts): array
    {
        foreach ($lines as $index => $line) {
            $line['total'] = $netAmounts[$index] + ($line['vat_mode'] === 'exclusive' ? $line['vat'] : 0);
            $line['cost'] = $line['total'];
            $lines[$index] = $line;
        }

        return $lines;
    }

    private function applyPayment(StockEntry $entry, int $paid): void
    {
        if ($paid > $entry->total) {
            throw ValidationException::withMessages(['paid_amount' => 'Payment cannot exceed the invoice total.']);
        }
        $entry->paid = $paid;
        $entry->initial_paid = $paid;
    }
}
