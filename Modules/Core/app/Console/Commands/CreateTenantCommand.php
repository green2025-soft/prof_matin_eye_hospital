<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Services\TenantProvisioner;
use Modules\Core\Validation\TenantRules;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateTenantCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'tenant:create {--force}';

    protected $description = 'Register a tenant using an existing empty database, then initialize its schema, defaults and administrator.';

    public function handle(TenantProvisioner $provisioner): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $rules = TenantRules::metadata() + TenantRules::owner() + TenantRules::credentials();
        $name = trim(text(
            'Tenant name',
            required: true,
            validate: fn ($value) => $this->validatePrompt(trim($value), $rules['name']),
        ));
        $slug = text('Tenant slug', required: true, validate: $rules['slug']);
        $domains = text('Domains (comma separated, without ports)', required: true, validate: fn ($value) => Validator::make(
            TenantRules::normalize(['domains' => explode(',', $value)]),
            ['domains' => $rules['domains'], 'domains.*' => $rules['domains.*']],
        )->errors()->first());
        $credentials = [
            'database' => trim(text('DB_DATABASE (existing empty database)', required: true, validate: $rules['credentials.database'])),
            'username' => trim(text('DB_USERNAME', required: true, validate: $rules['credentials.username'])),
            'password' => password('DB_PASSWORD (leave blank if none)', validate: $rules['credentials.password']),
        ];

        $tenant = $provisioner->create(
            $name,
            $slug,
            TenantRules::normalize(['domains' => explode(',', $domains)])['domains'],
            trim(text(
                'Tenant administrator name',
                required: true,
                validate: fn ($value) => $this->validatePrompt(trim($value), $rules['owner_name']),
            )),
            normalizeEmail(text('Tenant administrator email', required: true, validate: $rules['owner_email'])),
            password('Password', required: true, validate: $rules['password']),
            $credentials,
        );

        $this->info('Tenant ready: '.$tenant->slug);

        return self::SUCCESS;
    }
}
