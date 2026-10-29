<?php

namespace Modules\TenantCore\Providers;

use Illuminate\Auth\Events\CurrentDeviceLogout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Contracts\TenantAccounts as TenantAccountsContract;
use Modules\TenantCore\Http\Middleware\ResolveBranch;
use Modules\TenantCore\Services\AuditLogger;
use Modules\TenantCore\Services\TenantAccounts;
use Spatie\Permission\Events\PermissionAttachedEvent;
use Spatie\Permission\Events\PermissionDetachedEvent;
use Spatie\Permission\Events\RoleAttachedEvent;
use Spatie\Permission\Events\RoleDetachedEvent;

class TenantCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TenantAccountsContract::class, TenantAccounts::class);
    }

    public function boot(): void
    {
        Event::listen(['eloquent.created: *', 'eloquent.updated: *', 'eloquent.deleted: *'], [AuditLogger::class, 'modelChanged']);
        Event::listen(Login::class, [AuditLogger::class, 'login']);
        Event::listen(Logout::class, [AuditLogger::class, 'logout']);
        Event::listen(CurrentDeviceLogout::class, [AuditLogger::class, 'logout']);
        Event::listen(RoleAttachedEvent::class, [AuditLogger::class, 'rolesAttached']);
        Event::listen(RoleDetachedEvent::class, [AuditLogger::class, 'rolesDetached']);
        Event::listen(PermissionAttachedEvent::class, [AuditLogger::class, 'permissionsAttached']);
        Event::listen(PermissionDetachedEvent::class, [AuditLogger::class, 'permissionsDetached']);

        Route::aliasMiddleware('branch', ResolveBranch::class);
        $this->callAfterResolving(HttpKernelContract::class, function (Kernel $kernel): void {
            $kernel->appendMiddlewareToGroup('web', ResolveBranch::class);
            $kernel->addToMiddlewarePriorityBefore(SubstituteBindings::class, ResolveBranch::class);
        });

        $this->loadViewsFrom(module_path('TenantCore', 'resources/views'), 'tenantcore');
        $this->loadRoutesFrom(module_path('TenantCore', 'routes/web.php'));
    }
}
