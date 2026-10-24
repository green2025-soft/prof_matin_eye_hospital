<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Modules\Core\Services\PlatformInstaller;

class MigratePlatformCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'platform:migrate {--force} {--pretend}';

    protected $description = 'Apply pending central migrations and synchronize the platform permission catalog.';

    public function handle(PlatformInstaller $installer): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $installer->migrate((bool) $this->option('pretend'), $this->output);

        return self::SUCCESS;
    }
}
