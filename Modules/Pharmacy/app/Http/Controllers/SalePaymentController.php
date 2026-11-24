<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Helpers\Money;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Models\Account;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Pharmacy\Http\Requests\SalePaymentRequest;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\SalePayment;
use Modules\Pharmacy\Models\StockEntry;
use Modules\Pharmacy\Services\SalePaymentService;
use Modules\TenantCore\Models\PaymentType;

class SalePaymentController extends BaseController
{
    protected string $model = SalePayment::class;

    protected string $service = SalePaymentService::class;

    protected string $title = 'Customer Collection';

    protected string $views = 'pharmacy::sale-payments';

    protected string $parameter = 'sale_payment';

    protected array $routeFilters = [
        'product_type' => 'product_type',
    ];

    protected array $defaultFilters = [
        'product_type' => 'medicine',
    ];

    public function index(SalePaymentRequest $request): View
    {
        $permissions = $request->attributes->get('navigation_permissions', []);
        $filters = $request->validated();
        $filters['product_type'] ??= $this->defaultFilters['product_type'];
        $request->attributes->set('pharmacy_product_type', $filters['product_type']);

        $query = SalePayment::where('product_type', $filters['product_type']);
        if (! empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }
        if (! empty($filters['from_date'])) {
            $query->where('payment_date', '>=', $filters['from_date']);
        }
        if (! empty($filters['to_date'])) {
            $query->where('payment_date', '<=', $filters['to_date']);
        }

        $records = $this->paginateQuery($query->orderByDesc('id'), $filters);
        $actor = $request->user('web');
        $customers = app(PharmacyCustomers::class)->findMany(
            $actor,
            $records->pluck('customer_id')->filter()->unique()->all(),
        );
        $data = [
            'records'   => $records,
            'filters'   => $filters,
            'pageTitle' => $this->title,
            'customers' => $customers,
            'canCreate' => isset($permissions['pharmacy-payments.create']),
            'canDelete' => isset($permissions['pharmacy-payments.delete']),
        ];

        return $this->listData($request, $data);
    }

    public function store(SalePaymentRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(SalePaymentRequest $request, SalePayment $salePayment): View
    {
        $permissions = $request->attributes->get('navigation_permissions', []);
        $request->attributes->set('pharmacy_product_type', $salePayment->product_type);
        $allocations = $salePayment->items()
            ->with('sale:id,number')
            ->get(['id', 'payment_id', 'sale_id', 'amount', 'discount']);
        $actor = $request->user('web');
        $customer = $salePayment->customer_id === null
            ? null
            : app(PharmacyCustomers::class)->findMany($actor, [$salePayment->customer_id])[$salePayment->customer_id] ?? null;
        $data = [
            'payment' => $salePayment,
            'pageTitle' => $salePayment->number,
            'allocations' => $allocations,
            'customer' => $customer,
            'canVoid' => $salePayment->voided_at === null && isset($permissions['pharmacy-payments.void']),
            'canDelete' => isset($permissions['pharmacy-payments.delete']),
        ];

        return view($this->views.'.show', $data);
    }

    public function reverse(Request $request, SalePayment $salePayment, SalePaymentService $service): RedirectResponse
    {
        $service->reverse($request->user('web'), $salePayment);

        return redirect()
            ->route('pharmacy.sale-payments.show', [
                'sale_payment' => $salePayment,
                'product_type' => $salePayment->product_type,
            ])
            ->with('status', 'Payment voided.');
    }

    public function invoices(SalePaymentRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $query = Sale::outstanding()
            ->where('product_type', $filters['product_type'] ?? 'medicine')
            ->where('customer_id', $filters['customer_id'] ?? 0);
        $records = $query->orderBy('entry_date')
            ->orderBy('id')
            ->paginate(50);
        $results = $records->getCollection()
            ->map(fn (StockEntry $invoice): array => [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'date' => $invoice->entry_date->format('Y-m-d'),
                'due' => Money::decimal($invoice->outstandingMinor()),
            ]);
        $data = [
            'results' => $results,
            'pagination' => [
                'more' => $records->hasMorePages(),
            ],
        ];

        return response()->json($data);
    }

    public function customers(SalePaymentRequest $request, PharmacyCustomers $customers): JsonResponse
    {
        $actor = $request->user('web');
        $filters = $request->validated();
        $eligibleIds = Sale::outstanding()
            ->where('product_type', $filters['product_type'] ?? 'medicine')
            ->select('customer_id')
            ->toBase();
        $data = $customers->search(
            $actor,
            $filters['search'] ?? '',
            false,
            (int) ($filters['page'] ?? 1),
            $eligibleIds,
        );

        return response()->json($data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $request = request();
        $permissions = $request->attributes->get('navigation_permissions', []);
        $productType = $record->exists ? $record->product_type : $request->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        $request->attributes->set('pharmacy_product_type', $productType);
        $customerId = getValue('customer_id', $record, $request->query('customer_id'));
        $customerId = is_scalar($customerId) && ctype_digit((string) $customerId) ? (int) $customerId : null;
        $allocationInputs = $this->oldRows('allocations', [], 200);
        $invoiceIds = collect($allocationInputs)
            ->pluck('sale_id')
            ->filter(fn ($id): bool => is_scalar($id) && ctype_digit((string) $id))
            ->all();
        $invoiceQuery = Sale::outstanding()
            ->where('product_type', $productType)
            ->where('customer_id', $customerId ?? 0);
        $customer = $customerId && (clone $invoiceQuery)->exists()
            ? (app(PharmacyCustomers::class)->findMany($actor, [$customerId])[$customerId] ?? null)
            : null;
        $invoices = $invoiceIds === [] ? collect() : $invoiceQuery
            ->whereIn('id', $invoiceIds)
            ->get()
            ->keyBy('id');
        $data = [
            'productType' => $productType,
            'selectedCustomer' => $customer,
            'allocationInputs' => $allocationInputs,
            'invoices' => $invoices,
            'paymentTypes' => PaymentType::orderBy('ordering')
                ->orderBy('id')
                ->get(['id', 'name']),
            'canDiscount' => isset($permissions['pharmacy-payments.discount']),
        ];

        return $data;
    }
}
