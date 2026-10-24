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

    protected $description = 'Register a tenant and prepare its own database, schema, and administrator.';

    public function handle(TenantProvisioner $provisioner): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $rules = TenantRules::metadata() + TenantRules::owner();
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
        );

        $this->info('Tenant ready: '.$tenant->slug);

        return self::SUCCESS;
    }
}
