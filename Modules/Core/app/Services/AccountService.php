<?php

namespace Modules\Core\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\Role;
use Modules\Core\Models\Tenant;
use Modules\Core\Policies\AccountPolicy;
use SensitiveParameter;

class AccountService
{
    public function __construct(private AccessManager $access, private AccountPolicy $policy, private SubscriptionService $subscriptions) {}

    public function save(Account $actor, ?Model $record, #[SensitiveParameter] array $values): Model
    {
        $this->access->assertActorContext($actor);
        $model = Account::modelForProvider($actor instanceof Admin ? 'admins' : 'users');

        if ($record !== null && (! $record instanceof Account || get_class($record) !== $model)) {
            throw new AuthorizationException;
        }

        $save = fn (): Model => $this->persist($actor, $record, $values);
        $tenant = Tenant::current();

        return ! $record?->exists && $tenant !== null ? $this->subscriptions->createUser($tenant, $actor, $save) : $save();
    }

    private function persist(Account $actor, ?Model $record, #[SensitiveParameter] array $values): Model
    {
        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($record, $values): Account {
                $model = Account::modelForProvider($freshActor instanceof Admin ? 'admins' : 'users');
                $target = $record?->exists
                    ? $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail()
                    : new $model;

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

                    $roles = $this->access->rolesForAssignment($freshActor, $values['roles'] ?? []);
                    $this->assertOwnerRemains($target, $roles);
                }

                $target->fill(Arr::only($values, ['name', 'email', 'branch_id', 'redirect_url']));

                $password = $values['password'] ?? null;

                if ($password !== null && $password !== '') {
                    $target->password = $password;
                    $target->remember_token = Str::random(60);
                }

                $target->save();

                if ($roles !== null) {
                    $currentRoles = $target->roles()->get(['id', 'name', 'guard_name']);
                    $removedRoles = $currentRoles->diff($roles);
                    $addedRoles = $roles->diff($currentRoles);

                    if ($removedRoles->isNotEmpty()) {
                        $target->removeRole($removedRoles);
                    }
                    if ($addedRoles->isNotEmpty()) {
                        $target->assignRole($addedRoles);
                    }
                }

                return $target->load(['roles.permissions', 'permissions']);
            });
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['email' => 'This email address is already in use.']);
        }
    }

    public function delete(Account $actor, Model $record): void
    {
        $model = Account::modelForProvider($actor instanceof Admin ? 'admins' : 'users');
        if (! $record instanceof Account || get_class($record) !== $model || ! $record->exists) {
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
        $model = Account::modelForProvider($actor instanceof Admin ? 'admins' : 'users');

        if (! $record instanceof Account || get_class($record) !== $model) {
            throw new AuthorizationException;
        }

        $canAssignRoles = $this->policy->assignRoles($actor, $record->exists ? $record : null);
        $isTenantUser = ! $actor instanceof Admin;
        $branches = new Collection;

        if ($isTenantUser) {
            $branches = $record->branch()->getRelated()->newQuery()->orderBy('name')->orderBy('id')->limit(100)->get(['id', 'name']);

            if ($record->branch_id !== null && ! $branches->contains('id', $record->branch_id) && $record->branch !== null) {
                $branches->push($record->branch);
            }
        }

        return [
            'account' => $record,
            'roles' => $canAssignRoles ? $this->assignableRoles($actor) : new Collection,
            'selectedRoles' => $record->roles->modelKeys(),
            'canAssignRoles' => $canAssignRoles,
            'isTenantUser' => $isTenantUser,
            'branches' => $branches,
        ];
    }

    private function assignableRoles(Account $actor): Collection
    {
        return $this->access->rolesQuery($actor)->orderBy('name')->orderBy('id')->get(['id', 'name', 'guard_name']);
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
