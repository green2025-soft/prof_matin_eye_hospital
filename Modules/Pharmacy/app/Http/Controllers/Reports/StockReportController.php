<?php

namespace Modules\Pharmacy\Http\Controllers\Reports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Pharmacy\Http\Requests\DateRangeReportRequest;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\ProductCategory;
use Modules\Pharmacy\Models\ProductVariant;
use Modules\Pharmacy\Models\Shelf;
use Modules\Pharmacy\Models\Supplier;

class StockReportController extends BaseController
{
    public function index(DateRangeReportRequest $request): View
    {
        $filters = $request->filters([
            'stock_status' => 'all',
        ]);
        if (! empty($filters['low_stock'])) {
            $filters['stock_status'] = 'low';
        }
        unset($filters['low_stock']);
        $query = $this->recordsQuery($filters);
        $summary = $this->summary($query);
        $records = $this->paginateQuery(
            $query->orderByDesc('closing_quantity')
                ->orderBy('product_name')
                ->orderBy('id'),
            $filters
        );
        $selectedCategory = $this->selectedOption(
            ProductCategory::where('type', $filters['product_type']),
            $filters['category_id'] ?? null
        );
        $selectedSupplier = $this->selectedOption(
            Supplier::where('type', $filters['product_type']),
            $filters['supplier_id'] ?? null
        );
        $selectedShelf = $this->selectedOption(
            Shelf::where('type', $filters['product_type']),
            $filters['shelf_id'] ?? null
        );
        $selectedProduct = $this->selectedOption(
            ProductVariant::with('product:id,name,type')
                ->whereHas('product', fn (Builder $products) => $products->where('type', $filters['product_type'])),
            $filters['variant_id'] ?? null,
            ['id', 'product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl']
        );

        $data = [
            'pageTitle' => 'Stock Report',
            'filters' => $filters,
            'summary' => $summary,
            'records' => $records,
            'selectedCategory' => $selectedCategory,
            'selectedSupplier' => $selectedSupplier,
            'selectedShelf' => $selectedShelf,
            'selectedProduct' => $selectedProduct,
        ];

        return view('pharmacy::reports.stock', $data);
    }

    private function recordsQuery(array $filters): Builder
    {
        $variants = ProductVariant::select('phr_product_variants.*')
            ->whereHas(
                'product',
                fn (Builder $products) => $products->where('type', $filters['product_type'])
                    ->when($filters['category_id'] ?? null, fn (Builder $products, $id) => $products->where('category_id', $id))
                    ->when($filters['supplier_id'] ?? null, fn (Builder $products, $id) => $products->where('supplier_id', $id))
                    ->when($filters['shelf_id'] ?? null, fn (Builder $products, $id) => $products->where('shelf_id', $id))
            )
            ->selectSub(
                Product::whereColumn('id', 'phr_product_variants.product_id')
                    ->select('name'),
                'product_name'
            )
            ->selectSub(
                Product::whereColumn('id', 'phr_product_variants.product_id')
                    ->select('reorder_level'),
                'reorder_level'
            )
            ->when($filters['variant_id'] ?? null, fn (Builder $variants, $id) => $variants->whereKey($id));

        if (filled($filters['search'] ?? null)) {
            $term = '%'.addcslashes($filters['search'], '\\%_').'%';
            $variants->where(
                fn (Builder $matches) => $matches->whereLike('sku', $term)
                    ->orWhereHas(
                        'product',
                        fn (Builder $products) => $products->whereLike('name', $term)
                            ->orWhereLike('code', $term)
                            ->orWhereLike('barcode', $term)
                    )
            );
        }

        $this->movementTotals($variants, $filters);
        $this->lotTotals($variants, $filters['product_type']);

        $records = ProductVariant::fromSub($variants->toBase(), 'phr_product_variants')
            ->with(['product.category:id,name', 'product.supplier:id,name', 'product.shelf:id,name'])
            ->withCasts([
                'opening_quantity' => 'integer',
                'purchase_quantity' => 'integer',
                'sale_quantity' => 'integer',
                'purchase_return_quantity' => 'integer',
                'sale_return_quantity' => 'integer',
                'other_quantity' => 'integer',
                'closing_quantity' => 'integer',
                'quantity' => 'integer',
                'available_quantity' => 'integer',
                'expired_quantity' => 'integer',
                'future_quantity' => 'integer',
                'reorder_level' => 'integer',
            ]);

        return $this->stockStatus($records, $filters['stock_status']);
    }

    private function movementTotals(Builder $variants, array $filters): void
    {
        $period = fn (Builder $movements) => $movements->whereBetween('movement_date', [$filters['from_date'], $filters['to_date']]);
        $variants->withSum('stockBalance as quantity', 'quantity')
            ->withSum([
                'movements as opening_quantity' => fn (Builder $movements) => $movements->where('movement_date', '<', $filters['from_date']),
            ], 'quantity')
            ->withSum([
                'movements as purchase_quantity' => fn (Builder $movements) => $period($movements)
                    ->where('type', 'purchase'),
            ], 'quantity')
            ->withSum([
                'movements as sale_quantity' => fn (Builder $movements) => $period($movements)
                    ->where('type', 'sale'),
            ], 'quantity')
            ->withSum([
                'movements as purchase_return_quantity' => fn (Builder $movements) => $period($movements)
                    ->where('type', 'purchase_return'),
            ], 'quantity')
            ->withSum([
                'movements as sale_return_quantity' => fn (Builder $movements) => $period($movements)
                    ->where('type', 'sale_return'),
            ], 'quantity')
            ->withSum([
                'movements as other_quantity' => fn (Builder $movements) => $period($movements)
                    ->whereNotIn('type', ['purchase', 'sale', 'purchase_return', 'sale_return']),
            ], 'quantity')
            ->withSum([
                'movements as closing_quantity' => fn (Builder $movements) => $movements->where('movement_date', '<=', $filters['to_date']),
            ], 'quantity')
            ->withSum([
                'movements as closing_cost' => fn (Builder $movements) => $movements->where('movement_date', '<=', $filters['to_date']),
            ], 'cost');
    }

    private function lotTotals(Builder $variants, string $productType): void
    {
        $today = now()
            ->toDateString();
        $available = function (Builder $lots) use ($productType, $today): void {
            $lots->whereNull('reversed_at')
                ->where('remaining_quantity', '>', 0)
                ->where('received_at', '<=', $today)
                ->whereHas(
                    'variant',
                    fn (Builder $variants) => $variants->where('active', true)
                        ->whereHas('product', fn (Builder $products) => $products->where('active', true))
                );
            if ($productType === 'medicine') {
                $lots->where(
                    fn (Builder $expiry) => $expiry->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>=', $today)
                );
            }
        };
        $expired = function (Builder $lots) use ($productType, $today): void {
            $lots->whereNull('reversed_at')
                ->where('remaining_quantity', '>', 0)
                ->where('received_at', '<=', $today)
                ->where('expiry_date', '<', $today);
            if ($productType !== 'medicine') {
                $lots->whereKey([]);
            }
        };

        $variants->withSum([
            'stockLots as available_quantity' => $available,
        ], 'remaining_quantity')
            ->withSum([
                'stockLots as expired_quantity' => $expired,
            ], 'remaining_quantity')
            ->withSum([
                'stockLots as future_quantity' => fn (Builder $lots) => $lots->whereNull('reversed_at')
                    ->where('remaining_quantity', '>', 0)
                    ->where('received_at', '>', $today),
            ], 'remaining_quantity');
    }

    private function stockStatus(Builder $records, string $status): Builder
    {
        return $records
            ->when($status === 'available', fn (Builder $stock) => $stock->where('available_quantity', '>', 0))
            ->when(
                $status === 'out',
                fn (Builder $stock) => $stock->where(
                    fn (Builder $quantity) => $quantity->whereNull('available_quantity')
                        ->orWhere('available_quantity', '<=', 0)
                )
            )
            ->when(
                $status === 'low',
                fn (Builder $stock) => $stock->where(
                    fn (Builder $quantity) => $quantity->whereNull('available_quantity')
                        ->orWhereColumn('available_quantity', '<=', 'reorder_level')
                )
            )
            ->when($status === 'expired', fn (Builder $stock) => $stock->where('expired_quantity', '>', 0))
            ->when($status === 'future', fn (Builder $stock) => $stock->where('future_quantity', '>', 0))
            ->when($status === 'negative', fn (Builder $stock) => $stock->where('quantity', '<', 0));
    }

    private function summary(Builder $records): object
    {
        return (object) [
            'product_count' => (clone $records)->count(),
            'opening_quantity' => (clone $records)->sum('opening_quantity'),
            'purchase_quantity' => (clone $records)->sum('purchase_quantity'),
            'sale_quantity' => (clone $records)->sum('sale_quantity'),
            'purchase_return_quantity' => (clone $records)->sum('purchase_return_quantity'),
            'sale_return_quantity' => (clone $records)->sum('sale_return_quantity'),
            'other_quantity' => (clone $records)->sum('other_quantity'),
            'closing_quantity' => (clone $records)->sum('closing_quantity'),
            'available_quantity' => (clone $records)->sum('available_quantity'),
            'expired_quantity' => (clone $records)->sum('expired_quantity'),
            'closing_cost' => (clone $records)->sum('closing_cost'),
        ];
    }
}
