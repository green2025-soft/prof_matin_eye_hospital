<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Inventory\Http\Requests\InventoryItemRequest;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductUnit;
use Modules\Inventory\Models\ProductVariant;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\Sale;
use Modules\Inventory\Models\StockBalance;
use Modules\Inventory\Models\StockEntry;
use Modules\Inventory\Models\StockLot;
use Modules\Inventory\Services\InventoryAccess;
use Modules\Inventory\Services\StockService;

class InventorySelectorController extends Controller
{
    public function reportProducts(Request $request, InventoryAccess $access): JsonResponse
    {
        $access->authorize($request->user('web'), 'inventory-reports.view');
        $filters = $request->validate([
            'product_type' => ['required', 'in:medicine,optic'],
            'search' => ['nullable', 'string', 'max:80'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);
        $query = ProductVariant::query()
            ->with('product:id,name')
            ->whereHas('product', fn ($products) => $products->where('type', $filters['product_type']))
            ->select(['id', 'product_id', 'sku'])
            ->withSum('stockBalance as stock_quantity', 'quantity');
        if (filled($filters['search'] ?? null)) {
            $term = '%'.addcslashes($filters['search'], '\\%_').'%';
            $query->where(
                fn ($matches) => $matches->whereLike('sku', $term)
                    ->orWhereLike('name', $term)
                    ->orWhereHas(
                        'product',
                        fn ($products) => $products->whereLike('name', $term)
                            ->orWhereLike('code', $term)
                            ->orWhereLike('barcode', $term)
                    )
            );
        }
        $records = $query->orderByDesc('stock_quantity')
            ->orderBy(
                Product::query()
                    ->select('name')
                    ->whereColumn('id', 'inv_product_variants.product_id')
            )
            ->orderBy('id')
            ->simplePaginate(20, ['*'], 'page', (int) ($filters['page'] ?? 1));

        return response()
            ->json([
                'results' => $records->getCollection()
                    ->map(
                        fn (ProductVariant $variant): array => [
                            'id' => $variant->id,
                            'name' => $variant->product->name.' / '.$variant->sku.' / Stock: '.(int) $variant->stock_quantity.' Pcs',
                        ]
                    ),
                'pagination' => [
                    'more' => $records->hasMorePages(),
                ],
            ]);
    }

    public function items(InventoryItemRequest $request): JsonResponse
    {
        $values = $request->validated();
        $query = ProductVariant::query()
            ->where('active', true)
            ->whereHas('product', function ($query) use ($values): void {
                $query->where('type', $values['product_type'])
                    ->where('active', true);
                if (($values['purpose'] ?? 'stock') !== 'purchase') {
                    $query->whereHas('units', fn ($units) => $units->where('factor', 1));
                }
            })
            ->with([
                'product:id,name,type,purchase_price,sale_price,needs_setup',
                'product.units' => fn ($units) => $units->select(['id', 'product_id', 'unit_id', 'factor'])
                    ->orderBy('factor')
                    ->orderBy('id'),
                'product.units.unit:id,name,symbol',
            ]);
        $search = trim($values['search'] ?? '');
        if ($search !== '') {
            $exactQuery = (clone $query)->where(function ($matches) use ($search): void {
                $matches->where('inv_product_variants.sku', $search)
                    ->orWhereHas('product', function ($product) use ($search): void {
                        $product->where(
                            fn ($aliases) => $aliases->where('inv_products.code', $search)
                                ->orWhere('inv_products.barcode', $search)
                        );
                    });
            });
            if ($exactQuery->exists()) {
                $query = $exactQuery;
            } else {
                $term = '%'.addcslashes($search, '\\%_').'%';
                $numeric = ctype_digit($search);
                $query->where(function ($matches) use ($search, $term, $numeric): void {
                    if ($numeric) {
                        $digits = str_pad($search, 6, '0', STR_PAD_LEFT);
                        $matches->whereIn('inv_product_variants.sku', [$search, 'MED-'.$search, 'OPT-'.$search, 'MED-'.$digits, 'OPT-'.$digits]);
                    } else {
                        $matches->whereLike('inv_product_variants.sku', $term);
                    }
                    $matches->orWhereLike('inv_product_variants.name', $term)
                        ->orWhereHas('product', function ($product) use ($search, $term, $numeric): void {
                            $product->where(function ($aliases) use ($search, $term, $numeric): void {
                                $aliases->whereLike('inv_products.name', $term);
                                if ($numeric) {
                                    $aliases->orWhereKey($search)
                                        ->orWhere('inv_products.source_key', 'drug_medicine:'.$search);
                                } else {
                                    $aliases->orWhereLike('inv_products.code', $term);
                                }
                            });
                        });
                });
            }
        }
        $records = $query->orderBy('sku')
            ->orderBy('id')
            ->simplePaginate(20,
                ['id', 'product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl'], 'page', (int) ($values['page'] ?? 1));
        $variantIds = $records->getCollection()
            ->modelKeys();
        $balances = ($values['purpose'] ?? 'stock') === 'sale'
            ? app(StockService::class)
                ->availableQuantities(
                    $values['product_type'],
                    now()
                        ->toDateString(),
                    $variantIds
                )
            : StockBalance::query()
                ->whereIn('variant_id', $variantIds)
                ->pluck('quantity', 'variant_id');
        $results = $records->getCollection()
            ->map(function (ProductVariant $variant) use ($balances): array {
                $description = $variant->description();

                return [
                    'id' => $variant->id,
                    'product_id' => $variant->product_id,
                    'name' => $variant->product->name.' / '.$description,
                    'description' => $description,
                    'needs_setup' => $variant->product->needs_setup,
                    'product_name' => $variant->product->name,
                    'sku' => $variant->sku,
                    'variant_name' => $variant->name,
                    'color' => $variant->color,
                    'size' => $variant->size,
                    'sph' => $variant->sph,
                    'cyl' => $variant->cyl,
                    'available_quantity' => (string) ($balances->get($variant->id) ?? 0),
                    'purchase_price' => $variant->product->purchase_price,
                    'sale_price' => $variant->product->sale_price,
                    'units' => $variant->product->units->map(
                        fn (ProductUnit $unit): array => [
                            'id' => $unit->id,
                            'unit_id' => $unit->unit_id,
                            'name' => $unit->unit->symbol ?: $unit->unit->name,
                            'symbol' => $unit->unit->symbol,
                            'factor' => $unit->factor,
                        ]
                    )
                        ->values()
                        ->all(),
                ];
            })
            ->values()
            ->all();

        return response()
            ->json([
                'results' => $results,
                'pagination' => [
                    'more' => $records->hasMorePages(),
                ],
            ]);
    }

    public function purchaseSources(Request $request, InventoryAccess $access): JsonResponse
    {
        $access->authorize($request->user('web'), 'inventory-purchase-returns.create');

        return $this->sourceOptions($request, Purchase::query());
    }

    public function saleSources(Request $request, InventoryAccess $access): JsonResponse
    {
        $access->authorize($request->user('web'), 'inventory-sale-returns.create');

        return $this->sourceOptions($request, Sale::query());
    }

    private function sourceOptions(Request $request, Builder $query): JsonResponse
    {
        $values = $request->validate([
            'search' => ['nullable', 'string', 'max:80'],
            'product_type' => ['required', 'in:medicine,optic'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ]);
        $query->where('product_type', $values['product_type'])
            ->whereNull('voided_at')
            ->whereNotNull('posted_at')
            ->whereHas('lines', fn ($lines) => $lines->whereColumn('base_quantity', '>', 'returned_base_quantity'));
        if (! empty($values['search'])) {
            $query->where('number', 'like', '%'.$values['search'].'%');
        }
        $records = $query->orderByDesc('id')
            ->paginate(20, ['id', 'number']);

        return response()
            ->json([
                'results' => $records->getCollection()
                    ->map(
                        fn (StockEntry $invoice): array => [
                            'id' => $invoice->id,
                            'name' => $invoice->number,
                        ]
                    ),
                'pagination' => [
                    'more' => $records->hasMorePages(),
                ],
            ]);
    }

    public function lots(Request $request, InventoryAccess $access): JsonResponse
    {
        $actor = $request->user('web');
        abort_unless($access->allows($actor, 'inventory-damage.create') || $access->allows($actor, 'inventory-adjustments.create'), 403);
        $values = $request->validate([
            'variant_id' => ['required', 'integer', 'min:1'],
            'search' => ['nullable', 'string', 'max:80'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ]);
        $query = StockLot::query()
            ->where('variant_id', $values['variant_id'])
            ->whereNull('reversed_at')
            ->where('remaining_quantity', '>', 0);
        if (! empty($values['search'])) {
            $query->where('batch_no', 'like', '%'.$values['search'].'%');
        }
        $records = $query->orderBy('received_at')
            ->orderBy('id')
            ->paginate(20);

        return response()
            ->json([
                'results' => $records->getCollection()
                    ->map(
                        fn (StockLot $lot): array => [
                            'id' => $lot->id,
                            'name' => '#'.$lot->id.' / '.($lot->batch_no ?: 'No batch').' / '.($lot->expiry_date?->format('Y-m-d') ?? 'No expiry').' / Qty '.$lot->remaining_quantity,
                        ]
                    ),
                'pagination' => [
                    'more' => $records->hasMorePages(),
                ],
            ]);
    }
}
