<?php

namespace Modules\Inventory\Services;

use Brick\Math\BigInteger;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Inventory\Models\ProductUnit;
use Modules\Inventory\Models\StockEntry;
use Modules\Inventory\Models\StockItem;

abstract class ReturnEntryService extends StockEntryService
{
    protected function authorizeReturnUpdate(Account $actor, StockEntry $entry, array $values): void
    {
        $this->inventory->authorize($actor, $entry->crudPermission().'.update');
        Gate::forUser($actor)->authorize('update', $entry);
        if ($entry->product_type !== $values['product_type']) {
            throw ValidationException::withMessages(['product_type' => 'A return must retain its original product type.']);
        }
    }

    protected function returnInputs(array $inputs): array
    {
        if (count($inputs) > 100) {
            throw ValidationException::withMessages(['lines' => 'Add each original invoice line once, up to 100 lines.']);
        }
        $inputs = array_filter($inputs, static fn ($input): bool => ! is_array($input)
            || ! in_array($input['quantity'] ?? null, [0, '0'], true));
        if ($inputs === []) {
            throw ValidationException::withMessages(['lines' => 'Enter a return quantity for at least one item.']);
        }

        return $inputs;
    }

    protected function returnLine(StockItem $original, ?ProductUnit $unit,
        array $input, ?StockItem $returned, int|string $index): array
    {
        $unitId = (int) ($input['product_unit_id'] ?? $original->product_unit_id);
        $factor = $unitId === $original->product_unit_id ? $original->factor : $unit?->factor;
        $productId = $original->variant->product_id;
        if ($factor === null || ($unitId !== $original->product_unit_id && (int) $unit?->product_id !== (int) $productId)) {
            throw ValidationException::withMessages(["lines.$index.product_unit_id" => 'Choose a unit belonging to the original product.']);
        }
        $quantity = (int) ($input['quantity'] ?? 0);
        if ($quantity < 1 || $quantity > 1_000_000_000) {
            throw ValidationException::withMessages(["lines.$index.quantity" => 'Enter a valid positive whole quantity.']);
        }
        $baseQuantity = $this->items->bounded(BigInteger::of($quantity)->multipliedBy($factor));
        $remaining = $original->base_quantity - $original->returned_base_quantity;
        if ($baseQuantity > $remaining) {
            throw ValidationException::withMessages(["lines.$index.quantity" => 'Return quantity exceeds the remaining original quantity.']);
        }
        $line = [
            'variant_id' => $original->variant_id, 'product_unit_id' => $unitId,
            'quantity' => $quantity, 'factor' => $factor, 'base_quantity' => $baseQuantity,
            'unit_price' => $this->items->roundedRatio($original->unit_price, $factor, $original->factor),
            'vat_mode' => $original->vat_mode, 'cost' => 0,
        ];
        // Allocate remaining amounts so voiding an earlier return cannot duplicate a rounding penny.
        foreach (['discount', 'invoice_discount', 'vat', 'total'] as $field) {
            $available = $original->$field - (int) ($returned?->$field ?? 0);
            $line[$field] = $this->items->floorRatio($available, $baseQuantity, $remaining);
        }
        $line['subtotal'] = $line['total'] + $line['discount'] + $line['invoice_discount']
            - ($line['vat_mode'] === 'exclusive' ? $line['vat'] : 0);

        return $line;
    }

    protected function applyReturnTotals(StockEntry $entry, StockEntry $source, array $lines): void
    {
        if ($entry->entry_date->lt($source->entry_date)) {
            throw ValidationException::withMessages(['entry_date' => 'The return date cannot precede its invoice.']);
        }
        $entry->total = $this->items->sum(array_column($lines, 'total'));
        $returned = $source->returned + $entry->total;
        $payable = $source->total - $returned;
        if ($payable < $source->settlement_discount) {
            throw ValidationException::withMessages(['record' => 'Void the settlement discount payment before returning this amount.']);
        }
        $refund = max(0, $source->paid - $source->refunded + $source->settlement_discount - $payable);
        $source->returned = $returned;
        $source->refunded += $refund;
        $source->save();
        $entry->initial_paid = $refund;
    }

    protected function reverseReturnBalance(StockEntry $entry, StockEntry $source): void
    {
        $source->returned -= $entry->total;
        $source->refunded -= $entry->initial_paid;
        if ($source->returned < 0 || $source->refunded < 0) {
            throw ValidationException::withMessages(['record' => 'The original invoice balance has changed.']);
        }
        $source->save();
    }

    protected function reverseReturnQuantity(?StockItem $original, StockItem $returned): void
    {
        if ($original === null || $original->returned_base_quantity < $returned->base_quantity) {
            throw ValidationException::withMessages(['record' => 'The original invoice quantity has changed.']);
        }
        $original->decrement('returned_base_quantity', $returned->base_quantity);
    }
}
