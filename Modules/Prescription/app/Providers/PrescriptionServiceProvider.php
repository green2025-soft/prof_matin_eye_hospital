<?php

namespace Modules\Prescription\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Prescription\View\Composers\MenuComposer;
use Modules\Prescription\View\Composers\PrintComposer;

class PrescriptionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('core::layouts.app', MenuComposer::class);
        View::composer('prescription::prints.layout', PrintComposer::class);

        $this->loadViewsFrom(module_path('Prescription', 'resources/views'), 'prescription');
        $this->loadRoutesFrom(module_path('Prescription', 'routes/web.php'));
    }
}
