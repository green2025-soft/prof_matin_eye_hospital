<?php

namespace Modules\Pharmacy\Services;

use Brick\Math\BigInteger;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use LogicException;
use Modules\Pharmacy\Models\SaleItem;
use Modules\Pharmacy\Models\SaleReturnItem;
use Modules\Pharmacy\Models\StockAllocation;
use Modules\Pharmacy\Models\StockBalance;
use Modules\Pharmacy\Models\StockEntry;
use Modules\Pharmacy\Models\StockItem;
use Modules\Pharmacy\Models\StockLot;
use Modules\Pharmacy\Models\StockMovement;
use Modules\Pharmacy\Models\StockShortageSettlement;

class StockShortageService
{
    public function remaining(Connection $connection, StockAllocation $allocation): array
    {
        $this->requireTransaction($connection);
        $activeSettlement = fn (Builder $query) => $query->whereNull('reversed_at');
        $activeReturn = fn (Builder $query) => $query->whereNull('lot_id')
            ->whereNull('reversed_at');
        $totals = StockAllocation::on($connection->getName())
            ->whereKey($allocation->id)
            ->withSum(['shortageSettlements as settled_quantity' => $activeSettlement], 'quantity')
            ->withSum(['shortageSettlements as settled_cost' => $activeSettlement], 'snapshot_cost')
            ->withSum(['returns as returned_quantity' => $activeReturn], 'quantity')
            ->withSum(['returns as returned_cost' => $activeReturn], 'cost')
            ->firstOrFail();

        return $this->remainingBalance($allocation, (int) $totals->settled_quantity, (int) $totals->settled_cost,
            (int) $totals->returned_quantity, (int) $totals->returned_cost);
    }

    public function settle(StockEntry $trigger, Collection $lines, ?string $movementDate = null): void
    {
        $connection = $trigger->getConnection();
        $this->requireTransaction($connection);
        $date = $movementDate ?? max(now(config('app.timezone'))->toDateString(), $trigger->entry_date->toDateString());
        $triggerLines = $lines->keyBy('variant_id');
        $receiptReturns = [];
        $branches = array_values(array_unique($trigger->stockBranchIds()));
        sort($branches, SORT_NUMERIC);
        $activeSettlement = fn (Builder $query) => $query->whereNull('reversed_at');
        $activeReturn = fn (Builder $query) => $query->whereNull('lot_id')
            ->whereNull('reversed_at');

        foreach ($branches as $branchId) {
            $allocations = StockAllocation::on($connection->getName())
                ->whereHasMorph('stockItem', [SaleItem::class], function (Builder $query) use ($branchId, $date, $triggerLines): void {
                    $query->whereNull('superseded_at')
                        ->whereIn('variant_id', $triggerLines->keys())
                        ->whereHas('sale', fn (Builder $query) => $query->withoutGlobalScope('branch')
                            ->where('branch_id', $branchId)
                            ->whereNull('voided_at')
                            ->whereNull('deleted_at')
                            ->whereDate('entry_date', '<=', $date));
                })
                ->whereNull('lot_id')
                ->whereNull('source_allocation_id')
                ->whereNull('reversed_at')
                ->with(['stockItem' => fn (MorphTo $relation) => $relation->morphWith([
                    SaleItem::class => ['sale' => fn ($query) => $query->withoutGlobalScope('branch')],
                ])])
                ->withSum(['shortageSettlements as settled_quantity' => $activeSettlement], 'quantity')
                ->withSum(['shortageSettlements as settled_cost' => $activeSettlement], 'snapshot_cost')
                ->withSum(['returns as returned_quantity' => $activeReturn], 'quantity')
                ->withSum(['returns as returned_cost' => $activeReturn], 'cost')
                ->orderBy('id')
                ->lockForUpdate()
                ->lazyById(100);

            foreach ($allocations as $allocation) {
                $remaining = $this->remainingBalance($allocation, (int) $allocation->settled_quantity,
                    (int) $allocation->settled_cost, (int) $allocation->returned_quantity, (int) $allocation->returned_cost);
                if ($remaining['quantity'] === 0) {
                    continue;
                }
                $saleLine = $allocation->stockItem;
                $sale = $saleLine->sale;
                $lots = StockLot::on($connection->getName())
                    ->withoutGlobalScope('branch')
                    ->where('branch_id', $branchId)
                    ->where('variant_id', $saleLine->variant_id)
                    ->whereNull('reversed_at')
                    ->where('remaining_quantity', '>', 0)
                    ->whereDate('received_at', '<=', $date);
                if ($sale->product_type === 'medicine') {
                    $lots->where(fn (Builder $query) => $query->whereNull('expiry_date')
                        ->orWhereDate('expiry_date', '>=', $date));
                }
                $order = $sale->stock_method === 'lifo' ? 'desc' : 'asc';
                $lots->orderBy('received_at', $order)
                    ->orderBy('id', $order);

                while ($remaining['quantity'] > 0) {
                    $batch = (clone $lots)
                        ->limit(100)
                        ->lockForUpdate()
                        ->get();
                    if ($batch->isEmpty()) {
                        break;
                    }
                    $this->loadReturnedSales($connection, $batch, $receiptReturns);
                    foreach ($batch as $lot) {
                        // A return cannot cover its own sale's deficit: either reversal would otherwise be blocked.
                        if (in_array((int) $sale->id, $receiptReturns[$lot->id], true)) {
                            $lots->whereKeyNot($lot->id);

                            continue;
                        }
                        $quantity = min($remaining['quantity'], (int) $lot->remaining_quantity);
                        $snapshotCost = $this->portion($remaining['cost'], $quantity, $remaining['quantity']);
                        $receiptCost = $this->portion((int) $lot->remaining_cost, $quantity, (int) $lot->remaining_quantity);
                        StockLot::on($connection->getName())
                            ->withoutGlobalScope('branch')
                            ->whereKey($lot->id)
                            ->update([
                                'remaining_quantity' => (int) $lot->remaining_quantity - $quantity,
                                'remaining_cost' => (int) $lot->remaining_cost - $receiptCost,
                                'updated_at' => now(),
                            ]);
                        StockShortageSettlement::on($connection->getName())
                            ->insert([
                                'allocation_id' => $allocation->id,
                                'lot_id' => $lot->id,
                                'quantity' => $quantity,
                                'snapshot_cost' => $snapshotCost,
                                'receipt_cost' => $receiptCost,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        $this->variance($connection, $trigger, $triggerLines[$saleLine->variant_id], $branchId,
                            (int) $lot->id, $snapshotCost - $receiptCost, $date, false);
                        $remaining['quantity'] -= $quantity;
                        $remaining['cost'] -= $snapshotCost;
                        if ($remaining['quantity'] === 0) {
                            break;
                        }
                    }
                }
            }
        }
    }

    public function restore(StockEntry $entry, Collection $lines): void
    {
        $connection = $entry->getConnection();
        $this->requireTransaction($connection);
        $originals = $lines->keyBy('id');
        $lineType = $entry->lines()->getRelated()->getMorphClass();
        $settlements = StockShortageSettlement::on($connection->getName())
            ->whereHas('allocation', fn (Builder $query) => $query->where('stock_item_type', $lineType)
                ->whereIn('stock_item_id', $originals->keys())
                ->whereNull('source_allocation_id')
                ->whereNull('reversed_at'))
            ->whereNull('reversed_at')
            ->with('allocation:id,stock_item_type,stock_item_id')
            ->orderBy('lot_id')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();
        $lots = StockLot::on($connection->getName())
            ->withoutGlobalScope('branch')
            ->whereIn('id', $settlements->pluck('lot_id'))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($settlements as $settlement) {
            $lot = $lots->get($settlement->lot_id);
            if ($lot === null || $lot->reversed_at !== null) {
                throw ValidationException::withMessages(['record' => 'The receipt covering this sale is no longer available.']);
            }
            $quantity = (int) $lot->remaining_quantity + (int) $settlement->quantity;
            $cost = (int) $lot->remaining_cost + (int) $settlement->receipt_cost;
            if ($quantity > (int) $lot->initial_quantity || $cost > (int) $lot->initial_cost) {
                throw ValidationException::withMessages(['record' => 'The receipt covering this sale has inconsistent quantities.']);
            }
            StockLot::on($connection->getName())
                ->withoutGlobalScope('branch')
                ->whereKey($lot->id)
                ->update([
                    'remaining_quantity' => $quantity,
                    'remaining_cost' => $cost,
                    'updated_at' => now(),
                ]);
            $lot->forceFill([
                'remaining_quantity' => $quantity,
                'remaining_cost' => $cost,
            ]);
            StockShortageSettlement::on($connection->getName())
                ->whereKey($settlement->id)
                ->update([
                    'reversed_at' => now(),
                    'updated_at' => now(),
                ]);
            $this->variance($connection, $entry, $originals[$settlement->allocation->stock_item_id], (int) $lot->branch_id,
                (int) $lot->id, (int) $settlement->receipt_cost - (int) $settlement->snapshot_cost,
                now(config('app.timezone'))->toDateString(), true);
        }
    }

    private function remainingBalance(StockAllocation $allocation, int $settledQuantity, int $settledCost, int $returnedQuantity, int $returnedCost): array
    {
        $quantity = (int) $allocation->quantity - $settledQuantity - $returnedQuantity;
        $cost = (int) $allocation->cost - $settledCost - $returnedCost;
        if ($quantity < 0 || $cost < 0 || ($quantity === 0 && $cost !== 0)) {
            throw ValidationException::withMessages(['record' => 'The outstanding stock quantity and cost are inconsistent.']);
        }

        return [
            'quantity' => $quantity,
            'cost' => $cost,
        ];
    }

    private function variance(Connection $connection, StockEntry $entry, StockItem $line, int $branchId,
        int $lotId, int $cost, string $date, bool $reversal): void
    {
        if ($cost === 0) {
            return;
        }
        $balance = StockBalance::on($connection->getName())
            ->withoutGlobalScope('branch')
            ->where('branch_id', $branchId)
            ->where('variant_id', $line->variant_id)
            ->lockForUpdate()
            ->first();
        if ($balance === null) {
            throw new LogicException('Stock balances must be locked before shortage settlement.');
        }
        $value = BigInteger::of($balance->cost)->plus($cost);
        if ($value->isGreaterThan(PHP_INT_MAX) || $value->isLessThan(PHP_INT_MIN)) {
            throw ValidationException::withMessages(['record' => 'The stock value exceeds the supported limit.']);
        }
        StockBalance::on($connection->getName())
            ->withoutGlobalScope('branch')
            ->whereKey($balance->id)
            ->update([
                'cost' => $value->toInt(),
                'updated_at' => now(),
            ]);
        StockMovement::on($connection->getName())
            ->insert([
                'branch_id' => $branchId,
                'variant_id' => $line->variant_id,
                'stock_entry_type' => $entry->getMorphClass(),
                'stock_entry_id' => $entry->id,
                'stock_item_type' => $line->getMorphClass(),
                'stock_item_id' => $line->id,
                'lot_id' => $lotId,
                'type' => 'stock_variance',
                'quantity' => 0,
                'cost' => $cost,
                'movement_date' => $date,
                'is_reversal' => $reversal,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    }

    private function requireTransaction(Connection $connection): void
    {
        if ($connection->transactionLevel() < 1) {
            throw new LogicException('Stock shortage settlement requires an enclosing tenant transaction.');
        }
    }

    private function portion(int $cost, int $quantity, int $available): int
    {
        if ($available < 1 || $quantity < 1 || $quantity > $available || $cost < 0) {
            throw ValidationException::withMessages(['record' => 'The receipt quantity or cost is inconsistent.']);
        }

        return BigInteger::of($cost)->multipliedBy($quantity)->quotient($available)->toInt();
    }

    private function loadReturnedSales(Connection $connection, Collection $lots, array &$cache): void
    {
        $roots = $lots->reject(fn (StockLot $lot): bool => array_key_exists($lot->id, $cache));
        if ($roots->isEmpty()) {
            return;
        }

        $lineage = $roots->keyBy('id');
        $frontier = $roots->pluck('parent_lot_id')
            ->filter()
            ->unique()
            ->diff($lineage->keys());
        // Fetch ancestors together at each depth, including parents in another transfer branch.
        while ($frontier->isNotEmpty()) {
            $parents = StockLot::on($connection->getName())
                ->withoutGlobalScope('branch')
                ->whereIn('id', $frontier)
                ->get(['id', 'parent_lot_id', 'source_item_type', 'source_item_id']);
            $lineage = $lineage->union($parents->keyBy('id'));
            $frontier = $parents->pluck('parent_lot_id')
                ->filter()
                ->unique()
                ->diff($lineage->keys());
        }

        $returnType = (new SaleReturnItem)->getMorphClass();
        $returnIds = $lineage->where('source_item_type', $returnType)
            ->pluck('source_item_id')
            ->unique();
        $returns = SaleReturnItem::on($connection->getName())
            ->whereIn('id', $returnIds)
            ->with(['saleReturn' => fn ($query) => $query->withoutGlobalScope('branch')
                ->select(['id', 'branch_id', 'sale_id'])])
            ->get(['id', 'sale_return_id'])
            ->keyBy('id');

        foreach ($roots as $root) {
            $saleIds = [];
            $visited = [];
            $lot = $root;
            while ($lot !== null) {
                if (isset($visited[$lot->id])) {
                    throw ValidationException::withMessages(['record' => 'The stock receipt lineage is inconsistent.']);
                }
                $visited[$lot->id] = true;
                if ($lot->source_item_type === $returnType) {
                    $saleId = $returns->get($lot->source_item_id)?->saleReturn?->sale_id;
                    if ($saleId !== null) {
                        $saleIds[] = (int) $saleId;
                    }
                }
                $lot = $lineage->get($lot->parent_lot_id);
            }
            $cache[$root->id] = array_values(array_unique($saleIds));
        }
    }
}
