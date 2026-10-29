<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('tenant')->create('doctor_permissions', function (Blueprint $table): void {
            $table->unsignedBigInteger('permission_id')->primary();
            $table->foreign('permission_id')->references('id')->on(config('permission.table_names.permissions'))->cascadeOnDelete();
        });

        $connection = DB::connection('tenant');
        $connection->transaction(function () use ($connection): void {
            $tables = config('permission.table_names');
            $permissionKey = config('permission.column_names.permission_pivot_key') ?: 'permission_id';
            $modelKey = config('permission.column_names.model_morph_key', 'model_id');
            $doctorType = 'Modules\\Hospital\\Models\\Doctor';

            $connection->table($tables['roles'])->where('guard_name', 'web')
                ->where('name', 'super-admin')->lockForUpdate()->first(['id']);

            $doctorCount = $connection->table('doctors')->count();
            if ($doctorCount > 0) {
                // Preserve only grants already held by every current Doctor.
                $sharedPermissions = $connection->table($tables['model_has_permissions'].' as assignment')
                    ->join('doctors', 'doctors.id', '=', 'assignment.'.$modelKey)
                    ->join($tables['permissions'].' as permission', 'permission.id', '=', 'assignment.'.$permissionKey)
                    ->where('assignment.model_type', $doctorType)->where('permission.guard_name', 'web')
                    ->select('assignment.'.$permissionKey)->groupBy('assignment.'.$permissionKey)
                    ->havingRaw('COUNT(DISTINCT doctors.id) = ?', [$doctorCount]);
                $connection->table('doctor_permissions')->insertUsing(['permission_id'], $sharedPermissions);
            }

            $connection->table($tables['model_has_permissions'])->where('model_type', $doctorType)->delete();

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
        Schema::connection('tenant')->dropIfExists('doctor_permissions');
        // A shared set cannot reconstruct the former individual Doctor grants.
    }
};
