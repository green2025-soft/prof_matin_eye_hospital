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
        Schema::table('due_collections', function (Blueprint $table) {
            //
        });
        Schema::connection('tenant')->table('due_collections', function (Blueprint $table): void {
            $table->decimal('discount', 10, 2)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->table('due_collections', function (Blueprint $table): void {
            $table->dropColumn('discount');
        });
    }

};
