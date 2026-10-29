<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LogicException;
use Modules\Core\Models\Concerns\HasCrudContract;
use Modules\Core\Policies\AccountPolicy;
use Modules\Core\Services\ContextCache;
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
            $owner = static::query()->firstOrCreate(
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
        return $this instanceof Admin ? 'core.accounts' : 'tenantcore.accounts';
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
