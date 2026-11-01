<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->create('inventory_payments', function (Blueprint $table) {
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

        Schema::connection('tenant')->create('inventory_payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('inventory_payments')->restrictOnDelete();
            $table->foreignId('document_id')->constrained('inventory_documents')->restrictOnDelete();
            $table->unsignedBigInteger('amount_minor');
            $table->unsignedBigInteger('discount_minor')->default(0);
            $table->unique(['payment_id', 'document_id']);
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('inventory_payment_allocations');
        Schema::connection('tenant')->dropIfExists('inventory_payments');
    }
};
