<?php

namespace Modules\Core\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Role;
use Modules\Core\Policies\AccountPolicy;
use SensitiveParameter;

class AccountService
{
    public function __construct(private AccessManager $access, private AccountPolicy $policy) {}

    public function save(Account $actor, ?Model $record, #[SensitiveParameter] array $values): Model
    {
        $this->access->assertActorContext($actor);

        if ($record !== null && (! $record instanceof Account || get_class($record) !== get_class($actor))) {
            throw new AuthorizationException;
        }

        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($record, $values): Account {
                $target = $record?->exists
                    ? $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail()
                    : $freshActor->newInstance();

                $allowed = $target->exists
                    ? $this->policy->update($freshActor, $target)
                    : $this->policy->create($freshActor);

                if (! $allowed) {
                    throw new AuthorizationException;
                }

                $roles = null;

                if (array_key_exists('roles_present', $values)) {
                    if (! $this->policy->assignRoles($freshActor, $target->exists ? $target : null)) {
                        throw new AuthorizationException;
                    }

                    $roles = $this->assertRoleAssignments($freshActor, $values['roles'] ?? []);
                    $this->assertOwnerRemains($target, $roles);
                }

                if (array_key_exists('name', $values)) {
                    $target->name = trim($values['name']);
                }

                if (array_key_exists('email', $values)) {
                    $target->email = normalizeEmail($values['email']);
                }

                $password = $values['password'] ?? null;

                if ($password !== null && $password !== '') {
                    $target->password = $password;
                    $target->remember_token = Str::random(60);
                } elseif (! $target->exists) {
                    throw ValidationException::withMessages(['password' => 'A password is required for a new account.']);
                }

                $target->save();

                if ($roles !== null) {
                    $target->syncRoles($roles);
                }

                return $target->load(['roles.permissions', 'permissions']);
            });
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['email' => 'This email address is already in use.']);
        }
    }

    public function delete(Account $actor, Model $record): void
    {
        if (! $record instanceof Account || get_class($record) !== get_class($actor) || ! $record->exists) {
            throw new AuthorizationException;
        }

        $this->access->transaction($actor, function (Account $freshActor) use ($record): void {
            $target = $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail();

            if (! $this->policy->delete($freshActor, $target)) {
                throw new AuthorizationException;
            }

            $this->assertOwnerRemains($target, new Collection);
            $target->delete();
        });
    }

    public function formData(Account $actor, Model $record): array
    {
        $this->access->assertActorContext($actor);

        if (! $record instanceof Account || get_class($record) !== get_class($actor)) {
            throw new AuthorizationException;
        }

        $canAssignRoles = $this->policy->assignRoles($actor, $record->exists ? $record : null);

        return [
            'account' => $record,
            'roles' => $canAssignRoles ? $this->assignableRoles($actor) : new Collection,
            'selectedRoles' => $record->roles->modelKeys(),
            'canAssignRoles' => $canAssignRoles,
        ];
    }

    private function assignableRoles(Account $actor): Collection
    {
        return $this->access->rolesQuery($actor)->orderBy('name')->orderBy('id')->get(['id', 'name', 'guard_name']);
    }

    private function assertRoleAssignments(Account $actor, array $roleIds): Collection
    {
        $roles = Role::on($actor->getConnectionName())->where('guard_name', 'web')
            ->whereIn('id', $roleIds)->orderBy('id')->lockForUpdate()->with('permissions')->get();

        if ($roles->count() !== count($roleIds)) {
            throw ValidationException::withMessages(['roles' => 'Select valid roles from this application.']);
        }

        foreach ($roles as $role) {
            $allowed = $role->isReserved()
                ? $this->access->isOwner($actor)
                : $this->access->roleWithinActor($actor, $role);

            if (! $allowed) {
                throw ValidationException::withMessages(['roles' => 'You cannot assign a role with greater access than your own.']);
            }
        }

        return $roles;
    }

    private function assertOwnerRemains(Account $account, Collection $roles): void
    {
        if (! $account->exists || ! $this->access->isOwner($account)
            || $roles->contains(fn (Role $role): bool => $role->isReserved())) {
            return;
        }

        $anotherOwner = $account->newQuery()->whereKeyNot($account->getKey())
            ->whereHas('roles', fn ($query) => $query->where('guard_name', 'web')->where('name', Role::OWNER_NAME))->exists();

        if (! $anotherOwner) {
            throw ValidationException::withMessages(['roles' => 'Keep at least one super administrator.']);
        }
    }
}
