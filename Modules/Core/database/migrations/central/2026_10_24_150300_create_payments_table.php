<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central')->create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->restrictOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->string('provider', 32);
            $table->string('idempotency_key', 80);
            $table->string('provider_reference', 190)->nullable();
            $table->unsignedBigInteger('amount_minor');
            $table->char('currency', 3);
            $table->string('method', 32);
            $table->string('status', 24)->default('completed');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->dateTime('paid_at');
            $table->timestamps();
            $table->unique(['provider', 'idempotency_key']);
            $table->unique(['provider', 'provider_reference']);
            $table->index(['tenant_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::connection('central')->dropIfExists('payments');
    }
};
