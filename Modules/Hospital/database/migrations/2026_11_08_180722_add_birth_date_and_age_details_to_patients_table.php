<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('patients', function (Blueprint $table): void {
            $table->unsignedTinyInteger('age_months')->default(0)->after('age');
            $table->unsignedTinyInteger('age_days')->default(0)->after('age_months');
            $table->date('date_of_birth')->nullable()->after('age_days');
            $table->string('mobile_no', 30)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Keep nullable phone numbers so rollback does not discard patients created without one.
        Schema::connection('tenant')->table('patients', function (Blueprint $table): void {
            $table->dropColumn(['age_months', 'age_days', 'date_of_birth']);
        });
    }
};
