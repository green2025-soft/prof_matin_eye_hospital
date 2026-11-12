<?php

namespace Modules\Core\Models;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use LogicException;
use Modules\Core\Models\Concerns\HasCrudContract;
use Modules\Core\Policies\AccountPolicy;
use Modules\Core\Services\ContextCache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
#[UsePolicy(AccountPolicy::class)]
abstract class Account extends Authenticatable
{
    use HasCrudContract;
    use HasRoles;
    use Notifiable;

    protected $table = 'users';

    protected string $guard_name = 'web';

    protected string $profileRoutePrefix = 'tenantcore.accounts';

    protected array $exposed = ['id', 'name', 'email', 'created_at'];

    protected array $searchable = ['name', 'email'];

    protected array $listWith = ['roles:id,name,guard_name', 'roles.permissions:id,name,guard_name', 'permissions:id,name,guard_name'];

    protected array $exposedRelations = ['roles' => ['id', 'name', 'guard_name']];

    protected static function booted(): void
    {
        $clearContext = static fn (self $account) => app(ContextCache::class)->flush($account->getConnection()->getName(), 'actors');
        static::saved($clearContext);
        static::deleted($clearContext);
    }

    public function authenticationRelations(): array
    {
        return ['roles.permissions', 'permissions'];
    }

    public function matchesApplication(): bool
    {
        $tenant = Tenant::current();

        if ($tenant === null) {
            return $this instanceof Admin
                && $this->getConnectionName() === 'central'
                && config('database.default') === 'central';
        }

        return ! $this instanceof Admin
            && $this->getConnectionName() === 'tenant'
            && config('database.default') === 'tenant'
            && $this->getConnection()->getDatabaseName() === $tenant->getDatabaseName();
    }

    public function assertApplicationContext(): void
    {
        if (! $this->matchesApplication()) {
            throw new AuthorizationException('This account does not belong to the current application.');
        }
    }

    public function isOwner(): bool
    {
        return $this->matchesApplication() && $this->hasOwnerRole();
    }

    public function permissionNames(): Collection
    {
        $this->assertApplicationContext();
        $connection = $this->getConnection()->getName();
        $guard = $this->permissionGuardName();

        return $this->getAllPermissions()
            ->filter(fn (Permission $permission): bool => $permission->guard_name === $guard
                && $permission->getConnection()->getName() === $connection)
            ->pluck('name');
    }

    // Grant boundaries use current database permissions, independently of page navigation.
    public function canGrantPermissions(Collection $permissions): bool
    {
        if (! $this->matchesApplication()) {
            return false;
        }

        $connection = $this->getConnection()->getName();
        $guard = $this->permissionGuardName();
        $allowed = array_fill_keys($this->permissionNames()->all(), true);

        return $permissions->every(fn (Permission $permission): bool => $permission->guard_name === $guard
            && $permission->getConnection()->getName() === $connection
            && isset($allowed[$permission->name]));
    }

    // Serialize access changes and reload the account before protected aggregate writes.
    public function withAccessLock(Closure $operation): mixed
    {
        $this->assertApplicationContext();
        $snapshot = request()->attributes->get('account_access_snapshot');

        if (($snapshot['actor'] ?? null)?->is($this) !== true) {
            $snapshot = [
                'owner' => $this->isOwner(),
                'permissions' => $this->permissionNames()->all(),
            ];
        }

        return $this->getConnection()->transaction(function () use ($operation, $snapshot): mixed {
            $ownerRole = Role::on($this->getConnectionName())
                ->where('guard_name', 'web')
                ->where('name', Role::OWNER_NAME)
                ->lockForUpdate()
                ->first(['id']);

            if ($ownerRole === null) {
                throw ValidationException::withMessages(['access' => 'Initialize the application owner before managing access.']);
            }

            $actor = $this->newQuery()
                ->with($this->authenticationRelations())
                ->whereKey($this->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $actor->assertApplicationContext();

            // A revocation while this write waited for its lock invalidates the request's access snapshot.
            $lostPermissions = ! $actor->isOwner() && ($snapshot['owner']
                || array_diff($snapshot['permissions'], $actor->permissionNames()->all()) !== []);

            if ($lostPermissions) {
                throw new AuthorizationException('Your access changed. Reload the page and try again.');
            }

            return $operation($actor);
        }, 3);
    }

    public static function modelForProvider(string $provider): string
    {
        $model = config('auth.providers.'.$provider.'.model');

        if (! in_array($provider, ['admins', 'users'], true) || ! is_string($model) || ! is_subclass_of($model, self::class)) {
            throw new LogicException('Resolve the account provider before using an account.');
        }

        return $model;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function createOwner(string $name, string $email, string $password): static
    {
        return (new static)->getConnection()->transaction(function () use ($name, $email, $password): static {
            $owner = static::firstOrCreate(
                ['email' => normalizeEmail($email)],
                ['name' => trim($name), 'password' => $password],
            );

            $owner->assignOwnerRole();

            return $owner;
        });
    }

    public function assignOwnerRole(): void
    {
        $this->assignRole(Role::findOrCreate(Role::OWNER_NAME, 'web'));
    }

    public function hasOwnerRole(): bool
    {
        $this->loadMissing('roles');

        return $this->roles->contains(fn (Role $role): bool => $role->guard_name === 'web' && $role->isReserved());
    }

    public function profileRoutePrefix(): string
    {
        return $this->profileRoutePrefix;
    }

    public function loginType(): string
    {
        return 'user';
    }

    public function permissionGuardName(): string
    {
        return $this->guard_name;
    }

    public function profileUpdatePermission(): string
    {
        return 'accounts.update';
    }
}
