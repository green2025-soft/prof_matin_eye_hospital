<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('central');

        $schema->create('sms_accounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->unique()->constrained('tenants');
            $table->boolean('enabled')->default(false);
            $table->unsignedBigInteger('balance')->default(0);
            $table->unsignedBigInteger('rate')->default(0);
            $table->unsignedInteger('monthly_limit')->nullable();
            $table->json('allowed_features')->nullable();
            $table->text('gateway')->nullable();
            $table->timestamps();
        });

        $schema->create('sms_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->string('feature', 80);
            $table->char('event_key', 64);
            $table->string('recipient', 30);
            $table->longText('message');
            $table->unsignedSmallInteger('segments');
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->unsignedBigInteger('rate')->default(0);
            $table->unsignedBigInteger('charge')->default(0);
            $table->string('status', 24)->default('pending');
            $table->string('error', 500)->nullable();
            $table->timestamp('attempted_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'event_key']);
            $table->index(['tenant_id', 'created_at']);
            $table->index(['tenant_id', 'status', 'attempted_at']);
        });

        $schema->create('sms_recharges', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->unsignedBigInteger('amount');
            $table->string('payment_method', 100);
            $table->string('payment_reference', 150);
            $table->string('proof_path')->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('requested_by', 80);
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->string('review_note', 1000)->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        $schema = Schema::connection('central');
        $schema->dropIfExists('sms_recharges');
        $schema->dropIfExists('sms_messages');
        $schema->dropIfExists('sms_accounts');
    }
};
