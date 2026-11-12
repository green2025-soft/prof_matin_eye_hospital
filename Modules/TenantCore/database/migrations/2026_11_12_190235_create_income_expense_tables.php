<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('income_heads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->timestamps();
        });

        Schema::create('expense_heads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->timestamps();
        });

        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->foreignId('head_id')->constrained('income_heads')->restrictOnDelete();
            $table->foreignId('payment_type_id')->constrained()->restrictOnDelete();
            $table->date('entry_date');
            $table->decimal('amount', 20, 2);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['branch_id', 'entry_date']);
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->foreignId('head_id')->constrained('expense_heads')->restrictOnDelete();
            $table->foreignId('payment_type_id')->constrained()->restrictOnDelete();
            $table->date('entry_date');
            $table->decimal('amount', 20, 2);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['branch_id', 'entry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('incomes');
        Schema::dropIfExists('expense_heads');
        Schema::dropIfExists('income_heads');
    }
};
