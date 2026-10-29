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
            $table->string('doctor_id', 40)->nullable()->unique();
        });
        Schema::connection('tenant')->create('doctor_sequences', function (Blueprint $table): void {
            $table->string('scope', 40)->primary();
            $table->unsignedBigInteger('last_number')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback removes registration codes and their sequence history.
        Schema::connection('tenant')->table('doctors', function (Blueprint $table): void {
            $table->dropUnique(['doctor_id']);
            $table->dropColumn('doctor_id');
        });
        Schema::connection('tenant')->dropIfExists('doctor_sequences');
    }
};
