<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Http\Requests\PurchaseReturnRequest;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\PurchaseReturn;
use Modules\Pharmacy\Services\PharmacyAccess;
use Modules\Pharmacy\Services\PurchaseReturnService;
use Modules\TenantCore\Models\PaymentType;

class PurchaseReturnController extends BaseController
{
    protected string $model = PurchaseReturn::class;

    protected string $service = PurchaseReturnService::class;

    protected string $title = 'Purchase return';

    protected string $views = 'pharmacy::purchase-returns';

    protected string $parameter = 'purchase_return';

    protected array $routeFilters = [
        'product_type' => 'product_type',
    ];

    protected array $defaultFilters = [
        'product_type' => 'medicine',
    ];

    public function index(ListRequest $request): View
    {
        $view = $this->listData($request);
        $request->attributes->set('pharmacy_product_type', $view->getData()['filters']['product_type']);
        $actor = $request->user('web');
        $access = app(PharmacyAccess::class);

        return $view->with([
            'resourceParameter' => $this->parameter,
            'canCreate' => $access->allows($actor, 'pharmacy-purchase-returns.create'),
            'canUpdateReturns' => $access->allows($actor, 'pharmacy-purchase-returns.update'),
            'canDeleteReturns' => $access->allows($actor, 'pharmacy-purchase-returns.delete'),
        ]);
    }

    public function store(PurchaseReturnRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, PurchaseReturn $purchaseReturn, PurchaseReturnService $entries): View
    {
        $request->attributes->set('pharmacy_product_type', $purchaseReturn->product_type);

        return view(
            $this->views.'.show',
            $entries->invoiceData($request->user('web'), $purchaseReturn) + [
                'routePrefix' => $this->routePrefix(),
                'resourceParameter' => $this->parameter,
            ]
        );
    }

    protected function formData(Account $actor, Model $record): array
    {
        $productType = $record->exists ? $record->product_type : request()
            ->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        request()->attributes->set('pharmacy_product_type', $productType);
        $sourceId = $record->exists ? $record->purchase_id : getValue('purchase_id', null, request('purchase_id'));
        $sourceId = is_scalar($sourceId) && ctype_digit((string) $sourceId) ? $sourceId : null;
        $source = $this->selectedOption(
            Purchase::query()
                ->where('product_type', $productType)
                ->whereNull('voided_at')
                ->whereNotNull('posted_at')
                ->with([
                    'supplier:id,name,contact_no,address',
                    'lines' => fn ($query) => $query->orderBy('id'),
                    'lines.variant.product.units.unit:id,name,symbol',
                    'lines.productUnit.unit:id,name,symbol',
                ]),
            $sourceId,
            ['*'],
        );
        if ($record->exists) {
            $record->load([
                'lines' => fn ($query) => $query->orderBy('id'),
            ]);
        }

        return [
            'entry' => $record,
            'source' => $source,
            'hasOldLines' => array_key_exists(
                'lines',
                request()
                    ->session()
                    ->getOldInput()
            ),
            'lineInputs' => $this->oldRows('lines'),
            'savedLines' => $record->exists ? $record->lines->keyBy('purchase_item_id') : collect(),
            'returnQuantities' => $record->exists ? $record->lines()
                ->pluck('base_quantity', 'purchase_item_id') : collect(),
            'returnedAmounts' => $source === null ? collect() : app(PurchaseReturnService::class)
                ->returnedAmounts($source, $record),
            'paymentTypes' => PaymentType::query()
                ->orderBy('ordering')
                ->orderBy('id')
                ->get(['id', 'name']),
        ];
    }

    public function reverse(Request $request, PurchaseReturn $purchaseReturn, PurchaseReturnService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $purchaseReturn);

        return redirect()
            ->route($this->routePrefix().'.show', [
                $this->parameter => $purchaseReturn,
                'product_type' => $purchaseReturn->product_type,
            ])
            ->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
