<?php

namespace Modules\Core\Tasks;

use Illuminate\Config\Repository;
use LogicException;
use Modules\Core\Models\Tenant;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask;

class SwitchTenantDatabase extends SwitchTenantDatabaseTask
{
    private string $connectionKey;

    private array $defaultCredentials;

    public function __construct(private Repository $config)
    {
        $this->connectionKey = 'database.connections.'.$this->tenantDatabaseConnectionName();
        $this->defaultCredentials = $this->config->getMany([
            $this->connectionKey.'.url',
            $this->connectionKey.'.username',
            $this->connectionKey.'.password',
        ]);
    }

    public function makeCurrent(IsTenant $tenant): void
    {
        if (! $tenant instanceof Tenant) {
            throw new LogicException('The configured Core tenant is required for database switching.');
        }

        $credentials = $this->defaultCredentials;
        // A connection URL would override the tenant's saved username and password.
        $credentials[$this->connectionKey.'.url'] = null;
        $username = $tenant->database_username;

        // Legacy tenants use the configured account; a supplied account may have an empty password.
        if ($username !== null) {
            $credentials[$this->connectionKey.'.username'] = $username;
            $credentials[$this->connectionKey.'.password'] = $tenant->database_password ?? '';
        }

        $this->config->set($credentials);

        parent::makeCurrent($tenant);
    }

    public function forgetCurrent(): void
    {
        $this->config->set($this->defaultCredentials);

        parent::forgetCurrent();
    }
}
