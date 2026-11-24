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
use Modules\Pharmacy\Http\Requests\PurchasePaymentRequest;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\PurchasePayment;
use Modules\Pharmacy\Models\StockEntry;
use Modules\Pharmacy\Models\Supplier;
use Modules\Pharmacy\Services\PurchasePaymentService;
use Modules\TenantCore\Models\PaymentType;

class PurchasePaymentController extends BaseController
{
    protected string $model = PurchasePayment::class;

    protected string $service = PurchasePaymentService::class;

    protected string $title = 'Supplier payment';

    protected string $views = 'pharmacy::purchase-payments';

    protected string $parameter = 'purchase_payment';

    protected array $routeFilters = [
        'product_type' => 'product_type',
    ];

    protected array $defaultFilters = [
        'product_type' => 'medicine',
    ];

    public function index(PurchasePaymentRequest $request): View
    {
        $permissions = $request->attributes->get('navigation_permissions', []);
        $filters = $request->validated();
        $filters['product_type'] ??= $this->defaultFilters['product_type'];
        $request->attributes->set('pharmacy_product_type', $filters['product_type']);

        $query = PurchasePayment::where('product_type', $filters['product_type']);
        if (! empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }
        if (! empty($filters['from_date'])) {
            $query->where('payment_date', '>=', $filters['from_date']);
        }
        if (! empty($filters['to_date'])) {
            $query->where('payment_date', '<=', $filters['to_date']);
        }

        $records = $this->paginateQuery($query->orderByDesc('id'), $filters);
        $suppliers = Supplier::whereIn('id', $records->pluck('supplier_id')->filter())
            ->pluck('name', 'id');
        $data = [
            'records' => $records,
            'filters' => $filters,
            'pageTitle' => 'Supplier payments',
            'suppliers' => $suppliers,
            'canCreate' => isset($permissions['pharmacy-payments.create']),
            'canDelete' => isset($permissions['pharmacy-payments.delete']),
        ];

        return $this->listData($request, $data);
    }

    public function store(PurchasePaymentRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(PurchasePaymentRequest $request, PurchasePayment $purchasePayment): View
    {
        $permissions = $request->attributes->get('navigation_permissions', []);
        $request->attributes->set('pharmacy_product_type', $purchasePayment->product_type);
        $allocations = $purchasePayment->items()
            ->with('purchase:id,number')
            ->get(['id', 'payment_id', 'purchase_id', 'amount', 'discount']);
        $supplier = Supplier::find($purchasePayment->supplier_id, ['id', 'name']);
        $data = [
            'payment' => $purchasePayment,
            'pageTitle' => $purchasePayment->number,
            'allocations' => $allocations,
            'supplier' => $supplier,
            'canVoid' => $purchasePayment->voided_at === null && isset($permissions['pharmacy-payments.void']),
            'canDelete' => isset($permissions['pharmacy-payments.delete']),
        ];

        return view($this->views.'.show', $data);
    }

    public function reverse(Request $request, PurchasePayment $purchasePayment, PurchasePaymentService $service): RedirectResponse
    {
        $service->reverse($request->user('web'), $purchasePayment);

        return redirect()
            ->route('pharmacy.purchase-payments.show', [
                'purchase_payment' => $purchasePayment,
                'product_type' => $purchasePayment->product_type,
            ])
            ->with('status', 'Payment voided.');
    }

    public function invoices(PurchasePaymentRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $records = Purchase::outstanding()
            ->where('product_type', $filters['product_type'] ?? 'medicine')
            ->where('supplier_id', $filters['supplier_id'] ?? 0)
            ->orderBy('entry_date')
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

    public function suppliers(PurchasePaymentRequest $request): JsonResponse
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
        $allocationInputs = $this->oldRows('allocations', [], 200);
        $invoiceIds = collect($allocationInputs)
            ->pluck('purchase_id')
            ->filter(fn ($id): bool => is_scalar($id) && ctype_digit((string) $id))
            ->all();
        $eligibleSupplierIds = Purchase::outstanding()
            ->where('product_type', $productType)
            ->select('supplier_id');
        $supplier = $this->selectedOption(
            Supplier::whereIn('id', $eligibleSupplierIds),
            getValue('supplier_id', $record, $request->query('supplier_id')),
        );
        $invoices = $invoiceIds === [] ? collect() : Purchase::outstanding()
            ->where('product_type', $productType)
            ->where('supplier_id', $supplier?->id ?? 0)
            ->whereIn('id', $invoiceIds)
            ->get()
            ->keyBy('id');
        $data = [
            'productType' => $productType,
            'selectedSupplier' => $supplier,
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
