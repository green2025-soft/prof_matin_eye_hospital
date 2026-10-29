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
        Schema::connection('tenant')->table('districts', function (Blueprint $table): void {
            $table->unique(['id', 'division_id'], 'districts_id_division_unique');
        });
        Schema::connection('tenant')->table('thanas', function (Blueprint $table): void {
            $table->unique(['id', 'district_id'], 'thanas_id_district_unique');
        });

        Schema::connection('tenant')->create('patient_sequences', function (Blueprint $table): void {
            $table->string('scope', 40)->primary();
            $table->unsignedBigInteger('last_number')->default(0);
        });

        Schema::connection('tenant')->create('patients', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->string('patient_id', 40)->unique();
            $table->string('name');
            $table->string('father_husband_name')->nullable();
            $table->unsignedTinyInteger('age');
            $table->string('gender', 10);
            $table->string('blood_group', 3)->nullable();
            $table->string('mobile_no', 30);
            $table->foreignId('division_id')->nullable()->constrained('divisions')->restrictOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->restrictOnDelete();
            $table->foreignId('thana_id')->nullable()->constrained('thanas')->restrictOnDelete();
            // Keep saved geography consistent when a parent catalog is edited later.
            $table->foreign(['district_id', 'division_id'])->references(['id', 'division_id'])->on('districts')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign(['thana_id', 'district_id'])->references(['id', 'district_id'])->on('thanas')->restrictOnUpdate()->restrictOnDelete();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->index(['branch_id', 'id']);
            $table->index(['branch_id', 'name', 'id']);
            $table->index(['branch_id', 'mobile_no', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('patients');
        Schema::connection('tenant')->dropIfExists('patient_sequences');
        Schema::connection('tenant')->table('thanas', function (Blueprint $table): void {
            $table->dropUnique('thanas_id_district_unique');
        });
        Schema::connection('tenant')->table('districts', function (Blueprint $table): void {
            $table->dropUnique('districts_id_division_unique');
        });
    }
};
