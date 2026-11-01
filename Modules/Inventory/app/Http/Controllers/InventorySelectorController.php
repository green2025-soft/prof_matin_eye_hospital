<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Inventory\Http\Requests\InventoryItemRequest;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\ProductUnit;
use Modules\Inventory\Models\ProductVariant;
use Modules\Inventory\Models\StockBalance;
use Modules\Inventory\Models\StockLot;
use Modules\Inventory\Services\InventoryAccess;
use Modules\Inventory\Services\StockService;

class InventorySelectorController extends Controller
{
    public function reportProducts(Request $request, InventoryAccess $access): JsonResponse
    {
        $access->authorize($request->user('web'), 'inventory-reports.view');
        $filters = $request->validate([
            'product_type' => ['required', 'in:medicine,optic'], 'search' => ['nullable', 'string', 'max:80'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);
        $branchId = (int) $request->attributes->get('branch_id');
        $query = (new ProductVariant)->getConnection()->table('inventory_product_variants as variants')
            ->join('inventory_products as products', 'products.id', '=', 'variants.product_id')
            ->leftJoin('inventory_stock_balances as balances', fn ($join) => $join->on('balances.variant_id', '=', 'variants.id')->where('balances.branch_id', $branchId))
            ->where('products.type', $filters['product_type']);
        if (filled($filters['search'] ?? null)) {
            $term = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $filters['search']).'%';
            $query->where(fn ($matches) => $matches->whereRaw("products.name LIKE ? ESCAPE '!'", [$term])
                ->orWhereRaw("variants.sku LIKE ? ESCAPE '!'", [$term])->orWhereRaw("products.code LIKE ? ESCAPE '!'", [$term])
                ->orWhereRaw("products.barcode LIKE ? ESCAPE '!'", [$term])->orWhereRaw("variants.name LIKE ? ESCAPE '!'", [$term]));
        }
        $records = $query->select(['variants.id'])
            ->selectRaw("CONCAT(products.name, ' / ', variants.sku, ' / Stock: ', COALESCE(balances.quantity, 0), ' Pcs') AS name")
            ->orderByRaw('COALESCE(balances.quantity, 0) DESC')->orderBy('products.name')->orderBy('variants.id')
            ->simplePaginate(20, ['*'], 'page', (int) ($filters['page'] ?? 1));

        return response()->json(['results' => $records->items(), 'pagination' => ['more' => $records->hasMorePages()]]);
    }

    public function items(InventoryItemRequest $request): JsonResponse
    {
        $values = $request->validated();
        $query = ProductVariant::query()->where('active', true)->whereHas('product', function ($query) use ($values): void {
            $query->where('type', $values['product_type'])->where('active', true);
            if (($values['purpose'] ?? 'stock') !== 'purchase') {
                $query->whereHas('units', fn ($units) => $units->where('factor', 1));
            }
        })->with([
            'product:id,name,type,purchase_price,sale_price,needs_setup',
            'product.units' => fn ($units) => $units->select(['id', 'product_id', 'unit_id', 'factor'])->orderBy('factor')->orderBy('id'),
            'product.units.unit:id,name,symbol',
        ]);
        $search = trim($values['search'] ?? '');
        if ($search !== '') {
            $exactQuery = (clone $query)->where(function ($matches) use ($search): void {
                $matches->where('inventory_product_variants.sku', $search)
                    ->orWhereHas('product', function ($product) use ($search): void {
                        $product->where(fn ($aliases) => $aliases->where('inventory_products.code', $search)
                            ->orWhere('inventory_products.barcode', $search));
                    });
            });
            if ($exactQuery->exists()) {
                $query = $exactQuery;
            } else {
                $term = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $search).'%';
                $numeric = ctype_digit($search);
                $query->where(function ($matches) use ($search, $term, $numeric): void {
                    if ($numeric) {
                        $digits = str_pad($search, 6, '0', STR_PAD_LEFT);
                        $matches->whereIn('inventory_product_variants.sku', [$search, 'MED-'.$search, 'OPT-'.$search, 'MED-'.$digits, 'OPT-'.$digits]);
                    } else {
                        $matches->whereRaw("inventory_product_variants.sku LIKE ? ESCAPE '!'", [$term]);
                    }
                    $matches->orWhereRaw("inventory_product_variants.name LIKE ? ESCAPE '!'", [$term])
                        ->orWhereHas('product', function ($product) use ($search, $term, $numeric): void {
                            $product->where(function ($aliases) use ($search, $term, $numeric): void {
                                $aliases->whereRaw("inventory_products.name LIKE ? ESCAPE '!'", [$term]);
                                if ($numeric) {
                                    $aliases->orWhereKey($search)->orWhere('inventory_products.source_key', 'drug_medicine:'.$search);
                                } else {
                                    $aliases->orWhereRaw("inventory_products.code LIKE ? ESCAPE '!'", [$term]);
                                }
                            });
                        });
                });
            }
        }
        $records = $query->orderBy('sku')->orderBy('id')->simplePaginate(20,
            ['id', 'product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl'], 'page', (int) ($values['page'] ?? 1));
        $variantIds = $records->getCollection()->modelKeys();
        $balances = ($values['purpose'] ?? 'stock') === 'sale'
            ? app(StockService::class)->availableQuantities($values['product_type'], now()->toDateString(), $variantIds)
            : StockBalance::query()->whereIn('variant_id', $variantIds)->pluck('quantity', 'variant_id');
        $results = $records->getCollection()->map(function (ProductVariant $variant) use ($balances): array {
            $details = [];
            if ($variant->product->type === 'optic') {
                if (filled($variant->name) && mb_strtolower(trim($variant->name)) !== 'default') {
                    $details[] = $variant->name;
                }
                if (filled($variant->color)) {
                    $details[] = 'Color '.$variant->color;
                }
                if (filled($variant->size)) {
                    $details[] = 'Size '.$variant->size;
                }
                if ($variant->sph !== null) {
                    $details[] = 'SPH '.$variant->sph;
                }
                if ($variant->cyl !== null) {
                    $details[] = 'CYL '.$variant->cyl;
                }
            }
            $label = $variant->product->name.' / '.$variant->sku;
            if ($details !== []) {
                $label .= ' / '.implode(' · ', $details);
            }

            return [
                'id' => $variant->id, 'product_id' => $variant->product_id, 'name' => $label, 'needs_setup' => $variant->product->needs_setup,
                'product_name' => $variant->product->name, 'sku' => $variant->sku, 'variant_name' => $variant->name,
                'color' => $variant->color, 'size' => $variant->size, 'sph' => $variant->sph, 'cyl' => $variant->cyl,
                'available_quantity' => (string) ($balances->get($variant->id) ?? 0),
                'purchase_price' => $variant->product->purchase_price, 'sale_price' => $variant->product->sale_price,
                'units' => $variant->product->units->map(fn (ProductUnit $unit): array => [
                    'id' => $unit->id, 'unit_id' => $unit->unit_id, 'name' => $unit->unit->symbol ?: $unit->unit->name,
                    'symbol' => $unit->unit->symbol, 'factor' => $unit->factor,
                ])->values()->all(),
            ];
        })->values()->all();

        return response()->json(['results' => $results, 'pagination' => ['more' => $records->hasMorePages()]]);
    }

    public function sources(Request $request, InventoryAccess $access): JsonResponse
    {
        $values = $request->validate(['search' => ['nullable', 'string', 'max:80'], 'source_type' => ['required', 'in:sale,purchase'], 'product_type' => ['required', 'in:medicine,optic'], 'page' => ['sometimes', 'integer', 'min:1']]);
        $permission = $values['source_type'] === 'sale' ? 'inventory-sale-returns.create' : 'inventory-purchase-returns.create';
        $access->authorize($request->user('web'), $permission);
        $query = Document::query()->where('type', $values['source_type'])->where('product_type', $values['product_type'])
            ->whereNull('voided_at')->whereNotNull('posted_at')
            ->whereHas('lines', fn ($lines) => $lines->whereColumn('base_quantity', '>', 'returned_base_quantity'));
        if (! empty($values['search'])) {
            $query->where('number', 'like', '%'.$values['search'].'%');
        }
        $records = $query->orderByDesc('id')->paginate(20, ['id', 'number']);

        return response()->json(['results' => $records->getCollection()->map(fn (Document $document): array => ['id' => $document->id, 'name' => $document->number]), 'pagination' => ['more' => $records->hasMorePages()]]);
    }

    public function lots(Request $request, InventoryAccess $access): JsonResponse
    {
        $actor = $request->user('web');
        abort_unless($access->allows($actor, 'inventory-damage.create') || $access->allows($actor, 'inventory-adjustments.create'), 403);
        $values = $request->validate(['variant_id' => ['required', 'integer', 'min:1'], 'search' => ['nullable', 'string', 'max:80'], 'page' => ['sometimes', 'integer', 'min:1']]);
        $query = StockLot::query()->where('variant_id', $values['variant_id'])->whereNull('reversed_at')->where('remaining_quantity', '>', 0);
        if (! empty($values['search'])) {
            $query->where('batch_no', 'like', '%'.$values['search'].'%');
        }
        $records = $query->orderBy('received_at')->orderBy('id')->paginate(20);

        return response()->json(['results' => $records->getCollection()->map(fn (StockLot $lot): array => ['id' => $lot->id, 'name' => '#'.$lot->id.' / '.($lot->batch_no ?: 'No batch').' / '.($lot->expiry_date?->format('Y-m-d') ?? 'No expiry').' / Qty '.$lot->remaining_quantity]), 'pagination' => ['more' => $records->hasMorePages()]]);
    }
}
