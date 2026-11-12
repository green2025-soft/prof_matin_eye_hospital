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
        Schema::create('acc_account_heads', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('acc_account_heads')->restrictOnDelete();
            $table->string('type', 20);
            $table->unsignedTinyInteger('depth');
            $table->boolean('is_transaction')->default(false);
            $table->boolean('active')->default(true);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('acc_voucher_types', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('prefix', 8);
            $table->string('name', 100);
            $table->boolean('is_manual')->default(true);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('acc_account_modules', function (Blueprint $table): void {
            $table->id();
            $table->string('key', 100)->unique();
            $table->string('name', 150);
            $table->string('module', 30);
            $table->foreignId('voucher_type_id')->constrained('acc_voucher_types')->restrictOnDelete();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('acc_module_entry_accounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('account_module_id')->constrained('acc_account_modules')->cascadeOnDelete();
            $table->string('component', 50);
            $table->string('name', 100);
            $table->string('side', 6);
            $table->foreignId('account_head_id')->constrained('acc_account_heads')->restrictOnDelete();
            $table->boolean('use_payment_type')->default(false);
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->timestamps();
            $table->unique(['account_module_id', 'component']);
        });

        Schema::create('acc_payment_accounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payment_type_id')->unique()->constrained('payment_types')->restrictOnDelete();
            $table->foreignId('account_head_id')->constrained('acc_account_heads')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('acc_journal_entries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('voucher_type_id')->constrained('acc_voucher_types')->restrictOnDelete();
            $table->string('number', 80)->unique();
            $table->date('entry_date');
            $table->string('reference', 100)->nullable();
            $table->text('narration')->nullable();
            $table->string('source_type', 180)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('feature', 100)->nullable();
            $table->unsignedBigInteger('total')->default(0);
            $table->string('created_by', 100)->nullable();
            $table->string('created_by_name')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->timestamps();
            $table->unique(['source_type', 'source_id', 'feature'], 'acc_journal_source_unique');
            $table->index(['branch_id', 'entry_date']);
        });

        Schema::create('acc_journal_entry_details', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained('acc_journal_entries')->cascadeOnDelete();
            $table->foreignId('account_head_id')->constrained('acc_account_heads')->restrictOnDelete();
            $table->unsignedBigInteger('debit')->default(0);
            $table->unsignedBigInteger('credit')->default(0);
            $table->string('remarks')->nullable();
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->timestamps();
        });

        Schema::create('acc_accounting_sequences', function (Blueprint $table): void {
            $table->string('name', 80)->primary();
            $table->unsignedBigInteger('last_number')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acc_accounting_sequences');
        Schema::dropIfExists('acc_journal_entry_details');
        Schema::dropIfExists('acc_journal_entries');
        Schema::dropIfExists('acc_payment_accounts');
        Schema::dropIfExists('acc_module_entry_accounts');
        Schema::dropIfExists('acc_account_modules');
        Schema::dropIfExists('acc_voucher_types');
        Schema::dropIfExists('acc_account_heads');
    }
};
