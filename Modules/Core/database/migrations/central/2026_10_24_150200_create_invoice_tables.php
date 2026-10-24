<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central')->create('invoice_sequences', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('year')->unique();
            $table->unsignedBigInteger('counter')->default(0);
            $table->timestamps();
        });
        Schema::connection('central')->create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->restrictOnDelete();
            $table->string('number', 64)->nullable()->unique();
            $table->char('currency', 3);
            $table->string('status', 24)->default('draft');
            $table->unsignedBigInteger('subtotal_minor')->default(0);
            $table->unsignedBigInteger('total_minor')->default(0);
            $table->unsignedBigInteger('paid_minor')->default(0);
            $table->date('due_at')->nullable();
            $table->dateTime('issued_at')->nullable();
            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();
            $table->json('merchant_snapshot')->nullable();
            $table->json('customer_snapshot')->nullable();
            $table->text('payment_instructions')->nullable();
            $table->text('notes')->nullable();
            $table->text('void_reason')->nullable();
            $table->dateTime('voided_at')->nullable();
            $table->timestamps();
            $table->unique(['subscription_id', 'period_start', 'period_end'], 'invoices_subscription_period_unique');
            $table->index(['tenant_id', 'status', 'id']);
            $table->index(['status', 'id']);
        });
        Schema::connection('central')->create('invoice_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->restrictOnDelete();
            $table->string('description', 1000);
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('unit_amount_minor');
            $table->unsignedBigInteger('line_total_minor');
            $table->unsignedSmallInteger('position');
            $table->timestamps();
            $table->unique(['invoice_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::connection('central')->dropIfExists('invoice_items');
        Schema::connection('central')->dropIfExists('invoices');
        Schema::connection('central')->dropIfExists('invoice_sequences');
    }
};
