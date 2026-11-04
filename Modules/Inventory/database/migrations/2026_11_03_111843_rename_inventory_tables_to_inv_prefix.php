<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES = [
        'inventory_product_categories' => 'inv_product_categories',
        'inventory_dosages' => 'inv_dosages',
        'inventory_units' => 'inv_units',
        'inventory_shelves' => 'inv_shelves',
        'inventory_suppliers' => 'inv_suppliers',
        'inventory_products' => 'inv_products',
        'inventory_product_variants' => 'inv_product_variants',
        'inventory_product_units' => 'inv_product_units',
        'inventory_seed_runs' => 'inv_seed_runs',
        'inventory_sequences' => 'inv_sequences',
        'inventory_purchases' => 'inv_purchases',
        'inventory_purchase_items' => 'inv_purchase_items',
        'inventory_sales' => 'inv_sales',
        'inventory_sale_items' => 'inv_sale_items',
        'inventory_purchase_returns' => 'inv_purchase_returns',
        'inventory_purchase_return_items' => 'inv_purchase_return_items',
        'inventory_sale_returns' => 'inv_sale_returns',
        'inventory_sale_return_items' => 'inv_sale_return_items',
        'inventory_adjustments' => 'inv_adjustments',
        'inventory_adjustment_items' => 'inv_adjustment_items',
        'inventory_opening_stocks' => 'inv_opening_stocks',
        'inventory_opening_stock_items' => 'inv_opening_stock_items',
        'inventory_wastages' => 'inv_wastages',
        'inventory_wastage_items' => 'inv_wastage_items',
        'inventory_transfers' => 'inv_transfers',
        'inventory_transfer_items' => 'inv_transfer_items',
        'inventory_stock_balances' => 'inv_stock_balances',
        'inventory_stock_lots' => 'inv_stock_lots',
        'inventory_stock_allocations' => 'inv_stock_allocations',
        'inventory_stock_movements' => 'inv_stock_movements',
        'inventory_stock_shortage_settlements' => 'inv_stock_shortage_settlements',
        'inventory_payments' => 'inv_payments',
        'inventory_purchase_payment_allocations' => 'inv_purchase_payment_allocations',
        'inventory_sale_payment_allocations' => 'inv_sale_payment_allocations',
    ];

    private const UNIQUE_INDEXES = [
        ['inv_purchases', 'inventory_purchases_number_unique', 'inv_purchases_number_unique'],
        ['inv_sales', 'inventory_sales_number_unique', 'inv_sales_number_unique'],
        ['inv_purchase_returns', 'inventory_purchase_returns_number_unique', 'inv_purchase_returns_number_unique'],
        ['inv_sale_returns', 'inventory_sale_returns_number_unique', 'inv_sale_returns_number_unique'],
        ['inv_adjustments', 'inventory_adjustments_number_unique', 'inv_adjustments_number_unique'],
        ['inv_opening_stocks', 'inventory_opening_stocks_number_unique', 'inv_opening_stocks_number_unique'],
        ['inv_wastages', 'inventory_wastages_number_unique', 'inv_wastages_number_unique'],
        ['inv_transfers', 'inventory_transfers_number_unique', 'inv_transfers_number_unique'],
        ['inv_payments', 'inventory_payments_number_unique', 'inv_payments_number_unique'],
        ['inv_purchases', 'inventory_supplier_invoice_unique', 'inv_supplier_invoice_unique'],
    ];

    public function up(): void
    {
        $this->renameTables(self::TABLES);
        $this->renameUniqueIndexes(self::UNIQUE_INDEXES);
    }

    public function down(): void
    {
        $indexes = [];

        foreach (self::UNIQUE_INDEXES as [$table, $from, $to]) {
            $indexes[] = [$table, $to, $from];
        }

        $this->renameUniqueIndexes($indexes);
        $this->renameTables(array_flip(array_reverse(self::TABLES, true)));
    }

    private function renameTables(array $tables): void
    {
        $schema = Schema::connection('tenant');

        foreach ($tables as $from => $to) {
            if ($schema->hasTable($from) === $schema->hasTable($to)) {
                throw new RuntimeException("Expected exactly one Inventory table to exist: {$from} or {$to}.");
            }
        }

        foreach ($tables as $from => $to) {
            if ($schema->hasTable($from)) {
                // Native renames preserve the rows and update foreign-key table references.
                $schema->rename($from, $to);
            }
        }
    }

    private function renameUniqueIndexes(array $indexes): void
    {
        $schema = Schema::connection('tenant');

        foreach ($indexes as [$tableName, $from, $to]) {
            // A resumed rollback may already have restored this table's original name.
            if (! $schema->hasTable($tableName)) {
                continue;
            }

            $hasFrom = $schema->hasIndex($tableName, $from, 'unique');
            $hasTo = $schema->hasIndex($tableName, $to, 'unique');

            if ($hasFrom === $hasTo) {
                throw new RuntimeException("Expected exactly one Inventory unique index to exist on {$tableName}: {$from} or {$to}.");
            }

            if ($hasFrom) {
                $schema->table($tableName, function (Blueprint $table) use ($from, $to): void {
                    $table->renameIndex($from, $to);
                });
            }
        }
    }
};
