<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central')->create('checkout_attempts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->restrictOnDelete();
            $table->foreignId('active_invoice_id')->nullable()->unique()->constrained('invoices')->restrictOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->restrictOnDelete();
            $table->uuid('token')->unique();
            $table->string('reference', 20)->unique();
            $table->string('provider', 24);
            $table->string('provider_reference', 190)->nullable();
            $table->string('submitted_reference', 190)->nullable();
            $table->unsignedBigInteger('amount_minor');
            $table->char('currency', 3);
            $table->string('status', 24);
            $table->text('redirect_url')->nullable();
            $table->text('return_url');
            $table->longText('credential_snapshot')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('expires_at');
            $table->dateTime('checked_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'checked_at', 'id']);
            $table->unique(['provider', 'provider_reference']);
        });
    }

    public function down(): void
    {
        Schema::connection('central')->dropIfExists('checkout_attempts');
    }
};
