<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\TenantProvisioner;

class RetryTenantCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'tenant:retry {tenant : Tenant slug} {--adopt : Retry an explicit existing-database adoption} {--force}';

    protected $description = 'Resume an unfinished tenant setup using its original database ownership token.';

    public function handle(TenantProvisioner $provisioner): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $tenant = Tenant::query()->where('slug', $this->argument('tenant'))->firstOrFail();
        $adopt = (bool) $this->option('adopt');
        $provisioner->retry(
            $tenant,
            $adopt ? '' : $this->ask('Tenant administrator name'),
            $adopt ? null : $this->secret('Password (at least 12 characters)'),
            $adopt,
        );

        $this->info('Tenant ready: '.$tenant->slug);

        return self::SUCCESS;
    }
}
