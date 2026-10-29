<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('tenant')->create('due_collections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->unsignedBigInteger('consultation_id')->nullable();
            $table->unsignedBigInteger('investigation_id')->nullable();
            $table->unsignedBigInteger('operation_id')->nullable();
            $table->string('invoice_no', 120)->unique();
            $table->decimal('amount', 20, 2);
            $table->foreignId('payment_type_id')->constrained('payment_types')->restrictOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->foreign(['consultation_id', 'branch_id'])->references(['id', 'branch_id'])->on('consultations')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign(['investigation_id', 'branch_id'])->references(['id', 'branch_id'])->on('patient_investigations')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign(['operation_id', 'branch_id'])->references(['id', 'branch_id'])->on('patient_operations')->restrictOnUpdate()->restrictOnDelete();
        });

        DB::connection('tenant')->statement('ALTER TABLE due_collections ADD CONSTRAINT due_collections_one_source_check CHECK ((consultation_id IS NOT NULL) + (investigation_id IS NOT NULL) + (operation_id IS NOT NULL) = 1), ADD CONSTRAINT due_collections_positive_amount_check CHECK (amount > 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('due_collections');
    }
};
