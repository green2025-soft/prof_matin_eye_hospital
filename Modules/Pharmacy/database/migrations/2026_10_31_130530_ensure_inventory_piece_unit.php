<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $units = DB::connection('tenant')->table('inventory_units');
        $piece = ['name' => 'Piece', 'symbol' => 'Pcs', 'created_at' => now(), 'updated_at' => now()];
        $units->insertOrIgnore($piece + ['source_key' => 'base:pcs']);

        if (! (clone $units)->where('symbol', 'Pcs')->exists()) {
            // A renamed source unit may already own this provenance key.
            $units->insertOrIgnore($piece);
        }
    }

    public function down(): void
    {
        // Keep the tenant's Piece master and any product conversions that reference it.
    }
};
