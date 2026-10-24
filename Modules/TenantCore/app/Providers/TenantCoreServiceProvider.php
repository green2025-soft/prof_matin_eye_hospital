<?php

namespace Modules\TenantCore\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Contracts\TenantAccounts as TenantAccountsContract;
use Modules\Core\Policies\AccountPolicy;
use Modules\TenantCore\Models\User;
use Modules\TenantCore\Services\TenantAccounts;

class TenantCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TenantAccountsContract::class, TenantAccounts::class);
    }

    public function boot(): void
    {
        Gate::policy(User::class, AccountPolicy::class);
        $this->loadRoutesFrom(module_path('TenantCore', 'routes/web.php'));
    }
}
