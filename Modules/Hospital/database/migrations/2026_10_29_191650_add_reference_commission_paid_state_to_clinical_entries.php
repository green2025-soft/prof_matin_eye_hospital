<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['consultations', 'patient_investigations', 'patient_operations'] as $name) {
            Schema::connection('tenant')->table($name, function (Blueprint $table): void {
                $table->decimal('reference_commission_paid_amount', 20, 2)->nullable();
                $table->dateTime('reference_commission_paid_at')->nullable();
            });
            DB::connection('tenant')->statement("ALTER TABLE `{$name}` ADD CONSTRAINT `{$name}_reference_commission_paid_check` CHECK ((reference_commission_paid_amount IS NULL AND reference_commission_paid_at IS NULL) OR (reference_commission_paid_amount IS NOT NULL AND reference_commission_paid_amount > 0 AND reference_commission_paid_at IS NOT NULL))");
        }
    }

    public function down(): void
    {
        foreach (['consultations', 'patient_investigations', 'patient_operations'] as $name) {
            DB::connection('tenant')->statement("ALTER TABLE `{$name}` DROP CHECK `{$name}_reference_commission_paid_check`");
            Schema::connection('tenant')->table($name, function (Blueprint $table): void {
                $table->dropColumn(['reference_commission_paid_amount', 'reference_commission_paid_at']);
            });
        }
    }
};
