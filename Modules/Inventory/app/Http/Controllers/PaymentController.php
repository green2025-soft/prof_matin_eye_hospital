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
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\Payment;
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

    protected array $routeFilters = ['product_type' => 'product_type', 'direction' => 'direction'];

    protected array $defaultFilters = ['product_type' => 'medicine', 'direction' => 'customer'];

    public function index(PaymentRequest $request): View
    {
        $filters = array_filter($request->validated(), fn ($value): bool => $value !== null && $value !== '') + $this->defaultFilters;
        $request->attributes->set('inventory_product_type', $filters['product_type']);
        $request->attributes->set('inventory_payment_direction', $filters['direction']);
        $query = Payment::query()->where('direction', $filters['direction'])->where('product_type', $filters['product_type']);
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
            'records' => $records, 'filters' => $filters,
            'pageTitle' => $filters['direction'] === 'customer' ? 'Customer payments' : 'Supplier payments',
            'customers' => app(InventoryCustomers::class)->findMany($actor, $records->pluck('customer_id')->filter()->unique()->all()),
            'suppliers' => Supplier::query()->whereIn('id', $records->pluck('supplier_id')->filter())->pluck('name', 'id'),
            'canCreate' => app(InventoryAccess::class)->allows($actor, 'inventory-payments.create'),
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
        $payment->load('allocations.document:id,number');
        $actor = $request->user('web');

        return view('inventory::payments.show', [
            'payment' => $payment, 'pageTitle' => $payment->number,
            'customer' => $payment->customer_id ? (app(InventoryCustomers::class)->findMany($actor, [$payment->customer_id])[$payment->customer_id] ?? null) : null,
            'supplier' => $payment->supplier_id ? Supplier::query()->find($payment->supplier_id, ['id', 'name']) : null,
            'canVoid' => $payment->voided_at === null && app(InventoryAccess::class)->allows($actor, 'inventory-payments.void'),
        ]);
    }

    public function reverse(Request $request, Payment $payment, PaymentService $service): RedirectResponse
    {
        $service->reverse($request->user('web'), $payment);

        return redirect()->route('inventory.payments.show', [
            'payment' => $payment, 'product_type' => $payment->product_type, 'direction' => $payment->direction,
        ])->with('status', 'Payment voided.');
    }

    public function invoices(PaymentRequest $request): JsonResponse
    {
        app(InventoryAccess::class)->authorize($request->user('web'), 'inventory-payments.create');
        $filters = $request->validated();
        $direction = $filters['direction'] ?? 'customer';
        $productType = $filters['product_type'] ?? 'medicine';
        $query = $direction === 'customer'
            ? Document::customerDues($productType)->where('customer_id', $filters['customer_id'] ?? 0)
            : Document::supplierDues($productType)->where('supplier_id', $filters['supplier_id'] ?? 0);
        $records = $query->orderBy('document_date')->orderBy('id')->paginate(50);

        return response()->json(['results' => $records->getCollection()->map(fn (Document $document): array => [
            'id' => $document->id, 'number' => $document->number, 'date' => $document->document_date->format('Y-m-d'),
            'due' => Money::decimal($document->outstandingMinor()),
        ]), 'pagination' => ['more' => $records->hasMorePages()]]);
    }

    public function customers(Request $request, InventoryCustomers $customers): JsonResponse
    {
        $access = app(InventoryAccess::class);
        $actor = $request->user('web');
        abort_unless($access->allows($actor, 'inventory-payments.create') || $access->allows($actor, 'inventory-reports.view'), 403);
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:80'], 'page' => ['nullable', 'integer', 'min:1'],
            'product_type' => ['nullable', Rule::in(['medicine', 'optic'])],
        ]);
        $eligibleIds = Document::customerDues($filters['product_type'] ?? 'medicine')->select('customer_id')->toBase();

        return response()->json($customers->search($actor, $filters['search'] ?? '', false, (int) ($filters['page'] ?? 1), $eligibleIds));
    }

    public function suppliers(Request $request): JsonResponse
    {
        $access = app(InventoryAccess::class);
        $actor = $request->user('web');
        abort_unless($access->allows($actor, 'inventory-payments.create') || $access->allows($actor, 'inventory-reports.view'), 403);
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:80'], 'page' => ['nullable', 'integer', 'min:1'],
            'product_type' => ['nullable', Rule::in(['medicine', 'optic'])],
        ]);
        $eligibleIds = Document::supplierDues($filters['product_type'] ?? 'medicine')->select('supplier_id');
        $query = Supplier::query()->whereIn('id', $eligibleIds);
        if (filled($filters['search'] ?? null)) {
            $term = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $filters['search']).'%';
            $query->where(function (Builder $query) use ($term): void {
                $query->whereRaw("name LIKE ? ESCAPE '!'", [$term])->orWhereRaw("contact_no LIKE ? ESCAPE '!'", [$term]);
            });
        }
        $records = $query->orderBy('name')->orderBy('id')->simplePaginate(20, ['id', 'name'], 'page', (int) ($filters['page'] ?? 1));

        return response()->json(['results' => $records->items(), 'pagination' => ['more' => $records->hasMorePages()]]);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $productType = $record->exists ? $record->product_type : request()->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        request()->attributes->set('inventory_product_type', $productType);
        $direction = $record->exists ? $record->direction : request()->query('direction', 'customer');
        $direction = in_array($direction, ['customer', 'supplier'], true) ? $direction : 'customer';
        request()->attributes->set('inventory_payment_direction', $direction);
        $customerId = getValue('customer_id', $record, request()->query('customer_id'));
        $customerId = is_scalar($customerId) && ctype_digit((string) $customerId) ? (int) $customerId : null;
        $customer = null;
        $supplier = null;
        if ($direction === 'customer') {
            $documents = Document::customerDues($productType)->where('customer_id', $customerId ?? 0);
            $customer = $customerId && (clone $documents)->exists()
                ? (app(InventoryCustomers::class)->findMany($actor, [$customerId])[$customerId] ?? null) : null;
        } else {
            $supplier = $this->selectedOption(
                Supplier::query()->whereIn('id', Document::supplierDues($productType)->select('supplier_id')),
                getValue('supplier_id', $record, request()->query('supplier_id')),
            );
            $documents = Document::supplierDues($productType)->where('supplier_id', $supplier?->id ?? 0);
        }
        $allocationInputs = $this->oldRows('allocations', [], 200);
        $ids = collect($allocationInputs)->pluck('document_id')->filter(fn ($id): bool => is_scalar($id) && ctype_digit((string) $id))->all();

        return [
            'direction' => $direction,
            'selectedCustomer' => $customer, 'selectedSupplier' => $supplier, 'allocationInputs' => $allocationInputs,
            'documents' => $ids === [] ? collect() : $documents->whereIn('id', $ids)->get()->keyBy('id'),
            'paymentTypes' => PaymentType::query()->orderBy('ordering')->orderBy('id')->get(['id', 'name']),
            'canDiscount' => app(InventoryAccess::class)->allows($actor, 'inventory-payments.discount'),
        ];
    }
}
