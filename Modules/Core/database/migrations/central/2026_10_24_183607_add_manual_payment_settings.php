<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::connection('central')->table('settings')->insertOrIgnore([
            'group' => 'manual',
            'data' => json_encode([
                'enabled' => true,
                'instructions' => '',
                'extra_charge' => '0.00',
            ], JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Operator-managed settings are retained when this initialization is rolled back.
    }
};
