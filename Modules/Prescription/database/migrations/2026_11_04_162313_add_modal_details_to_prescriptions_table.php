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
            $table->json('history_details')->nullable();
            $table->json('examination_details')->nullable();
            $table->text('follow_up_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->table('prescriptions', function (Blueprint $table): void {
            $table->dropColumn(['history_details', 'examination_details', 'follow_up_notes']);
        });
    }
};
