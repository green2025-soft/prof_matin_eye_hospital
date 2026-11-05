<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('inventory_document_lines', function (Blueprint $table): void {
            $table->string('discount_calculation', 10)->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->unsignedBigInteger('discount_unit_amount_minor')->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->table('inventory_document_lines', function (Blueprint $table): void {
            $table->dropColumn(['discount_calculation', 'discount_percent', 'discount_unit_amount_minor']);
        });
    }
};
