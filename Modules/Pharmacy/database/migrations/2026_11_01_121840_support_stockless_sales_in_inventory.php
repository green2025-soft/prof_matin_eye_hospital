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
        $schema->table('inventory_stock_balances', function (Blueprint $table) {
            $table->bigInteger('quantity')->default(0)->change();
            $table->bigInteger('cost_minor')->default(0)->change();
        });
        $schema->table('inventory_stock_allocations', function (Blueprint $table) {
            $table->unsignedBigInteger('lot_id')->nullable()->change();
        });
        $schema->table('inventory_stock_movements', function (Blueprint $table) {
            $table->unsignedBigInteger('lot_id')->nullable()->change();
        });
        $schema->create('inventory_stock_shortage_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allocation_id')->constrained('inventory_stock_allocations')->restrictOnDelete();
            $table->foreignId('lot_id')->constrained('inventory_stock_lots')->restrictOnDelete();
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('snapshot_cost_minor');
            $table->unsignedBigInteger('receipt_cost_minor');
            $table->timestamp('reversed_at')->nullable();
            $table->timestamps();
            $table->index(['allocation_id', 'reversed_at'], 'inventory_shortage_allocation_active');
            $table->index(['lot_id', 'reversed_at'], 'inventory_shortage_receipt_active');
        });
    }

    public function down(): void
    {
        $connection = DB::connection('tenant');
        if ($connection->table('inventory_stock_shortage_settlements')->exists()
            || $connection->table('inventory_stock_allocations')->whereNull('lot_id')->exists()
            || $connection->table('inventory_stock_movements')->whereNull('lot_id')->exists()
            || $connection->table('inventory_stock_balances')->where('quantity', '<', 0)->orWhere('cost_minor', '<', 0)->exists()) {
            throw new RuntimeException('Stockless sale history must be preserved. Use a forward migration instead of rolling this change back.');
        }

        $schema = Schema::connection('tenant');
        $schema->dropIfExists('inventory_stock_shortage_settlements');
        $schema->table('inventory_stock_movements', function (Blueprint $table) {
            $table->unsignedBigInteger('lot_id')->nullable(false)->change();
        });
        $schema->table('inventory_stock_allocations', function (Blueprint $table) {
            $table->unsignedBigInteger('lot_id')->nullable(false)->change();
        });
        $schema->table('inventory_stock_balances', function (Blueprint $table) {
            $table->unsignedBigInteger('quantity')->default(0)->change();
            $table->unsignedBigInteger('cost_minor')->default(0)->change();
        });
    }
};
