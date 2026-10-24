<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Modules\Core\Services\TenantProvisioner;

class AdoptTenantCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'tenant:adopt {--force}';

    protected $description = 'Explicitly register an existing tenant database without resetting its account.';

    public function handle(TenantProvisioner $provisioner): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $tenant = $provisioner->adopt(
            $this->ask('Tenant name'),
            $this->ask('Tenant slug'),
            array_map('trim', explode(',', $this->ask('Domains (comma separated, without ports)'))),
            $this->ask('Existing database name'),
            $this->ask('Existing tenant administrator email'),
        );

        $this->info('Existing tenant ready: '.$tenant->slug.'. Its account was preserved.');

        return self::SUCCESS;
    }
}
