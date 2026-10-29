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
        Schema::connection('tenant')->create('patient_investigations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->date('entry_date');
            $table->foreignId('payment_type_id')->constrained('payment_types')->restrictOnDelete();
            $table->string('eye_type', 10);
            $table->decimal('amount', 20, 2)->default(0);
            $table->string('discount_mode', 10);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 20, 2)->default(0);
            $table->decimal('extra_discount_percent', 5, 2)->default(0);
            $table->decimal('extra_discount_amount', 20, 2)->default(0);
            $table->decimal('paid_amount', 20, 2)->default(0);
            $table->decimal('payable_amount', 20, 2)->default(0);
            $table->decimal('due_amount', 20, 2)->default(0);
            $table->date('due_date');
            $table->date('delivery_date');
            $table->timestamps();
            $table->index(['branch_id', 'entry_date', 'id']);
            $table->foreign(['doctor_id', 'branch_id'])->references(['id', 'branch_id'])->on('doctors')->restrictOnDelete();
            $table->foreign(['patient_id', 'branch_id'])->references(['id', 'branch_id'])->on('patients')->restrictOnDelete();
        });

        Schema::connection('tenant')->create('patient_investigation_items', function (Blueprint $table): void {
            $table->foreignId('patient_investigation_id')->constrained('patient_investigations')->cascadeOnDelete();
            $table->foreignId('investigation_id')->constrained('investigations')->restrictOnDelete();
            $table->decimal('rate', 20, 2)->default(0);
            $table->string('eye_type', 10);
            $table->unsignedSmallInteger('position')->default(0);
            $table->primary(['patient_investigation_id', 'investigation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('patient_investigation_items');
        Schema::connection('tenant')->dropIfExists('patient_investigations');
    }
};
