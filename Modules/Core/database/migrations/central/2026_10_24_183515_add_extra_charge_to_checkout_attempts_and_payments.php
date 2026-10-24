<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central')->table('checkout_attempts', function (Blueprint $table): void {
            $table->unsignedBigInteger('extra_charge_minor')->default(0)->after('amount_minor');
        });
        Schema::connection('central')->table('payments', function (Blueprint $table): void {
            $table->unsignedBigInteger('extra_charge_minor')->default(0)->after('amount_minor');
        });
    }

    public function down(): void
    {
        Schema::connection('central')->table('payments', function (Blueprint $table): void {
            $table->dropColumn('extra_charge_minor');
        });
        Schema::connection('central')->table('checkout_attempts', function (Blueprint $table): void {
            $table->dropColumn('extra_charge_minor');
        });
    }
};
