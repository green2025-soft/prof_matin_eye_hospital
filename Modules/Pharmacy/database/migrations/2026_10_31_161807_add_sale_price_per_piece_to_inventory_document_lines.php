<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('inventory_document_lines', function (Blueprint $table): void {
            $table->unsignedBigInteger('sale_price_per_piece_minor')->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->table('inventory_document_lines', function (Blueprint $table): void {
            $table->dropColumn('sale_price_per_piece_minor');
        });
    }
};
