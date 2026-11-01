<?php

namespace Modules\Inventory\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Services\AccessManager;

class MenuComposer
{
    public function __construct(private Request $request, private AccessManager $access) {}

    public function compose(View $view): void
    {
        if (! $this->request->routeIs('inventory.*')) {
            return;
        }
        $actor = $this->request->user('web');
        $subscription = $this->request->attributes->get('application_subscription');
        $plan = $subscription?->isUsable() ? $subscription->plan : null;

        $view->with('moduleMenu', [
            'view' => 'inventory::partials.menu', 'homeLabel' => 'Inventory', 'homeUrl' => route('inventory.dashboard'),
            'homeActive' => $this->request->routeIs('inventory.dashboard'), 'permissions' => $this->access->navigationPermissions($actor, $plan),
            'productType' => $this->productType(),
        ]);
    }

    private function productType(): ?string
    {
        $type = $this->request->attributes->get('inventory_product_type');
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
