<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Modules\Core\Services\TenantProvisioner;

class CreateTenantCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'tenant:create {--force}';

    protected $description = 'Register a tenant and prepare its own database, schema, and administrator.';

    public function handle(TenantProvisioner $provisioner): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $tenant = $provisioner->create(
            $this->ask('Tenant name'),
            $this->ask('Tenant slug'),
            array_map('trim', explode(',', $this->ask('Domains (comma separated, without ports)'))),
            $this->ask('Tenant administrator name'),
            $this->ask('Tenant administrator email'),
            $this->secret('Password (at least 12 characters)'),
        );

        $this->info('Tenant ready: '.$tenant->slug);

        return self::SUCCESS;
    }
}
