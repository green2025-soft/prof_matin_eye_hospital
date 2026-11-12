<?php

namespace Modules\Accounting\Providers;

use Illuminate\Support\ServiceProvider;

class AccountingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $viewsPath = __DIR__.'/../../resources/views';

        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'accounting');
        }
    }
}
