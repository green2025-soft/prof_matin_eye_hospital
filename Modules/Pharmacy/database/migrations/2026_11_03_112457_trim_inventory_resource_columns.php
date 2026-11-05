<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('tenant');

        $schema->table('inv_purchases', function (Blueprint $table): void {
            $table->dropColumn(['reason', 'stock_method', 'vat_mode', 'vat_percent', 'vat_calculation']);
        });
        $schema->table('inv_sales', function (Blueprint $table): void {
            $table->dropColumn(['reason', 'vat_calculation']);
        });
        $schema->table('inv_purchase_returns', function (Blueprint $table): void {
            $table->dropColumn([
                'reason', 'stock_method', 'vat_mode', 'vat_percent', 'vat_calculation',
                'subtotal_minor', 'discount_minor', 'vat_minor', 'paid_minor', 'settlement_discount_minor',
                'returned_minor', 'refunded_minor', 'outstanding_minor',
            ]);
        });
        $schema->table('inv_sale_returns', function (Blueprint $table): void {
            $table->dropColumn([
                'reason', 'stock_method', 'vat_mode', 'vat_percent', 'vat_calculation',
                'subtotal_minor', 'discount_minor', 'vat_minor', 'paid_minor', 'settlement_discount_minor',
                'returned_minor', 'refunded_minor', 'outstanding_minor',
            ]);
        });
        $schema->table('inv_adjustments', function (Blueprint $table): void {
            $table->dropForeign('inventory_adjustments_payment_type_id_foreign');
            $table->dropColumn([
                'payment_type_id', 'vat_mode', 'vat_percent', 'vat_calculation', 'subtotal_minor',
                'discount_minor', 'vat_minor', 'total_minor', 'paid_minor', 'initial_paid_minor',
                'settlement_discount_minor', 'returned_minor', 'refunded_minor', 'outstanding_minor', 'deleted_at',
            ]);
        });
        $schema->table('inv_opening_stocks', function (Blueprint $table): void {
            $table->dropForeign('inventory_opening_stocks_payment_type_id_foreign');
            $table->dropColumn([
                'payment_type_id', 'reason', 'stock_method', 'vat_mode', 'vat_percent', 'vat_calculation',
                'subtotal_minor', 'discount_minor', 'vat_minor', 'total_minor', 'paid_minor', 'initial_paid_minor',
                'settlement_discount_minor', 'returned_minor', 'refunded_minor', 'outstanding_minor', 'deleted_at',
            ]);
        });
        $schema->table('inv_wastages', function (Blueprint $table): void {
            $table->dropForeign('inventory_wastages_payment_type_id_foreign');
            $table->dropColumn([
                'payment_type_id', 'vat_mode', 'vat_percent', 'vat_calculation', 'subtotal_minor',
                'discount_minor', 'vat_minor', 'total_minor', 'paid_minor', 'initial_paid_minor',
                'settlement_discount_minor', 'returned_minor', 'refunded_minor', 'outstanding_minor', 'deleted_at',
            ]);
        });
        $schema->table('inv_transfers', function (Blueprint $table): void {
            $table->dropForeign('inventory_transfers_payment_type_id_foreign');
            $table->dropColumn([
                'payment_type_id', 'vat_mode', 'vat_percent', 'vat_calculation', 'subtotal_minor',
                'discount_minor', 'vat_minor', 'total_minor', 'paid_minor', 'initial_paid_minor',
                'settlement_discount_minor', 'returned_minor', 'refunded_minor', 'outstanding_minor', 'deleted_at',
            ]);
        });

        $schema->table('inv_purchase_items', function (Blueprint $table): void {
            $table->dropForeign('inventory_purchase_items_stock_lot_id_foreign');
            $table->dropColumn(['stock_lot_id', 'direction']);
        });
        $schema->table('inv_sale_items', function (Blueprint $table): void {
            $table->dropForeign('inventory_sale_items_stock_lot_id_foreign');
            $table->dropColumn([
                'stock_lot_id', 'sale_price_per_piece_minor', 'discount_calculation', 'discount_percent',
                'discount_unit_amount_minor', 'vat_percent', 'vat_calculation', 'vat_unit_amount_minor',
                'batch_no', 'expiry_date', 'direction',
            ]);
        });
        $schema->table('inv_purchase_return_items', function (Blueprint $table): void {
            $table->dropForeign('inventory_purchase_return_items_stock_lot_id_foreign');
            $table->dropColumn([
                'stock_lot_id', 'returned_base_quantity', 'sale_price_per_piece_minor', 'discount_calculation',
                'discount_percent', 'discount_unit_amount_minor', 'vat_percent', 'vat_calculation',
                'vat_unit_amount_minor', 'batch_no', 'expiry_date', 'direction',
            ]);
        });
        $schema->table('inv_sale_return_items', function (Blueprint $table): void {
            $table->dropForeign('inventory_sale_return_items_stock_lot_id_foreign');
            $table->dropColumn([
                'stock_lot_id', 'returned_base_quantity', 'sale_price_per_piece_minor', 'discount_calculation',
                'discount_percent', 'discount_unit_amount_minor', 'vat_percent', 'vat_calculation',
                'vat_unit_amount_minor', 'batch_no', 'expiry_date', 'direction',
            ]);
        });
        $schema->table('inv_adjustment_items', function (Blueprint $table): void {
            $table->index(['adjustment_id', 'id']);
            $table->dropIndex('inventory_adjustment_items_active');
            $table->dropColumn([
                'returned_base_quantity', 'subtotal_minor', 'discount_minor', 'invoice_discount_minor',
                'vat_minor', 'total_minor', 'sale_price_per_piece_minor', 'discount_calculation',
                'discount_percent', 'discount_unit_amount_minor', 'vat_mode', 'vat_percent',
                'vat_calculation', 'vat_unit_amount_minor', 'superseded_at',
            ]);
        });
        $schema->table('inv_opening_stock_items', function (Blueprint $table): void {
            $table->index(['opening_stock_id', 'id']);
            $table->dropIndex('inventory_opening_stock_items_active');
            $table->dropForeign('inventory_opening_stock_items_stock_lot_id_foreign');
            $table->dropColumn([
                'stock_lot_id', 'returned_base_quantity', 'subtotal_minor', 'discount_minor', 'invoice_discount_minor',
                'vat_minor', 'total_minor', 'sale_price_per_piece_minor', 'discount_calculation', 'discount_percent',
                'discount_unit_amount_minor', 'vat_mode', 'vat_percent', 'vat_calculation', 'vat_unit_amount_minor',
                'direction', 'superseded_at',
            ]);
        });
        $schema->table('inv_wastage_items', function (Blueprint $table): void {
            $table->index(['wastage_id', 'id']);
            $table->dropIndex('inventory_wastage_items_active');
            $table->dropColumn([
                'returned_base_quantity', 'unit_price_minor', 'subtotal_minor', 'discount_minor', 'invoice_discount_minor',
                'vat_minor', 'total_minor', 'sale_price_per_piece_minor', 'discount_calculation', 'discount_percent',
                'discount_unit_amount_minor', 'vat_mode', 'vat_percent', 'vat_calculation', 'vat_unit_amount_minor',
                'batch_no', 'expiry_date', 'direction', 'superseded_at',
            ]);
        });
        $schema->table('inv_transfer_items', function (Blueprint $table): void {
            $table->index(['transfer_id', 'id']);
            $table->dropIndex('inventory_transfer_items_active');
            $table->dropForeign('inventory_transfer_items_stock_lot_id_foreign');
            $table->dropColumn([
                'stock_lot_id', 'returned_base_quantity', 'unit_price_minor', 'subtotal_minor', 'discount_minor',
                'invoice_discount_minor', 'vat_minor', 'total_minor', 'sale_price_per_piece_minor', 'discount_calculation',
                'discount_percent', 'discount_unit_amount_minor', 'vat_mode', 'vat_percent', 'vat_calculation',
                'vat_unit_amount_minor', 'batch_no', 'expiry_date', 'direction', 'superseded_at',
            ]);
        });
    }

    public function down(): void
    {
        throw new RuntimeException('Unused Inventory columns were removed. Use a forward migration to restore schema when required.');
    }
};
