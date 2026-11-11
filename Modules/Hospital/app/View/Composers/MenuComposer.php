<?php

namespace Modules\Hospital\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Services\AccessManager;

class MenuComposer
{
    public function __construct(private Request $request, private readonly AccessManager $access) {}

    public function compose(View $view): void
    {
        if (! $this->request->routeIs('hospital.*')) {
            return;
        }

        $permissions = $this->request->attributes->get('navigation_permissions');

        if ($permissions === null) {
            $subscription = $this->request->attributes->get('application_subscription');
            $plan = $subscription?->isUsable() ? $subscription->plan : null;
            $permissions = $this->access->navigationPermissions($this->request->user('web'), $plan);
        }

        $view->with('moduleMenu', [
            'view' => 'hospital::partials.menu',
            'homeLabel' => 'Hospital',
            'homeUrl' => route('hospital.dashboard'),
            'homeActive' => $this->request->routeIs('hospital.dashboard'),
            'permissions' => $permissions,
        ]);
    }
}
