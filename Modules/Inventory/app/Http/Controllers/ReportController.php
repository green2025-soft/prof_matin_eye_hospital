<?php

namespace Modules\Inventory\Http\Controllers;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Hospital\Contracts\InventoryCustomers;
use Modules\Inventory\Http\Requests\ReportRequest;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\ProductCategory;
use Modules\Inventory\Models\ProductVariant;
use Modules\Inventory\Models\Shelf;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Services\InventoryAccess;
use Modules\Inventory\Services\StockService;

class ReportController extends BaseController
{
    public function index(ReportRequest $request): View
    {
        $report = $request->route('report', 'stock');
        if ($report === 'stock') {
            return $this->stockReport($request);
        }
        if ($report === 'profit-loss') {
            return $this->profitLossReport($request);
        }
        $filters = array_filter($request->validated(), fn ($value): bool => $value !== null && $value !== '') + ['from_date' => now()->startOfMonth()->toDateString(), 'to_date' => now()->toDateString(), 'product_type' => 'medicine', 'expiry_before' => now()->addDays(90)->toDateString()];
        if ($report === 'expiry') {
            $filters['product_type'] = 'medicine';
        }
        $request->attributes->set('inventory_product_type', $filters['product_type']);
        $connection = (new Document)->getConnection();
        $branchId = (int) $request->attributes->get('branch_id');
        $summary = null;

        switch ($report) {
            case 'expiry':
                $query = $connection->table('inventory_stock_lots as lots')->where('lots.branch_id', $branchId)->whereNull('lots.reversed_at')->where('lots.remaining_quantity', '>', 0)
                    ->whereNotNull('lots.expiry_date')->where('lots.expiry_date', '<=', $filters['expiry_before'])
                    ->join('inventory_product_variants as variants', 'variants.id', '=', 'lots.variant_id')
                    ->join('inventory_products as products', 'products.id', '=', 'variants.product_id')->where('products.type', 'medicine');
                $this->products($query, array_diff_key($filters, ['product_type' => true]), 'lots');
                $records = $this->paginateQuery($query->orderBy('lots.expiry_date')->orderBy('lots.id'), $filters,
                    ['products.name', 'variants.sku', 'lots.batch_no', 'lots.expiry_date', 'lots.remaining_quantity', 'lots.remaining_cost_minor']);
                break;
            case 'movements':
                $query = $connection->table('inventory_stock_movements as movements')->where('movements.branch_id', $branchId)
                    ->where('movements.movement_date', '<=', $filters['to_date'])
                    ->join('inventory_product_variants as variants', 'variants.id', '=', 'movements.variant_id')
                    ->join('inventory_products as products', 'products.id', '=', 'variants.product_id');
                $this->products($query, $filters, 'movements');
                $summary = (clone $query)->selectRaw('COALESCE(SUM(CASE WHEN movements.movement_date < ? THEN movements.quantity ELSE 0 END),0) as opening_quantity', [$filters['from_date']])
                    ->selectRaw('COALESCE(SUM(CASE WHEN movements.movement_date >= ? AND movements.quantity > 0 THEN movements.quantity ELSE 0 END),0) as in_quantity', [$filters['from_date']])
                    ->selectRaw('COALESCE(SUM(CASE WHEN movements.movement_date >= ? AND movements.quantity < 0 THEN -movements.quantity ELSE 0 END),0) as out_quantity', [$filters['from_date']])
                    ->selectRaw('COALESCE(SUM(movements.quantity),0) as closing_quantity')->first();
                $query->where('movements.movement_date', '>=', $filters['from_date'])
                    ->join('inventory_documents as documents', 'documents.id', '=', 'movements.document_id')
                    ->orderByDesc('movements.movement_date')->orderByDesc('movements.id');
                $records = $this->paginateQuery($query, $filters,
                    ['products.name', 'variants.sku', 'movements.movement_date', 'movements.type', 'movements.quantity', 'movements.cost_minor', 'movements.is_reversal', 'documents.number']);
                break;
            case 'purchase-discounts':
                $query = $connection->table('inventory_documents as documents')->where('documents.branch_id', $branchId)->where('documents.type', 'purchase')->whereNull('documents.voided_at')->where('documents.product_type', $filters['product_type'])
                    ->leftJoin('inventory_suppliers as suppliers', 'suppliers.id', '=', 'documents.supplier_id');
                $this->dates($query, $filters, 'documents.document_date');
                $query->select(['documents.id', 'documents.number', 'documents.document_date', 'documents.subtotal_minor', 'documents.discount_minor', 'documents.total_minor', 'documents.settlement_discount_minor', 'suppliers.name']);
                $query->selectSub($connection->table('inventory_document_lines')->whereColumn('document_id', 'documents.id')->whereNull('superseded_at')->selectRaw('COALESCE(SUM(discount_minor),0)'), 'line_discount_minor');
                $records = $this->paginateQuery($query->orderByDesc('documents.document_date')->orderByDesc('documents.id'), $filters);
                break;
            case 'daily':
                $documents = $connection->table('inventory_documents')->where('branch_id', $branchId)->whereNull('voided_at')->where('product_type', $filters['product_type']);
                $this->dates($documents, $filters, 'document_date');
                $documents->selectRaw("document_date as activity_date, type as activity, COUNT(*) as entries, SUM(total_minor) as value_minor, SUM(CASE WHEN type IN ('purchase', 'sale_return') THEN -CAST(initial_paid_minor AS SIGNED) ELSE CAST(initial_paid_minor AS SIGNED) END) as cash_minor")->groupBy('document_date', 'type');
                $payments = $connection->table('inventory_payments')->where('branch_id', $branchId)->whereNull('voided_at')->where('product_type', $filters['product_type']);
                $this->dates($payments, $filters, 'payment_date');
                $payments->selectRaw("payment_date as activity_date, CASE WHEN direction = 'customer' THEN 'customer_collection' ELSE 'supplier_payment' END as activity, COUNT(*) as entries, SUM(amount_minor + discount_minor) as value_minor, SUM(CASE WHEN direction = 'customer' THEN CAST(amount_minor AS SIGNED) ELSE -CAST(amount_minor AS SIGNED) END) as cash_minor")->groupBy('payment_date', 'direction');
                $records = $this->paginateQuery($connection->query()->fromSub($documents->unionAll($payments), 'daily')
                    ->orderByDesc('activity_date')->orderBy('activity'), $filters);
                break;
            default:
                abort(404);
        }

        return view('inventory::reports.index', [
            'pageTitle' => ['expiry' => 'Medicine expiry', 'movements' => 'Stock movements', 'purchase-discounts' => 'Purchase discounts', 'daily' => 'Daily report'][$report],
            'report' => $report, 'filters' => $filters, 'records' => $records, 'summary' => $summary,
            'selectedProduct' => $this->selectedReportProduct($filters),
        ]);
    }

    public function customerDues(ReportRequest $request, InventoryCustomers $customers): View
    {
        $filters = $this->dueFilters($request);
        $query = Document::customerDues($filters['product_type']);
        if (! empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }
        [$records, $summary] = $this->dueRecords($query, 'customer_id', $filters);
        $actor = $request->user('web');
        $parties = $customers->findMany($actor, $records->pluck('party_id')->all());

        return view('inventory::reports.customer-dues', [
            'filters' => $filters, 'records' => $records, 'summary' => $summary,
            'customers' => $parties,
            'selectedCustomer' => $parties[$filters['customer_id'] ?? 0] ?? null,
            'canCreatePayment' => app(InventoryAccess::class)->allows($actor, 'inventory-payments.create'),
        ]);
    }

    public function supplierDues(ReportRequest $request): View
    {
        $filters = $this->dueFilters($request);
        $query = Document::supplierDues($filters['product_type']);
        if (! empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }
        [$records, $summary] = $this->dueRecords($query, 'supplier_id', $filters);
        $suppliers = Supplier::query()->whereIn('id', $records->pluck('party_id'))->get(['id', 'name', 'contact_no'])->keyBy('id');

        return view('inventory::reports.supplier-dues', [
            'filters' => $filters, 'records' => $records, 'summary' => $summary,
            'suppliers' => $suppliers,
            'selectedSupplier' => $suppliers->get($filters['supplier_id'] ?? 0),
            'canCreatePayment' => app(InventoryAccess::class)->allows($request->user('web'), 'inventory-payments.create'),
        ]);
    }

    private function dueFilters(ReportRequest $request): array
    {
        $filters = array_filter(Arr::only($request->validated(), [
            'product_type', 'customer_id', 'supplier_id', 'per_page', 'page',
        ]), fn ($value): bool => $value !== null && $value !== '') + ['product_type' => 'medicine'];
        $request->attributes->set('inventory_product_type', $filters['product_type']);

        return $filters;
    }

    private function dueRecords(EloquentBuilder $query, string $partyColumn, array $filters): array
    {
        $balances = $query->toBase();
        $due = Document::dueAmountSql();
        $summary = (clone $balances)->selectRaw("COUNT(DISTINCT $partyColumn) AS party_count, COUNT(*) AS invoice_count")
            ->selectRaw("COALESCE(SUM($due), 0) AS due_minor")->first();
        $records = $this->paginateQuery($balances->select($partyColumn.' AS party_id')
            ->selectRaw('COUNT(*) AS invoice_count, SUM(total_minor) AS invoice_total_minor, SUM(returned_minor) AS returned_minor')
            ->selectRaw('SUM(paid_minor) AS paid_minor, SUM(refunded_minor) AS refunded_minor, SUM(settlement_discount_minor) AS settlement_discount_minor')
            ->selectRaw("SUM($due) AS due_minor")->groupBy($partyColumn)->orderByDesc('due_minor')->orderBy($partyColumn), $filters);

        return [$records, $summary];
    }

    private function stockReport(ReportRequest $request): View
    {
        $filters = array_filter(Arr::only($request->validated(), [
            'from_date', 'to_date', 'product_type', 'search', 'variant_id', 'category_id', 'supplier_id', 'shelf_id',
            'stock_status', 'low_stock', 'per_page', 'page',
        ]), fn ($value): bool => $value !== null && $value !== '') + [
            'product_type' => 'medicine', 'stock_status' => 'all',
        ];
        if (! empty($filters['low_stock'])) {
            $filters['stock_status'] = 'low';
        }
        unset($filters['low_stock']);
        $request->attributes->set('inventory_product_type', $filters['product_type']);
        $query = $this->stockRecordsQuery($filters);
        $summary = (new Document)->getConnection()->query()->fromSub(clone $query, 'stock')
            ->selectRaw('COUNT(*) AS product_count, COALESCE(SUM(opening_quantity), 0) AS opening_quantity')
            ->selectRaw('COALESCE(SUM(purchase_quantity), 0) AS purchase_quantity, COALESCE(SUM(sale_quantity), 0) AS sale_quantity')
            ->selectRaw('COALESCE(SUM(purchase_return_quantity), 0) AS purchase_return_quantity, COALESCE(SUM(sale_return_quantity), 0) AS sale_return_quantity')
            ->selectRaw('COALESCE(SUM(other_quantity), 0) AS other_quantity, COALESCE(SUM(closing_quantity), 0) AS closing_quantity')
            ->selectRaw('COALESCE(SUM(closing_cost_minor), 0) AS closing_cost_minor, COALESCE(SUM(quantity), 0) AS quantity')
            ->selectRaw('COALESCE(SUM(available_quantity), 0) AS available_quantity, COALESCE(SUM(available_cost_minor), 0) AS available_cost_minor')
            ->selectRaw('COALESCE(SUM(expired_quantity), 0) AS expired_quantity, COALESCE(SUM(expired_cost_minor), 0) AS expired_cost_minor')
            ->selectRaw('COALESCE(SUM(future_quantity), 0) AS future_quantity, COALESCE(SUM(future_cost_minor), 0) AS future_cost_minor')->first();

        return view('inventory::reports.stock', [
            'filters' => $filters, 'summary' => $summary,
            'records' => $this->paginateQuery($query->orderByDesc('closing_quantity')->orderBy('products.name')->orderBy('variants.id'), $filters),
            'selectedCategory' => $this->selectedOption(ProductCategory::query()->where('type', $filters['product_type']), $filters['category_id'] ?? null),
            'selectedSupplier' => $this->selectedOption(Supplier::query()->where('type', $filters['product_type']), $filters['supplier_id'] ?? null),
            'selectedShelf' => $this->selectedOption(Shelf::query()->where('type', $filters['product_type']), $filters['shelf_id'] ?? null),
            'selectedProduct' => $this->selectedReportProduct($filters),
        ]);
    }

    private function stockRecordsQuery(array $filters): Builder
    {
        $connection = (new Document)->getConnection();
        $branchId = (int) request()->attributes->get('branch_id');
        $movements = $connection->table('inventory_stock_movements')->where('branch_id', $branchId)
            ->where('movement_date', '<=', $filters['to_date'])->groupBy('variant_id')->select('variant_id')
            ->selectRaw('SUM(CASE WHEN movement_date < ? THEN quantity ELSE 0 END) AS opening_quantity', [$filters['from_date']])
            ->selectRaw("SUM(CASE WHEN movement_date >= ? AND type = 'purchase' THEN quantity ELSE 0 END) AS purchase_quantity", [$filters['from_date']])
            ->selectRaw("SUM(CASE WHEN movement_date >= ? AND type = 'sale' THEN -quantity ELSE 0 END) AS sale_quantity", [$filters['from_date']])
            ->selectRaw("SUM(CASE WHEN movement_date >= ? AND type = 'purchase_return' THEN -quantity ELSE 0 END) AS purchase_return_quantity", [$filters['from_date']])
            ->selectRaw("SUM(CASE WHEN movement_date >= ? AND type = 'sale_return' THEN quantity ELSE 0 END) AS sale_return_quantity", [$filters['from_date']])
            ->selectRaw("SUM(CASE WHEN movement_date >= ? AND type NOT IN ('purchase', 'sale', 'purchase_return', 'sale_return') THEN quantity ELSE 0 END) AS other_quantity", [$filters['from_date']])
            ->selectRaw('SUM(quantity) AS closing_quantity, SUM(cost_minor) AS closing_cost_minor');
        $availability = app(StockService::class)->stockAvailability($filters['product_type'], now()->toDateString());
        $available = 'CASE WHEN products.active = 1 AND variants.active = 1 THEN COALESCE(availability.available_quantity, 0) ELSE 0 END';
        $availableCost = 'CASE WHEN products.active = 1 AND variants.active = 1 THEN COALESCE(availability.available_cost_minor, 0) ELSE 0 END';
        $query = $connection->table('inventory_product_variants as variants')
            ->join('inventory_products as products', 'products.id', '=', 'variants.product_id')
            ->leftJoin('inventory_stock_balances as balances', fn ($join) => $join->on('balances.variant_id', '=', 'variants.id')->where('balances.branch_id', $branchId))
            ->leftJoin('inventory_product_categories as categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('inventory_suppliers as suppliers', 'suppliers.id', '=', 'products.supplier_id')
            ->leftJoin('inventory_shelves as shelves', 'shelves.id', '=', 'products.shelf_id')
            ->leftJoinSub($movements, 'movements', 'movements.variant_id', '=', 'variants.id')
            ->leftJoinSub($availability, 'availability', 'availability.variant_id', '=', 'variants.id')
            ->where('products.type', $filters['product_type'])
            ->select(['variants.id', 'products.name', 'variants.sku', 'products.strength', 'variants.name as variant_name', 'variants.color', 'variants.size', 'variants.sph', 'variants.cyl',
                'categories.name as category_name', 'suppliers.name as supplier_name', 'shelves.name as shelf_name', 'products.reorder_level', 'products.purchase_price', 'products.sale_price'])
            ->selectRaw('COALESCE(balances.quantity, 0) AS quantity, (products.active = 1 AND variants.active = 1) AS sale_enabled')
            ->selectRaw("$available AS available_quantity, $availableCost AS available_cost_minor")
            ->selectRaw('COALESCE(availability.expired_quantity, 0) AS expired_quantity, COALESCE(availability.expired_cost_minor, 0) AS expired_cost_minor')
            ->selectRaw('COALESCE(availability.future_quantity, 0) AS future_quantity, COALESCE(availability.future_cost_minor, 0) AS future_cost_minor')
            ->selectRaw('COALESCE(movements.opening_quantity, 0) AS opening_quantity, COALESCE(movements.purchase_quantity, 0) AS purchase_quantity')
            ->selectRaw('COALESCE(movements.sale_quantity, 0) AS sale_quantity, COALESCE(movements.purchase_return_quantity, 0) AS purchase_return_quantity')
            ->selectRaw('COALESCE(movements.sale_return_quantity, 0) AS sale_return_quantity, COALESCE(movements.other_quantity, 0) AS other_quantity')
            ->selectRaw('COALESCE(movements.closing_quantity, 0) AS closing_quantity, COALESCE(movements.closing_cost_minor, 0) AS closing_cost_minor');

        foreach (['category_id', 'supplier_id', 'shelf_id'] as $field) {
            if (! empty($filters[$field])) {
                $query->where('products.'.$field, $filters[$field]);
            }
        }
        if (! empty($filters['variant_id'])) {
            $query->where('variants.id', $filters['variant_id']);
        }
        if (filled($filters['search'] ?? null)) {
            $term = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $filters['search']).'%';
            $query->where(fn (Builder $query) => $query->whereRaw("products.name LIKE ? ESCAPE '!'", [$term])
                ->orWhereRaw("variants.sku LIKE ? ESCAPE '!'", [$term])->orWhereRaw("products.code LIKE ? ESCAPE '!'", [$term])
                ->orWhereRaw("products.barcode LIKE ? ESCAPE '!'", [$term]));
        }
        switch ($filters['stock_status']) {
            case 'available':
                $query->whereRaw("($available) > 0");
                break;
            case 'out':
                $query->whereRaw("($available) <= 0");
                break;
            case 'low':
                $query->whereRaw("($available) <= products.reorder_level");
                break;
            case 'expired':
                $query->where('availability.expired_quantity', '>', 0);
                break;
            case 'future':
                $query->where('availability.future_quantity', '>', 0);
                break;
            case 'negative':
                $query->where('balances.quantity', '<', 0);
                break;
        }

        return $query;
    }

    private function profitLossReport(ReportRequest $request): View
    {
        $filters = array_filter(Arr::only($request->validated(), [
            'from_date', 'to_date', 'product_type', 'variant_id', 'per_page', 'page',
        ]), fn ($value): bool => $value !== null && $value !== '') + ['product_type' => 'medicine'];
        $request->attributes->set('inventory_product_type', $filters['product_type']);
        $query = $this->productProfitQuery($filters);
        $connection = (new Document)->getConnection();
        $summary = $connection->query()->fromSub(clone $query, 'profit')
            ->selectRaw('COUNT(*) AS product_count, COALESCE(SUM(sale_quantity), 0) AS sale_quantity, COALESCE(SUM(return_quantity), 0) AS return_quantity')
            ->selectRaw('COALESCE(SUM(sale_amount_minor), 0) AS sale_amount_minor, COALESCE(SUM(return_amount_minor), 0) AS return_amount_minor')
            ->selectRaw('COALESCE(SUM(net_revenue_minor), 0) AS net_revenue_minor, COALESCE(SUM(cost_minor), 0) AS cost_minor')
            ->selectRaw('COALESCE(SUM(discount_minor), 0) AS discount_minor, COALESCE(SUM(gross_profit_minor), 0) AS gross_profit_minor')
            ->selectRaw('COALESCE(SUM(damage_minor), 0) AS damage_minor, COALESCE(SUM(adjustment_minor), 0) AS adjustment_minor')
            ->selectRaw('COALESCE(SUM(stock_variance_minor), 0) AS stock_variance_minor, COALESCE(SUM(other_minor), 0) AS other_minor')
            ->selectRaw('COALESCE(SUM(profit_minor), 0) AS profit_minor')->first();
        $summary->customer_discount_minor = '0';
        $summary->supplier_discount_minor = '0';
        $showInvoiceAdjustments = empty($filters['variant_id']);
        if ($showInvoiceAdjustments) {
            // Settlement discounts belong to invoices; no product allocation was recorded for them.
            $payments = $connection->table('inventory_payments')->where('branch_id', (int) request()->attributes->get('branch_id'))
                ->where('product_type', $filters['product_type'])->whereNull('voided_at');
            $this->dates($payments, $filters, 'payment_date');
            $discounts = $payments->selectRaw("COALESCE(SUM(CASE WHEN direction = 'customer' THEN discount_minor ELSE 0 END), 0) AS customer_discount_minor")
                ->selectRaw("COALESCE(SUM(CASE WHEN direction = 'supplier' THEN discount_minor ELSE 0 END), 0) AS supplier_discount_minor")->first();
            $summary->customer_discount_minor = $discounts->customer_discount_minor;
            $summary->supplier_discount_minor = $discounts->supplier_discount_minor;
        }
        $summary->net_profit_minor = (string) BigInteger::of($summary->profit_minor)
            ->minus($summary->customer_discount_minor)->plus($summary->supplier_discount_minor);

        return view('inventory::reports.profit-loss', [
            'filters' => $filters, 'summary' => $summary, 'showInvoiceAdjustments' => $showInvoiceAdjustments,
            'selectedProduct' => $this->selectedReportProduct($filters),
            'records' => $this->paginateQuery($query->orderBy('products.name')->orderBy('variants.id'), $filters),
        ]);
    }

    private function productProfitQuery(array $filters): Builder
    {
        $connection = (new Document)->getConnection();
        $branchId = (int) request()->attributes->get('branch_id');
        $netLine = 'CAST(lines.total_minor AS SIGNED) - CAST(lines.vat_minor AS SIGNED)';
        $lineDiscount = 'CAST(lines.discount_minor AS SIGNED) + CAST(lines.invoice_discount_minor AS SIGNED)';
        $trades = $connection->table('inventory_document_lines as lines')
            ->join('inventory_documents as documents', 'documents.id', '=', 'lines.document_id')
            ->where('documents.branch_id', $branchId)->where('documents.product_type', $filters['product_type'])
            ->whereIn('documents.type', ['sale', 'sale_return'])->whereNotNull('documents.posted_at')
            ->whereNull('documents.voided_at')->whereNull('documents.deleted_at')->whereNull('lines.superseded_at')
            ->groupBy('lines.variant_id')->select('lines.variant_id')
            ->selectRaw("SUM(CASE WHEN documents.type = 'sale' THEN lines.base_quantity ELSE 0 END) AS sale_quantity")
            ->selectRaw("SUM(CASE WHEN documents.type = 'sale_return' THEN lines.base_quantity ELSE 0 END) AS return_quantity")
            ->selectRaw("SUM(CASE WHEN documents.type = 'sale' THEN $netLine ELSE 0 END) AS sale_amount_minor")
            ->selectRaw("SUM(CASE WHEN documents.type = 'sale_return' THEN $netLine ELSE 0 END) AS return_amount_minor")
            ->selectRaw("SUM(CASE WHEN documents.type = 'sale' THEN CAST(lines.cost_minor AS SIGNED) ELSE -CAST(lines.cost_minor AS SIGNED) END) AS cost_minor")
            ->selectRaw("SUM(CASE WHEN documents.type = 'sale' THEN $lineDiscount ELSE -($lineDiscount) END) AS discount_minor");
        $this->dates($trades, $filters, 'documents.document_date');
        $stockEffects = $connection->table('inventory_stock_movements')->where('branch_id', $branchId)
            ->whereIn('type', ['damage', 'adjustment', 'stock_variance'])->groupBy('variant_id')->select('variant_id')
            ->selectRaw("SUM(CASE WHEN type = 'damage' THEN -cost_minor ELSE 0 END) AS damage_minor")
            ->selectRaw("SUM(CASE WHEN type = 'adjustment' THEN cost_minor ELSE 0 END) AS adjustment_minor")
            ->selectRaw("SUM(CASE WHEN type = 'stock_variance' THEN cost_minor ELSE 0 END) AS stock_variance_minor");
        $this->dates($stockEffects, $filters, 'movement_date');
        $revenue = 'COALESCE(trades.sale_amount_minor, 0) - COALESCE(trades.return_amount_minor, 0)';
        $cost = 'COALESCE(trades.cost_minor, 0)';
        $other = 'COALESCE(stock_effects.adjustment_minor, 0) + COALESCE(stock_effects.stock_variance_minor, 0) - COALESCE(stock_effects.damage_minor, 0)';
        $query = $connection->table('inventory_product_variants as variants')
            ->join('inventory_products as products', 'products.id', '=', 'variants.product_id')
            ->leftJoinSub($trades, 'trades', 'trades.variant_id', '=', 'variants.id')
            ->leftJoinSub($stockEffects, 'stock_effects', 'stock_effects.variant_id', '=', 'variants.id')
            ->where('products.type', $filters['product_type'])
            ->where(fn (Builder $query) => $query->whereNotNull('trades.variant_id')->orWhereNotNull('stock_effects.variant_id'))
            ->select(['variants.id', 'products.name', 'variants.sku', 'variants.name as variant_name', 'variants.color', 'variants.size', 'variants.sph', 'variants.cyl'])
            ->selectRaw('COALESCE(trades.sale_quantity, 0) AS sale_quantity, COALESCE(trades.return_quantity, 0) AS return_quantity')
            ->selectRaw('COALESCE(trades.sale_amount_minor, 0) AS sale_amount_minor, COALESCE(trades.return_amount_minor, 0) AS return_amount_minor')
            ->selectRaw("($revenue) AS net_revenue_minor, $cost AS cost_minor")
            ->selectRaw('COALESCE(trades.discount_minor, 0) AS discount_minor')
            ->selectRaw("($revenue) - $cost AS gross_profit_minor, ($other) AS other_minor, ($revenue) - $cost + ($other) AS profit_minor")
            ->selectRaw('COALESCE(stock_effects.damage_minor, 0) AS damage_minor, COALESCE(stock_effects.adjustment_minor, 0) AS adjustment_minor')
            ->selectRaw('COALESCE(stock_effects.stock_variance_minor, 0) AS stock_variance_minor');
        if (! empty($filters['variant_id'])) {
            $query->where('variants.id', $filters['variant_id']);
        }

        return $query;
    }

    private function selectedReportProduct(array $filters): ?ProductVariant
    {
        return $this->selectedOption(
            ProductVariant::query()->with('product:id,name,type')
                ->whereHas('product', fn ($query) => $query->where('type', $filters['product_type'])),
            $filters['variant_id'] ?? null,
            ['id', 'product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl'],
        );
    }

    private function products(Builder $query, array $filters, string $stockAlias): void
    {
        if (! empty($filters['product_type'])) {
            $query->where('products.type', $filters['product_type']);
        }
        if (! empty($filters['variant_id'])) {
            $query->where($stockAlias.'.variant_id', $filters['variant_id']);
        }
        if (! empty($filters['search'])) {
            $query->where(function (Builder $query) use ($filters): void {
                $query->where('products.name', 'like', '%'.$filters['search'].'%')->orWhere('variants.sku', 'like', '%'.$filters['search'].'%');
            });
        }
    }

    private function dates(Builder $query, array $filters, string $column): void
    {
        $query->whereBetween($column, [$filters['from_date'], $filters['to_date']]);
    }
}
