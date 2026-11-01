<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('inventory_documents', function (Blueprint $table): void {
            $table->softDeletes();
        });
        Schema::connection('tenant')->table('inventory_document_lines', function (Blueprint $table): void {
            $table->timestamp('superseded_at')->nullable();
            $table->index(['document_id', 'superseded_at', 'id'], 'inventory_document_active_lines');
        });
    }

    public function down(): void
    {
        $connection = DB::connection('tenant');
        if ($connection->table('inventory_document_lines')->whereNotNull('superseded_at')->exists()
            || $connection->table('inventory_documents')->whereNotNull('deleted_at')->exists()) {
            throw new RuntimeException('Purchase revisions or deleted entries exist. Keep their history before rolling back this migration.');
        }

        Schema::connection('tenant')->table('inventory_document_lines', function (Blueprint $table): void {
            $table->dropIndex('inventory_document_active_lines');
            $table->dropColumn('superseded_at');
        });
        Schema::connection('tenant')->table('inventory_documents', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });
    }
};
