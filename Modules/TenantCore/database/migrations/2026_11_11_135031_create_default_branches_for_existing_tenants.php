<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\ContextCache;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('tenant');

        if (! $schema->hasColumn('branches', 'is_default')) {
            $schema->table('branches', function (Blueprint $table): void {
                $table->boolean('is_default')->default(false);
            });
        }

        $tenant = Tenant::current();

        // Fresh setup creates its branch with the owner and seed data transaction.
        if ($tenant === null || ! $tenant->isReady()) {
            return;
        }

        $connection = DB::connection('tenant');

        if ($connection->table('branches')->where('is_default', true)->exists()) {
            return;
        }

        $now = now();
        $connection->table('branches')->insert([
            'name' => $tenant->name,
            'address' => $tenant->address ?? '',
            'is_default' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        app(ContextCache::class)->flush('tenant', 'branches');
    }

    public function down(): void
    {
        // Keep the hidden marker and branch that may now own patients or financial records.
    }
};
