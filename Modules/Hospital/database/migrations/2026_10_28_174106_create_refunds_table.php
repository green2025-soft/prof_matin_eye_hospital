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
        foreach (['consultations', 'patient_investigations', 'patient_operations'] as $sourceTable) {
            Schema::connection('tenant')->table($sourceTable, function (Blueprint $table) use ($sourceTable): void {
                $table->unique(['id', 'branch_id'], $sourceTable.'_id_branch_unique');
            });
        }

        Schema::connection('tenant')->create('refunds', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->unsignedBigInteger('consultation_id')->nullable()->unique();
            $table->unsignedBigInteger('patient_investigation_id')->nullable()->unique();
            $table->unsignedBigInteger('patient_operation_id')->nullable()->unique();
            $table->foreignId('refund_cause_id')->constrained('refund_causes')->restrictOnDelete();
            $table->decimal('amount', 20, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->foreign(['consultation_id', 'branch_id'])->references(['id', 'branch_id'])->on('consultations')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign(['patient_investigation_id', 'branch_id'])->references(['id', 'branch_id'])->on('patient_investigations')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign(['patient_operation_id', 'branch_id'])->references(['id', 'branch_id'])->on('patient_operations')->restrictOnUpdate()->restrictOnDelete();
        });

        DB::connection('tenant')->statement('ALTER TABLE refunds ADD CONSTRAINT refunds_one_source_check CHECK ((consultation_id IS NOT NULL) + (patient_investigation_id IS NOT NULL) + (patient_operation_id IS NOT NULL) = 1), ADD CONSTRAINT refunds_positive_amount_check CHECK (amount > 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('refunds');
        foreach (['consultations', 'patient_investigations', 'patient_operations'] as $sourceTable) {
            Schema::connection('tenant')->table($sourceTable, function (Blueprint $table) use ($sourceTable): void {
                $table->dropUnique($sourceTable.'_id_branch_unique');
            });
        }
    }
};
