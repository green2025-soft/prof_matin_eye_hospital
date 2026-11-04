<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->create('prescription_options', function (Blueprint $table): void {
            $table->id();
            $table->string('section', 30);
            $table->string('name');
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->timestamps();
            $table->unique(['section', 'name']);
            $table->index(['section', 'ordering', 'id']);
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('prescription_options');
    }
};
