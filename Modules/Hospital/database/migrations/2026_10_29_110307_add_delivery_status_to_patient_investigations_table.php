<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('tenant')->table('patient_investigations', function (Blueprint $table): void {
            $table->string('delivery_status', 10)->default('pending')->after('delivery_date');
        });

        DB::connection('tenant')->statement("ALTER TABLE patient_investigations ADD CONSTRAINT patient_investigations_delivery_status_check CHECK (delivery_status IN ('pending', 'delivered'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection('tenant')->statement('ALTER TABLE patient_investigations DROP CHECK patient_investigations_delivery_status_check');

        Schema::connection('tenant')->table('patient_investigations', function (Blueprint $table): void {
            $table->dropColumn('delivery_status');
        });
    }
};
