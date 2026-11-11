<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use Modules\Core\Models\Plan;

return new class extends Migration
{
    public function up(): void
    {
        $this->renamePermissions('pharmacy-shelves', 'pharmacy-shelf');
    }

    public function down(): void
    {
        $this->renamePermissions('pharmacy-shelf', 'pharmacy-shelves');
    }

    private function renamePermissions(string $from, string $to): void
    {
        $plans = Plan::whereNotNull('route_permissions')
            ->select(['id', 'route_permissions'])
            ->lazyById(100);

        foreach ($plans as $plan) {
            $plan->route_permissions = array_map(
                static fn (string $permission): string => Str::replaceStart($from.'.', $to.'.', $permission),
                $plan->route_permissions,
            );

            if ($plan->isDirty('route_permissions')) {
                $plan->save();
            }
        }
    }
};
