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
        Schema::connection('tenant')->table('doctors', function (Blueprint $table): void {
            $table->unique(['id', 'branch_id'], 'doctors_id_branch_unique');
        });
        Schema::connection('tenant')->table('patients', function (Blueprint $table): void {
            $table->unique(['id', 'branch_id'], 'patients_id_branch_unique');
        });

        Schema::connection('tenant')->create('appointments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->date('appointment_date');
            $table->time('appointment_time')->nullable();
            $table->timestamps();
            $table->index(['branch_id', 'appointment_date', 'id']);
            $table->unique(['id', 'branch_id']);
            $table->unique(['id', 'doctor_id', 'patient_id', 'branch_id'], 'appointments_context_unique');
            $table->foreign(['doctor_id', 'branch_id'])->references(['id', 'branch_id'])->on('doctors')->restrictOnDelete();
            $table->foreign(['patient_id', 'branch_id'])->references(['id', 'branch_id'])->on('patients')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('appointments');
        Schema::connection('tenant')->table('patients', function (Blueprint $table): void {
            $table->dropUnique('patients_id_branch_unique');
        });
        Schema::connection('tenant')->table('doctors', function (Blueprint $table): void {
            $table->dropUnique('doctors_id_branch_unique');
        });
    }
};
