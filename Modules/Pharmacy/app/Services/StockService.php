<?php

namespace Modules\Pharmacy\Services;

use Brick\Math\BigInteger;
use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use LogicException;
use Modules\Core\Helpers\Money;
use Modules\Pharmacy\Models\Adjustment;
use Modules\Pharmacy\Models\AdjustmentItem;
use Modules\Pharmacy\Models\OpeningStock;
use Modules\Pharmacy\Models\OpeningStockItem;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\PurchaseItem;
use Modules\Pharmacy\Models\PurchaseReturn;
use Modules\Pharmacy\Models\PurchaseReturnItem;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\SaleReturn;
use Modules\Pharmacy\Models\SaleReturnItem;
use Modules\Pharmacy\Models\StockAllocation;
use Modules\Pharmacy\Models\StockBalance;
use Modules\Pharmacy\Models\StockEntry;
use Modules\Pharmacy\Models\StockItem;
use Modules\Pharmacy\Models\StockLot;
use Modules\Pharmacy\Models\StockMovement;
use Modules\Pharmacy\Models\Wastage;
use Modules\Pharmacy\Models\WastageItem;

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
            ->pluck('available_quantity', 'variant_id')->map(fn ($quantity): int => (int) $quantity);
    }

    public function saleRevisionQuantities(Sale $sale, string $stockDate): Collection
    {
        $eligibleLot = function (Builder $query) use ($sale, $stockDate): void {
            $query->where('branch_id', $sale->branch_id)->whereNull('reversed_at')->where('received_at', '<=', $stockDate);
            if ($sale->product_type === 'medicine') {
                $expiryDate = max(now(config('app.timezone'))->toDateString(), $stockDate);
                $query->where(fn (Builder $query) => $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', $expiryDate));
            }
        };
        $originalAllocation = fn ($query) => $query->whereNull('source_allocation_id')->whereNull('reversed_at');
        $lines = $sale->lines()->select(['id', 'sale_id', 'variant_id'])
            ->withSum(['allocations as eligible_quantity' => fn (Builder $query) => $originalAllocation($query)->whereHas('lot', $eligibleLot)], 'quantity')
            ->withSum(['shortageSettlements as settled_quantity' => fn (Builder $query) => $query
                ->whereNull($query->getModel()->qualifyColumn('reversed_at'))->whereHas('lot', $eligibleLot)], 'quantity')->get();
        $quantities = collect();
        // A sale has at most 100 lines; the database sums each line's eligible allocations and settlements.
        foreach ($lines as $line) {
            $quantity = $this->supportedInteger(BigInteger::of((int) $line->eligible_quantity)->plus((int) $line->settled_quantity));
            if ($quantity > 0) {
                $quantities->put($line->variant_id, $this->supportedInteger(BigInteger::of($quantities->get($line->variant_id, 0))->plus($quantity)));
            }
        }

        return $quantities;
    }

    public function stockAvailability(string $productType, string $stockDate): Builder
    {
        $expiryDate = max(now(config('app.timezone'))->toDateString(), $stockDate);
        $activeLot = fn (Builder $query) => $query->whereNull('reversed_at')->where('remaining_quantity', '>', 0);
        $available = function (Builder $query) use ($activeLot, $productType, $stockDate, $expiryDate): void {
            $activeLot($query)->where('received_at', '<=', $stockDate);
            if ($productType === 'medicine') {
                $query->where(fn (Builder $query) => $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', $expiryDate));
            }
        };
        $expired = function (Builder $query) use ($activeLot, $productType, $stockDate, $expiryDate): void {
            $activeLot($query)->where('received_at', '<=', $stockDate)->where('expiry_date', '<', $expiryDate);
            if ($productType !== 'medicine') {
                $query->whereKey([]);
            }
        };
        $future = fn (Builder $query) => $activeLot($query)->where('received_at', '>', $stockDate);

        return StockBalance::query()->select('variant_id')
            ->withSum(['lots as available_quantity' => $available], 'remaining_quantity')
            ->withSum(['lots as available_cost' => $available], 'remaining_cost')
            ->withSum(['lots as expired_quantity' => $expired], 'remaining_quantity')
            ->withSum(['lots as expired_cost' => $expired], 'remaining_cost')
            ->withSum(['lots as future_quantity' => $future], 'remaining_quantity')
            ->withSum(['lots as future_cost' => $future], 'remaining_cost');
    }

    public function receive(Purchase|OpeningStock $entry): int
    {
        return $this->postLines($entry, $this->receiveLine(...));
    }

    public function issueSale(Sale $entry, ?string $minimumExpiryDate = null, bool $allowShortage = false): int
    {
        return $this->postLines($entry, fn (Connection $connection, StockEntry $entry, StockItem $line): int => $this->consumeLine($connection, $entry, $line, $minimumExpiryDate, null, $allowShortage));
    }

    public function issueWastage(Wastage $entry): int
    {
        return $this->postLines($entry, fn (Connection $connection, Wastage $entry, WastageItem $line): int => $this->consumeLine($connection, $entry, $line, null, $line->stock_lot_id));
    }

    public function transfer(StockEntry $entry): int
    {
        return $this->postLines($entry, $this->transferLine(...));
    }

    public function returnSale(SaleReturn $entry): int
    {
        $this->requireTransaction($entry->getConnection());
        $sale = $entry->sale()->lockForUpdate()->firstOrFail();
        $sale->lines()->whereIn('id', $entry->lines()->select('sale_item_id'))
            ->orderBy('id')->lockForUpdate()->get();

        return $this->postLines($entry, $this->returnSaleLine(...), ['saleItem.sale']);
    }

    public function returnPurchase(PurchaseReturn $entry): int
    {
        $this->requireTransaction($entry->getConnection());
        $purchase = $entry->purchase()->lockForUpdate()->firstOrFail();
        $purchase->lines()->whereIn('id', $entry->lines()->select('purchase_item_id'))
            ->orderBy('id')->lockForUpdate()->get();

        return $this->postLines($entry, $this->returnPurchaseLine(...), ['purchaseItem.purchase']);
    }

    public function adjust(Adjustment $entry): int
    {
        return $this->postLines($entry, function (Connection $connection, Adjustment $entry, AdjustmentItem $line): int {
            if ($line->direction === 'increase') {
                return $this->receiveLine($connection, $entry, $line);
            }
            if ($line->direction !== 'decrease') {
                throw ValidationException::withMessages(['lines' => 'Choose whether this adjustment increases or decreases stock.']);
            }

            return $this->consumeLine(
                $connection, $entry, $line,
                selectedLotId: $line->stock_lot_id,
                batchNo: $line->batch_no,
                expiryDate: $line->expiry_date?->toDateString(),
            );
        });
    }

    private function postLines(StockEntry $entry, Closure $operation, array $relations = []): int
    {
        $connection = $entry->getConnection();
        $this->requireTransaction($connection);

        if ($entry->posted_at !== null || $entry->stock_reversed_at !== null) {
            throw ValidationException::withMessages(['record' => 'This entry has already been posted.']);
        }

        $lines = $entry->lines()->with(['variant.product', ...$relations])->orderBy('variant_id')->orderBy('id')->get();
        $this->lockBalances($connection, $entry, $lines);
        $totalCost = 0;

        foreach ($lines as $line) {
            if ($line->base_quantity < 1 || $line->factor < 1 || $line->quantity < 1
                || BigInteger::of($line->quantity)->multipliedBy($line->factor)->compareTo($line->base_quantity) !== 0) {
                throw ValidationException::withMessages(['lines' => 'The product quantity and unit conversion are inconsistent.']);
            }
            $cost = $operation($connection, $entry, $line);

            $line->cost = $cost;
            $line->save();
            $totalCost = $this->supportedInteger(BigInteger::of($totalCost)->plus($cost));
        }
        $this->shortages->settle($entry, $lines);

        return $totalCost;
    }

    public function reverse(StockEntry $entry): void
    {
        $connection = $entry->getConnection();
        $this->requireTransaction($connection);

        if ($entry->posted_at === null || $entry->stock_reversed_at !== null) {
            throw ValidationException::withMessages(['record' => 'This entry cannot be reversed again.']);
        }

        $lines = $entry->lines()->orderBy('variant_id')->orderBy('id')->get();
        $this->lockBalances($connection, $entry, $lines);
        $lineIds = $lines->modelKeys();
        $lineType = $entry->lines()->getRelated()->getMorphClass();
        $allocations = StockAllocation::on($connection->getName())->where('stock_item_type', $lineType)
            ->whereIn('stock_item_id', $lineIds)->pluck('id');
        if (StockAllocation::on($connection->getName())->whereIn('source_allocation_id', $allocations)
            ->whereNull('reversed_at')->exists()) {
            throw ValidationException::withMessages(['record' => 'This sale has a stock return. Reverse that return first.']);
        }
        $this->shortages->restore($entry, $lines);
        $movements = StockMovement::on($connection->getName())->withoutGlobalScope('branch')
            ->where('stock_entry_type', $entry->getMorphClass())->where('stock_entry_id', $entry->id)
            ->where('stock_item_type', $lineType)
            ->whereIn('stock_item_id', $lines->modelKeys())
            ->where('type', '!=', 'stock_variance')
            ->where('is_reversal', false)->orderByDesc('quantity')->orderBy('id')->get();
        $lots = StockLot::on($connection->getName())->withoutGlobalScope('branch')
            ->whereIn('id', $movements->pluck('lot_id')->filter()->unique())
            ->withExists([
                'allocations as has_active_allocations' => fn (Builder $query) => $query
                    ->whereNull('source_allocation_id')->whereNull('reversed_at'),
                'childLots as has_active_children' => fn (Builder $query) => $query
                    ->withoutGlobalScope('branch')->whereNull('reversed_at'),
            ])->orderBy('id')->lockForUpdate()->get()->keyBy('id');

        foreach ($movements as $movement) {
            $lot = $lots->get($movement->lot_id);
            if ($movement->lot_id !== null && ($lot === null || $lot->reversed_at !== null)) {
                throw ValidationException::withMessages(['record' => 'The original stock receipt is no longer available.']);
            }

            if ($movement->quantity > 0 && $lot !== null) {
                if ((int) $lot->remaining_quantity !== (int) $lot->initial_quantity
                    || (int) $lot->remaining_cost !== (int) $lot->initial_cost
                    || $lot->has_active_allocations || $lot->has_active_children) {
                    throw ValidationException::withMessages(['record' => 'Stock from this receipt has been used. Reverse the later movements first.']);
                }
                StockLot::on($connection->getName())->withoutGlobalScope('branch')->whereKey($lot->id)->update([
                    'remaining_quantity' => 0, 'remaining_cost' => 0, 'reversed_at' => now(), 'updated_at' => now(),
                ]);
                $lot->forceFill(['remaining_quantity' => 0, 'remaining_cost' => 0, 'reversed_at' => now()]);
            } elseif ($lot !== null) {
                $this->restoreLot($connection, $lot, -(int) $movement->quantity, -(int) $movement->cost);
            }

            $this->changeBalance($connection, (int) $movement->branch_id, (int) $movement->variant_id,
                -(int) $movement->quantity, -(int) $movement->cost);
            StockMovement::on($connection->getName())->insert([
                'branch_id' => $movement->branch_id, 'variant_id' => $movement->variant_id,
                'stock_entry_type' => $entry->getMorphClass(), 'stock_entry_id' => $entry->id,
                'stock_item_type' => $lineType, 'stock_item_id' => $movement->stock_item_id,
                'lot_id' => $movement->lot_id, 'type' => $movement->type,
                'quantity' => -(int) $movement->quantity, 'cost' => -(int) $movement->cost,
                'movement_date' => now(config('app.timezone'))->toDateString(), 'is_reversal' => true,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        StockAllocation::on($connection->getName())->where('stock_item_type', $lineType)
            ->whereIn('stock_item_id', $lineIds)
            ->whereNull('reversed_at')->update(['reversed_at' => now(), 'updated_at' => now()]);

        $entry->stock_reversed_at = now();
        $entry->save();
        $this->shortages->settle($entry, $lines, now(config('app.timezone'))->toDateString());
    }

    private function receiveLine(Connection $connection, StockEntry $entry, PurchaseItem|OpeningStockItem|AdjustmentItem $line): int
    {
        $this->receiveLot($connection, $entry, $line, $entry->branch_id, $line->base_quantity,
            $line->cost, $line->batch_no, $line->expiry_date?->toDateString());

        return $line->cost;
    }

    private function consumeLine(Connection $connection, StockEntry $entry, StockItem $line,
        ?string $minimumExpiryDate = null, ?int $selectedLotId = null, bool $allowShortage = false,
        ?string $batchNo = null, ?string $expiryDate = null): int
    {
        if (! in_array($entry->stock_method, ['fifo', 'lifo'], true)) {
            throw ValidationException::withMessages(['record' => 'Select a valid stock method in Pharmacy settings.']);
        }
        $lots = $this->eligibleLots($connection, $entry->branch_id, $entry->entry_date->toDateString(), $minimumExpiryDate)
            ->where('variant_id', $line->variant_id);
        if ($selectedLotId !== null) {
            $lots->where('id', $selectedLotId);
        }
        if (filled($batchNo)) {
            $lots->where('batch_no', $batchNo);
        }
        if ($expiryDate !== null) {
            $lots->whereDate('expiry_date', $expiryDate);
        }

        $direction = $entry->stock_method === 'lifo' ? 'desc' : 'asc';
        $remaining = $line->base_quantity;
        $cost = 0;
        $lots->orderBy('received_at', $direction)->orderBy('id', $direction);
        while ($remaining > 0) {
            $batch = (clone $lots)->limit(100)->lockForUpdate()->get();
            if ($batch->isEmpty()) {
                if (! $allowShortage) {
                    throw ValidationException::withMessages(['lines' => 'There is not enough eligible stock for the selected product and date.']);
                }

                return $this->supportedInteger(BigInteger::of($cost)->plus($this->recordShortage($connection, $entry, $line, $remaining)));
            }
            foreach ($batch as $lot) {
                $quantity = min($remaining, (int) $lot->remaining_quantity);
                $takenCost = $this->takeLot($connection, $entry, $line, $lot, $quantity);
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
        $query = StockLot::on($connection->getName())->withoutGlobalScope('branch')->where('branch_id', $branchId)
            ->whereNull('reversed_at')->where('remaining_quantity', '>', 0)->where('received_at', '<=', $stockDate);
        if ($minimumExpiryDate !== null) {
            $query->where(function (Builder $query) use ($minimumExpiryDate): void {
                $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', $minimumExpiryDate);
            });
        }

        return $query;
    }

    private function recordShortage(Connection $connection, StockEntry $entry, StockItem $line, int $quantity): int
    {
        $price = $line->variant?->product?->purchase_price;
        $cost = $this->supportedInteger(BigInteger::of(Money::minor((string) ($price ?? '0')))->multipliedBy($quantity));
        StockAllocation::on($connection->getName())->insert([
            'stock_item_type' => $line->getMorphClass(), 'stock_item_id' => $line->id,
            'lot_id' => null, 'quantity' => $quantity,
            'cost' => $cost, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->movement($connection, $entry, $line, $entry->branch_id, null, -$quantity, -$cost);

        return $cost;
    }

    private function transferLine(Connection $connection, StockEntry $entry, StockItem $line): int
    {
        if ($entry->destination_branch_id === null || $entry->destination_branch_id === $entry->branch_id) {
            throw ValidationException::withMessages(['destination_branch_id' => 'Select another branch.']);
        }

        $cost = $this->consumeLine($connection, $entry, $line);
        $allocations = $line->allocations()->with(['lot' => fn ($query) => $query->withoutGlobalScope('branch')])
            ->orderBy('id')->get();
        foreach ($allocations as $allocation) {
            $this->receiveLot($connection, $entry, $line, $entry->destination_branch_id,
                (int) $allocation->quantity, (int) $allocation->cost, $allocation->lot->batch_no,
                $allocation->lot->expiry_date?->toDateString(), (int) $allocation->lot_id);
        }

        return $cost;
    }

    private function returnSaleLine(Connection $connection, SaleReturn $entry, SaleReturnItem $line): int
    {
        $source = $line->saleItem;
        $source = $this->returnSource($entry, $line, $source, $source?->sale, $entry->sale_id);
        $activeReturn = fn (Builder $query) => $query->whereNull('reversed_at');
        $allocations = $source->allocations()->whereNull('source_allocation_id')->whereNull('reversed_at')
            ->withSum(['returns as returned_quantity' => $activeReturn], 'quantity')
            ->withSum(['returns as returned_cost' => $activeReturn], 'cost')
            ->with('lot')->orderBy('id')->lockForUpdate()->get();
        $remaining = $line->base_quantity;
        $cost = 0;

        foreach ($allocations as $allocation) {
            $available = (int) $allocation->quantity - (int) $allocation->returned_quantity;
            if ($available === 0) {
                continue;
            }

            $quantity = min($remaining, $available);
            $restoredCost = $this->portion((int) $allocation->cost - (int) $allocation->returned_cost, $quantity, $available);
            if ($allocation->lot_id === null) {
                $restoredCost = $this->returnShortage($connection, $entry, $line, $allocation, $quantity, $restoredCost);
            } else {
                $originalLot = $allocation->lot;
                $lotId = $this->receiveLot($connection, $entry, $line, $entry->branch_id, $quantity,
                    $restoredCost, $originalLot->batch_no, $originalLot->expiry_date?->toDateString(), (int) $originalLot->id);
                $this->recordReturnAllocation($connection, $line, $allocation, $lotId, $quantity, $restoredCost);
            }
            $cost = $this->supportedInteger(BigInteger::of($cost)->plus($restoredCost));
            $remaining -= $quantity;
            if ($remaining === 0) {
                $source->increment('returned_base_quantity', $line->base_quantity);

                return $cost;
            }
        }

        throw ValidationException::withMessages(['lines' => 'The return quantity exceeds the original sale allocation.']);
    }

    private function returnShortage(Connection $connection, StockEntry $entry, StockItem $line, StockAllocation $allocation,
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
            $this->movement($connection, $entry, $line, $entry->branch_id, null, $uncoveredQuantity, $cost);
            $remaining -= $uncoveredQuantity;
            $remainingSnapshotCost -= $cost;
        }
        if ($remaining === 0) {
            return $cost;
        }

        $activeSettlement = fn (Builder $query) => $query->where('allocation_id', $allocation->id)->whereNull('reversed_at');
        $previousReturn = fn (Builder $query) => $query->whereHas('allocations', fn (Builder $query) => $query
            ->where('source_allocation_id', $allocation->id)->whereNull('reversed_at'));
        $receiptLots = StockLot::on($connection->getName())->withoutGlobalScope('branch')
            ->whereHas('shortageSettlements', $activeSettlement)
            ->withSum(['shortageSettlements as settled_quantity' => $activeSettlement], 'quantity')
            ->withSum(['shortageSettlements as settled_cost' => $activeSettlement], 'receipt_cost')
            ->withSum(['childLots as returned_quantity' => $previousReturn], 'initial_quantity')
            ->withSum(['childLots as returned_cost' => $previousReturn], 'initial_cost')
            ->orderBy('id')->lockForUpdate()->lazyById(100);

        foreach ($receiptLots as $originalLot) {
            $available = (int) $originalLot->settled_quantity - (int) $originalLot->returned_quantity;
            if ($available < 1) {
                continue;
            }
            $quantity = min($remaining, $available);
            $actualCost = $this->portion((int) $originalLot->settled_cost - (int) $originalLot->returned_cost, $quantity, $available);
            $returnedSnapshotCost = $this->portion($remainingSnapshotCost, $quantity, $remaining);
            if ($originalLot->reversed_at !== null) {
                throw ValidationException::withMessages(['lines' => 'The receipt covering this sale is no longer available.']);
            }
            $lotId = $this->receiveLot($connection, $entry, $line, $entry->branch_id, $quantity,
                $actualCost, $originalLot->batch_no, $originalLot->expiry_date?->toDateString(), (int) $originalLot->id);
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

    private function recordReturnAllocation(Connection $connection, StockItem $line, StockAllocation $source, ?int $lotId,
        int $quantity, int $snapshotCost): void
    {
        StockAllocation::on($connection->getName())->insert([
            'stock_item_type' => $line->getMorphClass(), 'stock_item_id' => $line->id,
            'lot_id' => $lotId, 'source_allocation_id' => $source->id,
            'quantity' => $quantity, 'cost' => $snapshotCost, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function returnPurchaseLine(Connection $connection, PurchaseReturn $entry, PurchaseReturnItem $line): int
    {
        $source = $line->purchaseItem;
        $source = $this->returnSource($entry, $line, $source, $source?->purchase, $entry->purchase_id);
        $lot = StockLot::on($connection->getName())->where('source_item_type', $source->getMorphClass())
            ->where('source_item_id', $source->id)
            ->where('branch_id', $entry->branch_id)->whereNull('reversed_at')->orderBy('id')->lockForUpdate()->first();
        if ($lot === null || (int) $lot->remaining_quantity < $line->base_quantity) {
            throw ValidationException::withMessages(['lines' => 'Only remaining stock from the original purchase can be returned.']);
        }

        $cost = $this->takeLot($connection, $entry, $line, $lot, $line->base_quantity);
        $source->increment('returned_base_quantity', $line->base_quantity);

        return $cost;
    }

    private function returnSource(StockEntry $entry, StockItem $line, ?StockItem $source, ?StockEntry $invoice, ?int $invoiceId): StockItem
    {
        if ($source === null || $invoice === null || $invoice->id !== $invoiceId
            || $invoice->branch_id !== $entry->branch_id || $invoice->product_type !== $entry->product_type
            || $invoice->voided_at !== null || $invoice->deleted_at !== null || $source->superseded_at !== null
            || (int) $source->variant_id !== $line->variant_id
            || (int) $source->base_quantity - (int) $source->returned_base_quantity < $line->base_quantity) {
            throw ValidationException::withMessages(['lines' => 'Select a valid original invoice line and an available return quantity.']);
        }

        return $source;
    }

    private function takeLot(Connection $connection, StockEntry $entry, StockItem $line, StockLot $lot, int $quantity): int
    {
        $cost = $this->portion((int) $lot->remaining_cost, $quantity, (int) $lot->remaining_quantity);
        StockLot::on($connection->getName())->withoutGlobalScope('branch')->whereKey($lot->id)->update([
            'remaining_quantity' => (int) $lot->remaining_quantity - $quantity,
            'remaining_cost' => (int) $lot->remaining_cost - $cost, 'updated_at' => now(),
        ]);
        StockAllocation::on($connection->getName())->insert([
            'stock_item_type' => $line->getMorphClass(), 'stock_item_id' => $line->id,
            'lot_id' => $lot->id, 'quantity' => $quantity,
            'cost' => $cost, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->movement($connection, $entry, $line, $entry->branch_id, (int) $lot->id, -$quantity, -$cost);

        return $cost;
    }

    private function receiveLot(Connection $connection, StockEntry $entry, StockItem $line, int $branchId, int $quantity,
        int $cost, ?string $batchNo, ?string $expiryDate, ?int $parentLotId = null): int
    {
        $lotId = StockLot::on($connection->getName())->insertGetId([
            'branch_id' => $branchId, 'variant_id' => $line->variant_id,
            'source_item_type' => $line->getMorphClass(), 'source_item_id' => $line->id,
            'parent_lot_id' => $parentLotId, 'received_at' => $entry->entry_date->toDateString(),
            'initial_quantity' => $quantity, 'remaining_quantity' => $quantity,
            'initial_cost' => $cost, 'remaining_cost' => $cost,
            'batch_no' => $batchNo, 'expiry_date' => $expiryDate, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->movement($connection, $entry, $line, $branchId, $lotId, $quantity, $cost);

        return $lotId;
    }

    private function movement(Connection $connection, StockEntry $entry, StockItem $line, int $branchId, ?int $lotId,
        int $quantity, int $cost): void
    {
        $this->changeBalance($connection, $branchId, $line->variant_id, $quantity, $cost);
        StockMovement::on($connection->getName())->insert([
            'branch_id' => $branchId, 'variant_id' => $line->variant_id,
            'stock_entry_type' => $entry->getMorphClass(), 'stock_entry_id' => $entry->id,
            'stock_item_type' => $line->getMorphClass(), 'stock_item_id' => $line->id,
            'lot_id' => $lotId, 'type' => $entry::TYPE,
            'quantity' => $quantity, 'cost' => $cost, 'movement_date' => $entry->entry_date->toDateString(),
            'is_reversal' => false, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function changeBalance(Connection $connection, int $branchId, int $variantId, int $quantity, int $cost): void
    {
        $query = StockBalance::on($connection->getName())->withoutGlobalScope('branch')
            ->where('branch_id', $branchId)->where('variant_id', $variantId);
        $balance = $query->first();
        $newQuantity = $this->supportedInteger(BigInteger::of($balance->quantity)->plus($quantity));
        $newCost = $this->supportedInteger(BigInteger::of($balance->cost)->plus($cost));
        $query->update(['quantity' => $newQuantity, 'cost' => $newCost, 'updated_at' => now()]);
    }

    private function restoreLot(Connection $connection, StockLot $lot, int $quantity, int $cost): void
    {
        $newQuantity = $this->supportedInteger(BigInteger::of($lot->remaining_quantity)->plus($quantity));
        $newCost = $this->supportedInteger(BigInteger::of($lot->remaining_cost)->plus($cost));
        if ($newQuantity > (int) $lot->initial_quantity || $newCost > (int) $lot->initial_cost) {
            throw ValidationException::withMessages(['record' => 'The original stock allocation cannot be restored twice.']);
        }

        StockLot::on($connection->getName())->withoutGlobalScope('branch')->whereKey($lot->id)->update([
            'remaining_quantity' => $newQuantity, 'remaining_cost' => $newCost, 'updated_at' => now(),
        ]);
        $lot->forceFill(['remaining_quantity' => $newQuantity, 'remaining_cost' => $newCost]);
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

    private function lockBalances(Connection $connection, StockEntry $entry, Collection $lines): void
    {
        $branches = $entry->stockBranchIds();
        sort($branches, SORT_NUMERIC);
        $variantIds = $lines->pluck('variant_id')->unique()->sort()->values();

        foreach (array_unique($branches) as $branchId) {
            foreach ($variantIds as $variantId) {
                StockBalance::on($connection->getName())->insertOrIgnore([
                    'branch_id' => $branchId, 'variant_id' => $variantId, 'quantity' => 0,
                    'cost' => 0, 'created_at' => now(), 'updated_at' => now(),
                ]);
                StockBalance::on($connection->getName())->withoutGlobalScope('branch')->where('branch_id', $branchId)
                    ->where('variant_id', $variantId)->lockForUpdate()->first();
            }
        }
    }

    private function requireTransaction(Connection $connection): void
    {
        if ($connection->transactionLevel() < 1) {
            throw new LogicException('Stock movements require an enclosing tenant transaction.');
        }
    }
}
