<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->create('operation_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->timestamps();
            $table->index(['ordering', 'id']);
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('operation_categories');
    }
};
