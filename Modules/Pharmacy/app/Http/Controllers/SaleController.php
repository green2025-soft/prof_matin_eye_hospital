<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Pharmacy\Http\Requests\SaleRequest;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Services\ItemService;
use Modules\Pharmacy\Services\SaleService;
use Modules\Pharmacy\Services\StockService;
use Modules\TenantCore\Models\PaymentType;

class SaleController extends BaseController
{
    protected string $model = Sale::class;

    protected string $service = SaleService::class;

    protected string $title = 'Sale';

    protected string $views = 'pharmacy::sales';

    protected string $parameter = 'sale';

    protected array $routeFilters = [
        'product_type' => 'product_type',
    ];

    protected array $defaultFilters = [
        'product_type' => 'medicine',
    ];

    public function index(ListRequest $request): View
    {
        $view           = $this->listData($request);
        $request->attributes->set('pharmacy_product_type', $view->getData()['filters']['product_type']);
        $actor          = $request->user('web');
        $permissions    = $request->attributes->get('navigation_permissions', []);
        $customerIds    = $view->getData()['records']->pluck('customer_id')->filter()->unique()->all();

        $data = [
            'resourceParameter' => $this->parameter,
            'canCreate'         => isset($permissions['pharmacy-sales.create']),
            'canUpdateSales'    => isset($permissions['pharmacy-sales.update']),
            'canDeleteSales'    => isset($permissions['pharmacy-sales.delete']),
            'customers'         => $customerIds === [] ? [] : app(PharmacyCustomers::class)->findMany($actor, $customerIds),
        ];

        return $view->with($data);
    }

    public function store(SaleRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, Sale $sale, SaleService $entries): View
    {
        $request->attributes->set('pharmacy_product_type', $sale->product_type);
        $settings = Setting::values('pharmacy', $sale->getConnectionName());

        $data = $entries->invoiceData($request->user('web'), $sale) + [
            'routePrefix' => $this->routePrefix(),
            'resourceParameter' => $this->parameter,
            'invoicePaper' => $settings['sale_invoice_paper'],
            'salePosWidth' => $settings['sale_pos_width_mm'],
        ];

        return view($this->views.'.show', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $request        = request();
        $productType    = $record->exists ? $record->product_type : $request->query('product_type', 'medicine');
        $productType    = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        $request->attributes->set('pharmacy_product_type', $productType);
        $settings       = Setting::values('pharmacy', $record->getConnectionName());
        if ($record->exists) {
            $record->load([
                'lines' => fn ($query) => $query->orderBy('id'),
                'lines.variant.product:id,name,type,purchase_price,sale_price',
                'lines.variant.product.units' => fn ($query) => $query->orderBy('factor')->orderBy('id'),
                'lines.variant.product.units.unit:id,name,symbol',
            ]);
        }
        $savedLines     = $record->exists ? $record->lines : collect();
        $hasOldLines    = $request->session()->hasOldInput('lines');
        $lineInputs     = $hasOldLines ? $this->oldRows('lines') : [];
        $fields         = SaleService::itemFields($settings, $productType);
        $variants       = $hasOldLines ? app(ItemService::class)->selectedVariants($lineInputs) : $savedLines->pluck('variant')->keyBy('id');
        // Show current stock consistently with the picker; posting also checks the invoice date.
        $stockDate      = now()->toDateString();
        $stock          = app(StockService::class);
        $customerId     = getValue('customer_id', $record);
        $customerId     = is_scalar($customerId) && ctype_digit((string) $customerId) ? (int) $customerId : null;
        $customers      = $customerId === null ? [] : app(PharmacyCustomers::class)->findMany($actor, [$customerId]);
        $permissions    = $request->attributes->get('navigation_permissions', []);

        $data = [
            'entry'             => $record,
            'lineInputs'        => $lineInputs,
            'variants'          => $variants,
            'savedLines'        => $savedLines,
            'hasOldLines'       => $hasOldLines,
            'pharmacySettings'  => $settings,
            'itemFields'        => $fields,
            'stockQuantities'   => $stock->availableQuantities($productType,$stockDate,$variants->keys()->all()),
            'revisionStock'     => $record->exists ? $stock->saleRevisionQuantities($record, $stockDate) : collect(),
            'paymentTypes'      => PaymentType::orderBy('ordering')->orderBy('id')->get(['id', 'name']),
            'selectedCustomer'  => $customers[$customerId] ?? null,
            'canCreateCustomer' => isset($permissions['pharmacy-customers.create'], $permissions['pharmacy-sales.create']),
        ];

        return $data;
    }

    public function reverse(Request $request, Sale $sale, SaleService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $sale);

        return redirect()->route($this->routePrefix().'.show', [
                $this->parameter    => $sale,
                'product_type'  => $sale->product_type,
            ])
            ->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
