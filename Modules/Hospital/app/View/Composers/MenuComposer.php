<?php

namespace Modules\Hospital\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuComposer
{
    public function __construct(private Request $request) {}

    public function compose(View $view): void
    {
        if (! $this->request->routeIs('hospital.*')) {
            return;
        }

        $view->with('moduleMenu', [
            'view' => 'hospital::partials.menu',
            'homeLabel' => 'Hospital',
            'homeUrl' => route('hospital.dashboard'),
            'homeActive' => $this->request->routeIs('hospital.dashboard'),
            'permissions' => $this->request->attributes->get('navigation_permissions', []),
        ]);
    }
}
