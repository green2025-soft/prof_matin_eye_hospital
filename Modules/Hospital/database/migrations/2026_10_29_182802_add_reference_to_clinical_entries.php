<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['consultations', 'patient_investigations', 'patient_operations'] as $name) {
            Schema::connection('tenant')->table($name, function (Blueprint $table): void {
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->foreign(['reference_id', 'branch_id'])->references(['id', 'branch_id'])
                    ->on('references')->restrictOnUpdate()->restrictOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach (['consultations', 'patient_investigations', 'patient_operations'] as $name) {
            Schema::connection('tenant')->table($name, function (Blueprint $table): void {
                $table->dropForeign(['reference_id', 'branch_id']);
                $table->dropColumn('reference_id');
            });
        }
    }
};
