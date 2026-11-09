<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('prescriptions', function (Blueprint $table): void {
            $table->string('rx_number', 80)->nullable()->unique();
        });

        Schema::connection('tenant')->table('glass_prescriptions', function (Blueprint $table): void {
            $table->string('rx_number', 80)->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->table('glass_prescriptions', function (Blueprint $table): void {
            $table->dropUnique(['rx_number']);
            $table->dropColumn('rx_number');
        });

        Schema::connection('tenant')->table('prescriptions', function (Blueprint $table): void {
            $table->dropUnique(['rx_number']);
            $table->dropColumn('rx_number');
        });
    }
};
