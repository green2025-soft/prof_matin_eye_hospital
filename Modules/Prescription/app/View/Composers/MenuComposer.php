<?php

namespace Modules\Prescription\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Models\Setting;
use Modules\Core\Services\AccessManager;

class MenuComposer
{
    public function __construct(private Request $request, private AccessManager $access) {}

    public function compose(View $view): void
    {
        if (! $this->request->routeIs('prescription.*')) {
            return;
        }

        $subscription = $this->request->attributes->get('application_subscription');
        $plan = $subscription?->isUsable() ? $subscription->plan : null;

        $view->with('moduleMenu', [
            'view' => 'prescription::partials.menu',
            'homeLabel' => 'Prescription',
            'homeUrl' => route('prescription.dashboard'),
            'homeActive' => $this->request->routeIs('prescription.dashboard'),
            'permissions' => $this->access->navigationPermissions($this->request->user('web'), $plan),
            'settings' => Setting::values('prescription'),
        ]);
    }
}
