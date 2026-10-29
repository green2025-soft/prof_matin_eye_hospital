<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('departments', function (Blueprint $table): void {
            $table->unsignedSmallInteger('ordering')->default(0)->after('description');
            $table->index(['ordering', 'id']);
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->table('departments', function (Blueprint $table): void {
            $table->dropIndex(['ordering', 'id']);
            $table->dropColumn('ordering');
        });
    }
};
