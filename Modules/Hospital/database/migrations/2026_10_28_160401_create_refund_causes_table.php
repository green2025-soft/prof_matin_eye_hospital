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
        Schema::connection('tenant')->create('refund_causes', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedSmallInteger('ordering')->default(0);
            $table->timestamps();
            $table->index(['ordering', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('refund_causes');
    }
};
