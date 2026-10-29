<?php

namespace Modules\Core\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\SessionGuard;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\ResourceRegistrar as LaravelResourceRegistrar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use LogicException;
use Modules\Core\Console\Commands\AdoptTenantCommand;
use Modules\Core\Console\Commands\CreateTenantCommand;
use Modules\Core\Console\Commands\InstallPlatformCommand;
use Modules\Core\Console\Commands\MigratePlatformCommand;
use Modules\Core\Console\Commands\MigrateTenantsCommand;
use Modules\Core\Console\Commands\ReconcilePaymentsCommand;
use Modules\Core\Console\Commands\RetryTenantCommand;
use Modules\Core\Console\Commands\SyncSubscriptionsCommand;
use Modules\Core\Helpers\WorkspaceMail;
use Modules\Core\Models\Account;
use Modules\Core\Routing\ResourceRegistrar;
use Modules\Core\Services\AccessManager;
use Modules\Core\Services\CachedUserProvider;
use Modules\Core\Services\ContextCache;
use Modules\Core\View\Composers\LayoutComposer;
use Spatie\Permission\Events\PermissionAttachedEvent;
use Spatie\Permission\Events\PermissionDetachedEvent;
use Spatie\Permission\Events\RoleAttachedEvent;
use Spatie\Permission\Events\RoleDetachedEvent;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LaravelResourceRegistrar::class, ResourceRegistrar::class);
    }

    public function boot(AccessManager $access): void
    {
        Auth::provider('cached-eloquent', fn (Application $app, array $configuration): CachedUserProvider => new CachedUserProvider(
            $app['hash'], $configuration['model'],
        ));

        Event::listen(['eloquent.saved: *', 'eloquent.deleted: *'], static function (string $event, array $models): void {
            $model = $models[0] ?? null;
            if ($model instanceof Role || $model instanceof Permission) {
                app(ContextCache::class)->flush($model->getConnection()->getName(), 'actors');
            }
        });
        Event::listen([
            RoleAttachedEvent::class,
            RoleDetachedEvent::class,
            PermissionAttachedEvent::class,
            PermissionDetachedEvent::class,
        ], static fn (object $event) => app(ContextCache::class)->flush($event->model->getConnection()->getName(), 'actors'));

        Paginator::useBootstrapFive();

        // Native policies retain precedence over these opt-in CRUD permission gates.
        foreach (['viewAny' => 'view', 'view' => 'view', 'create' => 'create', 'update' => 'update', 'delete' => 'delete'] as $ability => $action) {
            Gate::define($ability, fn (Account $actor, mixed $subject = null): bool => $access->allowsCrud($actor, $subject, $action));
        }

        View::composer('core::layouts.app', LayoutComposer::class);

        Mail::extend('workspace', fn (array $configuration) => $this->app->make(WorkspaceMail::class)->transport($configuration));
        Queue::before(fn () => $this->app->make(WorkspaceMail::class)->reset());

        Auth::extend('scoped-session', function (Application $app, string $name, array $config): SessionGuard {
            $request = $app['request'];
            $scope = $request->attributes->get('application_scope');
            $provider = $request->attributes->get('application_provider');

            if (! is_string($scope) || ! in_array($provider, ['admins', 'users'], true)) {
                throw new LogicException('Resolve the application before using the web guard.');
            }

            $config['provider'] = $provider;

            // Native session and remember-cookie keys include the HTTP account scope.
            return $app['auth']->createSessionDriver($name.'_'.$scope, $config);
        });

        RateLimiter::for('login', function (Request $request): Limit {
            $email = normalizeEmail($request->input('email'));
            $scope = $request->attributes->get('application_scope', 'central');

            return Limit::perMinute(5)->by(hash('sha256', $scope.'|'.$email.'|'.$request->ip()));
        });

        RateLimiter::for('password-reset', fn (Request $request): Limit => Limit::perMinute(5)
            ->by(hash('sha256', $request->attributes->get('application_scope').'|'.normalizeEmail($request->input('email')).'|'.$request->ip())));

        ResetPassword::createUrlUsing(function (Account $account, #[\SensitiveParameter] string $token): string {
            $parameters = ['token' => $token, 'email' => $account->getEmailForPasswordReset()];
            if ($account->loginType() !== 'user') {
                $parameters['account_type'] = $account->loginType();
            }

            return route('auth.password.reset', $parameters);
        });

        RateLimiter::for('checkout', fn (Request $request): Limit => Limit::perMinute(10)
            ->by($request->attributes->get('application_scope').'|'.$request->user('web')?->getAuthIdentifier()));
        RateLimiter::for('gateway-callback', fn (Request $request): Limit => Limit::perMinute(180)
            ->by($request->route('gateway').'|'.$request->ip()));

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'core');
        $this->loadRoutesFrom(module_path('Core', 'routes/web.php'));

        $this->commands([
            InstallPlatformCommand::class,
            CreateTenantCommand::class,
            AdoptTenantCommand::class,
            MigrateTenantsCommand::class,
            MigratePlatformCommand::class,
            RetryTenantCommand::class,
            SyncSubscriptionsCommand::class,
            ReconcilePaymentsCommand::class,
        ]);
    }
}
