<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Core\Models\Plan;

return new class extends Migration
{
    public function up(): void
    {
        $this->renamePermissions('inventory', 'pharmacy');
    }

    public function down(): void
    {
        $this->renamePermissions('pharmacy', 'inventory');
    }

    private function renamePermissions(string $from, string $to): void
    {
        DB::connection('central')->transaction(function () use ($from, $to): void {
            $plans = Plan::query()
                ->whereNotNull('route_permissions')
                ->get(['id', 'route_permissions']);

            foreach ($plans as $plan) {
                $permissions = array_map(static function (string $permission) use ($from, $to): string {
                    $permission = Str::replaceStart($from.'.', $to.'.', $permission);

                    return Str::replaceStart($from.'-', $to.'-', $permission);
                }, $plan->route_permissions);

                if ($permissions !== $plan->route_permissions) {
                    $plan->route_permissions = $permissions;
                    $plan->save();
                }
            }
        });
    }
};
