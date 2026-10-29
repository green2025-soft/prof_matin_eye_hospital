<?php

namespace Modules\Core\Tasks;

use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemManager;
use LogicException;
use Modules\Core\Helpers\WorkspaceMail;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;
use Spatie\Permission\PermissionRegistrar;

class SwitchTenantSettings implements SwitchTenantTask
{
    private const CONFIG_KEYS = [
        'database.default',
        'auth.guards.web.provider',
        'cache.prefix',
        'cache.stores.file.path',
        'cache.stores.file.lock_path',
        'permission.cache.key',
        'filesystems.disks.local.root',
        'filesystems.disks.public.root',
        'filesystems.disks.public.url',
    ];

    private array $centralConfiguration;

    public function __construct(
        private Application $app,
        private Repository $config,
        private CacheManager $cache,
        private FilesystemManager $filesystems,
        private PermissionRegistrar $permissions,
    ) {
        $this->centralConfiguration = $this->config->getMany(self::CONFIG_KEYS);
        $this->centralConfiguration['database.default'] = 'central';
        $this->centralConfiguration['auth.guards.web.provider'] = 'admins';
    }

    public function makeCurrent(IsTenant $tenant): void
    {
        if (! ctype_digit((string) $tenant->getKey())) {
            throw new LogicException('A persisted tenant identifier is required.');
        }

        $scope = 'tenant_'.$tenant->getKey();

        $this->config->set([
            'database.default' => 'tenant',
            'auth.guards.web.provider' => 'users',
            'cache.prefix' => $this->centralConfiguration['cache.prefix'].$scope.':',
            'cache.stores.file.path' => $this->scopedPath('cache.stores.file.path', $scope),
            'cache.stores.file.lock_path' => $this->scopedPath('cache.stores.file.lock_path', $scope),
            'permission.cache.key' => $this->centralConfiguration['permission.cache.key'].'.'.$scope,
            'filesystems.disks.local.root' => $this->scopedPath('filesystems.disks.local.root', $scope),
            'filesystems.disks.public.root' => $this->scopedPath('filesystems.disks.public.root', $scope),
            'filesystems.disks.public.url' => rtrim($this->centralConfiguration['filesystems.disks.public.url'], '/').'/'.$scope,
        ]);

        $this->resetStores();
    }

    public function forgetCurrent(): void
    {
        $this->config->set($this->centralConfiguration);

        $this->resetStores();
    }

    private function scopedPath(string $key, string $scope): string
    {
        $path = $this->centralConfiguration[$key];

        if (! is_string($path) || $path === '') {
            throw new LogicException('An application storage path is not configured.');
        }

        return rtrim($path, '/\\').'/'.$scope;
    }

    private function resetStores(): void
    {
        $this->app->make(WorkspaceMail::class)->reset();
        $stores = array_keys($this->config->get('cache.stores', []));
        $this->cache->forgetDriver($stores);
        $this->filesystems->forgetDisk(['local', 'public']);

        foreach (['cache.store', 'cache.psr6', 'filesystem.disk', 'filesystem.cloud'] as $binding) {
            $this->app->forgetInstance($binding);
        }

        foreach ($stores as $store) {
            $this->app->forgetInstance('cache.__memoized:'.$store);
        }

        $this->permissions->initializeCache();
    }
}
