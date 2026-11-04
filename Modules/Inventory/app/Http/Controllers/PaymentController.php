<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Core\Helpers\Money;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Models\Account;
use Modules\Hospital\Contracts\InventoryCustomers;
use Modules\Inventory\Http\Requests\PaymentRequest;
use Modules\Inventory\Models\Payment;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\PurchasePaymentAllocation;
use Modules\Inventory\Models\Sale;
use Modules\Inventory\Models\SalePaymentAllocation;
use Modules\Inventory\Models\StockEntry;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Services\InventoryAccess;
use Modules\Inventory\Services\PaymentService;
use Modules\TenantCore\Models\PaymentType;

class PaymentController extends BaseController
{
    protected string $model = Payment::class;

    protected string $title = 'Inventory payment';

    protected string $views = 'inventory::payments';

    protected string $parameter = 'payment';

    protected string $service = PaymentService::class;

    protected array $routeFilters = [
        'product_type' => 'product_type',
        'direction' => 'direction',
    ];

    protected array $defaultFilters = [
        'product_type' => 'medicine',
        'direction' => 'customer',
    ];

    public function index(PaymentRequest $request): View
    {
        $filters = array_filter($request->validated(), fn ($value): bool => $value !== null && $value !== '') + $this->defaultFilters;
        $request->attributes->set('inventory_product_type', $filters['product_type']);
        $request->attributes->set('inventory_payment_direction', $filters['direction']);
        $query = Payment::query()
            ->where('direction', $filters['direction'])
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
        $records = $this->paginateQuery($query->orderByDesc('id'), $filters);
        $actor = $request->user('web');

        return $this->listData($request, [
            'records' => $records,
            'filters' => $filters,
            'pageTitle' => $filters['direction'] === 'customer' ? 'Customer payments' : 'Supplier payments',
            'customers' => app(InventoryCustomers::class)
                ->findMany(
                    $actor,
                    $records->pluck('customer_id')
                        ->filter()
                        ->unique()
                        ->all()
                ),
            'suppliers' => Supplier::query()
                ->whereIn(
                    'id',
                    $records->pluck('supplier_id')
                        ->filter()
                )
                ->pluck('name', 'id'),
            'canCreate' => app(InventoryAccess::class)
                ->allows($actor, 'inventory-payments.create'),
        ]);
    }

    public function store(PaymentRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(PaymentRequest $request, Payment $payment): View
    {
        abort_unless($payment->matchesBranchContext(), 404);
        $request->attributes->set('inventory_product_type', $payment->product_type);
        $request->attributes->set('inventory_payment_direction', $payment->direction);
        if ($payment->direction === 'customer') {
            $allocations = $payment->saleAllocations()
                ->with('sale:id,number')
                ->get()
                ->map(
                    fn (SalePaymentAllocation $allocation): array => [
                        'number' => $allocation->sale?->number,
                        'amount' => $allocation->amount,
                        'discount' => $allocation->discount,
                    ]
                );
        } else {
            $allocations = $payment->purchaseAllocations()
                ->with('purchase:id,number')
                ->get()
                ->map(
                    fn (PurchasePaymentAllocation $allocation): array => [
                        'number' => $allocation->purchase?->number,
                        'amount' => $allocation->amount,
                        'discount' => $allocation->discount,
                    ]
                );
        }
        $actor = $request->user('web');

        return view('inventory::payments.show', [
            'payment' => $payment,
            'pageTitle' => $payment->number,
            'allocations' => $allocations,
            'customer' => $payment->customer_id ? (app(InventoryCustomers::class)
                ->findMany($actor, [$payment->customer_id])[$payment->customer_id] ?? null) : null,
            'supplier' => $payment->supplier_id ? Supplier::query()
                ->find($payment->supplier_id, ['id', 'name']) : null,
            'canVoid' => $payment->voided_at === null && app(InventoryAccess::class)
                ->allows($actor, 'inventory-payments.void'),
        ]);
    }

    public function reverse(Request $request, Payment $payment, PaymentService $service): RedirectResponse
    {
        $service->reverse($request->user('web'), $payment);

        return redirect()
            ->route('inventory.payments.show', [
                'payment' => $payment,
                'product_type' => $payment->product_type,
                'direction' => $payment->direction,
            ])
            ->with('status', 'Payment voided.');
    }

    public function invoices(PaymentRequest $request): JsonResponse
    {
        app(InventoryAccess::class)
            ->authorize($request->user('web'), 'inventory-payments.create');
        $filters = $request->validated();
        $direction = $filters['direction'] ?? 'customer';
        $productType = $filters['product_type'] ?? 'medicine';
        $query = $direction === 'customer'
            ? Sale::query()
                ->outstanding()
                ->where('product_type', $productType)
                ->where('customer_id', $filters['customer_id'] ?? 0)
            : Purchase::query()
                ->outstanding()
                ->where('product_type', $productType)
                ->where('supplier_id', $filters['supplier_id'] ?? 0);
        $records = $query->orderBy('entry_date')
            ->orderBy('id')
            ->paginate(50);

        return response()
            ->json([
                'results' => $records->getCollection()
                    ->map(
                        fn (StockEntry $invoice): array => [
                            'id' => $invoice->id,
                            'number' => $invoice->number,
                            'date' => $invoice->entry_date->format('Y-m-d'),
                            'due' => Money::decimal($invoice->outstandingMinor()),
                        ]
                    ),
                'pagination' => [
                    'more' => $records->hasMorePages(),
                ],
            ]);
    }

    public function customers(Request $request, InventoryCustomers $customers): JsonResponse
    {
        $access = app(InventoryAccess::class);
        $actor = $request->user('web');
        abort_unless($access->allows($actor, 'inventory-payments.create') || $access->allows($actor, 'inventory-reports.view'), 403);
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:80'],
            'page' => ['nullable', 'integer', 'min:1'],
            'product_type' => ['nullable', Rule::in(['medicine', 'optic'])],
        ]);
        $eligibleIds = Sale::query()
            ->outstanding()
            ->where('product_type', $filters['product_type'] ?? 'medicine')
            ->select('customer_id')
            ->toBase();

        return response()
            ->json($customers->search($actor, $filters['search'] ?? '', false, (int) ($filters['page'] ?? 1), $eligibleIds));
    }

    public function suppliers(Request $request): JsonResponse
    {
        $access = app(InventoryAccess::class);
        $actor = $request->user('web');
        abort_unless($access->allows($actor, 'inventory-payments.create') || $access->allows($actor, 'inventory-reports.view'), 403);
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:80'],
            'page' => ['nullable', 'integer', 'min:1'],
            'product_type' => ['nullable', Rule::in(['medicine', 'optic'])],
        ]);
        $eligibleIds = Purchase::query()
            ->outstanding()
            ->where('product_type', $filters['product_type'] ?? 'medicine')
            ->select('supplier_id');
        $query = Supplier::query()
            ->whereIn('id', $eligibleIds);
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

        return response()
            ->json([
                'results' => $records->items(),
                'pagination' => [
                    'more' => $records->hasMorePages(),
                ],
            ]);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $productType = $record->exists ? $record->product_type : request()
            ->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        request()->attributes->set('inventory_product_type', $productType);
        $direction = $record->exists ? $record->direction : request()
            ->query('direction', 'customer');
        $direction = in_array($direction, ['customer', 'supplier'], true) ? $direction : 'customer';
        request()->attributes->set('inventory_payment_direction', $direction);
        $customerId = getValue(
            'customer_id',
            $record,
            request()
                ->query('customer_id')
        );
        $customerId = is_scalar($customerId) && ctype_digit((string) $customerId) ? (int) $customerId : null;
        $customer = null;
        $supplier = null;
        $allocationInputs = $this->oldRows('allocations', [], 200);
        if ($direction === 'customer') {
            $query = Sale::query()
                ->outstanding()
                ->where('product_type', $productType)
                ->where('customer_id', $customerId ?? 0);
            $customer = $customerId && (clone $query)->exists()
                ? (app(InventoryCustomers::class)
                    ->findMany($actor, [$customerId])[$customerId] ?? null) : null;
            $ids = collect($allocationInputs)
                ->pluck('sale_id')
                ->filter(fn ($id): bool => is_scalar($id) && ctype_digit((string) $id))
                ->all();
            $sales = $ids === [] ? collect() : $query->whereIn('id', $ids)
                ->get()
                ->keyBy('id');
            foreach ($allocationInputs as $index => $allocation) {
                $saleId = $allocation['sale_id'] ?? null;
                $allocationInputs[$index]['invoice'] = is_scalar($saleId) ? $sales->get($saleId) : null;
            }
        } else {
            $supplier = $this->selectedOption(
                Supplier::query()
                    ->whereIn(
                        'id',
                        Purchase::query()
                            ->outstanding()
                            ->where('product_type', $productType)
                            ->select('supplier_id')
                    ),
                getValue(
                    'supplier_id',
                    $record,
                    request()
                        ->query('supplier_id')
                ),
            );
            $ids = collect($allocationInputs)
                ->pluck('purchase_id')
                ->filter(fn ($id): bool => is_scalar($id) && ctype_digit((string) $id))
                ->all();
            $purchases = $ids === [] ? collect() : Purchase::query()
                ->outstanding()
                ->where('product_type', $productType)
                ->where('supplier_id', $supplier?->id ?? 0)
                ->whereIn('id', $ids)
                ->get()
                ->keyBy('id');
            foreach ($allocationInputs as $index => $allocation) {
                $purchaseId = $allocation['purchase_id'] ?? null;
                $allocationInputs[$index]['invoice'] = is_scalar($purchaseId) ? $purchases->get($purchaseId) : null;
            }
        }

        return [
            'direction' => $direction,
            'selectedCustomer' => $customer,
            'selectedSupplier' => $supplier,
            'allocationInputs' => $allocationInputs,
            'paymentTypes' => PaymentType::query()
                ->orderBy('ordering')
                ->orderBy('id')
                ->get(['id', 'name']),
            'canDiscount' => app(InventoryAccess::class)
                ->allows($actor, 'inventory-payments.discount'),
        ];
    }
}
