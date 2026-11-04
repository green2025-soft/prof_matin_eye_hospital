<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->create('prescriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->restrictOnDelete();
            $table->date('prescription_date');
            $table->text('chief_complaints')->nullable();
            $table->text('history')->nullable();
            $table->text('examination')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('investigations')->nullable();
            $table->text('advice')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamps();
            $table->index(['branch_id', 'prescription_date', 'id']);
        });

        Schema::connection('tenant')->create('prescription_medicines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('prescription_id')->constrained('prescriptions')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('inv_products')->restrictOnDelete();
            $table->string('medicine_name');
            $table->string('dose', 100)->nullable();
            $table->string('frequency', 100)->nullable();
            $table->string('duration', 100)->nullable();
            $table->string('instructions')->nullable();
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->timestamps();
            $table->index(['prescription_id', 'ordering', 'id']);
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('prescription_medicines');
        Schema::connection('tenant')->dropIfExists('prescriptions');
    }
};
