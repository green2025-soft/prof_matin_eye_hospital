<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Core\Models\Concerns\HasCrudContract;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
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
}
