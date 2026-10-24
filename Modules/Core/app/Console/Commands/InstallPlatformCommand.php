<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Modules\Core\Services\PlatformInstaller;

class InstallPlatformCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'platform:install {--force}';

    protected $description = 'Create the central database/schema and a separate platform administrator.';

    public function handle(PlatformInstaller $installer): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $admin = $installer->install(
            $this->ask('Platform administrator name'),
            $this->ask('Platform administrator email'),
            $this->secret('Password (at least 12 characters)'),
        );

        $this->info('Platform ready. Administrator: '.$admin->email);

        return self::SUCCESS;
    }
}
