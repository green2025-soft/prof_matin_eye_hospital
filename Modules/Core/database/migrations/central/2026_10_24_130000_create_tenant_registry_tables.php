<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug', 80)->unique();
            $table->string('database', 64)->unique();
            $table->uuid('database_token')->unique();
            $table->string('owner_email');
            $table->string('status', 16)->default('pending')->index();
            $table->string('provisioning_error')->nullable();
            $table->timestamps();
        });

        Schema::create('domains', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->restrictOnDelete();
            $table->string('domain')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
        Schema::dropIfExists('tenants');
    }
};
