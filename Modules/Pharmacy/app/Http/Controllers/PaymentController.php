<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Helpers\Money;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Models\Account;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Pharmacy\Http\Requests\PaymentRequest;
use Modules\Pharmacy\Models\Payment;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\StockEntry;
use Modules\Pharmacy\Models\Supplier;
use Modules\Pharmacy\Services\PaymentService;
use Modules\TenantCore\Models\PaymentType;

class PaymentController extends BaseController
{
    protected string $model     = Payment::class;

    protected string $title     = 'Pharmacy payment';

    protected string $views     = 'pharmacy::payments';

    protected string $parameter = 'payment';

    protected string $service   = PaymentService::class;

    protected array $routeFilters = [
        'product_type'  => 'product_type',
        'direction'     => 'direction',
    ];

    protected array $defaultFilters = [
        'product_type'  => 'medicine',
        'direction'     => 'customer',
    ];

    public function index(PaymentRequest $request): View
    {
        $permissions    = $request->attributes->get('navigation_permissions', []);
        $filters        = $request->validated();
        $filters['product_type'] ??= $this->defaultFilters['product_type'];
        $filters['direction'] ??= $this->defaultFilters['direction'];
        $request->attributes->set('pharmacy_product_type', $filters['product_type']);
        $request->attributes->set('pharmacy_payment_direction', $filters['direction']);

        $query      = Payment::where('direction', $filters['direction'])
            ->where('product_type', $filters['product_type']);

        if ($filters['direction'] === 'customer' && ! empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }
        if ($filters['direction'] === 'supplier' && ! empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }
        if (! empty($filters['from_date'])) {
            $query->where('payment_date', '>=', $filters['from_date']);
        }
        if (! empty($filters['to_date'])) {
            $query->where('payment_date', '<=', $filters['to_date']);
        }

        $records        = $this->paginateQuery($query->orderByDesc('id'), $filters);
        $actor          = $request->user('web');
        $customerIds    = $records->pluck('customer_id')->filter()->unique()->all();
        $customers = app(PharmacyCustomers::class)->findMany($actor, $customerIds);
        $suppliers = Supplier::whereIn('id', $records->pluck('supplier_id')->filter())->pluck('name', 'id');

        $data = [
            'records' => $records,
            'filters' => $filters,
            'pageTitle' => ucfirst($filters['direction']).' payments',
            'customers' => $customers,
            'suppliers' => $suppliers,
            'canCreate' => isset($permissions['pharmacy-payments.create']),
        ];

        return $this->listData($request, $data);
    }

    public function store(PaymentRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(PaymentRequest $request, Payment $payment): View
    {
        $permissions = $request->attributes->get('navigation_permissions', []);
        $request->attributes->set('pharmacy_product_type', $payment->product_type);
        $request->attributes->set('pharmacy_payment_direction', $payment->direction);

        if ($payment->direction === 'customer') {
            $allocations = $payment->saleAllocations()
                ->with('sale:id,number')
                ->get(['id', 'sale_id', 'amount', 'discount']);
        } else {
            $allocations = $payment->purchaseAllocations()
                ->with('purchase:id,number')
                ->get(['id', 'purchase_id', 'amount', 'discount']);
        }
        $actor = $request->user('web');
        $customer = $payment->customer_id
            ? (app(PharmacyCustomers::class)->findMany($actor, [$payment->customer_id])[$payment->customer_id] ?? null)
            : null;
        $supplier = $payment->supplier_id ? Supplier::find($payment->supplier_id, ['id', 'name']) : null;

        $data = [
            'payment' => $payment,
            'pageTitle' => $payment->number,
            'allocations' => $allocations,
            'customer' => $customer,
            'supplier' => $supplier,
            'canVoid' => $payment->voided_at === null && isset($permissions['pharmacy-payments.void']),
        ];

        return view('pharmacy::payments.show', $data);
    }

    public function reverse(Request $request, Payment $payment, PaymentService $service): RedirectResponse
    {
        $service->reverse($request->user('web'), $payment);

        return redirect()
            ->route('pharmacy.payments.show', [
                'payment' => $payment,
                'product_type' => $payment->product_type,
                'direction' => $payment->direction,
            ])
            ->with('status', 'Payment voided.');
    }

    public function invoices(PaymentRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $direction = $filters['direction'] ?? 'customer';
        $productType = $filters['product_type'] ?? 'medicine';
        $query = $direction === 'customer'
            ? Sale::outstanding()
                ->where('product_type', $productType)
                ->where('customer_id', $filters['customer_id'] ?? 0)
            : Purchase::outstanding()
                ->where('product_type', $productType)
                ->where('supplier_id', $filters['supplier_id'] ?? 0);
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

    public function customers(PaymentRequest $request, PharmacyCustomers $customers): JsonResponse
    {
        $actor = $request->user('web');
        $filters = $request->validated();
        $eligibleIds = Sale::outstanding()
            ->where('product_type', $filters['product_type'] ?? 'medicine')
            ->select('customer_id')
            ->toBase();

        $data = $customers->search($actor, $filters['search'] ?? '', false, (int) ($filters['page'] ?? 1), $eligibleIds);

        return response()->json($data);
    }

    public function suppliers(PaymentRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $eligibleIds = Purchase::outstanding()
            ->where('product_type', $filters['product_type'] ?? 'medicine')
            ->select('supplier_id');
        $query = Supplier::whereIn('id', $eligibleIds);
        if (filled($filters['search'] ?? null)) {
            $term = '%'.addcslashes($filters['search'], '\\%_').'%';
            $query->where(function (Builder $query) use ($term): void {
                $query->whereLike('name', $term)
                    ->orWhereLike('contact_no', $term);
            });
        }
        $records = $query->orderBy('name')
            ->orderBy('id')
            ->simplePaginate(20, ['id', 'name'], 'page', (int) ($filters['page'] ?? 1));

        $data = [
            'results' => $records->items(),
            'pagination' => [
                'more' => $records->hasMorePages(),
            ],
        ];

        return response()->json($data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $request = request();
        $permissions = $request->attributes->get('navigation_permissions', []);
        $productType = $record->exists ? $record->product_type : $request->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        $request->attributes->set('pharmacy_product_type', $productType);
        $direction = $record->exists ? $record->direction : $request->query('direction', 'customer');
        $direction = in_array($direction, ['customer', 'supplier'], true) ? $direction : 'customer';
        $request->attributes->set('pharmacy_payment_direction', $direction);
        $customerId = getValue('customer_id', $record, $request->query('customer_id'));
        $customerId = is_scalar($customerId) && ctype_digit((string) $customerId) ? (int) $customerId : null;
        $customer = null;
        $supplier = null;
        $allocationInputs = $this->oldRows('allocations', [], 200);
        $invoiceField = $direction === 'customer' ? 'sale_id' : 'purchase_id';
        $invoiceIds = collect($allocationInputs)
            ->pluck($invoiceField)
            ->filter(fn ($id): bool => is_scalar($id) && ctype_digit((string) $id))
            ->all();

        if ($direction === 'customer') {
            $query = Sale::outstanding()
                ->where('product_type', $productType)
                ->where('customer_id', $customerId ?? 0);
            $customer = $customerId && (clone $query)->exists()
                ? (app(PharmacyCustomers::class)
                    ->findMany($actor, [$customerId])[$customerId] ?? null) : null;
            $invoices = $invoiceIds === [] ? collect() : $query->whereIn('id', $invoiceIds)
                ->get()
                ->keyBy('id');
        } else {
            $eligibleSuppliers = Purchase::outstanding()
                ->where('product_type', $productType)
                ->select('supplier_id');
            $supplier = $this->selectedOption(
                Supplier::whereIn('id', $eligibleSuppliers),
                getValue('supplier_id', $record, $request->query('supplier_id')),
            );
            $invoices = $invoiceIds === [] ? collect() : Purchase::outstanding()
                ->where('product_type', $productType)
                ->where('supplier_id', $supplier?->id ?? 0)
                ->whereIn('id', $invoiceIds)
                ->get()
                ->keyBy('id');
        }

        $paymentTypes = PaymentType::orderBy('ordering')
            ->orderBy('id')
            ->get(['id', 'name']);

        $data = [
            'direction' => $direction,
            'selectedCustomer' => $customer,
            'selectedSupplier' => $supplier,
            'allocationInputs' => $allocationInputs,
            'invoices' => $invoices,
            'paymentTypes' => $paymentTypes,
            'canDiscount' => isset($permissions['pharmacy-payments.discount']),
        ];

        return $data;
    }
}
