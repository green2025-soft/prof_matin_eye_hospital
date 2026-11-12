<?php

namespace Modules\Accounting\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Accounting\Contracts\JournalPosting;
use Modules\Accounting\Services\JournalService;
use Modules\Accounting\View\Composers\MenuComposer;
use Modules\Core\Http\Middleware\EnsureModulePermission;
use Modules\TenantCore\View\Composers\ReportComposer;

class AccountingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(module_path('Accounting', 'config/accounting.php'), 'accounting');
        $this->app->bind(JournalPosting::class, JournalService::class);
    }

    public function boot(Router $router): void
    {
        $router->aliasMiddleware('accounting.permission', EnsureModulePermission::class.':accounting.view');
        View::composer('core::layouts.app', MenuComposer::class);
        View::composer('accounting::reports.layout', ReportComposer::class);

        $this->loadViewsFrom(module_path('Accounting', 'resources/views'), 'accounting');
        $this->loadRoutesFrom(module_path('Accounting', 'routes/web.php'));
    }
}
