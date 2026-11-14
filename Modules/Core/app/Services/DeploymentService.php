<?php

namespace Modules\Core\Services;

use Closure;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Tenant;
use RuntimeException;
use Symfony\Component\Console\Output\NullOutput;
use Throwable;

class DeploymentService
{
    private const PROGRESS_KEY = 'core.deployment.progress';

    private Repository $progress;

    public function __construct(private Kernel $console, CacheManager $cache)
    {
        $this->progress = $cache->store('file');
    }

    public function status(): array
    {
        $state = $this->progress->get(self::PROGRESS_KEY, []);
        $total = count($state['tenant_ids'] ?? []) + 3;

        return [
            'status' => $state['status'] ?? 'idle',
            'message' => $state['message'] ?? 'Upload the updated files, then run the server update.',
            'progress' => intdiv(count($state['results'] ?? []) * 100, $total),
            'tenant_total' => $total - 3,
            'tenant_processed' => $state['tenant_processed'] ?? 0,
            'results' => $state['results'] ?? [],
            'started_at' => $state['started_at'] ?? null,
            'finished_at' => $state['finished_at'] ?? null,
        ];
    }

    // Continue resumes this upload; Run Update restarts phases for newly uploaded code.
    public function start(bool $restart = false): array
    {
        return $this->locked(function () use ($restart): array {
            $state = $this->progress->get(self::PROGRESS_KEY, []);
            if (! $restart && ($state['status'] ?? null) === 'running') {
                return $this->status();
            }

            $this->save([
                'status' => 'running',
                'phase' => 'prepare',
                'message' => 'Preparing the server update.',
                'tenant_ids' => Tenant::where('status', '!=', Tenant::STATUS_DELETING)
                    ->orderBy('id')
                    ->pluck('id')
                    ->all(),
                'tenant_processed' => 0,
                'failed_tenants' => [],
                'results' => [],
                'started_at' => now()->format('d-m-Y h:i A'),
                'finished_at' => null,
            ]);

            return $this->status();
        });
    }

    // Each HTTP request completes one phase or one tenant; no queue worker is needed.
    public function runNext(): array
    {
        return $this->locked(function (): array {
            $state = $this->progress->get(self::PROGRESS_KEY, []);
            if (($state['status'] ?? null) !== 'running') {
                return $this->status();
            }

            $state = match ($state['phase']) {
                'prepare' => $this->prepare($state),
                'platform' => $this->migratePlatform($state),
                'tenants' => $this->migrateTenant($state),
                'finish' => $this->finish($state),
            };
            $this->save($state);

            return $this->status();
        });
    }

    private function prepare(array $state): array
    {
        $success = $this->clearCaches();
        $state = $this->record($state, 'Prepare server', $success);
        $state['phase'] = 'platform';

        return $state;
    }

    private function migratePlatform(array $state): array
    {
        $success = $this->command('platform:migrate', ['--force' => true]);
        $state = $this->record($state, 'Central database', $success);
        $state['phase'] = $state['tenant_ids'] === [] ? 'finish' : 'tenants';

        return $state;
    }

    private function migrateTenant(array $state): array
    {
        $id = $state['tenant_ids'][$state['tenant_processed']];
        $success = $this->command('tenants:migrate', [
            '--tenant' => [(string) $id],
            '--force' => true,
        ]);

        // The existing command reports tenant failures; other workspaces can still update.
        $state = $this->record($state, 'Tenant #'.$id, $success, stopOnFailure: false);
        $state['tenant_processed']++;
        if (! $success) {
            $state['failed_tenants'][] = $id;
        }
        if ($state['tenant_processed'] === count($state['tenant_ids'])) {
            $state['phase'] = 'finish';
        }

        return $state;
    }

    private function finish(array $state): array
    {
        $success = $this->clearCaches() && $this->buildCaches();
        $state = $this->record($state, 'Build production caches', $success);
        if ($success) {
            $state['status'] = $state['failed_tenants'] === [] ? 'completed' : 'completed_with_errors';
            $state['message'] = $state['failed_tenants'] === []
                ? 'Server update completed successfully.'
                : 'Some tenants could not update. Check the application log, fix the cause and run the update again.';
            $state['finished_at'] = now()->format('d-m-Y h:i A');
        }

        return $state;
    }

    private function record(array $state, string $label, bool $success, bool $stopOnFailure = true): array
    {
        $message = $success ? 'Completed.' : 'Update failed. See the application log for details.';
        $state['results'][] = [
            'label' => $label,
            'success' => $success,
            'message' => $message,
        ];
        $state['message'] = $label.': '.$message;
        if (! $success && $stopOnFailure) {
            $state['status'] = 'failed';
            $state['finished_at'] = now()->format('d-m-Y h:i A');
        }

        return $state;
    }

    // Clear generated caches only; never erase application cache, sessions or uploaded files.
    private function clearCaches(): bool
    {
        foreach (['config:clear', 'route:clear', 'event:clear', 'view:clear', 'clear-compiled'] as $command) {
            if (! $this->command($command)) {
                return false;
            }
        }

        $cleared = ! is_file(app()->getCachedConfigPath())
            && ! is_file(app()->getCachedRoutesPath())
            && ! is_file(app()->getCachedEventsPath())
            && ! is_file(app()->getCachedServicesPath())
            && ! is_file(app()->getCachedPackagesPath())
            && glob(config('view.compiled').'/*') === [];
        if (! $cleared) {
            Log::error('Deployment could not remove generated bootstrap caches.');
        }

        return $cleared;
    }

    // Native config/route commands bootstrap again; keep the web root from this request.
    private function buildCaches(): bool
    {
        $previousPublicPath = $_SERVER['APP_DEPLOY_PUBLIC_PATH'] ?? null;
        $_SERVER['APP_DEPLOY_PUBLIC_PATH'] = app()->publicPath();

        try {
            foreach (['config:cache', 'event:cache', 'route:cache', 'view:cache'] as $command) {
                if (! $this->command($command)) {
                    $this->clearCaches();

                    return false;
                }
            }

            return true;
        } finally {
            if ($previousPublicPath === null) {
                unset($_SERVER['APP_DEPLOY_PUBLIC_PATH']);
            } else {
                $_SERVER['APP_DEPLOY_PUBLIC_PATH'] = $previousPublicPath;
            }
        }
    }

    private function command(string $command, array $arguments = []): bool
    {
        $application = app();

        try {
            $status = $this->console->call($command, $arguments, new NullOutput);
            if ($status !== 0) {
                Log::error('Deployment command failed.', ['command' => $command]);
            }

            return $status === 0;
        } catch (Throwable $exception) {
            report($exception);

            return false;
        } finally {
            // Config caching leaves a fresh global application; return to the HTTP request.
            Container::setInstance($application);
            Facade::clearResolvedInstances();
            Facade::setFacadeApplication($application);
        }
    }

    private function save(array $state): void
    {
        if (! $this->progress->forever(self::PROGRESS_KEY, $state)) {
            throw new RuntimeException('Cannot save update progress. Check the storage directory permissions.');
        }
    }

    // flock releases when a timed-out PHP process exits, so a later request can resume safely.
    private function locked(Closure $operation): array
    {
        $lock = fopen(storage_path('framework/deployment.lock'), 'c');
        if ($lock === false) {
            throw new RuntimeException('Cannot lock the update. Check the storage directory permissions.');
        }

        try {
            if (! flock($lock, LOCK_EX | LOCK_NB)) {
                throw ValidationException::withMessages([
                    'record' => 'An update step is already running. Wait a moment, then continue the update.',
                ]);
            }

            return $operation();
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}
