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
        $catalogContext = $this->request->query('menu') === 'prescription'
            && $this->request->routeIs(
                'pharmacy.products.*',
                'pharmacy.categories.*',
                'pharmacy.shelves.*',
                'pharmacy.variants.*',
                'pharmacy.product-units.*',
            );

        if (! $this->request->routeIs('prescription.*') && ! $catalogContext) {
            return;
        }

        $actor = $this->request->user('web');
        $subscription = $this->request->attributes->get('application_subscription');
        $plan = $subscription?->isUsable() ? $subscription->plan : null;
        $permissions = $this->access->navigationPermissions($actor, $plan);

        if ($catalogContext && ! ($permissions['prescription.view'] ?? false)) {
            return;
        }

        $view->with('contextMenu', [
            'view' => 'prescription::partials.menu',
            'homeLabel' => 'Prescription',
            'homeUrl' => route('prescription.dashboard'),
            'homeActive' => $this->request->routeIs('prescription.dashboard'),
            'permissions' => $permissions,
            'settings' => Setting::values('prescription'),
            'productType' => $this->request->attributes->get('pharmacy_product_type')
                ?? $this->request->query('type', 'medicine'),
        ]);
    }
}
