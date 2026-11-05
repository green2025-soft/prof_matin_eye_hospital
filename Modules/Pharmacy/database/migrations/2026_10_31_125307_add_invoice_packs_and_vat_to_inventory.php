<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('tenant');

        // Keep a product-leading index available for its foreign key throughout the change.
        $schema->table('inventory_product_units', function (Blueprint $table): void {
            $table->unique(['product_id', 'unit_id', 'factor'], 'inventory_product_unit_conversion_unique');
        });
        $schema->table('inventory_product_units', function (Blueprint $table): void {
            $table->dropUnique('inventory_product_units_product_id_unit_id_unique');
        });
        $schema->table('inventory_documents', function (Blueprint $table): void {
            $table->string('supplier_invoice_no', 80)->nullable();
            $table->string('active_supplier_invoice_no', 80)->nullable();
            $table->string('vat_calculation', 10)->default('percent');
            $table->unique(['branch_id', 'supplier_id', 'active_supplier_invoice_no'], 'inventory_supplier_invoice_unique');
        });
        $schema->table('inventory_document_lines', function (Blueprint $table): void {
            $table->string('vat_mode', 10)->nullable();
            $table->decimal('vat_percent', 5, 2)->nullable();
            $table->string('vat_calculation', 10)->nullable();
        });

        $now = now();
        $units = [];
        foreach (['Strip', 'Box', 'Pair', 'Bottle', 'Tube', 'Vial', 'Ampoule', 'Carton', 'Pack'] as $name) {
            $units[] = ['name' => $name, 'symbol' => $name, 'created_at' => $now, 'updated_at' => $now];
        }
        DB::connection('tenant')->table('inventory_units')->insertOrIgnore($units);
    }

    public function down(): void
    {
        $hasMultiplePacks = DB::connection('tenant')->table('inventory_product_units')->select(['product_id', 'unit_id'])
            ->groupBy('product_id', 'unit_id')->havingRaw('COUNT(*) > 1')->exists();
        if ($hasMultiplePacks) {
            throw new RuntimeException('Products have multiple pack sizes. Keep this migration or resolve those conversions before rolling back.');
        }

        $schema = Schema::connection('tenant');
        $schema->table('inventory_product_units', function (Blueprint $table): void {
            $table->unique(['product_id', 'unit_id'], 'inventory_product_units_product_id_unit_id_unique');
        });
        $schema->table('inventory_product_units', function (Blueprint $table): void {
            $table->dropUnique('inventory_product_unit_conversion_unique');
        });
        $schema->table('inventory_documents', function (Blueprint $table): void {
            $table->dropUnique('inventory_supplier_invoice_unique');
            $table->dropColumn(['supplier_invoice_no', 'active_supplier_invoice_no', 'vat_calculation']);
        });
        $schema->table('inventory_document_lines', function (Blueprint $table): void {
            $table->dropColumn(['vat_mode', 'vat_percent', 'vat_calculation']);
        });
        // Unit names are tenant-maintained master data and survive a rollback.
    }
};
