<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Services\ContextCache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->rename('phr_shelves', 'phr_shelf');

        $this->renamePermissions('pharmacy-shelves', 'pharmacy-shelf');
    }

    public function down(): void
    {
        Schema::connection('tenant')->rename('phr_shelf', 'phr_shelves');

        $this->renamePermissions('pharmacy-shelf', 'pharmacy-shelves');
    }

    private function renamePermissions(string $from, string $to): void
    {
        // Keep permission IDs so existing role, Doctor and direct grants remain valid.
        foreach (['view', 'create', 'update', 'delete'] as $action) {
            Permission::on('tenant')
                ->where('name', $from.'.'.$action)
                ->update(['name' => $to.'.'.$action]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        app(ContextCache::class)->flush('tenant', 'actors');
        app(ContextCache::class)->flush('tenant', 'doctor-permissions');
    }
};
