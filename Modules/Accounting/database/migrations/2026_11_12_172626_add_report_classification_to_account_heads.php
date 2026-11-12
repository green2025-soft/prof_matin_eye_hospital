<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Accounting\Models\AccountHead;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('tenant')->table('acc_account_heads', function (Blueprint $table): void {
            $table->string('system_key', 30)->nullable()->index();
            $table->string('cash_flow_activity', 20)->nullable();
        });

        // Existing tenants receive these initial tags once; later seeds preserve user choices.
        foreach (config('accounting.report_roles', []) as $code => $role) {
            AccountHead::on('tenant')->where('code', $code)->update(['system_key' => $role]);
        }
        foreach (config('accounting.cash_flow_activities', []) as $code => $activity) {
            AccountHead::on('tenant')->where('code', $code)->update(['cash_flow_activity' => $activity]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->table('acc_account_heads', function (Blueprint $table): void {
            $table->dropIndex(['system_key']);
            $table->dropColumn(['system_key', 'cash_flow_activity']);
        });
    }
};
