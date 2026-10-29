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
        Schema::connection('tenant')->create('references', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->string('reference_id', 40)->unique();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('contact_no', 30);
            $table->text('address')->nullable();
            $table->decimal('consultation_percent', 5, 2)->default(0);
            $table->decimal('investigation_percent', 5, 2)->default(0);
            $table->decimal('operation_percent', 5, 2)->default(0);
            $table->timestamps();
            $table->unique(['id', 'branch_id']);
            $table->index(['branch_id', 'id']);
            $table->index(['branch_id', 'name', 'id']);
            $table->index(['branch_id', 'contact_no', 'id']);
        });
        Schema::connection('tenant')->create('reference_sequences', function (Blueprint $table): void {
            $table->string('scope', 40)->primary();
            $table->unsignedBigInteger('last_number')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('references');
        Schema::connection('tenant')->dropIfExists('reference_sequences');
    }
};
