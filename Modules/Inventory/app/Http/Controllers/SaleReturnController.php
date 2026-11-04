<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Hospital\Contracts\InventoryCustomers;
use Modules\Inventory\Http\Requests\SaleReturnRequest;
use Modules\Inventory\Models\Sale;
use Modules\Inventory\Models\SaleReturn;
use Modules\Inventory\Services\InventoryAccess;
use Modules\Inventory\Services\SaleReturnService;
use Modules\TenantCore\Models\PaymentType;

class SaleReturnController extends BaseController
{
    protected string $model = SaleReturn::class;

    protected string $service = SaleReturnService::class;

    protected string $title = 'Sales return';

    protected string $views = 'inventory::sale-returns';

    protected string $parameter = 'sale_return';

    protected array $routeFilters = [
        'product_type' => 'product_type',
    ];

    protected array $defaultFilters = [
        'product_type' => 'medicine',
    ];

    public function index(ListRequest $request): View
    {
        $view = $this->listData($request);
        $request->attributes->set('inventory_product_type', $view->getData()['filters']['product_type']);
        $actor = $request->user('web');
        $access = app(InventoryAccess::class);
        $customerIds = $view->getData()['records']->pluck('customer_id')
            ->filter()
            ->unique()
            ->all();

        return $view->with([
            'resourceParameter' => $this->parameter,
            'canCreate' => $access->allows($actor, 'inventory-sale-returns.create'),
            'canUpdateReturns' => $access->allows($actor, 'inventory-sale-returns.update'),
            'canDeleteReturns' => $access->allows($actor, 'inventory-sale-returns.delete'),
            'customers' => $customerIds === [] ? [] : app(InventoryCustomers::class)
                ->findMany($actor, $customerIds),
        ]);
    }

    public function store(SaleReturnRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, SaleReturn $saleReturn, SaleReturnService $entries): View
    {
        $request->attributes->set('inventory_product_type', $saleReturn->product_type);

        return view(
            $this->views.'.show',
            $entries->invoiceData($request->user('web'), $saleReturn) + [
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
        request()->attributes->set('inventory_product_type', $productType);
        $sourceId = $record->exists ? $record->sale_id : getValue('sale_id', null, request('sale_id'));
        $sourceId = is_scalar($sourceId) && ctype_digit((string) $sourceId) ? $sourceId : null;
        $source = $this->selectedOption(
            Sale::query()
                ->where('product_type', $productType)
                ->whereNull('voided_at')
                ->whereNotNull('posted_at')
                ->with([
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
        $customerId = $source?->customer_id;
        $customers = $customerId === null ? [] : app(InventoryCustomers::class)
            ->findMany($actor, [$customerId]);

        return [
            'entry' => $record,
            'source' => $source,
            'sourceCustomer' => $customers[$customerId] ?? null,
            'hasOldLines' => array_key_exists(
                'lines',
                request()
                    ->session()
                    ->getOldInput()
            ),
            'lineInputs' => $this->oldRows('lines'),
            'savedLines' => $record->exists ? $record->lines->keyBy('sale_item_id') : collect(),
            'returnQuantities' => $record->exists ? $record->lines()
                ->pluck('base_quantity', 'sale_item_id') : collect(),
            'returnedAmounts' => $source === null ? collect() : app(SaleReturnService::class)
                ->returnedAmounts($source, $record),
            'paymentTypes' => PaymentType::query()
                ->orderBy('ordering')
                ->orderBy('id')
                ->get(['id', 'name']),
        ];
    }

    public function reverse(Request $request, SaleReturn $saleReturn, SaleReturnService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $saleReturn);

        return redirect()
            ->route($this->routePrefix().'.show', [
                $this->parameter => $saleReturn,
                'product_type' => $saleReturn->product_type,
            ])
            ->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
