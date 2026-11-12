<?php

namespace Modules\Hospital\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Http\Middleware\EnsureModulePermission;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Services\HospitalCashReportSource;
use Modules\Hospital\Services\HospitalUserProvider;
use Modules\Hospital\Services\PatientService;
use Modules\Hospital\View\Composers\MenuComposer;
use Spatie\Permission\Models\Permission;

class HospitalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(module_path('Hospital', 'config/accounting.php'), 'accounting.integrations');

        $this->app->bind(PharmacyCustomers::class, PatientService::class);
        $this->app->tag([HospitalCashReportSource::class], 'tenantcore.cash-report-sources');
    }

    public function boot(Router $router): void
    {
        $router->aliasMiddleware('hospital.permission', EnsureModulePermission::class.':hospital.view');

        Event::listen(['eloquent.saved: '.Permission::class, 'eloquent.deleted: '.Permission::class], static function (Permission $permission): void {
            if ($permission->getConnection()->getName() === 'tenant') {
                Doctor::forgetSharedPermissions();
            }
        });

        Auth::provider('hospital-accounts', fn (Application $app, array $configuration): HospitalUserProvider => new HospitalUserProvider(
            $app['hash'], $configuration['model'], $configuration['doctor_model'],
        ));
        $this->app['config']->set([
            'auth.providers.users.driver' => 'hospital-accounts',
            'auth.providers.users.doctor_model' => Doctor::class,
            'auth.providers.doctors' => ['driver' => 'eloquent', 'model' => Doctor::class],
            'auth.passwords.doctors' => [
                'provider' => 'doctors',
                'connection' => 'tenant',
                'table' => 'doctor_password_reset_tokens',
                'expire' => 60,
                'throttle' => 60,
            ],
            'auth.login_types.doctor' => ['label' => 'Doctor', 'broker' => 'doctors'],
        ]);

        View::composer('core::layouts.app', MenuComposer::class);
        $this->loadViewsFrom(module_path('Hospital', 'resources/views'), 'hospital');
        $this->loadRoutesFrom(module_path('Hospital', 'routes/web.php'));
    }
}
