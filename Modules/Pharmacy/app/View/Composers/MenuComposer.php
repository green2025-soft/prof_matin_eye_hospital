<?php

namespace Modules\Pharmacy\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuComposer
{
    public function __construct(private Request $request) {}

    public function compose(View $view): void
    {
        if (! $this->request->routeIs('pharmacy.*')) {
            return;
        }

        $view->with('moduleMenu', [
            'view' => 'pharmacy::partials.menu',
            'homeLabel' => 'Pharmacy',
            'homeUrl' => route('pharmacy.dashboard'),
            'homeActive' => $this->request->routeIs('pharmacy.dashboard'),
            'permissions' => $this->request->attributes->get('navigation_permissions', []),
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
