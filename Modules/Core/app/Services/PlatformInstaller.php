<?php

namespace Modules\Core\Services;

use LogicException;
use Modules\Core\Models\Admin;
use Modules\Core\Models\Plan;
use Modules\Core\Models\Setting;
use Modules\Core\Models\Tenant;
use Symfony\Component\Console\Output\OutputInterface;

class PlatformInstaller
{
    public function __construct(private DatabaseInstaller $databases, private PermissionCatalog $permissions) {}

    public function install(string $name, string $email, #[\SensitiveParameter] string $password): Admin
    {
        if (Tenant::checkCurrent() || config('database.default') !== 'central') {
            throw new LogicException('Install the platform from the central command context.');
        }

        $database = config('database.connections.central.database');

        if ($database === config('database.connections.mysql.database')) {
            throw new LogicException('The central database must differ from the existing tenant database.');
        }

        return $this->databases->locked($database, function () use ($database, $name, $email, $password): Admin {
            $this->databases->create($database);
            $this->databases->claim('central', 'central');
            $this->migrateSchema();

            return Admin::createOwner($name, $email, $password);
        });
    }

    public function migrate(bool $pretend = false, ?OutputInterface $output = null): void
    {
        if (Tenant::checkCurrent() || config('database.default') !== 'central') {
            throw new LogicException('Migrate the platform from the central command context.');
        }

        $this->databases->locked(config('database.connections.central.database'), function () use ($pretend, $output): void {
            $this->databases->verify('central', 'central');
            $this->migrateSchema($pretend, $output);
        });
    }

    private function migrateSchema(bool $pretend = false, ?OutputInterface $output = null): void
    {
        $this->databases->migrate('central', $pretend, $output);

        if ($pretend) {
            return;
        }

        $this->permissions->sync(platform: true);
        Setting::initialize('central');

        Plan::query()->firstOrCreate(['slug' => config('billing.default_plan')], [
            'name' => 'Starter',
            'description' => 'Free workspace plan. Configure paid plans from Plans.',
            'price_minor' => 0,
            'currency' => config('billing.currency'),
            'billing_interval' => 'monthly',
            'trial_days' => 0,
            'features' => [],
            'user_limit' => null,
            'is_active' => true,
        ]);

        foreach (Tenant::query()->whereIn('status', [Tenant::STATUS_ACTIVE, Tenant::STATUS_SUSPENDED])->lazyById(100) as $tenant) {
            app(SubscriptionService::class)->enrollDefault($tenant);
        }
    }
}
