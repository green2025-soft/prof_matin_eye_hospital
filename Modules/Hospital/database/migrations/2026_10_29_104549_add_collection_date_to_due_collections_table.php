<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('tenant')->table('due_collections', function (Blueprint $table): void {
            $table->date('collection_date')->nullable()->after('invoice_no');
        });

        DB::connection('tenant')->table('due_collections')->update([
            'collection_date' => DB::raw('DATE(created_at)'),
        ]);

        Schema::connection('tenant')->table('due_collections', function (Blueprint $table): void {
            $table->date('collection_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->table('due_collections', function (Blueprint $table): void {
            $table->dropColumn('collection_date');
        });
    }
};
