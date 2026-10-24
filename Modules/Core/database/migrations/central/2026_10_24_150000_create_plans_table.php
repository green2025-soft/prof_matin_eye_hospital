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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 80)->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price_minor')->default(0);
            $table->char('currency', 3);
            $table->string('billing_interval', 10)->default('monthly');
            $table->unsignedSmallInteger('trial_days')->default(0);
            $table->json('features')->nullable();
            $table->unsignedInteger('user_limit')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['name', 'id']);
            $table->index(['is_active', 'name', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
