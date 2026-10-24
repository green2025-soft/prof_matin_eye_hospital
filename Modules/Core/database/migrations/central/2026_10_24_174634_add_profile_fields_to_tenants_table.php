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
        Schema::connection('central')->table('tenants', function (Blueprint $table): void {
            $table->text('address')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('has_branches')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->table('tenants', function (Blueprint $table): void {
            $table->dropColumn(['address', 'phone', 'logo_path', 'has_branches']);
        });
    }
};
