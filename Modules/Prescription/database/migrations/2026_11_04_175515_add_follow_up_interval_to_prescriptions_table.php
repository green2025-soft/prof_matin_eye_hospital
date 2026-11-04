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
        Schema::connection('tenant')->table('prescriptions', function (Blueprint $table): void {
            $table->unsignedSmallInteger('follow_up_interval')->nullable();
            $table->string('follow_up_interval_unit', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->table('prescriptions', function (Blueprint $table): void {
            $table->dropColumn(['follow_up_interval', 'follow_up_interval_unit']);
        });
    }
};
