<?php

namespace Modules\Inventory\Services;

use Brick\Math\BigInteger;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\ProductUnit;

abstract class ReturnEntryService extends StockEntryService
{
    protected string $sourceModel;

    protected function prepareForWrite(Account $actor, Document $document, array $values): Collection
    {
        if (! $document->exists) {
            return collect();
        }
        $this->inventory->authorize($actor, $document->crudPermission().'.update');
        Gate::forUser($actor)->authorize('update', $document);
        if ($document->product_type !== $values['product_type']) {
            throw ValidationException::withMessages(['product_type' => 'A return must retain its original product type.']);
        }
        if ($document->source_document_id !== (int) $values['source_document_id']) {
            throw ValidationException::withMessages(['source_document_id' => 'A return must retain its original invoice.']);
        }

        $this->assertNoActiveLinks($document, 'editing');
        $this->reverseStock($document);
        $document->lines()->update(['superseded_at' => now(config('app.timezone'))]);
        $document->unsetRelation('lines');

        return collect();
    }

    protected function prepareEntry(Account $actor, Document $document, array $values, array $settings, Collection $previousLines): array
    {
        $sourceModel = $this->sourceModel;
        $source = $sourceModel::query()->whereKey($values['source_document_id'] ?? 0)->lockForUpdate()->first();
        if ($source === null || $source->branch_id !== $document->branch_id
            || $source->product_type !== $document->product_type || $source->voided_at !== null || $source->posted_at === null) {
            throw ValidationException::withMessages(['source_document_id' => 'Choose an active original invoice from this branch.']);
        }
        if ($document->document_date->lt($source->document_date)) {
            throw ValidationException::withMessages(['document_date' => 'The return date cannot precede its invoice.']);
        }
        $document->source_document_id = $source->id;
        $document->customer_id = $source->customer_id;
        $document->supplier_id = $source->supplier_id;
        $document->vat_mode = $source->vat_mode;
        $document->vat_percent = $source->vat_percent;
        $document->vat_calculation = $source->vat_calculation;
        if ($document->exists) {
            $document->stock_method = $document->getRawOriginal('stock_method');
        }

        $lines = $this->prepareReturnLines($source, $values['lines'] ?? []);
        $document->subtotal_minor = $this->items->sum(array_column($lines, 'subtotal_minor'));
        $document->discount_minor = $this->items->sum([
            ...array_column($lines, 'discount_minor'), ...array_column($lines, 'invoice_discount_minor'),
        ]);
        $document->vat_minor = $this->items->sum(array_column($lines, 'vat_minor'));
        $document->total_minor = $this->items->sum(array_column($lines, 'total_minor'));
        $this->applyReturnBalance($document, $source);

        return $lines;
    }

    protected function reverseStock(Document $document): void
    {
        $sourceModel = $this->sourceModel;
        $source = $sourceModel::query()->whereKey($document->source_document_id)->lockForUpdate()->firstOrFail();
        $source->returned_minor -= $document->total_minor;
        $source->refunded_minor -= $document->initial_paid_minor;
        if ($source->returned_minor < 0 || $source->refunded_minor < 0) {
            throw ValidationException::withMessages(['record' => 'The original invoice balance has changed.']);
        }
        $source->save();
        $this->stock->reverse($document);
    }

    private function prepareReturnLines(Document $source, array $inputs): array
    {
        if (count($inputs) > 100) {
            throw ValidationException::withMessages(['lines' => 'Add each original invoice line once, up to 100 lines.']);
        }
        $inputs = array_filter($inputs, static fn ($input): bool => ! is_array($input)
            || ! in_array($input['quantity'] ?? null, [0, '0'], true));
        if ($inputs === []) {
            throw ValidationException::withMessages(['lines' => 'Enter a return quantity for at least one item.']);
        }
        if (count(array_unique(array_column($inputs, 'source_line_id'))) !== count($inputs)) {
            throw ValidationException::withMessages(['lines' => 'Add each original invoice line once, up to 100 lines.']);
        }
        $originals = $source->lines()->with('variant:id,product_id')->whereIn('id', array_column($inputs, 'source_line_id'))
            ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        $units = ProductUnit::query()->whereIn('id', array_column($inputs, 'product_unit_id'))
            ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        $fields = ['discount_minor', 'invoice_discount_minor', 'vat_minor', 'total_minor'];
        $returnedAmounts = $this->returnedAmounts($source);
        $lines = [];
        foreach ($inputs as $index => $input) {
            $original = $originals->get((int) ($input['source_line_id'] ?? 0));
            if ($original === null) {
                throw ValidationException::withMessages(["lines.$index.source_line_id" => 'Choose a line from the original invoice.']);
            }
            $unitId = (int) ($input['product_unit_id'] ?? $original->product_unit_id);
            $unit = $units->get($unitId);
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
                'variant_id' => $original->variant_id, 'product_unit_id' => $unitId, 'source_line_id' => $original->id,
                'quantity' => $quantity, 'factor' => $factor, 'base_quantity' => $baseQuantity,
                'unit_price_minor' => $this->items->roundedRatio($original->unit_price_minor, $factor, $original->factor),
                'sale_price_per_piece_minor' => $original->sale_price_per_piece_minor,
                'discount_calculation' => $original->discount_calculation,
                'discount_percent' => $original->discount_percent,
                'discount_unit_amount_minor' => $original->discount_calculation === 'unit' && $original->discount_unit_amount_minor !== null
                    ? $this->items->roundedRatio($original->discount_unit_amount_minor, $factor, $original->factor) : null,
                'batch_no' => $original->batch_no, 'expiry_date' => $original->expiry_date?->toDateString(), 'cost_minor' => 0,
                'vat_mode' => $original->vat_mode ?? $source->vat_mode,
                'vat_percent' => $original->vat_percent ?? $source->vat_percent,
                'vat_calculation' => $original->vat_calculation ?? 'percent',
                'vat_unit_amount_minor' => $original->vat_calculation === 'unit' && $original->vat_unit_amount_minor !== null
                    ? $this->items->roundedRatio($original->vat_unit_amount_minor, $factor, $original->factor) : null,
            ];
            // Allocate remaining amounts so voiding an earlier return cannot duplicate a rounding penny.
            $returned = $returnedAmounts->get($original->id);
            foreach ($fields as $field) {
                $available = $original->$field - (int) ($returned?->$field ?? 0);
                $line[$field] = $this->items->floorRatio($available, $baseQuantity, $remaining);
            }
            $line['subtotal_minor'] = $line['total_minor'] + $line['discount_minor'] + $line['invoice_discount_minor']
                - ($line['vat_mode'] === 'exclusive' ? $line['vat_minor'] : 0);
            $lines[] = $line;
        }

        return $lines;
    }

    public function returnedAmounts(Document $source, ?Document $excluding = null): Collection
    {
        $query = $source->getConnection()->table('inventory_document_lines as lines')
            ->join('inventory_documents as documents', 'documents.id', '=', 'lines.document_id')
            ->where('documents.source_document_id', $source->id)->where('documents.branch_id', $source->branch_id)
            ->where('documents.product_type', $source->product_type)->whereNull('documents.voided_at')
            ->whereNull('documents.deleted_at')->whereNull('lines.superseded_at');
        if ($excluding?->exists) {
            $query->where('documents.id', '!=', $excluding->id);
        }

        return $query->groupBy('lines.source_line_id')->selectRaw('lines.source_line_id,
            SUM(lines.discount_minor) AS discount_minor, SUM(lines.invoice_discount_minor) AS invoice_discount_minor,
            SUM(lines.vat_minor) AS vat_minor, SUM(lines.total_minor) AS total_minor')->get()->keyBy('source_line_id');
    }

    private function applyReturnBalance(Document $document, Document $source): void
    {
        $returned = $source->returned_minor + $document->total_minor;
        $payable = $source->total_minor - $returned;
        if ($payable < $source->settlement_discount_minor) {
            throw ValidationException::withMessages(['source_document_id' => 'Void the settlement discount payment before returning this amount.']);
        }
        $refund = max(0, $source->paid_minor - $source->refunded_minor + $source->settlement_discount_minor - $payable);
        $source->returned_minor = $returned;
        $source->refunded_minor += $refund;
        $source->save();
        $document->paid_minor = $refund;
        $document->initial_paid_minor = $refund;
    }
}
