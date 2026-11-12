<?php

namespace Modules\Accounting\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuComposer
{
    public function __construct(private Request $request) {}

    public function compose(View $view): void
    {
        if (! $this->request->routeIs('accounting.*')) {
            return;
        }

        $view->with('moduleMenu', [
            'view' => 'accounting::partials.menu',
            'homeLabel' => 'Accounting',
            'homeUrl' => route('accounting.dashboard'),
            'homeActive' => $this->request->routeIs('accounting.dashboard'),
            'permissions' => $this->request->attributes->get('navigation_permissions', []),
        ]);
    }
}
