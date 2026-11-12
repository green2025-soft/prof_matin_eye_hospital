<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->create('sms_outbox', function (Blueprint $table): void {
            $table->id();
            $table->char('event_key', 64)->unique();
            $table->string('feature', 80);
            $table->string('recipient', 30);
            $table->longText('message');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->index(['processed_at', 'updated_at', 'id']);
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('sms_outbox');
    }
};
