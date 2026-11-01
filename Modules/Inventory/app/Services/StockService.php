<?php

namespace Modules\Inventory\Services;

use Brick\Math\BigInteger;
use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use LogicException;
use Modules\Core\Helpers\Money;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\DocumentLine;
use Modules\Inventory\Models\StockLot;
use stdClass;

class StockService
{
    public function __construct(private StockShortageService $shortages) {}

    public function availableQuantities(string $productType, string $saleDate, array $variantIds): Collection
    {
        $branchId = (int) request()->attributes->get('branch_id');
        if ($branchId < 1 || $variantIds === []) {
            return collect();
        }

        return $this->stockAvailability($productType, $saleDate)->whereIn('variant_id', $variantIds)
            ->pluck('available_quantity', 'variant_id');
    }

    public function stockAvailability(string $productType, string $stockDate): Builder
    {
        $expiryDate = max(now(config('app.timezone'))->toDateString(), $stockDate);
        $available = "received_at <= ? AND (? <> 'medicine' OR expiry_date IS NULL OR expiry_date >= ?)";
        $expired = "received_at <= ? AND ? = 'medicine' AND expiry_date < ?";
        $bindings = [$stockDate, $productType, $expiryDate];

        return (new StockLot)->getConnection()->table('inventory_stock_lots')
            ->where('branch_id', max(0, (int) request()->attributes->get('branch_id')))
            ->whereNull('reversed_at')->where('remaining_quantity', '>', 0)
            ->groupBy('variant_id')->select('variant_id')
            ->selectRaw("SUM(CASE WHEN $available THEN remaining_quantity ELSE 0 END) AS available_quantity", $bindings)
            ->selectRaw("SUM(CASE WHEN $available THEN remaining_cost_minor ELSE 0 END) AS available_cost_minor", $bindings)
            ->selectRaw("SUM(CASE WHEN $expired THEN remaining_quantity ELSE 0 END) AS expired_quantity", $bindings)
            ->selectRaw("SUM(CASE WHEN $expired THEN remaining_cost_minor ELSE 0 END) AS expired_cost_minor", $bindings)
            ->selectRaw('SUM(CASE WHEN received_at > ? THEN remaining_quantity ELSE 0 END) AS future_quantity', [$stockDate])
            ->selectRaw('SUM(CASE WHEN received_at > ? THEN remaining_cost_minor ELSE 0 END) AS future_cost_minor', [$stockDate]);
    }

    public function receive(Document $document): int
    {
        return $this->postLines($document, $this->receiveLine(...));
    }

    public function issue(Document $document, ?string $minimumExpiryDate = null, bool $useSelectedLot = true, bool $allowShortage = false): int
    {
        return $this->postLines($document, fn (Connection $connection, Document $entry, DocumentLine $line): int => $this->consumeLine($connection, $entry, $line, $minimumExpiryDate, $useSelectedLot, $allowShortage));
    }

    public function transfer(Document $document): int
    {
        return $this->postLines($document, $this->transferLine(...));
    }

    public function returnSale(Document $document): int
    {
        return $this->postLines($document, $this->returnSaleLine(...));
    }

    public function returnPurchase(Document $document): int
    {
        return $this->postLines($document, $this->returnPurchaseLine(...));
    }

    public function adjust(Document $document): int
    {
        return $this->postLines($document, function (Connection $connection, Document $entry, DocumentLine $line): int {
            if ($line->direction === 'increase') {
                return $this->receiveLine($connection, $entry, $line);
            }
            if ($line->direction !== 'decrease') {
                throw ValidationException::withMessages(['lines' => 'Choose whether this adjustment increases or decreases stock.']);
            }

            return $this->consumeLine($connection, $entry, $line);
        });
    }

    private function postLines(Document $document, Closure $operation): int
    {
        $connection = $document->getConnection();
        $this->requireTransaction($connection);

        if ($document->posted_at !== null || $document->stock_reversed_at !== null) {
            throw ValidationException::withMessages(['record' => 'This document has already been posted.']);
        }
        if (! in_array($document->stock_method, ['fifo', 'lifo'], true)) {
            throw ValidationException::withMessages(['record' => 'Select a valid stock method in Inventory settings.']);
        }

        $lines = $document->lines()->orderBy('variant_id')->orderBy('id')->get();
        $this->lockBalances($connection, $document, $lines);
        $this->lockSourceLines($connection, $lines);
        $totalCost = 0;

        foreach ($lines as $line) {
            if ($line->base_quantity < 1 || $line->factor < 1 || $line->quantity < 1
                || BigInteger::of($line->quantity)->multipliedBy($line->factor)->compareTo($line->base_quantity) !== 0) {
                throw ValidationException::withMessages(['lines' => 'The product quantity and unit conversion are inconsistent.']);
            }
            $cost = $operation($connection, $document, $line);

            $connection->table('inventory_document_lines')->where('id', $line->id)->update(['cost_minor' => $cost]);
            $totalCost = $this->supportedInteger(BigInteger::of($totalCost)->plus($cost));
        }
        $this->shortages->settle($document, $lines);

        return $totalCost;
    }

    public function reverse(Document $document): void
    {
        $connection = $document->getConnection();
        $this->requireTransaction($connection);

        if ($document->posted_at === null || $document->stock_reversed_at !== null) {
            throw ValidationException::withMessages(['record' => 'This document cannot be reversed again.']);
        }

        $lines = $document->lines()->orderBy('variant_id')->orderBy('id')->get();
        $this->lockBalances($connection, $document, $lines);
        $this->lockSourceLines($connection, $lines);
        $lineIds = $lines->modelKeys();
        $allocations = $connection->table('inventory_stock_allocations')->whereIn('document_line_id', $lineIds)->pluck('id');
        if ($connection->table('inventory_stock_allocations')->whereIn('source_allocation_id', $allocations)
            ->whereNull('reversed_at')->exists()) {
            throw ValidationException::withMessages(['record' => 'This sale has a stock return. Reverse that return first.']);
        }
        $this->shortages->restore($document, $lines);
        $movements = $connection->table('inventory_stock_movements')->where('document_id', $document->id)
            ->whereIn('document_line_id', $lines->modelKeys())
            ->where('type', '!=', 'stock_variance')
            ->where('is_reversal', false)->orderByDesc('quantity')->orderBy('id')->get();

        foreach ($movements as $movement) {
            $lot = $movement->lot_id === null ? null
                : $connection->table('inventory_stock_lots')->where('id', $movement->lot_id)->lockForUpdate()->first();
            if ($movement->lot_id !== null && ($lot === null || $lot->reversed_at !== null)) {
                throw ValidationException::withMessages(['record' => 'The original stock receipt is no longer available.']);
            }

            if ($movement->quantity > 0 && $lot !== null) {
                if ((int) $lot->remaining_quantity !== (int) $lot->initial_quantity
                    || (int) $lot->remaining_cost_minor !== (int) $lot->initial_cost_minor
                    || $connection->table('inventory_stock_allocations')->where('lot_id', $lot->id)
                        ->whereNull('source_allocation_id')->whereNull('reversed_at')->exists()
                    || $connection->table('inventory_stock_lots')->where('parent_lot_id', $lot->id)
                        ->whereNull('reversed_at')->exists()) {
                    throw ValidationException::withMessages(['record' => 'Stock from this receipt has been used. Reverse the later movements first.']);
                }
                $connection->table('inventory_stock_lots')->where('id', $lot->id)->update([
                    'remaining_quantity' => 0, 'remaining_cost_minor' => 0, 'reversed_at' => now(), 'updated_at' => now(),
                ]);
            } elseif ($lot !== null) {
                $this->restoreLot($connection, $lot, -(int) $movement->quantity, -(int) $movement->cost_minor);
            }

            $this->changeBalance($connection, (int) $movement->branch_id, (int) $movement->variant_id,
                -(int) $movement->quantity, -(int) $movement->cost_minor);
            $connection->table('inventory_stock_movements')->insert([
                'branch_id' => $movement->branch_id, 'variant_id' => $movement->variant_id,
                'document_id' => $document->id, 'document_line_id' => $movement->document_line_id,
                'lot_id' => $movement->lot_id, 'type' => $movement->type,
                'quantity' => -(int) $movement->quantity, 'cost_minor' => -(int) $movement->cost_minor,
                'movement_date' => now(config('app.timezone'))->toDateString(), 'is_reversal' => true,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $connection->table('inventory_stock_allocations')->whereIn('document_line_id', $lineIds)
            ->whereNull('reversed_at')->update(['reversed_at' => now(), 'updated_at' => now()]);

        foreach ($lines as $line) {
            if ($line->source_line_id !== null) {
                $connection->table('inventory_document_lines')->where('id', $line->source_line_id)
                    ->decrement('returned_base_quantity', $line->base_quantity);
            }
        }

        $document->stock_reversed_at = now();
        $document->save();
        $this->shortages->settle($document, $lines, now(config('app.timezone'))->toDateString());
    }

    private function receiveLine(Connection $connection, Document $document, DocumentLine $line): int
    {
        $this->receiveLot($connection, $document, $line, $document->branch_id, $line->base_quantity,
            $line->cost_minor, $line->batch_no, $line->expiry_date?->toDateString());

        return $line->cost_minor;
    }

    private function consumeLine(Connection $connection, Document $document, DocumentLine $line,
        ?string $minimumExpiryDate = null, bool $useSelectedLot = true, bool $allowShortage = false): int
    {
        $lots = $this->eligibleLots($connection, $document->branch_id, $document->document_date->toDateString(), $minimumExpiryDate)
            ->where('variant_id', $line->variant_id);
        if ($useSelectedLot) {
            if ($line->batch_no !== null && $line->batch_no !== '') {
                $lots->where('batch_no', $line->batch_no);
            }
            if ($line->expiry_date !== null) {
                $lots->whereDate('expiry_date', $line->expiry_date->toDateString());
            }
            if ($line->stock_lot_id !== null) {
                $lots->where('id', $line->stock_lot_id);
            }
        }

        $direction = $document->stock_method === 'lifo' ? 'desc' : 'asc';
        $remaining = $line->base_quantity;
        $cost = 0;
        $lots->orderBy('received_at', $direction)->orderBy('id', $direction);
        while ($remaining > 0) {
            $batch = (clone $lots)->limit(100)->lockForUpdate()->get();
            if ($batch->isEmpty()) {
                if (! $allowShortage) {
                    throw ValidationException::withMessages(['lines' => 'There is not enough eligible stock for the selected product and date.']);
                }

                return $this->supportedInteger(BigInteger::of($cost)->plus($this->recordShortage($connection, $document, $line, $remaining)));
            }
            foreach ($batch as $lot) {
                $quantity = min($remaining, (int) $lot->remaining_quantity);
                $takenCost = $this->takeLot($connection, $document, $line, $lot, $quantity);
                $cost = $this->supportedInteger(BigInteger::of($cost)->plus($takenCost));
                $remaining -= $quantity;
                if ($remaining === 0) {
                    return $cost;
                }
            }
        }

        return $cost;
    }

    private function eligibleLots(Connection $connection, int $branchId, string $stockDate, ?string $minimumExpiryDate): Builder
    {
        $query = $connection->table('inventory_stock_lots')->where('branch_id', $branchId)
            ->whereNull('reversed_at')->where('remaining_quantity', '>', 0)->where('received_at', '<=', $stockDate);
        if ($minimumExpiryDate !== null) {
            $query->where(function (Builder $query) use ($minimumExpiryDate): void {
                $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', $minimumExpiryDate);
            });
        }

        return $query;
    }

    private function recordShortage(Connection $connection, Document $document, DocumentLine $line, int $quantity): int
    {
        $price = $connection->table('inventory_products as products')
            ->join('inventory_product_variants as variants', 'variants.product_id', '=', 'products.id')
            ->where('variants.id', $line->variant_id)->value('products.purchase_price');
        $cost = $this->supportedInteger(BigInteger::of(Money::minor((string) ($price ?? '0')))->multipliedBy($quantity));
        $connection->table('inventory_stock_allocations')->insert([
            'document_line_id' => $line->id, 'lot_id' => null, 'quantity' => $quantity,
            'cost_minor' => $cost, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->movement($connection, $document, $line, $document->branch_id, null, -$quantity, -$cost);

        return $cost;
    }

    private function transferLine(Connection $connection, Document $document, DocumentLine $line): int
    {
        if ($document->destination_branch_id === null || $document->destination_branch_id === $document->branch_id) {
            throw ValidationException::withMessages(['destination_branch_id' => 'Select another branch.']);
        }

        $cost = $this->consumeLine($connection, $document, $line, null, false);
        $allocations = $connection->table('inventory_stock_allocations as allocations')
            ->join('inventory_stock_lots as lots', 'lots.id', '=', 'allocations.lot_id')
            ->where('allocations.document_line_id', $line->id)->orderBy('allocations.id')
            ->select('allocations.*', 'lots.batch_no', 'lots.expiry_date')->get();
        foreach ($allocations as $allocation) {
            $this->receiveLot($connection, $document, $line, $document->destination_branch_id,
                (int) $allocation->quantity, (int) $allocation->cost_minor, $allocation->batch_no,
                $allocation->expiry_date, (int) $allocation->lot_id);
        }

        return $cost;
    }

    private function returnSaleLine(Connection $connection, Document $document, DocumentLine $line): int
    {
        $source = $this->returnSource($connection, $document, $line, 'sale');
        $allocations = $connection->table('inventory_stock_allocations')->where('document_line_id', $source->id)
            ->whereNull('source_allocation_id')->whereNull('reversed_at')->orderBy('id')->lockForUpdate()->get();
        $returnedAllocations = $connection->table('inventory_stock_allocations')->whereIn('source_allocation_id', $allocations->pluck('id'))
            ->whereNull('reversed_at')->groupBy('source_allocation_id')
            ->selectRaw('source_allocation_id, SUM(quantity) AS quantity, SUM(cost_minor) AS cost')->get()->keyBy('source_allocation_id');
        $originalLots = $connection->table('inventory_stock_lots')->whereIn('id', $allocations->pluck('lot_id'))->get()->keyBy('id');
        $remaining = $line->base_quantity;
        $cost = 0;

        foreach ($allocations as $allocation) {
            $returned = $returnedAllocations->get($allocation->id);
            $available = (int) $allocation->quantity - (int) ($returned?->quantity ?? 0);
            if ($available === 0) {
                continue;
            }

            $quantity = min($remaining, $available);
            $restoredCost = $this->portion((int) $allocation->cost_minor - (int) ($returned?->cost ?? 0), $quantity, $available);
            if ($allocation->lot_id === null) {
                $restoredCost = $this->returnShortage($connection, $document, $line, $allocation, $quantity, $restoredCost);
            } else {
                $originalLot = $originalLots->get($allocation->lot_id);
                $lotId = $this->receiveLot($connection, $document, $line, $document->branch_id, $quantity,
                    $restoredCost, $originalLot->batch_no, $originalLot->expiry_date, (int) $originalLot->id);
                $this->recordReturnAllocation($connection, $line, $allocation, $lotId, $quantity, $restoredCost);
            }
            $cost = $this->supportedInteger(BigInteger::of($cost)->plus($restoredCost));
            $remaining -= $quantity;
            if ($remaining === 0) {
                $this->recordReturnedQuantity($connection, $source, $line->base_quantity);

                return $cost;
            }
        }

        throw ValidationException::withMessages(['lines' => 'The return quantity exceeds the original sale allocation.']);
    }

    private function returnShortage(Connection $connection, Document $document, DocumentLine $line, stdClass $allocation,
        int $quantity, int $snapshotCost): int
    {
        $shortage = $this->shortages->remaining($connection, $allocation);
        $uncoveredQuantity = min($quantity, $shortage['quantity']);
        $remaining = $quantity;
        $remainingSnapshotCost = $snapshotCost;
        $cost = 0;
        if ($uncoveredQuantity > 0) {
            $cost = $this->portion($snapshotCost, $uncoveredQuantity, $quantity);
            $this->recordReturnAllocation($connection, $line, $allocation, null, $uncoveredQuantity, $cost);
            $this->movement($connection, $document, $line, $document->branch_id, null, $uncoveredQuantity, $cost);
            $remaining -= $uncoveredQuantity;
            $remainingSnapshotCost -= $cost;
        }
        if ($remaining === 0) {
            return $cost;
        }

        $settlements = $connection->table('inventory_stock_shortage_settlements')->where('allocation_id', $allocation->id)
            ->whereNull('reversed_at')->groupBy('lot_id')->orderBy('lot_id')
            ->selectRaw('lot_id, SUM(quantity) AS quantity, SUM(receipt_cost_minor) AS cost_minor')->get();
        $returned = $connection->table('inventory_stock_allocations as returns')
            ->join('inventory_stock_lots as lots', 'lots.id', '=', 'returns.lot_id')
            ->where('returns.source_allocation_id', $allocation->id)->whereNull('returns.reversed_at')
            ->groupBy('lots.parent_lot_id')
            ->selectRaw('lots.parent_lot_id, SUM(returns.quantity) AS quantity, SUM(lots.initial_cost_minor) AS cost_minor')
            ->get()->keyBy('parent_lot_id');

        foreach ($settlements as $settlement) {
            $previousReturn = $returned->get($settlement->lot_id);
            $available = (int) $settlement->quantity - (int) ($previousReturn?->quantity ?? 0);
            if ($available < 1) {
                continue;
            }
            $quantity = min($remaining, $available);
            $actualCost = $this->portion((int) $settlement->cost_minor - (int) ($previousReturn?->cost_minor ?? 0), $quantity, $available);
            $returnedSnapshotCost = $this->portion($remainingSnapshotCost, $quantity, $remaining);
            $originalLot = $connection->table('inventory_stock_lots')->where('id', $settlement->lot_id)->lockForUpdate()->first();
            if ($originalLot === null || $originalLot->reversed_at !== null) {
                throw ValidationException::withMessages(['lines' => 'The receipt covering this sale is no longer available.']);
            }
            $lotId = $this->receiveLot($connection, $document, $line, $document->branch_id, $quantity,
                $actualCost, $originalLot->batch_no, $originalLot->expiry_date, (int) $originalLot->id);
            // The allocation retains the sale snapshot; returned stock retains its actual receipt value.
            $this->recordReturnAllocation($connection, $line, $allocation, $lotId, $quantity, $returnedSnapshotCost);
            $cost = $this->supportedInteger(BigInteger::of($cost)->plus($actualCost));
            $remaining -= $quantity;
            $remainingSnapshotCost -= $returnedSnapshotCost;
            if ($remaining === 0) {
                return $cost;
            }
        }

        throw ValidationException::withMessages(['lines' => 'The returned quantity exceeds the covered sale shortage.']);
    }

    private function recordReturnAllocation(Connection $connection, DocumentLine $line, stdClass $source, ?int $lotId,
        int $quantity, int $snapshotCost): void
    {
        $connection->table('inventory_stock_allocations')->insert([
            'document_line_id' => $line->id, 'lot_id' => $lotId, 'source_allocation_id' => $source->id,
            'quantity' => $quantity, 'cost_minor' => $snapshotCost, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function returnPurchaseLine(Connection $connection, Document $document, DocumentLine $line): int
    {
        $source = $this->returnSource($connection, $document, $line, 'purchase');
        $lot = $connection->table('inventory_stock_lots')->where('source_line_id', $source->id)
            ->where('branch_id', $document->branch_id)->whereNull('reversed_at')->orderBy('id')->lockForUpdate()->first();
        if ($lot === null || (int) $lot->remaining_quantity < $line->base_quantity) {
            throw ValidationException::withMessages(['lines' => 'Only remaining stock from the original purchase can be returned.']);
        }

        $cost = $this->takeLot($connection, $document, $line, $lot, $line->base_quantity);
        $this->recordReturnedQuantity($connection, $source, $line->base_quantity);

        return $cost;
    }

    private function returnSource(Connection $connection, Document $document, DocumentLine $line, string $sourceType): stdClass
    {
        $source = $connection->table('inventory_document_lines as lines')->join('inventory_documents as documents', 'documents.id', '=', 'lines.document_id')
            ->where('lines.id', $line->source_line_id)->where('documents.id', $document->source_document_id)
            ->where('documents.branch_id', $document->branch_id)->where('documents.type', $sourceType)
            ->where('documents.product_type', $document->product_type)->whereNull('documents.voided_at')
            ->whereNull('documents.deleted_at')->whereNull('lines.superseded_at')
            ->select('lines.*')->first();

        if ($source === null || (int) $source->variant_id !== $line->variant_id
            || (int) $source->base_quantity - (int) $source->returned_base_quantity < $line->base_quantity) {
            throw ValidationException::withMessages(['lines' => 'Select a valid original invoice line and an available return quantity.']);
        }

        return $source;
    }

    private function takeLot(Connection $connection, Document $document, DocumentLine $line, stdClass $lot, int $quantity): int
    {
        $cost = $this->portion((int) $lot->remaining_cost_minor, $quantity, (int) $lot->remaining_quantity);
        $connection->table('inventory_stock_lots')->where('id', $lot->id)->update([
            'remaining_quantity' => (int) $lot->remaining_quantity - $quantity,
            'remaining_cost_minor' => (int) $lot->remaining_cost_minor - $cost, 'updated_at' => now(),
        ]);
        $connection->table('inventory_stock_allocations')->insert([
            'document_line_id' => $line->id, 'lot_id' => $lot->id, 'quantity' => $quantity,
            'cost_minor' => $cost, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->movement($connection, $document, $line, $document->branch_id, (int) $lot->id, -$quantity, -$cost);

        return $cost;
    }

    private function receiveLot(Connection $connection, Document $document, DocumentLine $line, int $branchId, int $quantity,
        int $cost, ?string $batchNo, ?string $expiryDate, ?int $parentLotId = null): int
    {
        $lotId = $connection->table('inventory_stock_lots')->insertGetId([
            'branch_id' => $branchId, 'variant_id' => $line->variant_id, 'source_line_id' => $line->id,
            'parent_lot_id' => $parentLotId, 'received_at' => $document->document_date->toDateString(),
            'initial_quantity' => $quantity, 'remaining_quantity' => $quantity,
            'initial_cost_minor' => $cost, 'remaining_cost_minor' => $cost,
            'batch_no' => $batchNo, 'expiry_date' => $expiryDate, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->movement($connection, $document, $line, $branchId, $lotId, $quantity, $cost);

        return $lotId;
    }

    private function movement(Connection $connection, Document $document, DocumentLine $line, int $branchId, ?int $lotId,
        int $quantity, int $cost): void
    {
        $this->changeBalance($connection, $branchId, $line->variant_id, $quantity, $cost);
        $connection->table('inventory_stock_movements')->insert([
            'branch_id' => $branchId, 'variant_id' => $line->variant_id, 'document_id' => $document->id,
            'document_line_id' => $line->id, 'lot_id' => $lotId, 'type' => $document->type,
            'quantity' => $quantity, 'cost_minor' => $cost, 'movement_date' => $document->document_date->toDateString(),
            'is_reversal' => false, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function changeBalance(Connection $connection, int $branchId, int $variantId, int $quantity, int $cost): void
    {
        $query = $connection->table('inventory_stock_balances')->where('branch_id', $branchId)->where('variant_id', $variantId);
        $balance = $query->first();
        $newQuantity = $this->supportedInteger(BigInteger::of($balance->quantity)->plus($quantity));
        $newCost = $this->supportedInteger(BigInteger::of($balance->cost_minor)->plus($cost));
        $query->update(['quantity' => $newQuantity, 'cost_minor' => $newCost, 'updated_at' => now()]);
    }

    private function restoreLot(Connection $connection, stdClass $lot, int $quantity, int $cost): void
    {
        $newQuantity = $this->supportedInteger(BigInteger::of($lot->remaining_quantity)->plus($quantity));
        $newCost = $this->supportedInteger(BigInteger::of($lot->remaining_cost_minor)->plus($cost));
        if ($newQuantity > (int) $lot->initial_quantity || $newCost > (int) $lot->initial_cost_minor) {
            throw ValidationException::withMessages(['record' => 'The original stock allocation cannot be restored twice.']);
        }

        $connection->table('inventory_stock_lots')->where('id', $lot->id)->update([
            'remaining_quantity' => $newQuantity, 'remaining_cost_minor' => $newCost, 'updated_at' => now(),
        ]);
    }

    private function portion(int $cost, int $quantity, int $available): int
    {
        if ($quantity < 1 || $quantity > $available) {
            throw ValidationException::withMessages(['lines' => 'Use a positive quantity within the available stock.']);
        }

        // The last units retain every rounding remainder from the original receipt.
        return $quantity === $available ? $cost : $this->supportedInteger(BigInteger::of($cost)->multipliedBy($quantity)->quotient($available));
    }

    private function supportedInteger(BigInteger $value): int
    {
        if ($value->isGreaterThan(PHP_INT_MAX) || $value->isLessThan(PHP_INT_MIN)) {
            throw ValidationException::withMessages(['lines' => 'The stock quantity or value exceeds the supported limit.']);
        }

        return $value->toInt();
    }

    private function recordReturnedQuantity(Connection $connection, stdClass $source, int $quantity): void
    {
        $connection->table('inventory_document_lines')->where('id', $source->id)->increment('returned_base_quantity', $quantity);
    }

    private function lockBalances(Connection $connection, Document $document, Collection $lines): void
    {
        $branches = [$document->branch_id];
        if ($document->destination_branch_id !== null) {
            $branches[] = $document->destination_branch_id;
        }
        sort($branches, SORT_NUMERIC);
        $variantIds = $lines->pluck('variant_id')->unique()->sort()->values();

        foreach (array_unique($branches) as $branchId) {
            foreach ($variantIds as $variantId) {
                $connection->table('inventory_stock_balances')->insertOrIgnore([
                    'branch_id' => $branchId, 'variant_id' => $variantId, 'quantity' => 0,
                    'cost_minor' => 0, 'created_at' => now(), 'updated_at' => now(),
                ]);
                $connection->table('inventory_stock_balances')->where('branch_id', $branchId)
                    ->where('variant_id', $variantId)->lockForUpdate()->first();
            }
        }
    }

    private function lockSourceLines(Connection $connection, Collection $lines): void
    {
        $sourceIds = $lines->pluck('source_line_id')->filter()->unique()->sort()->values();
        if ($sourceIds->isNotEmpty()) {
            $connection->table('inventory_document_lines')->whereIn('id', $sourceIds)->orderBy('id')->lockForUpdate()->get();
        }
    }

    private function requireTransaction(Connection $connection): void
    {
        if ($connection->transactionLevel() < 1) {
            throw new LogicException('Stock movements require an enclosing tenant transaction.');
        }
    }
}
