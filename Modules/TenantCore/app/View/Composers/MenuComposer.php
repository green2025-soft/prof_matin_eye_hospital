<?php

namespace Modules\TenantCore\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Models\Tenant;

class MenuComposer
{
    public function __construct(private Request $request) {}

    public function compose(View $view): void
    {
        if ($this->request->attributes->get('application_provider') !== 'users') {
            return;
        }

        $view->with([
            'foundationMenuView' => 'tenantcore::partials.menu',
            'foundationHasBranches' => (bool) Tenant::current()?->has_branches,
        ]);
    }
}
