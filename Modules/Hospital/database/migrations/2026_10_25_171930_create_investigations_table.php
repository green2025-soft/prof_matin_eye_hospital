<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->create('investigations', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('category_id')->constrained('investigation_categories')->restrictOnDelete();
            $table->decimal('single_rate', 20, 2)->default(0);
            $table->decimal('both_rate', 20, 2)->default(0);
            $table->string('room_no', 50)->nullable();
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->timestamps();
            $table->index(['ordering', 'id']);
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('investigations');
    }
};
