<?php

namespace Modules\Core\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleService
{
    public function __construct(private readonly AccessManager $access) {}

    public function save(Account $actor, ?Model $record, array $values): Model
    {
        if ($record !== null && ! $record instanceof Role) {
            throw new AuthorizationException;
        }

        $role = $record?->exists ? $record : null;
        $this->access->assertActorContext($actor);

        if ($role !== null) {
            Gate::forUser($actor)->authorize('update', $role);
        } else {
            Gate::forUser($actor)->authorize('create', Role::class);
        }

        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($role, $values): Role {
                $connection = $freshActor->getConnection()->getName();
                $record = $role
                    ? Role::on($connection)->where('guard_name', 'web')->whereKey($role->getKey())->lockForUpdate()->firstOrFail()
                    : new Role(['guard_name' => 'web']);
                $record->setConnection($connection);

                if ($record->exists) {
                    Gate::forUser($freshActor)->authorize('update', $record);
                } else {
                    Gate::forUser($freshActor)->authorize('create', Role::class);
                }

                $name = trim($values['name'] ?? $record->name ?? '');

                if ($name === '' || mb_strlen($name) > 255 || (new Role(['name' => $name]))->isReserved()) {
                    throw ValidationException::withMessages(['name' => 'Use a role name other than the reserved '.Role::OWNER_NAME.' name.']);
                }

                $record->name = $name;
                $record->save();

                $record->getConnection()->afterCommit(fn () => app(PermissionRegistrar::class)->forgetCachedPermissions());

                return $record;
            });
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['name' => 'A role with this name already exists.']);
        }
    }

    public function delete(Account $actor, Model $record): void
    {
        if (! $record instanceof Role || ! $record->exists) {
            throw new AuthorizationException;
        }

        $role = $record;
        $this->access->assertActorContext($actor);
        Gate::forUser($actor)->authorize('delete', $role);

        $this->access->transaction($actor, function (Account $freshActor) use ($role): void {
            $record = Role::on($freshActor->getConnection()->getName())->where('guard_name', 'web')
                ->whereKey($role->getKey())->lockForUpdate()->firstOrFail();
            Gate::forUser($freshActor)->authorize('delete', $record);

            if ($record->users()->exists()) {
                throw ValidationException::withMessages(['role' => 'Remove this role from its accounts before deleting it.']);
            }

            $record->delete();
            $record->getConnection()->afterCommit(fn () => app(PermissionRegistrar::class)->forgetCachedPermissions());
        });
    }

    public function formData(Account $actor, Model $record): array
    {
        $this->access->assertActorContext($actor);

        if (! $record instanceof Role) {
            throw new AuthorizationException;
        }

        return [
            'role' => $record,
            'isReservedRole' => $record->isReserved(),
        ];
    }
}
