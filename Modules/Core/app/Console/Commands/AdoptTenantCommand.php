<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Services\TenantProvisioner;
use Modules\Core\Validation\TenantRules;

use function Laravel\Prompts\text;

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

        $rules = TenantRules::metadata();
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

        $tenant = $provisioner->adopt(
            $name,
            $slug,
            TenantRules::normalize(['domains' => explode(',', $domains)])['domains'],
            text('Existing database name', required: true, validate: TenantRules::database()),
            normalizeEmail(text('Existing tenant administrator email', required: true, validate: TenantRules::owner()['owner_email'])),
        );

        $this->info('Existing tenant ready: '.$tenant->slug.'. Its account was preserved.');

        return self::SUCCESS;
    }
}
