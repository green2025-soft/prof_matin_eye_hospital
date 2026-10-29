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
            $roleKey = config('permission.column_names.role_pivot_key') ?: 'role_id';
            $modelKey = config('permission.column_names.model_morph_key', 'model_id');
            $doctorType = 'Modules\Hospital\Models\Doctor';
            $names = [
                'hospital.view',
                'patients.view', 'patients.create', 'patients.update', 'patients.delete',
                'investigation-categories.view', 'investigation-categories.create', 'investigation-categories.update', 'investigation-categories.delete',
                'investigations.view', 'investigations.create', 'investigations.update', 'investigations.delete',
                'operation-categories.view', 'operation-categories.create', 'operation-categories.update', 'operation-categories.delete',
                'operations.view', 'operations.create', 'operations.update', 'operations.delete',
                'departments.view', 'departments.create', 'departments.update', 'departments.delete',
                'doctors.view', 'doctors.create', 'doctors.update', 'doctors.delete',
            ];

            // Use the same access lock as account and permission writes.
            $connection->table($tables['roles'])->where('guard_name', 'web')
                ->where('name', 'super-admin')->lockForUpdate()->first(['id']);

            $now = now();
            $permissions = [];
            foreach ($names as $name) {
                $permissions[] = ['name' => $name, 'guard_name' => 'doctor', 'created_at' => $now, 'updated_at' => $now];
            }
            $connection->table($tables['permissions'])->insertOrIgnore($permissions);

            $doctorPermissionIds = $connection->table($tables['permissions'])->where('guard_name', 'doctor')
                ->whereIn('name', $names)->pluck('id', 'name');
            $directGrants = $connection->table($tables['model_has_permissions'].' as assignment')
                ->join($tables['permissions'].' as permission', 'permission.id', '=', 'assignment.'.$permissionKey)
                ->where('assignment.model_type', $doctorType)->where('permission.guard_name', 'web')
                ->whereIn('permission.name', $names)
                ->select(['assignment.'.$modelKey.' as doctor_id', 'permission.name']);
            $roleGrants = $connection->table($tables['model_has_roles'].' as assignment')
                ->join($tables['role_has_permissions'].' as role_permission', 'role_permission.'.$roleKey, '=', 'assignment.'.$roleKey)
                ->join($tables['permissions'].' as permission', 'permission.id', '=', 'role_permission.'.$permissionKey)
                ->where('assignment.model_type', $doctorType)->where('permission.guard_name', 'web')
                ->whereIn('permission.name', $names)
                ->select(['assignment.'.$modelKey.' as doctor_id', 'permission.name']);

            foreach ([$directGrants, $roleGrants] as $grants) {
                $grants->orderBy('doctor_id')->orderBy('permission.name')->chunk(500, function (Collection $records) use ($connection, $tables, $permissionKey, $modelKey, $doctorType, $doctorPermissionIds): void {
                    $assignments = [];
                    foreach ($records as $record) {
                        $permissionId = $doctorPermissionIds->get($record->name);
                        if ($permissionId !== null) {
                            $assignments[] = [$permissionKey => $permissionId, 'model_type' => $doctorType, $modelKey => $record->doctor_id];
                        }
                    }
                    if ($assignments !== []) {
                        $connection->table($tables['model_has_permissions'])->insertOrIgnore($assignments);
                    }
                });
            }

            $connection->table($tables['model_has_roles'])->where('model_type', $doctorType)->delete();
            $webPermissions = $connection->table($tables['permissions'])->where('guard_name', 'web')->select('id');
            $connection->table($tables['model_has_permissions'])->where('model_type', $doctorType)
                ->whereIn($permissionKey, $webPermissions)->delete();

            $doctorRole = $connection->table($tables['roles'])->where('name', 'dr')->where('guard_name', 'web')
                ->lockForUpdate()->first(['id']);
            if ($doctorRole !== null
                && ! $connection->table($tables['model_has_roles'])->where($roleKey, $doctorRole->id)->exists()
                && ! $connection->table($tables['role_has_permissions'])->where($roleKey, $doctorRole->id)->exists()) {
                $connection->table($tables['roles'])->where('id', $doctorRole->id)->delete();
            }

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
        // Converted direct grants cannot reconstruct their former role assignments.
    }
};
