<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Inventory\View\Composers\MenuComposer;

class InventoryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('core::layouts.app', MenuComposer::class);
        $this->loadViewsFrom(module_path('Inventory', 'resources/views'), 'inventory');
        $this->loadRoutesFrom(module_path('Inventory', 'routes/web.php'));
    }
}
