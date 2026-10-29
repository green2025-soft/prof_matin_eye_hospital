<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $connection = DB::connection('tenant');

        $connection->transaction(function () use ($connection): void {
            $tables = config('permission.table_names');
            $permissionKey = config('permission.column_names.permission_pivot_key') ?: 'permission_id';
            $modelKey = config('permission.column_names.model_morph_key', 'model_id');
            $doctorType = 'Modules\\Hospital\\Models\\Doctor';

            // Serialize with the existing account and permission writers.
            $connection->table($tables['roles'])->where('guard_name', 'web')
                ->where('name', 'super-admin')->lockForUpdate()->first(['id']);

            $connection->table($tables['model_has_permissions'].' as assignment')
                ->join($tables['permissions'].' as previous_permission', 'previous_permission.id', '=', 'assignment.'.$permissionKey)
                ->join($tables['permissions'].' as current_permission', 'current_permission.name', '=', 'previous_permission.name')
                ->where('assignment.model_type', $doctorType)
                ->where('previous_permission.guard_name', 'doctor')
                ->where('current_permission.guard_name', 'web')
                ->select(['assignment.'.$modelKey.' as doctor_id', 'current_permission.id as permission_id'])
                ->orderBy('assignment.'.$modelKey)->orderBy('current_permission.id')
                ->chunk(500, function (Collection $records) use ($connection, $tables, $permissionKey, $modelKey, $doctorType): void {
                    $assignments = [];
                    foreach ($records as $record) {
                        $assignments[] = [$permissionKey => $record->permission_id, 'model_type' => $doctorType, $modelKey => $record->doctor_id];
                    }
                    $connection->table($tables['model_has_permissions'])->insertOrIgnore($assignments);
                });

            $doctorPermissions = $connection->table($tables['permissions'])->where('guard_name', 'doctor')->select('id');
            $connection->table($tables['model_has_permissions'])->where('model_type', $doctorType)
                ->whereIn($permissionKey, $doctorPermissions)->delete();

            $connection->table($tables['permissions'])->where('guard_name', 'doctor')
                ->whereNotIn('id', $connection->table($tables['model_has_permissions'])->select($permissionKey))
                ->whereNotIn('id', $connection->table($tables['role_has_permissions'])->select($permissionKey))
                ->delete();

            if (! $connection->pretending()) {
                $connection->afterCommit(fn () => app(PermissionRegistrar::class)->forgetCachedPermissions());
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Merged direct grants cannot recover their previous guard without risking later edits.
    }
};
