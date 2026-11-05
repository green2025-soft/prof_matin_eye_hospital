<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Setting;
use Modules\Core\Services\ContextCache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $this->renameModule('inventory', 'pharmacy');
    }

    public function down(): void
    {
        $this->renameModule('pharmacy', 'inventory');
    }

    private function renameModule(string $from, string $to): void
    {
        DB::connection('tenant')->transaction(function () use ($from, $to): void {
            $permissions = Permission::on('tenant')
                ->where(function ($query) use ($from): void {
                    $query->where('name', 'like', $from.'.%')
                        ->orWhere('name', 'like', $from.'-%');
                })
                ->get(['id', 'name']);

            // Renaming in place retains role, Doctor and direct permission grants.
            foreach ($permissions as $permission) {
                $permission->name = $to.substr($permission->name, strlen($from));
                $permission->save();
            }

            Setting::on('tenant')
                ->withoutGlobalScopes()
                ->where('group', $from)
                ->update(['group' => $to]);
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        app(ContextCache::class)->flush('tenant', 'doctor-permissions');
    }
};
