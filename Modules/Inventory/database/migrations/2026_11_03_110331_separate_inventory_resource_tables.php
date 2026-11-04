<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('tenant');

        // The operator approved resetting Inventory transactions; catalog and other modules stay intact.
        $schema->withoutForeignKeyConstraints(function () use ($schema): void {
            foreach ([
                'inventory_stock_shortage_settlements', 'inventory_stock_movements',
                'inventory_stock_allocations', 'inventory_purchase_payment_allocations',
                'inventory_sale_payment_allocations', 'inventory_payment_allocations', 'inventory_payments',
                'inventory_purchase_return_items', 'inventory_sale_return_items', 'inventory_purchase_items',
                'inventory_sale_items', 'inventory_adjustment_items', 'inventory_opening_stock_items',
                'inventory_wastage_items', 'inventory_transfer_items', 'inventory_document_lines',
                'inventory_stock_lots', 'inventory_stock_balances', 'inventory_purchase_returns',
                'inventory_sale_returns', 'inventory_purchases', 'inventory_sales', 'inventory_adjustments',
                'inventory_opening_stocks', 'inventory_wastages', 'inventory_transfers', 'inventory_documents',
            ] as $table) {
                $schema->dropIfExists($table);
            }
        });

        $schema->create('inventory_purchases', function (Blueprint $table): void {
            $this->headerColumns($table);
            $table->foreignId('supplier_id')->nullable()->constrained('inventory_suppliers')->restrictOnDelete();
            $table->string('supplier_invoice_no', 80)->nullable();
            $table->string('active_supplier_invoice_no', 80)->nullable();
            $table->unique(['branch_id', 'supplier_id', 'active_supplier_invoice_no'], 'inventory_supplier_invoice_unique');
        });
        $schema->create('inventory_sales', function (Blueprint $table): void {
            $this->headerColumns($table);
            $table->foreignId('customer_id')->nullable()->constrained('patients')->restrictOnDelete();
            $table->index(['customer_id', 'branch_id', 'voided_at'], 'inventory_sale_customer_due');
        });
        $schema->create('inventory_purchase_returns', function (Blueprint $table): void {
            $this->headerColumns($table);
            $table->foreignId('supplier_id')->nullable()->constrained('inventory_suppliers')->restrictOnDelete();
            $table->foreignId('source_document_id')->nullable()->constrained('inventory_purchases')->restrictOnDelete();
        });
        $schema->create('inventory_sale_returns', function (Blueprint $table): void {
            $this->headerColumns($table);
            $table->foreignId('customer_id')->nullable()->constrained('patients')->restrictOnDelete();
            $table->foreignId('source_document_id')->nullable()->constrained('inventory_sales')->restrictOnDelete();
        });
        $schema->create('inventory_adjustments', function (Blueprint $table): void {
            $this->headerColumns($table);
        });
        $schema->create('inventory_opening_stocks', function (Blueprint $table): void {
            $this->headerColumns($table);
        });
        $schema->create('inventory_wastages', function (Blueprint $table): void {
            $this->headerColumns($table);
        });
        $schema->create('inventory_transfers', function (Blueprint $table): void {
            $this->headerColumns($table);
            $table->foreignId('destination_branch_id')->nullable()->constrained('branches')->restrictOnDelete();
            $table->index(['destination_branch_id', 'document_date'], 'inventory_transfer_destination_date');
        });

        $schema->create('inventory_stock_balances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('variant_id')->constrained('inventory_product_variants')->restrictOnDelete();
            $table->bigInteger('quantity')->default(0);
            $table->bigInteger('cost_minor')->default(0);
            $table->timestamps();
            $table->unique(['branch_id', 'variant_id']);
        });
        $schema->create('inventory_stock_lots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('variant_id')->constrained('inventory_product_variants')->restrictOnDelete();
            $table->string('source_line_type', 40);
            $table->unsignedBigInteger('source_line_id');
            $table->foreignId('parent_lot_id')->nullable()->constrained('inventory_stock_lots')->restrictOnDelete();
            $table->date('received_at');
            $table->unsignedBigInteger('initial_quantity');
            $table->unsignedBigInteger('remaining_quantity');
            $table->unsignedBigInteger('initial_cost_minor');
            $table->unsignedBigInteger('remaining_cost_minor');
            $table->string('batch_no', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('reversed_at')->nullable();
            $table->timestamps();
            $table->index(['source_line_type', 'source_line_id', 'branch_id'], 'inventory_stock_lot_source');
            $table->index(['branch_id', 'variant_id', 'received_at', 'id'], 'inventory_stock_lot_consumption');
            $table->index(['branch_id', 'expiry_date'], 'inventory_stock_lot_expiry');
        });

        $schema->create('inventory_purchase_items', function (Blueprint $table): void {
            $this->itemColumns($table);
            $table->foreign('document_id')->references('id')->on('inventory_purchases')->restrictOnDelete();
        });
        $schema->create('inventory_sale_items', function (Blueprint $table): void {
            $this->itemColumns($table);
            $table->foreign('document_id')->references('id')->on('inventory_sales')->restrictOnDelete();
        });
        $schema->create('inventory_purchase_return_items', function (Blueprint $table): void {
            $this->itemColumns($table);
            $table->foreign('document_id')->references('id')->on('inventory_purchase_returns')->restrictOnDelete();
            $table->foreignId('source_line_id')->nullable()->constrained('inventory_purchase_items')->restrictOnDelete();
        });
        $schema->create('inventory_sale_return_items', function (Blueprint $table): void {
            $this->itemColumns($table);
            $table->foreign('document_id')->references('id')->on('inventory_sale_returns')->restrictOnDelete();
            $table->foreignId('source_line_id')->nullable()->constrained('inventory_sale_items')->restrictOnDelete();
        });
        $schema->create('inventory_adjustment_items', function (Blueprint $table): void {
            $this->itemColumns($table);
            $table->foreign('document_id')->references('id')->on('inventory_adjustments')->restrictOnDelete();
        });
        $schema->create('inventory_opening_stock_items', function (Blueprint $table): void {
            $this->itemColumns($table);
            $table->foreign('document_id')->references('id')->on('inventory_opening_stocks')->restrictOnDelete();
        });
        $schema->create('inventory_wastage_items', function (Blueprint $table): void {
            $this->itemColumns($table);
            $table->foreign('document_id')->references('id')->on('inventory_wastages')->restrictOnDelete();
        });
        $schema->create('inventory_transfer_items', function (Blueprint $table): void {
            $this->itemColumns($table);
            $table->foreign('document_id')->references('id')->on('inventory_transfers')->restrictOnDelete();
        });

        $schema->create('inventory_stock_allocations', function (Blueprint $table): void {
            $table->id();
            $table->string('document_line_type', 40);
            $table->unsignedBigInteger('document_line_id');
            $table->foreignId('lot_id')->nullable()->constrained('inventory_stock_lots')->restrictOnDelete();
            $table->foreignId('source_allocation_id')->nullable()->constrained('inventory_stock_allocations')->restrictOnDelete();
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('cost_minor');
            $table->timestamp('reversed_at')->nullable();
            $table->timestamps();
            $table->index(['document_line_type', 'document_line_id', 'id'], 'inventory_stock_allocation_line');
        });
        $schema->create('inventory_stock_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('variant_id')->constrained('inventory_product_variants')->restrictOnDelete();
            $table->string('document_type', 40);
            $table->unsignedBigInteger('document_id');
            $table->string('document_line_type', 40);
            $table->unsignedBigInteger('document_line_id');
            $table->foreignId('lot_id')->nullable()->constrained('inventory_stock_lots')->restrictOnDelete();
            $table->string('type', 30);
            $table->bigInteger('quantity');
            $table->bigInteger('cost_minor');
            $table->date('movement_date');
            $table->boolean('is_reversal')->default(false);
            $table->timestamps();
            $table->index(['branch_id', 'variant_id', 'movement_date', 'id'], 'inventory_stock_movement_history');
            $table->index(['document_type', 'document_id', 'is_reversal', 'id'], 'inventory_stock_movement_document');
            $table->index(['document_line_type', 'document_line_id', 'id'], 'inventory_stock_movement_line');
        });
        $schema->create('inventory_stock_shortage_settlements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('allocation_id')->constrained('inventory_stock_allocations')->restrictOnDelete();
            $table->foreignId('lot_id')->constrained('inventory_stock_lots')->restrictOnDelete();
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('snapshot_cost_minor');
            $table->unsignedBigInteger('receipt_cost_minor');
            $table->timestamp('reversed_at')->nullable();
            $table->timestamps();
            $table->index(['allocation_id', 'reversed_at'], 'inventory_shortage_allocation_active');
            $table->index(['lot_id', 'reversed_at'], 'inventory_shortage_receipt_active');
        });

        $schema->create('inventory_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->string('number', 40)->unique();
            $table->string('direction', 10);
            $table->string('product_type', 10);
            $table->date('payment_date');
            $table->foreignId('customer_id')->nullable()->constrained('patients')->restrictOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('inventory_suppliers')->restrictOnDelete();
            $table->foreignId('payment_type_id')->nullable()->constrained('payment_types')->restrictOnDelete();
            $table->unsignedBigInteger('amount_minor');
            $table->unsignedBigInteger('discount_minor')->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->timestamps();
            $table->index(['branch_id', 'payment_date', 'id']);
            $table->index(['branch_id', 'customer_id', 'id']);
            $table->index(['branch_id', 'supplier_id', 'id']);
        });
        $schema->create('inventory_purchase_payment_allocations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payment_id')->constrained('inventory_payments')->restrictOnDelete();
            $table->foreignId('document_id')->constrained('inventory_purchases')->restrictOnDelete();
            $table->unsignedBigInteger('amount_minor');
            $table->unsignedBigInteger('discount_minor')->default(0);
            $table->unique(['payment_id', 'document_id'], 'inventory_purchase_payment_document_unique');
        });
        $schema->create('inventory_sale_payment_allocations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payment_id')->constrained('inventory_payments')->restrictOnDelete();
            $table->foreignId('document_id')->constrained('inventory_sales')->restrictOnDelete();
            $table->unsignedBigInteger('amount_minor');
            $table->unsignedBigInteger('discount_minor')->default(0);
            $table->unique(['payment_id', 'document_id'], 'inventory_sale_payment_document_unique');
        });
    }

    public function down(): void
    {
        throw new RuntimeException('Inventory transaction tables were reset by operator request. Restore a backup or use a forward migration.');
    }

    private function headerColumns(Blueprint $table): void
    {
        $table->id();
        $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
        $table->string('product_type', 10);
        $table->string('number', 80)->unique();
        $table->date('document_date');
        $table->foreignId('payment_type_id')->nullable()->constrained('payment_types')->restrictOnDelete();
        $table->string('reason', 500)->nullable();
        $table->text('notes')->nullable();
        $table->string('stock_method', 4)->default('fifo');
        $table->string('vat_mode', 10)->default('none');
        $table->decimal('vat_percent', 5, 2)->default(0);
        $table->string('vat_calculation', 10)->default('percent');
        foreach (['subtotal_minor', 'discount_minor', 'vat_minor', 'total_minor', 'paid_minor', 'initial_paid_minor', 'settlement_discount_minor', 'returned_minor', 'refunded_minor', 'outstanding_minor', 'cost_minor'] as $column) {
            $table->unsignedBigInteger($column)->default(0);
        }
        $table->timestamp('posted_at')->nullable();
        $table->timestamp('stock_reversed_at')->nullable();
        $table->timestamp('voided_at')->nullable();
        $table->softDeletes();
        $table->timestamps();
        $table->index(['branch_id', 'product_type', 'document_date', 'id'], $table->getTable().'_branch_date');
    }

    private function itemColumns(Blueprint $table): void
    {
        $table->id();
        $table->unsignedBigInteger('document_id');
        $table->foreignId('variant_id')->constrained('inventory_product_variants')->restrictOnDelete();
        $table->unsignedBigInteger('product_unit_id');
        $table->foreignId('stock_lot_id')->nullable()->constrained('inventory_stock_lots')->restrictOnDelete();
        $table->unsignedInteger('quantity');
        $table->unsignedInteger('factor');
        $table->foreign(['product_unit_id', 'factor'])->references(['id', 'factor'])
            ->on('inventory_product_units')->restrictOnDelete()->restrictOnUpdate();
        $table->unsignedBigInteger('base_quantity');
        $table->unsignedBigInteger('returned_base_quantity')->default(0);
        foreach (['unit_price_minor', 'subtotal_minor', 'discount_minor', 'invoice_discount_minor', 'vat_minor', 'total_minor', 'cost_minor'] as $column) {
            $table->unsignedBigInteger($column)->default(0);
        }
        $table->unsignedBigInteger('sale_price_per_piece_minor')->nullable();
        $table->string('discount_calculation', 10)->nullable();
        $table->decimal('discount_percent', 5, 2)->nullable();
        $table->unsignedBigInteger('discount_unit_amount_minor')->nullable();
        $table->string('vat_mode', 10)->nullable();
        $table->decimal('vat_percent', 5, 2)->nullable();
        $table->string('vat_calculation', 10)->nullable();
        $table->unsignedBigInteger('vat_unit_amount_minor')->nullable();
        $table->string('batch_no', 100)->nullable();
        $table->date('expiry_date')->nullable();
        $table->string('direction', 8)->nullable();
        $table->timestamp('superseded_at')->nullable();
        $table->timestamps();
        $table->index(['document_id', 'superseded_at', 'id'], $table->getTable().'_active');
    }
};
