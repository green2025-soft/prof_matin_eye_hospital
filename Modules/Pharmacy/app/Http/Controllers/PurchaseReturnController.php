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
        $permissions = $request->attributes->get('navigation_permissions', []);

        $data = [
            'resourceParameter' => $this->parameter,
            'canCreate' => isset($permissions['pharmacy-purchase-returns.create']),
            'canUpdateReturns' => isset($permissions['pharmacy-purchase-returns.update']),
            'canDeleteReturns' => isset($permissions['pharmacy-purchase-returns.delete']),
        ];

        return $view->with($data);
    }

    public function store(PurchaseReturnRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, PurchaseReturn $purchaseReturn, PurchaseReturnService $entries): View
    {
        $request->attributes->set('pharmacy_product_type', $purchaseReturn->product_type);

        $data = $entries->invoiceData($request->user('web'), $purchaseReturn) + [
            'routePrefix' => $this->routePrefix(),
            'resourceParameter' => $this->parameter,
        ];

        return view($this->views.'.show', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $request = request();
        $productType = $record->exists ? $record->product_type : $request
            ->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        $request->attributes->set('pharmacy_product_type', $productType);
        $sourceId = $record->exists ? $record->purchase_id : getValue('purchase_id', null, $request->query('purchase_id'));
        $sourceId = is_scalar($sourceId) && ctype_digit((string) $sourceId) ? $sourceId : null;
        $source = $this->selectedOption(
            Purchase::where('product_type', $productType)
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

        $data = [
            'entry' => $record,
            'source' => $source,
            'hasOldLines' => $request->session()->hasOldInput('lines'),
            'lineInputs' => $this->oldRows('lines'),
            'returnedAmounts' => $source === null ? collect() : app(PurchaseReturnService::class)
                ->returnedAmounts($source, $record),
            'paymentTypes' => PaymentType::orderBy('ordering')
                ->orderBy('id')
                ->get(['id', 'name']),
        ];

        return $data;
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
