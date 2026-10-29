<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->create('operations', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('category_id')->constrained('operation_categories')->restrictOnDelete();
            $table->decimal('single_rate', 20, 2)->default(0);
            $table->decimal('both_rate', 20, 2)->default(0);
            $table->string('commission_type', 10);
            $table->decimal('commission_percent', 5, 2)->default(0);
            $table->decimal('commission_amount', 20, 2)->default(0);
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->timestamps();
            $table->index(['ordering', 'id']);
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('operations');
    }
};
