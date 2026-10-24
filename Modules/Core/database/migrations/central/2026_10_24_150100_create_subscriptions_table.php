<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->unique()->constrained('tenants')->restrictOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->restrictOnDelete();
            $table->string('status', 16)->default('pending');
            $table->unsignedBigInteger('price_minor');
            $table->char('currency', 3);
            $table->string('billing_interval', 10);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('trial_ends_at')->nullable();
            $table->dateTime('current_period_starts_at')->nullable();
            $table->dateTime('current_period_ends_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('cancel_at_period_end')->default(false);
            $table->timestamps();
            $table->index(['status', 'current_period_ends_at', 'id'], 'subscriptions_due_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
