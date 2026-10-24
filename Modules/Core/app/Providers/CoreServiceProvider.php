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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
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
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\CheckoutAttempt;
use Modules\Core\Models\Invoice;
use Modules\Core\Models\Payment;
use Modules\Core\Models\Plan;
use Modules\Core\Models\Role;
use Modules\Core\Models\Setting;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Policies\AccountPolicy;
use Modules\Core\Policies\InvoicePolicy;
use Modules\Core\Policies\PaymentPolicy;
use Modules\Core\Policies\PlanPolicy;
use Modules\Core\Policies\RolePolicy;
use Modules\Core\Policies\SettingPolicy;
use Modules\Core\Policies\SubscriptionPolicy;
use Modules\Core\Policies\TenantPolicy;
use Modules\Core\Routing\ResourceRegistrar;
use Modules\Core\View\Composers\LayoutComposer;

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
        Gate::policy(Plan::class, PlanPolicy::class);
        Gate::policy(Subscription::class, SubscriptionPolicy::class);
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(Setting::class, SettingPolicy::class);
        Route::model('tenant', Tenant::class);
        Route::model('plan', Plan::class);
        Route::model('subscription', Subscription::class);
        Route::model('payment', Payment::class);
        Route::model('checkout', CheckoutAttempt::class);
        Route::model('setting', Setting::class);
        Route::bind('invoice', fn (string $value): Invoice => Invoice::query()
            ->when(Tenant::current(), fn ($query, Tenant $tenant) => $query->where('tenant_id', $tenant->getKey()))
            ->findOrFail($value));
        Route::bind('role', fn (string $value): Role => Role::query()->where('guard_name', 'web')->findOrFail($value));
        Route::bind('account', function (string $value): Account {
            $model = Account::modelForProvider(request()->attributes->get('application_provider'));

            return $model::query()->findOrFail($value);
        });

        View::composer('core::layouts.app', LayoutComposer::class);

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

        ResetPassword::createUrlUsing(fn (Account $account, #[\SensitiveParameter] string $token): string => route('auth.password.reset', [
            'token' => $token,
            'email' => $account->getEmailForPasswordReset(),
        ]));

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
