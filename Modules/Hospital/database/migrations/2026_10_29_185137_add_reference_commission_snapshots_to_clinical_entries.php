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
                $table->decimal('reference_commission_percent', 5, 2)->nullable();
                $table->string('reference_discount_mode', 24)->nullable();
                $table->string('reference_due_mode', 24)->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['consultations', 'patient_investigations', 'patient_operations'] as $name) {
            Schema::connection('tenant')->table($name, function (Blueprint $table): void {
                $table->dropColumn(['reference_commission_percent', 'reference_discount_mode', 'reference_due_mode']);
            });
        }
    }
};
