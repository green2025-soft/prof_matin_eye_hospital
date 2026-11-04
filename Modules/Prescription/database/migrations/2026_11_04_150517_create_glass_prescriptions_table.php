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
        Schema::connection('tenant')->create('glass_prescriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->restrictOnDelete();
            $table->date('prescription_date');
            $table->string('pd', 30)->nullable();
            $table->text('disclaimer')->nullable();
            $table->json('lens_options')->nullable();
            $table->string('r_sphere_dv', 30)->nullable();
            $table->string('r_cylinder_dv', 30)->nullable();
            $table->string('r_axis_dv', 30)->nullable();
            $table->string('r_acuity_dv', 30)->nullable();
            $table->string('r_sphere_nv', 30)->nullable();
            $table->string('r_cylinder_nv', 30)->nullable();
            $table->string('r_axis_nv', 30)->nullable();
            $table->string('r_acuity_nv', 30)->nullable();
            $table->string('l_sphere_dv', 30)->nullable();
            $table->string('l_cylinder_dv', 30)->nullable();
            $table->string('l_axis_dv', 30)->nullable();
            $table->string('l_acuity_dv', 30)->nullable();
            $table->string('l_sphere_nv', 30)->nullable();
            $table->string('l_cylinder_nv', 30)->nullable();
            $table->string('l_axis_nv', 30)->nullable();
            $table->string('l_acuity_nv', 30)->nullable();
            $table->timestamps();
            $table->index(['branch_id', 'prescription_date', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('glass_prescriptions');
    }
};
