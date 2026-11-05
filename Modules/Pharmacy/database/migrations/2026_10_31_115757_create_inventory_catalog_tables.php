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
        Schema::connection('tenant')->create('inventory_product_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 16);
            $table->string('name');
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->string('source_key', 100)->nullable()->unique();
            $table->timestamps();
            $table->index(['type', 'ordering', 'id']);
        });

        Schema::connection('tenant')->create('inventory_dosages', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->string('source_key', 100)->nullable()->unique();
            $table->timestamps();

        });

        Schema::connection('tenant')->create('inventory_units', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('symbol', 30)->unique();
            $table->string('source_key', 100)->nullable()->unique();
            $table->timestamps();

        });

        Schema::connection('tenant')->create('inventory_shelves', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 16);
            $table->string('name');
            $table->string('number', 100)->nullable();
            $table->string('source_key', 100)->nullable()->unique();
            $table->timestamps();
            $table->index(['type', 'name']);
        });

        Schema::connection('tenant')->create('inventory_suppliers', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 16);
            $table->string('name');
            $table->string('contact_no', 100)->nullable();
            $table->text('address')->nullable();
            $table->boolean('active')->default(true);
            $table->string('source_key', 100)->nullable()->unique();
            $table->timestamps();
            $table->index(['type', 'name']);
        });

        Schema::connection('tenant')->create('inventory_products', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 16);
            $table->string('name');
            $table->string('code', 100)->nullable()->unique();
            $table->foreignId('category_id')->nullable()->constrained('inventory_product_categories')->restrictOnDelete();
            $table->foreignId('dosage_id')->nullable()->constrained('inventory_dosages')->restrictOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('inventory_suppliers')->restrictOnDelete();
            $table->foreignId('shelf_id')->nullable()->constrained('inventory_shelves')->restrictOnDelete();
            $table->string('strength')->nullable();
            $table->decimal('purchase_price', 20, 2)->default(0);
            $table->decimal('sale_price', 20, 2)->default(0);
            $table->unsignedBigInteger('reorder_level')->default(0);
            $table->boolean('needs_setup')->default(false);
            $table->boolean('active')->default(true);
            $table->string('source_key', 100)->nullable()->unique();
            $table->timestamps();
            $table->index(['type', 'name']);
            $table->index(['needs_setup', 'active']);
        });

        Schema::connection('tenant')->create('inventory_product_variants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('inventory_products')->restrictOnDelete();
            $table->string('sku', 100)->unique();
            $table->string('name')->nullable();
            $table->string('color', 100)->nullable();
            $table->string('size', 100)->nullable();
            $table->decimal('sph', 6, 2)->nullable();
            $table->decimal('cyl', 6, 2)->nullable();
            $table->boolean('active')->default(true);
            $table->string('source_key', 100)->nullable()->unique();
            $table->timestamps();

        });

        Schema::connection('tenant')->create('inventory_product_units', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('inventory_products')->restrictOnDelete();
            $table->foreignId('unit_id')->constrained('inventory_units')->restrictOnDelete();
            $table->unsignedInteger('factor');
            $table->timestamps();
            $table->unique(['product_id', 'unit_id']);
            $table->unique(['id', 'factor']);
        });

        Schema::connection('tenant')->create('inventory_seed_runs', function (Blueprint $table): void {
            $table->string('version', 60)->primary();
            $table->char('checksum', 64);
            $table->json('counts');
            $table->timestamp('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('inventory_seed_runs');
        Schema::connection('tenant')->dropIfExists('inventory_product_units');
        Schema::connection('tenant')->dropIfExists('inventory_product_variants');
        Schema::connection('tenant')->dropIfExists('inventory_products');
        Schema::connection('tenant')->dropIfExists('inventory_suppliers');
        Schema::connection('tenant')->dropIfExists('inventory_shelves');
        Schema::connection('tenant')->dropIfExists('inventory_units');
        Schema::connection('tenant')->dropIfExists('inventory_dosages');
        Schema::connection('tenant')->dropIfExists('inventory_product_categories');
    }
};
