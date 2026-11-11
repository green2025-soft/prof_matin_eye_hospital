<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Pharmacy\Http\Requests\SaleReturnRequest;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\SaleReturn;
use Modules\Pharmacy\Services\SaleReturnService;
use Modules\TenantCore\Models\PaymentType;

class SaleReturnController extends BaseController
{
    protected string $model = SaleReturn::class;

    protected string $service = SaleReturnService::class;

    protected string $title = 'Sales return';

    protected string $views = 'pharmacy::sale-returns';

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
        $request->attributes->set('pharmacy_product_type', $view->getData()['filters']['product_type']);
        $actor = $request->user('web');
        $permissions = $request->attributes->get('navigation_permissions', []);
        $customerIds = $view->getData()['records']->pluck('customer_id')
            ->filter()
            ->unique()
            ->all();

        $data = [
            'resourceParameter' => $this->parameter,
            'canCreate' => isset($permissions['pharmacy-sale-returns.create']),
            'canUpdateReturns' => isset($permissions['pharmacy-sale-returns.update']),
            'canDeleteReturns' => isset($permissions['pharmacy-sale-returns.delete']),
            'customers' => $customerIds === [] ? [] : app(PharmacyCustomers::class)
                ->findMany($actor, $customerIds),
        ];

        return $view->with($data);
    }

    public function store(SaleReturnRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, SaleReturn $saleReturn, SaleReturnService $entries): View
    {
        $request->attributes->set('pharmacy_product_type', $saleReturn->product_type);

        $data = $entries->invoiceData($request->user('web'), $saleReturn) + [
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
        $sourceId = $record->exists ? $record->sale_id : getValue('sale_id', null, $request->query('sale_id'));
        $sourceId = is_scalar($sourceId) && ctype_digit((string) $sourceId) ? $sourceId : null;
        $source = $this->selectedOption(
            Sale::where('product_type', $productType)
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
        $customers = $customerId === null ? [] : app(PharmacyCustomers::class)
            ->findMany($actor, [$customerId]);

        $data = [
            'entry' => $record,
            'source' => $source,
            'sourceCustomer' => $customers[$customerId] ?? null,
            'hasOldLines' => $request->session()->hasOldInput('lines'),
            'lineInputs' => $this->oldRows('lines'),
            'returnedAmounts' => $source === null ? collect() : app(SaleReturnService::class)
                ->returnedAmounts($source, $record),
            'paymentTypes' => PaymentType::orderBy('ordering')
                ->orderBy('id')
                ->get(['id', 'name']),
        ];

        return $data;
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
