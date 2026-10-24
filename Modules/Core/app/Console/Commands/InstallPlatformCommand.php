<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Modules\Core\Services\PlatformInstaller;
use Modules\Core\Validation\TenantRules;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

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

        $rules = TenantRules::owner();
        $admin = $installer->install(
            trim(text(
                'Platform administrator name',
                required: true,
                validate: fn ($value) => $this->validatePrompt(trim($value), $rules['owner_name']),
            )),
            normalizeEmail(text('Platform administrator email', required: true, validate: $rules['owner_email'])),
            password('Password', required: true, validate: $rules['password']),
        );

        $this->info('Platform ready. Administrator: '.$admin->email);

        return self::SUCCESS;
    }
}
