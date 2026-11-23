<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('prescriptions', function (Blueprint $table): void {
            $table->string('treatment_title')->default('Treatment Plan')->after('advice');
            $table->text('treatment_value')->nullable()->after('treatment_title');
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->table('prescriptions', function (Blueprint $table): void {
            $table->dropColumn(['treatment_title', 'treatment_value']);
        });
    }
};
