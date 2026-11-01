<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('patients', function (Blueprint $table): void {
            $table->string('type', 10)->default('patient');
            $table->unsignedTinyInteger('age')->nullable()->change();
            $table->string('gender', 10)->nullable()->change();
            $table->index(['type', 'name', 'id']);
        });
    }

    public function down(): void
    {
        if (DB::connection('tenant')->table('patients')->whereNull('age')->orWhereNull('gender')->exists()) {
            throw new RuntimeException('Complete customer ages and genders before rolling back this migration.');
        }

        Schema::connection('tenant')->table('patients', function (Blueprint $table): void {
            $table->dropIndex(['type', 'name', 'id']);
            $table->dropColumn('type');
            $table->unsignedTinyInteger('age')->nullable(false)->change();
            $table->string('gender', 10)->nullable(false)->change();
        });
    }
};
