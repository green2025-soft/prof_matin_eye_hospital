<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const COLUMNS = [
        'inv_adjustment_items' => ['unit_price_minor', 'cost_minor'],
        'inv_adjustments' => ['cost_minor'],
        'inv_opening_stock_items' => ['unit_price_minor', 'cost_minor'],
        'inv_opening_stocks' => ['cost_minor'],
        'inv_payments' => ['amount_minor', 'discount_minor'],
        'inv_purchase_items' => [
            'unit_price_minor', 'subtotal_minor', 'discount_minor', 'invoice_discount_minor',
            'vat_minor', 'total_minor', 'cost_minor', 'sale_price_per_piece_minor',
            'discount_unit_amount_minor', 'vat_unit_amount_minor',
        ],
        'inv_purchase_payment_allocations' => ['amount_minor', 'discount_minor'],
        'inv_purchase_return_items' => [
            'unit_price_minor', 'subtotal_minor', 'discount_minor', 'invoice_discount_minor',
            'vat_minor', 'total_minor', 'cost_minor',
        ],
        'inv_purchase_returns' => ['total_minor', 'initial_paid_minor', 'cost_minor'],
        'inv_purchases' => [
            'subtotal_minor', 'discount_minor', 'vat_minor', 'total_minor', 'paid_minor',
            'initial_paid_minor', 'settlement_discount_minor', 'returned_minor', 'refunded_minor',
            'outstanding_minor', 'cost_minor',
        ],
        'inv_sale_items' => [
            'unit_price_minor', 'subtotal_minor', 'discount_minor', 'invoice_discount_minor',
            'vat_minor', 'total_minor', 'cost_minor',
        ],
        'inv_sale_payment_allocations' => ['amount_minor', 'discount_minor'],
        'inv_sale_return_items' => [
            'unit_price_minor', 'subtotal_minor', 'discount_minor', 'invoice_discount_minor',
            'vat_minor', 'total_minor', 'cost_minor',
        ],
        'inv_sale_returns' => ['total_minor', 'initial_paid_minor', 'cost_minor'],
        'inv_sales' => [
            'subtotal_minor', 'discount_minor', 'vat_minor', 'total_minor', 'paid_minor',
            'initial_paid_minor', 'settlement_discount_minor', 'returned_minor', 'refunded_minor',
            'outstanding_minor', 'cost_minor',
        ],
        'inv_stock_allocations' => ['cost_minor'],
        'inv_stock_balances' => ['cost_minor'],
        'inv_stock_lots' => ['initial_cost_minor', 'remaining_cost_minor'],
        'inv_stock_movements' => ['cost_minor'],
        'inv_stock_shortage_settlements' => ['snapshot_cost_minor', 'receipt_cost_minor'],
        'inv_transfer_items' => ['cost_minor'],
        'inv_transfers' => ['cost_minor'],
        'inv_wastage_items' => ['cost_minor'],
        'inv_wastages' => ['cost_minor'],
    ];

    public function up(): void
    {
        $this->renameMoneyColumns(false);
    }

    public function down(): void
    {
        $this->renameMoneyColumns(true);
    }

    private function renameMoneyColumns(bool $restoreSuffix): void
    {
        $schema = Schema::connection('tenant');
        $renames = [];

        foreach (self::COLUMNS as $table => $columns) {
            $existing = $schema->getColumnListing($table);

            foreach ($columns as $original) {
                $renamed = substr($original, 0, -6);
                $from = $restoreSuffix ? $renamed : $original;
                $to = $restoreSuffix ? $original : $renamed;
                $hasFrom = in_array($from, $existing, true);
                $hasTo = in_array($to, $existing, true);

                if ($hasFrom === $hasTo) {
                    throw new RuntimeException("Expected exactly one Inventory money column: {$table}.{$from} or {$to}.");
                }

                if ($hasFrom) {
                    $renames[$table][] = [$from, $to];
                }
            }
        }

        foreach ($renames as $tableName => $columns) {
            // Renaming keeps integer money values, defaults and indexes intact.
            $schema->table($tableName, function (Blueprint $table) use ($columns): void {
                foreach ($columns as [$from, $to]) {
                    $table->renameColumn($from, $to);
                }
            });
        }
    }
};
