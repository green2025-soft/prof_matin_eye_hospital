<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Inventory\Models\Document;

abstract class TradeEntryService extends StockEntryService
{
    protected function prepareForWrite(Account $actor, Document $document, array $values): Collection
    {
        $previousLines = collect();
        if ($document->exists) {
            $this->inventory->authorize($actor, $document->crudPermission().'.update');
            Gate::forUser($actor)->authorize('update', $document);
            if ($document->product_type !== $values['product_type']) {
                throw ValidationException::withMessages(['product_type' => 'An invoice must retain its original product type.']);
            }
            $previousLines = $document->lines()->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        }
        $this->validateOriginalLines($values['lines'] ?? [], $previousLines);
        if (! $document->exists) {
            return $previousLines;
        }
        $this->assertNoActiveLinks($document, 'editing');
        $this->stock->reverse($document);
        $document->lines()->update(['superseded_at' => now(config('app.timezone'))]);
        $document->unsetRelation('lines');

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

    protected function calculateTotals(Document $document, array $lines, array $values): array
    {
        $discount = Money::minor($values['discount'] ?? '0');
        $this->setDiscountTotals($document, $lines, $discount);
        [$lines, $netAmounts] = $this->allocateInvoiceDiscount($lines, $discount);
        $lines = $this->applyVat($document, $lines, $netAmounts);
        $lines = $this->completeLineAmounts($lines, $netAmounts);

        $document->vat_minor = $this->items->sum(array_column($lines, 'vat_minor'));
        $document->total_minor = $this->items->sum(array_column($lines, 'total_minor'));
        $this->applyPayment($document, Money::minor($values['paid_amount'] ?? '0'));

        return $lines;
    }

    abstract protected function applyVat(Document $document, array $lines, array $weights): array;

    private function setDiscountTotals(Document $document, array $lines, int $discount): void
    {
        $subtotal = $this->items->sum(array_column($lines, 'subtotal_minor'));
        $lineDiscount = $this->items->sum(array_column($lines, 'discount_minor'));
        if ($discount > $subtotal - $lineDiscount) {
            throw ValidationException::withMessages(['discount' => 'Discount cannot exceed the remaining invoice amount.']);
        }

        $document->subtotal_minor = $subtotal;
        $document->discount_minor = $lineDiscount + $discount;
    }

    private function allocateInvoiceDiscount(array $lines, int $discount): array
    {
        // Keep submitted row keys so allocation stays aligned after a row is removed.
        $netAmounts = array_map(static fn (array $line): int => $line['total_minor'], $lines);
        $discounts = $this->items->allocate($discount, $netAmounts);
        foreach ($lines as $index => $line) {
            $line['invoice_discount_minor'] = $discounts[$index];
            $netAmounts[$index] -= $discounts[$index];
            $lines[$index] = $line;
        }

        return [$lines, $netAmounts];
    }

    private function completeLineAmounts(array $lines, array $netAmounts): array
    {
        foreach ($lines as $index => $line) {
            $line['total_minor'] = $netAmounts[$index] + ($line['vat_mode'] === 'exclusive' ? $line['vat_minor'] : 0);
            $line['cost_minor'] = $line['total_minor'];
            $lines[$index] = $line;
        }

        return $lines;
    }

    private function applyPayment(Document $document, int $paid): void
    {
        if ($paid > $document->total_minor) {
            throw ValidationException::withMessages(['paid_amount' => 'Payment cannot exceed the invoice total.']);
        }
        $document->paid_minor = $paid;
        $document->initial_paid_minor = $paid;
    }
}
