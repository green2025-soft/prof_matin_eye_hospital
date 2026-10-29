<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->create('branches', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('mobile_no', 30)->nullable();
            $table->string('telephone_no', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
            $table->index(['name', 'id']);
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('branches');
    }
};
