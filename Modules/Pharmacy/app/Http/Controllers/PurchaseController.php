<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Pharmacy\Http\Requests\PurchaseRequest;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\Supplier;
use Modules\Pharmacy\Models\Unit;
use Modules\Pharmacy\Services\ItemService;
use Modules\Pharmacy\Services\PurchaseItemService;
use Modules\Pharmacy\Services\PurchaseService;
use Modules\TenantCore\Models\PaymentType;

class PurchaseController extends BaseController
{
    protected string $model = Purchase::class;

    protected string $service = PurchaseService::class;

    protected string $title = 'Purchase';

    protected string $views = 'pharmacy::purchases';

    protected string $parameter = 'purchase';

    protected array $routeFilters = [
        'product_type' => 'product_type',
    ];

    protected array $defaultFilters = [
        'product_type' => 'medicine',
    ];

    public function index(ListRequest $request): View
    {
        $permissions = $request->attributes->get('navigation_permissions', []);
        $view = $this->listData($request);
        $request->attributes->set('pharmacy_product_type', $view->getData()['filters']['product_type']);

        $data = [
            'resourceParameter' => $this->parameter,
            'canCreate' => isset($permissions['pharmacy-purchases.create']),
            'canUpdatePurchases' => isset($permissions['pharmacy-purchases.update']),
            'canDeletePurchases' => isset($permissions['pharmacy-purchases.delete']),
        ];

        return $view->with($data);
    }

    public function store(PurchaseRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, Purchase $purchase, PurchaseService $entries): View
    {
        $actor = $request->user('web');
        $request->attributes->set('pharmacy_product_type', $purchase->product_type);

        $data = $entries->invoiceData($actor, $purchase) + [
            'routePrefix' => $this->routePrefix(),
            'resourceParameter' => $this->parameter,
            'canEditPurchase' => Gate::forUser($actor)
                ->allows('update', $purchase),
            'canDeletePurchase' => Gate::forUser($actor)
                ->allows('delete', $purchase),
        ];

        return view($this->views.'.show', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $request = request();
        $permissions = $request->attributes->get('navigation_permissions', []);
        $productType = $record->exists ? $record->product_type : $request
            ->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        $request->attributes->set('pharmacy_product_type', $productType);
        if ($record->exists) {
            $record->load([
                'lines' => fn ($query) => $query->orderBy('id'),
                'lines.productUnit:id,unit_id',
                'lines.variant.product:id,name,type,purchase_price,sale_price',
                'lines.variant.product.units' => fn ($query) => $query->orderBy('factor')
                    ->orderBy('id'),
                'lines.variant.product.units.unit:id,name,symbol',
            ]);
        }
        $hasOldLines = $request->session()->hasOldInput('lines');
        $lineInputs = $hasOldLines ? $this->oldRows('lines') : [];
        $settings = Setting::values('pharmacy', $record->getConnectionName());
        $pharmacyPurchase = $productType === 'medicine';

        $data = [
            'entry' => $record,
            'hasOldLines' => $hasOldLines,
            'lineInputs' => $lineInputs,
            'variants' => $hasOldLines ? app(ItemService::class)
                ->selectedVariants($lineInputs) : collect(),
            'itemFields' => PurchaseItemService::itemFields($settings, $productType),
            'pharmacySettings' => $settings,
            'paymentTypes' => PaymentType::orderBy('ordering')
                ->orderBy('id')
                ->get(['id', 'name']),
            'selectedSupplier' => $this->selectedOption(Supplier::query(), getValue('supplier_id', $record)),
            'pharmacyPurchase' => $pharmacyPurchase,
            'canUpdateProductPrices' => isset($permissions['pharmacy-products.update']),
            'purchaseUnits' => Unit::when(! $pharmacyPurchase, fn ($query) => $query->whereIn('symbol', ['Pcs', 'Pair', 'Box']))
                ->orderBy('name')
                ->orderBy('id')
                ->get(['id', 'name', 'symbol']),
        ];

        return $data;
    }

    public function reverse(Request $request, Purchase $purchase, PurchaseService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $purchase);

        return redirect()
            ->route($this->routePrefix().'.show', [
                'purchase' => $purchase,
                'product_type' => $purchase->product_type,
            ])
            ->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
