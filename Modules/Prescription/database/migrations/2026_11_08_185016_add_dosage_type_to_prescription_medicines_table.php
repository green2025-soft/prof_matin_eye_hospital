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
        Schema::connection('tenant')->table('prescription_medicines', function (Blueprint $table): void {
            $table->string('dosage_type')->nullable()->after('medicine_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->table('prescription_medicines', function (Blueprint $table): void {
            $table->dropColumn('dosage_type');
        });
    }
};
