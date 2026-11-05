<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('tenant');
        $schema->create('inventory_sequences', function (Blueprint $table) {
            $table->string('scope', 100)->primary();
            $table->unsignedBigInteger('last_number')->default(0);
        });

        $schema->create('inventory_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->string('type', 30);
            $table->string('product_type', 10);
            $table->string('number', 80)->unique();
            $table->date('document_date');
            $table->foreignId('customer_id')->nullable()->constrained('patients')->restrictOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('inventory_suppliers')->restrictOnDelete();
            $table->foreignId('source_document_id')->nullable()->constrained('inventory_documents')->restrictOnDelete();
            $table->foreignId('destination_branch_id')->nullable()->constrained('branches')->restrictOnDelete();
            $table->foreignId('payment_type_id')->nullable()->constrained('payment_types')->restrictOnDelete();
            $table->string('reason', 500)->nullable();
            $table->text('notes')->nullable();
            $table->string('stock_method', 4)->default('fifo');
            $table->string('vat_mode', 10)->default('exclusive');
            $table->decimal('vat_percent', 5, 2)->default(0);
            foreach (['subtotal_minor', 'discount_minor', 'vat_minor', 'total_minor', 'paid_minor', 'initial_paid_minor', 'settlement_discount_minor', 'returned_minor', 'refunded_minor', 'cost_minor'] as $column) {
                $table->unsignedBigInteger($column)->default(0);
            }
            $table->timestamp('posted_at')->nullable();
            $table->timestamp('stock_reversed_at')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->timestamps();
            $table->index(['branch_id', 'type', 'document_date', 'id'], 'inventory_document_branch_date');
            $table->index(['destination_branch_id', 'type', 'document_date'], 'inventory_document_destination_date');
            $table->index(['customer_id', 'branch_id', 'voided_at'], 'inventory_document_customer_due');
            $table->index(['supplier_id', 'branch_id', 'voided_at'], 'inventory_document_supplier_due');
        });

        $schema->create('inventory_document_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('inventory_documents')->restrictOnDelete();
            $table->foreignId('variant_id')->constrained('inventory_product_variants')->restrictOnDelete();
            $table->foreignId('product_unit_id');
            $table->unsignedBigInteger('stock_lot_id')->nullable();
            $table->foreignId('source_line_id')->nullable()->constrained('inventory_document_lines')->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('factor');
            $table->foreign(['product_unit_id', 'factor'], 'inventory_document_line_unit_factor_fk')
                ->references(['id', 'factor'])->on('inventory_product_units')->restrictOnDelete()->restrictOnUpdate();
            $table->unsignedBigInteger('base_quantity');
            $table->unsignedBigInteger('returned_base_quantity')->default(0);
            foreach (['unit_price_minor', 'subtotal_minor', 'discount_minor', 'invoice_discount_minor', 'vat_minor', 'total_minor', 'cost_minor'] as $column) {
                $table->unsignedBigInteger($column)->default(0);
            }
            $table->string('batch_no', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('direction', 8)->nullable();
            $table->timestamps();
            $table->index(['document_id', 'id']);
        });

        $schema->create('inventory_stock_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('variant_id')->constrained('inventory_product_variants')->restrictOnDelete();
            $table->unsignedBigInteger('quantity')->default(0);
            $table->unsignedBigInteger('cost_minor')->default(0);
            $table->timestamps();
            $table->unique(['branch_id', 'variant_id']);
        });

        $schema->create('inventory_stock_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('variant_id')->constrained('inventory_product_variants')->restrictOnDelete();
            $table->foreignId('source_line_id')->constrained('inventory_document_lines')->restrictOnDelete();
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
            $table->index(['branch_id', 'variant_id', 'received_at', 'id'], 'inventory_stock_lot_consumption');
            $table->index(['branch_id', 'expiry_date'], 'inventory_stock_lot_expiry');
        });

        $schema->create('inventory_stock_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_line_id')->constrained('inventory_document_lines')->restrictOnDelete();
            $table->foreignId('lot_id')->constrained('inventory_stock_lots')->restrictOnDelete();
            $table->foreignId('source_allocation_id')->nullable()->constrained('inventory_stock_allocations')->restrictOnDelete();
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('cost_minor');
            $table->timestamp('reversed_at')->nullable();
            $table->timestamps();
            $table->index(['document_line_id', 'id']);
        });

        $schema->table('inventory_document_lines', function (Blueprint $table) {
            $table->foreign('stock_lot_id', 'inventory_document_line_stock_lot_fk')
                ->references('id')->on('inventory_stock_lots')->restrictOnDelete();
        });

        $schema->create('inventory_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('variant_id')->constrained('inventory_product_variants')->restrictOnDelete();
            $table->foreignId('document_id')->constrained('inventory_documents')->restrictOnDelete();
            $table->foreignId('document_line_id')->constrained('inventory_document_lines')->restrictOnDelete();
            $table->foreignId('lot_id')->constrained('inventory_stock_lots')->restrictOnDelete();
            $table->string('type', 30);
            $table->bigInteger('quantity');
            $table->bigInteger('cost_minor');
            $table->date('movement_date');
            $table->boolean('is_reversal')->default(false);
            $table->timestamps();
            $table->index(['branch_id', 'variant_id', 'movement_date', 'id'], 'inventory_stock_movement_history');
            $table->index(['document_id', 'is_reversal', 'id'], 'inventory_stock_movement_document');
        });
    }

    public function down(): void
    {
        $schema = Schema::connection('tenant');
        $schema->table('inventory_document_lines', function (Blueprint $table) {
            $table->dropForeign('inventory_document_line_stock_lot_fk');
        });
        $schema->dropIfExists('inventory_stock_movements');
        $schema->dropIfExists('inventory_stock_allocations');
        $schema->dropIfExists('inventory_stock_lots');
        $schema->dropIfExists('inventory_stock_balances');
        $schema->dropIfExists('inventory_document_lines');
        $schema->dropIfExists('inventory_documents');
        $schema->dropIfExists('inventory_sequences');
    }
};
