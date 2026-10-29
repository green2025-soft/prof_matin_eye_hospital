<?php

namespace Modules\Core\Services;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Models\Account;

class CachedUserProvider extends EloquentUserProvider
{
    public function retrieveById($identifier)
    {
        $model = $this->createModel();
        $cache = app(ContextCache::class);

        // Credential, remember-token and customized queries retain Laravel's native reads.
        if (! $model instanceof Account || ! $cache->isCacheable() || $this->queryCallback !== null) {
            return parent::retrieveById($identifier);
        }

        $connection = $model->getConnection()->getName();
        $snapshot = $cache->remember($connection, 'actors', $model::class.':'.$identifier, function () use ($identifier): ?array {
            $account = parent::retrieveById($identifier);
            if ($account === null) {
                return null;
            }

            $account->loadMissing($account->authenticationRelations());
            $snapshot = ['attributes' => $account->getAttributes()];

            if ($account->relationLoaded('roles')) {
                $snapshot['roles'] = [];
                foreach ($account->getRelation('roles') as $role) {
                    $snapshot['roles'][] = [
                        'attributes' => $role->getAttributes(),
                        'permissions' => $role->getRelation('permissions')->map(fn ($permission): array => $permission->getAttributes())->all(),
                    ];
                }
            }
            if ($account->relationLoaded('permissions')) {
                $snapshot['permissions'] = $account->getRelation('permissions')->map(fn ($permission): array => $permission->getAttributes())->all();
            }

            return $snapshot;
        });

        if ($snapshot === null) {
            return null;
        }

        $account = $model->newFromBuilder($snapshot['attributes'], $connection);
        if (array_key_exists('roles', $snapshot)) {
            $roleModel = config('permission.models.role');
            $roles = new Collection;
            foreach ($snapshot['roles'] as $roleSnapshot) {
                $role = (new $roleModel)->newFromBuilder($roleSnapshot['attributes'], $connection);
                $role->setRelation('permissions', $this->permissionsFromSnapshot($roleSnapshot['permissions'], $connection));
                $roles->push($role);
            }
            $account->setRelation('roles', $roles);
        }
        if (array_key_exists('permissions', $snapshot)) {
            $account->setRelation('permissions', $this->permissionsFromSnapshot($snapshot['permissions'], $connection));
        }

        return $account;
    }

    private function permissionsFromSnapshot(array $attributes, string $connection): Collection
    {
        $permissionModel = config('permission.models.permission');
        $permissions = new Collection;
        foreach ($attributes as $permissionAttributes) {
            $permissions->push((new $permissionModel)->newFromBuilder($permissionAttributes, $connection));
        }

        return $permissions;
    }
}
