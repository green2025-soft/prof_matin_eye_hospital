<?php

namespace Modules\Hospital\Models;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Models\Account;
use Modules\Core\Services\ContextCache;
use Modules\Hospital\Policies\DoctorPolicy;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Permission\Models\Permission;

#[Fillable([
    'name', 'bn_name', 'mobile_no', 'designation', 'department_id',
    'specialist', 'bn_specialist', 'bmdc_reg', 'bn_bmdc_reg',
    'gender', 'blood_group', 'address',
    'education_degree', 'bn_education_degree', 'current_employment', 'bn_current_employment',
    'consultation_fee', 'follow_up_fee', 'pad_footer_text',
])]
#[UsePolicy(DoctorPolicy::class)]
class Doctor extends Account
{
    use UsesTenantConnection;

    protected $table = 'doctors';

    protected array $exposed = [
        'id', 'branch_id', 'doctor_id', 'name', 'bn_name', 'mobile_no', 'designation', 'department_id',
        'specialist', 'bn_specialist', 'bmdc_reg', 'bn_bmdc_reg',
        'gender', 'blood_group', 'address',
        'education_degree', 'bn_education_degree', 'current_employment', 'bn_current_employment',
        'consultation_fee', 'follow_up_fee', 'pad_footer_text', 'email', 'picture_path', 'created_at',
    ];

    protected array $listColumns = [
        'id', 'branch_id', 'doctor_id', 'name', 'bn_name', 'mobile_no', 'designation', 'department_id',
        'specialist', 'bn_specialist', 'bmdc_reg', 'bn_bmdc_reg', 'gender', 'blood_group',
        'picture_path', 'consultation_fee', 'follow_up_fee', 'email', 'created_at',
    ];

    protected array $searchable = ['id', 'doctor_id', 'name', 'bn_name', 'mobile_no', 'email', 'bmdc_reg'];

    protected array $containsSearch = ['doctor_id'];

    protected array $listWith = ['department:id,name'];

    protected array $formWith = [];

    protected array $exposedRelations = ['department' => ['id', 'name']];

    protected function casts(): array
    {
        return [
            ...parent::casts(),
            'branch_id' => 'integer',
            'department_id' => 'integer',
            'consultation_fee' => 'decimal:2',
            'follow_up_fee' => 'decimal:2',
        ];
    }

    public function getAuthIdentifier(): string
    {
        return 'doctor:'.$this->getKey();
    }

    public function loginType(): string
    {
        return 'doctor';
    }

    public function authenticationRelations(): array
    {
        return [];
    }

    public function hasPermissionTo($permission, ?string $guardName = null): bool
    {
        if ($guardName !== null && $guardName !== $this->permissionGuardName()) {
            return false;
        }

        return $this->hasDirectPermission($permission);
    }

    public function getAllPermissions(): Collection
    {
        $cache = app(ContextCache::class);
        $cacheable = $cache->isCacheable();
        $cacheKey = $this->sharedPermissionsCacheKey();
        $cachedPermissions = request()->attributes->get($cacheKey);
        if ($cacheable && $cachedPermissions instanceof Collection) {
            return $cachedPermissions;
        }

        $connection = $this->getConnection()->getName();
        $snapshot = $cache->remember($connection, 'doctor-permissions', 'shared', function (): array {
            $table = config('permission.table_names.permissions');

            return Permission::on($this->getConnectionName())
                ->join('doctor_permissions', 'doctor_permissions.permission_id', '=', $table.'.id')
                ->where($table.'.guard_name', $this->permissionGuardName())
                ->orderBy($table.'.id')->get([$table.'.id', $table.'.name', $table.'.guard_name'])
                ->map(fn (Permission $permission): array => $permission->getAttributes())->all();
        });
        $permissions = (new Permission)->newCollection();
        foreach ($snapshot as $attributes) {
            $permissions->push((new Permission)->newFromBuilder($attributes, $connection));
        }

        if ($cacheable) {
            request()->attributes->set($cacheKey, $permissions);
        }

        return $permissions;
    }

    public static function forgetSharedPermissions(): void
    {
        $doctor = new static;
        request()->attributes->remove($doctor->sharedPermissionsCacheKey());
        app(ContextCache::class)->flush($doctor->getConnection()->getName(), 'doctor-permissions');
    }

    private function sharedPermissionsCacheKey(): string
    {
        $connection = $this->getConnection();

        return 'hospital.doctor_permissions:'.$connection->getName().':'.$connection->getDatabaseName();
    }

    public function hasDirectPermission($permission): bool
    {
        $permission = $this->filterPermission($permission, $this->permissionGuardName());

        return $permission->guard_name === $this->permissionGuardName()
            && $this->getAllPermissions()->contains($permission->getKeyName(), $permission->getKey());
    }

    public function getDirectPermissions(): Collection
    {
        return $this->getAllPermissions();
    }

    public function getPermissionNames(): Collection
    {
        return $this->getAllPermissions()->pluck('name');
    }

    public function givePermissionTo(...$permissions): static
    {
        throw new AuthorizationException('Manage the shared Doctor permissions instead of individual grants.');
    }

    public function syncPermissions(...$permissions): static
    {
        throw new AuthorizationException('Manage the shared Doctor permissions instead of individual grants.');
    }

    public function revokePermissionTo($permission): static
    {
        throw new AuthorizationException('Manage the shared Doctor permissions instead of individual grants.');
    }

    public function getPermissionsViaRoles(): Collection
    {
        return collect();
    }

    public function hasOwnerRole(): bool
    {
        return false;
    }

    public function hasRole($roles, ?string $guard = null): bool
    {
        return false;
    }

    public function hasAllRoles($roles, ?string $guard = null): bool
    {
        return false;
    }

    public function hasExactRoles($roles, ?string $guard = null): bool
    {
        return false;
    }

    public function getRoleNames(): Collection
    {
        return collect();
    }

    public function assignRole(...$roles): static
    {
        throw new AuthorizationException('Doctors use shared permissions instead of roles.');
    }

    public function syncRoles(...$roles): static
    {
        throw new AuthorizationException('Doctors use shared permissions instead of roles.');
    }

    public function profileRoutePrefix(): string
    {
        return 'hospital.doctors';
    }

    public function profileUpdatePermission(): string
    {
        return 'doctors.update';
    }

    public function queryContract(): array
    {
        return [...parent::queryContract(), 'constraints' => ['branch_id' => max(0, (int) request()->attributes->get('branch_id', 0))]];
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)
            ->where($this->qualifyColumn('branch_id'), max(0, (int) request()->attributes->get('branch_id', 0)));
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function getPictureUrlAttribute(): ?string
    {
        return $this->picture_path === null ? null : Storage::disk('public')->url($this->picture_path);
    }
}
