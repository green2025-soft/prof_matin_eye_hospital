<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('tenant');

        foreach ([
            'inventory_purchases', 'inventory_sales', 'inventory_purchase_returns', 'inventory_sale_returns',
            'inventory_adjustments', 'inventory_opening_stocks', 'inventory_wastages', 'inventory_transfers',
        ] as $tableName) {
            $schema->table($tableName, function (Blueprint $table): void {
                $table->renameColumn('document_date', 'entry_date');
            });
        }

        $this->renameForeignKey($schema, 'inventory_purchase_items', 'document_id', 'purchase_id', 'inventory_purchases');
        $this->renameForeignKey($schema, 'inventory_sale_items', 'document_id', 'sale_id', 'inventory_sales');
        $this->renameForeignKey($schema, 'inventory_purchase_return_items', 'document_id', 'purchase_return_id', 'inventory_purchase_returns');
        $this->renameForeignKey($schema, 'inventory_sale_return_items', 'document_id', 'sale_return_id', 'inventory_sale_returns');
        $this->renameForeignKey($schema, 'inventory_adjustment_items', 'document_id', 'adjustment_id', 'inventory_adjustments');
        $this->renameForeignKey($schema, 'inventory_opening_stock_items', 'document_id', 'opening_stock_id', 'inventory_opening_stocks');
        $this->renameForeignKey($schema, 'inventory_wastage_items', 'document_id', 'wastage_id', 'inventory_wastages');
        $this->renameForeignKey($schema, 'inventory_transfer_items', 'document_id', 'transfer_id', 'inventory_transfers');

        $this->renameForeignKey($schema, 'inventory_purchase_returns', 'source_document_id', 'purchase_id', 'inventory_purchases');
        $this->renameForeignKey($schema, 'inventory_sale_returns', 'source_document_id', 'sale_id', 'inventory_sales');
        $this->renameForeignKey($schema, 'inventory_purchase_return_items', 'source_line_id', 'purchase_item_id', 'inventory_purchase_items');
        $this->renameForeignKey($schema, 'inventory_sale_return_items', 'source_line_id', 'sale_item_id', 'inventory_sale_items');
        $this->renameForeignKey($schema, 'inventory_purchase_payment_allocations', 'document_id', 'purchase_id', 'inventory_purchases');
        $this->renameForeignKey($schema, 'inventory_sale_payment_allocations', 'document_id', 'sale_id', 'inventory_sales');

        $schema->table('inventory_purchase_payment_allocations', function (Blueprint $table): void {
            $table->renameIndex('inventory_purchase_payment_document_unique', 'inventory_purchase_payment_purchase_unique');
        });
        $schema->table('inventory_sale_payment_allocations', function (Blueprint $table): void {
            $table->renameIndex('inventory_sale_payment_document_unique', 'inventory_sale_payment_sale_unique');
        });
        $schema->table('inventory_stock_lots', function (Blueprint $table): void {
            $table->renameColumn('source_line_type', 'source_item_type');
            $table->renameColumn('source_line_id', 'source_item_id');
        });
        $schema->table('inventory_stock_allocations', function (Blueprint $table): void {
            $table->renameColumn('document_line_type', 'stock_item_type');
            $table->renameColumn('document_line_id', 'stock_item_id');
            $table->renameIndex('inventory_stock_allocation_line', 'inventory_stock_allocation_item');
        });
        $schema->table('inventory_stock_movements', function (Blueprint $table): void {
            $table->renameColumn('document_type', 'stock_entry_type');
            $table->renameColumn('document_id', 'stock_entry_id');
            $table->renameColumn('document_line_type', 'stock_item_type');
            $table->renameColumn('document_line_id', 'stock_item_id');
            $table->renameIndex('inventory_stock_movement_document', 'inventory_stock_movement_entry');
            $table->renameIndex('inventory_stock_movement_line', 'inventory_stock_movement_item');
        });
    }

    public function down(): void
    {
        $schema = Schema::connection('tenant');

        foreach ([
            'inventory_purchases', 'inventory_sales', 'inventory_purchase_returns', 'inventory_sale_returns',
            'inventory_adjustments', 'inventory_opening_stocks', 'inventory_wastages', 'inventory_transfers',
        ] as $tableName) {
            $schema->table($tableName, function (Blueprint $table): void {
                $table->renameColumn('entry_date', 'document_date');
            });
        }

        $schema->table('inventory_stock_movements', function (Blueprint $table): void {
            $table->renameColumn('stock_entry_type', 'document_type');
            $table->renameColumn('stock_entry_id', 'document_id');
            $table->renameColumn('stock_item_type', 'document_line_type');
            $table->renameColumn('stock_item_id', 'document_line_id');
            $table->renameIndex('inventory_stock_movement_entry', 'inventory_stock_movement_document');
            $table->renameIndex('inventory_stock_movement_item', 'inventory_stock_movement_line');
        });
        $schema->table('inventory_stock_allocations', function (Blueprint $table): void {
            $table->renameColumn('stock_item_type', 'document_line_type');
            $table->renameColumn('stock_item_id', 'document_line_id');
            $table->renameIndex('inventory_stock_allocation_item', 'inventory_stock_allocation_line');
        });
        $schema->table('inventory_stock_lots', function (Blueprint $table): void {
            $table->renameColumn('source_item_type', 'source_line_type');
            $table->renameColumn('source_item_id', 'source_line_id');
        });
        $schema->table('inventory_purchase_payment_allocations', function (Blueprint $table): void {
            $table->renameIndex('inventory_purchase_payment_purchase_unique', 'inventory_purchase_payment_document_unique');
        });
        $schema->table('inventory_sale_payment_allocations', function (Blueprint $table): void {
            $table->renameIndex('inventory_sale_payment_sale_unique', 'inventory_sale_payment_document_unique');
        });

        $this->renameForeignKey($schema, 'inventory_purchase_items', 'purchase_id', 'document_id', 'inventory_purchases');
        $this->renameForeignKey($schema, 'inventory_sale_items', 'sale_id', 'document_id', 'inventory_sales');
        $this->renameForeignKey($schema, 'inventory_purchase_return_items', 'purchase_return_id', 'document_id', 'inventory_purchase_returns');
        $this->renameForeignKey($schema, 'inventory_sale_return_items', 'sale_return_id', 'document_id', 'inventory_sale_returns');
        $this->renameForeignKey($schema, 'inventory_adjustment_items', 'adjustment_id', 'document_id', 'inventory_adjustments');
        $this->renameForeignKey($schema, 'inventory_opening_stock_items', 'opening_stock_id', 'document_id', 'inventory_opening_stocks');
        $this->renameForeignKey($schema, 'inventory_wastage_items', 'wastage_id', 'document_id', 'inventory_wastages');
        $this->renameForeignKey($schema, 'inventory_transfer_items', 'transfer_id', 'document_id', 'inventory_transfers');

        $this->renameForeignKey($schema, 'inventory_purchase_returns', 'purchase_id', 'source_document_id', 'inventory_purchases');
        $this->renameForeignKey($schema, 'inventory_sale_returns', 'sale_id', 'source_document_id', 'inventory_sales');
        $this->renameForeignKey($schema, 'inventory_purchase_return_items', 'purchase_item_id', 'source_line_id', 'inventory_purchase_items');
        $this->renameForeignKey($schema, 'inventory_sale_return_items', 'sale_item_id', 'source_line_id', 'inventory_sale_items');
        $this->renameForeignKey($schema, 'inventory_purchase_payment_allocations', 'purchase_id', 'document_id', 'inventory_purchases');
        $this->renameForeignKey($schema, 'inventory_sale_payment_allocations', 'sale_id', 'document_id', 'inventory_sales');
    }

    private function renameForeignKey(Builder $schema, string $tableName, string $from, string $to, string $ownerTable): void
    {
        $schema->table($tableName, function (Blueprint $table) use ($from, $to): void {
            $table->dropForeign([$from]);
            $table->renameColumn($from, $to);
        });

        $oldIndex = $tableName.'_'.$from.'_foreign';
        $newIndex = $tableName.'_'.$to.'_foreign';

        // An existing compound index can already cover the foreign key.
        if ($schema->hasIndex($tableName, $oldIndex)) {
            $schema->table($tableName, function (Blueprint $table) use ($oldIndex, $newIndex): void {
                $table->renameIndex($oldIndex, $newIndex);
            });
        }

        $schema->table($tableName, function (Blueprint $table) use ($to, $ownerTable): void {
            $table->foreign($to)->references('id')->on($ownerTable)->restrictOnDelete();
        });
    }
};
