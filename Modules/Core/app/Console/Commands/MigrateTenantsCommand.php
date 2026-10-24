<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\DatabaseInstaller;
use Modules\Core\Services\PermissionCatalog;
use Throwable;

class MigrateTenantsCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'tenants:migrate
        {--tenant=* : Tenant ID or slug; omit to migrate every registered tenant}
        {--force : Run without confirmation in production}
        {--pretend : Display migration SQL without changing tenant schemas}';

    protected $description = 'Run the shared tenant migrations in each registered tenant database';

    public function handle(DatabaseInstaller $installer, PermissionCatalog $permissions): int
    {
        $selectors = [];

        foreach ($this->option('tenant') as $selector) {
            $selector = trim($selector);
            $selectors[] = ctype_digit($selector) ? (ltrim($selector, '0') ?: '0') : $selector;
        }

        $selectors = array_values(array_unique($selectors));
        $tenants = Tenant::query();

        if ($selectors !== []) {
            $ids = array_values(array_filter($selectors, 'ctype_digit'));
            $slugs = array_values(array_diff($selectors, $ids));
            $matches = Tenant::query()->where(function (Builder $query) use ($slugs, $ids): void {
                $query->whereIn('slug', $slugs);

                if ($ids !== []) {
                    $query->orWhereIn('id', $ids);
                }
            })->get(['id', 'slug']);

            $unknown = array_merge(
                array_diff($ids, array_map('strval', $matches->modelKeys())),
                array_diff($slugs, $matches->pluck('slug')->all()),
            );

            if ($unknown !== []) {
                $this->error('Unknown tenant identifiers: '.implode(', ', $unknown));

                return self::FAILURE;
            }

            $tenants->whereKey($matches->modelKeys());
        }

        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $pretend = (bool) $this->option('pretend');
        $processed = 0;
        $failed = [];

        foreach ($tenants->lazyById(100) as $tenant) {
            $processed++;
            $this->line("Tenant {$tenant->getKey()} ({$tenant->slug})");

            try {
                $installer->locked($tenant->database, function () use ($installer, $permissions, $tenant, $pretend): void {
                    $tenant->execute(function () use ($installer, $permissions, $tenant, $pretend): void {
                        $installer->verify('tenant', $tenant->database_token);
                        $installer->migrate('tenant', $pretend, $this->output);

                        if (! $pretend) {
                            $permissions->sync();
                        }
                    });
                });
            } catch (Throwable $exception) {
                report($exception);
                $failed[] = $tenant->getKey();
                $this->error("Tenant {$tenant->getKey()} failed. See the application log.");
            }
        }

        if ($failed !== []) {
            $this->error('Failed tenant IDs: '.implode(', ', $failed));

            return self::FAILURE;
        }

        if ($processed === 0) {
            $this->info('No registered tenants found.');

            return self::SUCCESS;
        }

        $this->info(($pretend ? 'Migration preview' : 'Migrations')." completed for {$processed} tenant(s).");

        return self::SUCCESS;
    }
}
