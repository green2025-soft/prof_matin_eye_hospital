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
use Modules\Pharmacy\Services\PharmacyAccess;
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
        $actor = $request->user('web');
        $access = app(PharmacyAccess::class);
        $view = $this->listData($request);
        $request->attributes->set('pharmacy_product_type', $view->getData()['filters']['product_type']);

        return $view->with([
            'resourceParameter' => $this->parameter,
            'canCreate' => $access->allows($actor, 'pharmacy-purchases.create'),
            'canUpdatePurchases' => $access->allows($actor, 'pharmacy-purchases.update'),
            'canDeletePurchases' => $access->allows($actor, 'pharmacy-purchases.delete'),
        ]);
    }

    public function store(PurchaseRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, Purchase $purchase, PurchaseService $entries): View
    {
        $actor = $request->user('web');
        $request->attributes->set('pharmacy_product_type', $purchase->product_type);

        return view(
            $this->views.'.show',
            $entries->invoiceData($actor, $purchase) + [
                'routePrefix' => $this->routePrefix(),
                'resourceParameter' => $this->parameter,
                'canEditPurchase' => Gate::forUser($actor)
                    ->allows('update', $purchase),
                'canDeletePurchase' => Gate::forUser($actor)
                    ->allows('delete', $purchase),
            ]
        );
    }

    protected function formData(Account $actor, Model $record): array
    {
        $productType = $record->exists ? $record->product_type : request()
            ->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        request()->attributes->set('pharmacy_product_type', $productType);
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
        $savedLines = $record->exists ? $record->lines->keyBy('id') : collect();
        $hasOldLines = array_key_exists(
            'lines',
            request()
                ->session()
                ->getOldInput()
        );
        $lineInputs = $hasOldLines ? $this->oldRows('lines') : [];
        $settings = Setting::values('pharmacy', $record->getConnectionName());
        $pharmacyPurchase = $productType === 'medicine';
        $defaultVatMode = $settings['vat_enabled'] ? $settings['vat_mode'] : ($pharmacyPurchase ? 'exclusive' : 'none');
        $vatMode = getValue('vat_mode', null, $defaultVatMode);
        $vatMode = in_array($vatMode, ['none', 'inclusive', 'exclusive'], true) ? $vatMode : 'exclusive';

        return [
            'entry' => $record,
            'savedLines' => $savedLines,
            'hasOldLines' => $hasOldLines,
            'lineInputs' => $lineInputs,
            'variants' => $hasOldLines ? app(ItemService::class)
                ->selectedVariants($lineInputs) : collect(),
            'itemFields' => PurchaseItemService::itemFields($settings, $productType),
            'paymentTypes' => PaymentType::query()
                ->orderBy('ordering')
                ->orderBy('id')
                ->get(['id', 'name']),
            'selectedSupplier' => $this->selectedOption(Supplier::query(), getValue('supplier_id', $record)),
            'pharmacyPurchase' => $pharmacyPurchase,
            'canUpdateProductPrices' => app(PharmacyAccess::class)
                ->allows($actor, 'pharmacy-products.update'),
            'purchaseUnits' => Unit::query()
                ->when(! $pharmacyPurchase, fn ($query) => $query->whereIn('symbol', ['Pcs', 'Pair', 'Box']))
                ->orderBy('name')
                ->orderBy('id')
                ->get(['id', 'name', 'symbol']),
            'purchaseVatMode' => $vatMode,
            'purchaseVatPercent' => $settings['vat_enabled'] ? $settings['vat_percent'] : '0.00',
        ];
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
