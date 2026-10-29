<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('users', function (Blueprint $table): void {
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('redirect_url', 2048)->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->table('users', function (Blueprint $table): void {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['branch_id', 'redirect_url']);
        });
    }
};
