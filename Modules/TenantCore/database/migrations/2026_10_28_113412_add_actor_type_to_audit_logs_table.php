<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('audit_logs', function (Blueprint $table): void {
            $table->string('actor_type', 100)->default('users')->after('actor_id');
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->table('audit_logs', function (Blueprint $table): void {
            $table->dropColumn('actor_type');
        });
    }
};
