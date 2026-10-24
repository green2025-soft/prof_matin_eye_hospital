<?php

namespace Modules\Core\Providers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\ResourceRegistrar as LaravelResourceRegistrar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as BladeView;
use LogicException;
use Modules\Core\Console\Commands\AdoptTenantCommand;
use Modules\Core\Console\Commands\CreateTenantCommand;
use Modules\Core\Console\Commands\InstallPlatformCommand;
use Modules\Core\Console\Commands\MigratePlatformCommand;
use Modules\Core\Console\Commands\MigrateTenantsCommand;
use Modules\Core\Console\Commands\RetryTenantCommand;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\Role;
use Modules\Core\Models\Tenant;
use Modules\Core\Policies\AccountPolicy;
use Modules\Core\Policies\RolePolicy;
use Modules\Core\Policies\TenantPolicy;
use Modules\Core\Routing\ResourceRegistrar;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LaravelResourceRegistrar::class, ResourceRegistrar::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Gate::policy(Tenant::class, TenantPolicy::class);
        Gate::policy(Admin::class, AccountPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Route::model('tenant', Tenant::class);
        Route::bind('role', fn (string $value): Role => Role::query()->where('guard_name', 'web')->findOrFail($value));
        Route::bind('account', function (string $value): Account {
            $provider = request()->attributes->get('application_provider');
            $model = config('auth.providers.'.$provider.'.model');

            if (! is_string($model) || ! is_subclass_of($model, Account::class)) {
                throw new LogicException('Resolve the account provider before binding an account.');
            }

            return $model::query()->findOrFail($value);
        });

        View::composer(['core::layouts.app', 'core::home'], function (BladeView $view): void {
            $view->with('accountRoutePrefix', request()->attributes->get('application_provider') === 'admins'
                ? 'core.accounts'
                : 'tenantcore.accounts');
        });

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

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'core');

        $this->commands([
            InstallPlatformCommand::class,
            CreateTenantCommand::class,
            AdoptTenantCommand::class,
            MigrateTenantsCommand::class,
            MigratePlatformCommand::class,
            RetryTenantCommand::class,
        ]);
    }
}
