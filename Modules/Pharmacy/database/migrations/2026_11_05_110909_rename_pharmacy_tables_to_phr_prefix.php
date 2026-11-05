<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES = [
        'product_categories',
        'dosages',
        'units',
        'shelves',
        'suppliers',
        'products',
        'product_variants',
        'product_units',
        'seed_runs',
        'sequences',
        'purchases',
        'purchase_items',
        'sales',
        'sale_items',
        'purchase_returns',
        'purchase_return_items',
        'sale_returns',
        'sale_return_items',
        'adjustments',
        'adjustment_items',
        'opening_stocks',
        'opening_stock_items',
        'wastages',
        'wastage_items',
        'transfers',
        'transfer_items',
        'stock_balances',
        'stock_lots',
        'stock_allocations',
        'stock_movements',
        'stock_shortage_settlements',
        'payments',
        'purchase_payment_allocations',
        'sale_payment_allocations',
    ];

    private const UNIQUE_INDEXES = [
        ['purchases', 'purchases_number_unique'],
        ['sales', 'sales_number_unique'],
        ['purchase_returns', 'purchase_returns_number_unique'],
        ['sale_returns', 'sale_returns_number_unique'],
        ['adjustments', 'adjustments_number_unique'],
        ['opening_stocks', 'opening_stocks_number_unique'],
        ['wastages', 'wastages_number_unique'],
        ['transfers', 'transfers_number_unique'],
        ['payments', 'payments_number_unique'],
        ['purchases', 'supplier_invoice_unique'],
    ];

    public function up(): void
    {
        $this->renameTables('inv_', 'phr_', self::TABLES);
        $this->renameUniqueIndexes('inv_', 'phr_');
    }

    public function down(): void
    {
        $this->renameTables('phr_', 'inv_', array_reverse(self::TABLES));
        $this->renameUniqueIndexes('phr_', 'inv_');
    }

    private function renameTables(string $fromPrefix, string $toPrefix, array $tables): void
    {
        $schema = Schema::connection('tenant');
        $connection = $schema->getConnection();
        $existing = array_flip($connection->withoutPretending(
            fn (): array => $schema->getTableListing($connection->getDatabaseName(), schemaQualified: false),
        ));

        // Validate the whole set before the first DDL change; a partial run can resume.
        foreach ($tables as $table) {
            if (isset($existing[$fromPrefix.$table]) === isset($existing[$toPrefix.$table])) {
                throw new RuntimeException("Expected exactly one Pharmacy table: {$fromPrefix}{$table} or {$toPrefix}{$table}.");
            }
        }

        foreach ($tables as $table) {
            if (isset($existing[$fromPrefix.$table])) {
                $schema->rename($fromPrefix.$table, $toPrefix.$table);
            }
        }
    }

    private function renameUniqueIndexes(string $fromPrefix, string $toPrefix): void
    {
        $schema = Schema::connection('tenant');
        $connection = $schema->getConnection();
        $existing = array_flip($connection->withoutPretending(
            fn (): array => $schema->getTableListing($connection->getDatabaseName(), schemaQualified: false),
        ));

        foreach (self::UNIQUE_INDEXES as [$table, $index]) {
            $tableName = $toPrefix.$table;
            // A migration preview has not physically renamed its tables yet.
            $metadataTable = isset($existing[$tableName]) ? $tableName : $fromPrefix.$table;
            $indexes = array_flip(array_column($connection->withoutPretending(
                fn (): array => $schema->getIndexes($metadataTable),
            ), 'name'));
            $from = $fromPrefix.$index;
            $to = $toPrefix.$index;

            if (isset($indexes[$from]) === isset($indexes[$to])) {
                throw new RuntimeException("Expected exactly one Pharmacy unique index on {$tableName}: {$from} or {$to}.");
            }

            if (isset($indexes[$from])) {
                $schema->table($tableName, function (Blueprint $blueprint) use ($from, $to): void {
                    $blueprint->renameIndex($from, $to);
                });
            }
        }
    }
};
