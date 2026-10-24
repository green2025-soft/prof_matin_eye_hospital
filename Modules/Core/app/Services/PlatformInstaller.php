<?php

namespace Modules\Core\Services;

use Illuminate\Support\Facades\Validator;
use LogicException;
use Modules\Core\Models\Admin;
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

        Validator::make(compact('name', 'email', 'password'), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:12', 'max:4096'],
        ])->validate();

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
    }
}
