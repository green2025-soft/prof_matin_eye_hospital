<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('refunds', function (Blueprint $table): void {
            $table->string('invoice_no', 120)->nullable()->unique();
            $table->decimal('due_adjustment', 20, 2)->default(0);
            $table->unique(['id', 'investigation_id'], 'refunds_id_investigation_unique');
            $table->dropUnique('refunds_investigation_id_unique');
        });

        DB::connection('tenant')->statement('ALTER TABLE refunds DROP CHECK refunds_positive_amount_check, ADD CONSTRAINT refunds_credit_amount_check CHECK (amount >= 0 AND due_adjustment >= 0 AND amount + due_adjustment > 0)');

        Schema::connection('tenant')->create('refund_items', function (Blueprint $table): void {
            $table->unsignedBigInteger('refund_id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('rate', 20, 2);
            $table->decimal('amount', 20, 2);
            $table->string('eye_type', 10);
            $table->unsignedSmallInteger('position');
            $table->primary(['refund_id', 'item_id']);
            $table->unique(['invoice_id', 'item_id']);
            $table->foreign(['refund_id', 'invoice_id'])->references(['id', 'investigation_id'])->on('refunds')->restrictOnUpdate()->cascadeOnDelete();
            $table->foreign(['invoice_id', 'item_id'])->references(['patient_investigation_id', 'investigation_id'])->on('patient_investigation_items')->restrictOnUpdate()->restrictOnDelete();
        });

        DB::connection('tenant')->statement('ALTER TABLE refund_items ADD CONSTRAINT refund_items_amount_check CHECK (rate >= 0 AND amount >= 0 AND amount <= rate)');
    }

    public function down(): void
    {
        $connection = DB::connection('tenant');
        if ($connection->table('refund_items')->exists() || $connection->table('refunds')->where('due_adjustment', '>', 0)->exists()) {
            throw new RuntimeException('Item refunds or due adjustments exist. Preserve their accounting history and use a forward migration.');
        }

        Schema::connection('tenant')->dropIfExists('refund_items');
        $connection->statement('ALTER TABLE refunds DROP CHECK refunds_credit_amount_check, ADD CONSTRAINT refunds_positive_amount_check CHECK (amount > 0)');
        Schema::connection('tenant')->table('refunds', function (Blueprint $table): void {
            $table->dropUnique('refunds_invoice_no_unique');
            $table->dropUnique('refunds_id_investigation_unique');
            $table->unique('investigation_id');
            $table->dropColumn(['invoice_no', 'due_adjustment']);
        });
    }
};
