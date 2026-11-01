<?php

namespace Modules\Inventory\Services;

use Brick\Math\BigInteger;
use Illuminate\Database\Connection;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use LogicException;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\DocumentLine;
use stdClass;

class StockShortageService
{
    public function remaining(Connection $connection, stdClass $allocation): array
    {
        $settled = $connection->table('inventory_stock_shortage_settlements')->where('allocation_id', $allocation->id)
            ->whereNull('reversed_at')->selectRaw('COALESCE(SUM(quantity), 0) AS quantity, COALESCE(SUM(snapshot_cost_minor), 0) AS cost_minor')->first();
        $returned = $connection->table('inventory_stock_allocations')->where('source_allocation_id', $allocation->id)
            ->whereNull('lot_id')->whereNull('reversed_at')
            ->selectRaw('COALESCE(SUM(quantity), 0) AS quantity, COALESCE(SUM(cost_minor), 0) AS cost_minor')->first();

        return $this->remainingBalance($allocation, (int) $settled->quantity, (int) $settled->cost_minor,
            (int) $returned->quantity, (int) $returned->cost_minor);
    }

    public function settle(Document $trigger, Collection $lines, ?string $movementDate = null): void
    {
        $connection = $trigger->getConnection();
        $this->requireTransaction($connection);
        $date = $movementDate ?? max(now(config('app.timezone'))->toDateString(), $trigger->document_date->toDateString());
        $triggerLines = $lines->keyBy('variant_id');
        $receiptReturns = [];
        $branches = array_values(array_unique(array_filter([(int) $trigger->branch_id, (int) $trigger->destination_branch_id])));
        sort($branches);

        $settled = $connection->table('inventory_stock_shortage_settlements')->whereNull('reversed_at')->groupBy('allocation_id')
            ->selectRaw('allocation_id, SUM(quantity) AS quantity, SUM(snapshot_cost_minor) AS cost_minor');
        $returned = $connection->table('inventory_stock_allocations')->whereNotNull('source_allocation_id')
            ->whereNull('lot_id')->whereNull('reversed_at')->groupBy('source_allocation_id')
            ->selectRaw('source_allocation_id, SUM(quantity) AS quantity, SUM(cost_minor) AS cost_minor');

        foreach ($branches as $branchId) {
            $allocations = $connection->table('inventory_stock_allocations as allocations')
                ->join('inventory_document_lines as lines', 'lines.id', '=', 'allocations.document_line_id')
                ->join('inventory_documents as documents', 'documents.id', '=', 'lines.document_id')
                ->leftJoinSub(clone $settled, 'settled', 'settled.allocation_id', '=', 'allocations.id')
                ->leftJoinSub(clone $returned, 'returned', 'returned.source_allocation_id', '=', 'allocations.id')
                ->where('documents.branch_id', $branchId)->where('documents.type', 'sale')
                ->whereNull('documents.voided_at')->whereNull('documents.deleted_at')->whereNull('lines.superseded_at')
                ->whereDate('documents.document_date', '<=', $date)->whereIn('lines.variant_id', $triggerLines->keys())
                ->whereNull('allocations.lot_id')->whereNull('allocations.source_allocation_id')->whereNull('allocations.reversed_at')
                ->whereRaw('allocations.quantity > COALESCE(settled.quantity, 0) + COALESCE(returned.quantity, 0)')
                ->orderBy('allocations.id')
                ->select(['allocations.*', 'lines.variant_id', 'documents.id AS sale_document_id', 'documents.product_type', 'documents.stock_method'])
                ->selectRaw('COALESCE(settled.quantity, 0) AS settled_quantity, COALESCE(settled.cost_minor, 0) AS settled_cost_minor')
                ->selectRaw('COALESCE(returned.quantity, 0) AS returned_quantity, COALESCE(returned.cost_minor, 0) AS returned_cost_minor')
                ->lockForUpdate()->lazyById(100, 'allocations.id', 'id');

            foreach ($allocations as $allocation) {
                $remaining = $this->remainingBalance($allocation, (int) $allocation->settled_quantity,
                    (int) $allocation->settled_cost_minor, (int) $allocation->returned_quantity, (int) $allocation->returned_cost_minor);
                $lots = $connection->table('inventory_stock_lots')->where('branch_id', $branchId)
                    ->where('variant_id', $allocation->variant_id)->whereNull('reversed_at')->where('remaining_quantity', '>', 0)
                    ->whereDate('received_at', '<=', $date);
                if ($allocation->product_type === 'medicine') {
                    $lots->where(fn ($query) => $query->whereNull('expiry_date')->orWhereDate('expiry_date', '>=', $date));
                }
                $order = $allocation->stock_method === 'lifo' ? 'desc' : 'asc';
                $lots->orderBy('received_at', $order)->orderBy('id', $order);
                while ($remaining['quantity'] > 0) {
                    $batch = (clone $lots)->limit(100)->lockForUpdate()->get();
                    if ($batch->isEmpty()) {
                        break;
                    }
                    foreach ($batch as $lot) {
                        $receiptReturns[$lot->id] ??= $this->returnedSales($connection, (int) $lot->id);
                        // A return cannot cover its own sale's deficit: that would prevent reversing either record.
                        if (in_array((int) $allocation->sale_document_id, $receiptReturns[$lot->id], true)) {
                            $lots->where('id', '!=', $lot->id);

                            continue;
                        }
                        $quantity = min($remaining['quantity'], (int) $lot->remaining_quantity);
                        $snapshotCost = $this->portion($remaining['cost_minor'], $quantity, $remaining['quantity']);
                        $receiptCost = $this->portion((int) $lot->remaining_cost_minor, $quantity, (int) $lot->remaining_quantity);
                        $connection->table('inventory_stock_lots')->where('id', $lot->id)->update([
                            'remaining_quantity' => (int) $lot->remaining_quantity - $quantity,
                            'remaining_cost_minor' => (int) $lot->remaining_cost_minor - $receiptCost, 'updated_at' => now(),
                        ]);
                        $connection->table('inventory_stock_shortage_settlements')->insert([
                            'allocation_id' => $allocation->id, 'lot_id' => $lot->id, 'quantity' => $quantity,
                            'snapshot_cost_minor' => $snapshotCost, 'receipt_cost_minor' => $receiptCost,
                            'created_at' => now(), 'updated_at' => now(),
                        ]);
                        $this->variance($connection, $trigger, $triggerLines[$allocation->variant_id], $branchId,
                            (int) $lot->id, $snapshotCost - $receiptCost, $date, false);
                        $remaining['quantity'] -= $quantity;
                        $remaining['cost_minor'] -= $snapshotCost;
                        if ($remaining['quantity'] === 0) {
                            break;
                        }
                    }
                }
            }
        }
    }

    public function restore(Document $document, Collection $lines): void
    {
        $connection = $document->getConnection();
        $this->requireTransaction($connection);
        $originals = $lines->keyBy('id');
        $settlements = $connection->table('inventory_stock_shortage_settlements as settlements')
            ->join('inventory_stock_allocations as allocations', 'allocations.id', '=', 'settlements.allocation_id')
            ->whereIn('allocations.document_line_id', $originals->keys())->whereNull('allocations.source_allocation_id')
            ->whereNull('allocations.reversed_at')->whereNull('settlements.reversed_at')
            ->orderBy('settlements.lot_id')->orderBy('settlements.id')
            ->select(['settlements.*', 'allocations.document_line_id'])->lockForUpdate()->get();

        foreach ($settlements as $settlement) {
            $lot = $connection->table('inventory_stock_lots')->where('id', $settlement->lot_id)->lockForUpdate()->first();
            if ($lot === null || $lot->reversed_at !== null) {
                throw ValidationException::withMessages(['record' => 'The receipt covering this sale is no longer available.']);
            }
            $quantity = (int) $lot->remaining_quantity + (int) $settlement->quantity;
            $cost = (int) $lot->remaining_cost_minor + (int) $settlement->receipt_cost_minor;
            if ($quantity > (int) $lot->initial_quantity || $cost > (int) $lot->initial_cost_minor) {
                throw ValidationException::withMessages(['record' => 'The receipt covering this sale has inconsistent quantities.']);
            }
            $connection->table('inventory_stock_lots')->where('id', $lot->id)->update([
                'remaining_quantity' => $quantity, 'remaining_cost_minor' => $cost, 'updated_at' => now(),
            ]);
            $connection->table('inventory_stock_shortage_settlements')->where('id', $settlement->id)
                ->update(['reversed_at' => now(), 'updated_at' => now()]);
            $this->variance($connection, $document, $originals[$settlement->document_line_id], (int) $lot->branch_id,
                (int) $lot->id, (int) $settlement->receipt_cost_minor - (int) $settlement->snapshot_cost_minor,
                now(config('app.timezone'))->toDateString(), true);
        }
    }

    private function remainingBalance(stdClass $allocation, int $settledQuantity, int $settledCost, int $returnedQuantity, int $returnedCost): array
    {
        $quantity = (int) $allocation->quantity - $settledQuantity - $returnedQuantity;
        $cost = (int) $allocation->cost_minor - $settledCost - $returnedCost;
        if ($quantity < 0 || $cost < 0 || ($quantity === 0 && $cost !== 0)) {
            throw ValidationException::withMessages(['record' => 'The outstanding stock quantity and cost are inconsistent.']);
        }

        return ['quantity' => $quantity, 'cost_minor' => $cost];
    }

    private function variance(Connection $connection, Document $document, DocumentLine $line, int $branchId,
        int $lotId, int $cost, string $date, bool $reversal): void
    {
        if ($cost === 0) {
            return;
        }
        $balance = $connection->table('inventory_stock_balances')->where('branch_id', $branchId)
            ->where('variant_id', $line->variant_id)->lockForUpdate()->first();
        if ($balance === null) {
            throw new LogicException('Stock balances must be locked before shortage settlement.');
        }
        $value = BigInteger::of($balance->cost_minor)->plus($cost);
        if ($value->isGreaterThan(PHP_INT_MAX) || $value->isLessThan(PHP_INT_MIN)) {
            throw ValidationException::withMessages(['record' => 'The stock value exceeds the supported limit.']);
        }
        $connection->table('inventory_stock_balances')->where('id', $balance->id)
            ->update(['cost_minor' => $value->toInt(), 'updated_at' => now()]);
        $connection->table('inventory_stock_movements')->insert([
            'branch_id' => $branchId, 'variant_id' => $line->variant_id, 'document_id' => $document->id,
            'document_line_id' => $line->id, 'lot_id' => $lotId, 'type' => 'stock_variance',
            'quantity' => 0, 'cost_minor' => $cost, 'movement_date' => $date, 'is_reversal' => $reversal,
            'created_at' => now(), 'updated_at' => now(),
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

    private function returnedSales(Connection $connection, int $lotId): array
    {
        $returns = $connection->select(<<<'SQL'
            WITH RECURSIVE receipt_lineage AS (
                SELECT id, parent_lot_id, source_line_id
                FROM inventory_stock_lots WHERE id = ?
                UNION ALL
                SELECT parent.id, parent.parent_lot_id, parent.source_line_id
                FROM inventory_stock_lots AS parent
                JOIN receipt_lineage AS child ON parent.id = child.parent_lot_id
            )
            SELECT DISTINCT documents.source_document_id AS id
            FROM receipt_lineage
            JOIN inventory_document_lines AS lines ON lines.id = receipt_lineage.source_line_id
            JOIN inventory_documents AS documents ON documents.id = lines.document_id
            WHERE documents.type = 'sale_return'
            SQL, [$lotId]);

        return array_map(static fn (stdClass $return): int => (int) $return->id, $returns);
    }
}
