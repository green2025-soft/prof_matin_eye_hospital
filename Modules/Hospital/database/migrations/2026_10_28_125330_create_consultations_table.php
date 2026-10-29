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
        Schema::connection('tenant')->create('consultations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('appointment_id')->nullable()->unique();
            $table->date('entry_date');
            $table->foreignId('payment_type_id')->constrained('payment_types')->restrictOnDelete();
            $table->string('patient_type', 10);
            $table->decimal('amount', 20, 2)->default(0);
            $table->string('discount_mode', 10);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 20, 2)->default(0);
            $table->decimal('paid_amount', 20, 2)->default(0);
            $table->decimal('payable_amount', 20, 2)->default(0);
            $table->decimal('due_amount', 20, 2)->default(0);
            $table->date('due_date');
            $table->timestamps();
            $table->index(['branch_id', 'entry_date', 'id']);
            $table->foreign(['doctor_id', 'branch_id'])->references(['id', 'branch_id'])->on('doctors')->restrictOnDelete();
            $table->foreign(['patient_id', 'branch_id'])->references(['id', 'branch_id'])->on('patients')->restrictOnDelete();
            $table->foreign(['appointment_id', 'doctor_id', 'patient_id', 'branch_id'], 'consultations_appointment_context_foreign')
                ->references(['id', 'doctor_id', 'patient_id', 'branch_id'])->on('appointments')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('consultations');
    }
};
