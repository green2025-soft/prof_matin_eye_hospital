<?php

namespace Modules\Pharmacy\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Services\AccessManager;

class MenuComposer
{
    public function __construct(private Request $request, private AccessManager $access) {}

    public function compose(View $view): void
    {
        if (! $this->request->routeIs('pharmacy.*')) {
            return;
        }
        $permissions = $this->request->attributes->get('navigation_permissions');
        if ($permissions === null) {
            $actor = $this->request->user('web');
            $subscription = $this->request->attributes->get('application_subscription');
            $plan = $subscription?->isUsable() ? $subscription->plan : null;
            $permissions = $this->access->navigationPermissions($actor, $plan);
        }

        $view->with('moduleMenu', [
            'view' => 'pharmacy::partials.menu', 'homeLabel' => 'Pharmacy', 'homeUrl' => route('pharmacy.dashboard'),
            'homeActive' => $this->request->routeIs('pharmacy.dashboard'), 'permissions' => $permissions,
            'productType' => $this->productType(),
        ]);
    }

    private function productType(): ?string
    {
        $type = $this->request->attributes->get('pharmacy_product_type');
        if (in_array($type, ['medicine', 'optic'], true)) {
            return $type;
        }

        $type = $this->request->query('product_type');
        if (in_array($type, ['medicine', 'optic'], true)) {
            return $type;
        }

        $type = $this->request->query('type');

        return in_array($type, ['medicine', 'optic'], true) ? $type : null;
    }
}
