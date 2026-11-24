<?php

namespace Modules\Pharmacy\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\ProductVariant;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\StockBalance;
use Modules\Pharmacy\Models\StockLot;
use Modules\Pharmacy\Services\ItemService;
use Modules\Pharmacy\Services\StockService;

class PharmacySelectorController extends Controller
{
    public function reportProducts(Request $request): Response
    {
        $productType    = $request->query('product_type', 'medicine');
        $search         = $request->string('search')->trim()->toString();
        $page           = $request->integer('page', 1);

        $query = ProductVariant::with('product:id,name')
            ->whereHas('product', fn ($products) => $products->where('type', $productType))
            ->select(['id', 'product_id', 'sku'])
            ->withSum('stockBalance as stock_quantity', 'quantity');

        if ($search !== '') {
            $term = '%'.addcslashes($search, '\\%_').'%';

            $query->where(fn ($matches) => $matches
                ->whereLike('sku', $term)
                ->orWhereLike('name', $term)
                ->orWhereHas('product', fn ($products) => $products
                    ->whereLike('name', $term)
                    ->orWhereLike('code', $term)
                    ->orWhereLike('barcode', $term)));
        }

        $records = $query
            ->orderByDesc('stock_quantity')
            ->orderBy(Product::select('name')->whereColumn('id', 'phr_product_variants.product_id'))
            ->orderBy('id')
            ->simplePaginate(20, ['*'], 'page', $page);

        $data = [
            'records' => $records,
        ];

        return response()->view('pharmacy::selectors.report-products', $data, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function items(Request $request, ItemService $items): Response
    {
        $productType = $request->query('product_type', 'medicine');
        $purpose    = $request->query('purpose', 'stock');
        $search     = $request->string('search')->trim()->toString();
        $page       = $request->integer('page', 1);

        $products = Product::where('type', $productType)->where('active', true);

        // Purchase can add a pack conversion; other entries need an existing base unit.
        if ($purpose !== 'purchase') {
            $products->whereHas('units', fn ($units) => $units->where('factor', 1));
        }

        $records = $items->optionRecords($products, $search, $page);

        $variantIds = $records->getCollection()->modelKeys();

        // Sales must exclude expired or future stock; stock entries use the branch balance.
        $balances = $purpose === 'sale' ? app(StockService::class)->availableQuantities($productType, now()->toDateString(), $variantIds): StockBalance::whereIn('variant_id', $variantIds)->pluck('quantity', 'variant_id');

        $data = [
            'records'   => $records,
            'balances'  => $balances,
            'results'   => $items->optionResults($records, $balances),
        ];

        return response()->view('pharmacy::selectors.items', $data, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function purchaseSources(Request $request): JsonResponse
    {
        return $this->sourceOptions($request, Purchase::select(['id', 'number as name']));
    }

    public function saleSources(Request $request): JsonResponse
    {
        return $this->sourceOptions($request, Sale::select(['id', 'number as name']));
    }

    private function sourceOptions(Request $request, Builder $query): JsonResponse
    {
        $productType = $request->query('product_type', 'medicine');
        $search = $request->string('search')->trim()->toString();
        $page = $request->integer('page', 1);

        $query->where('product_type', $productType)
            ->whereNull('voided_at')
            ->whereNotNull('posted_at')
            ->whereHas('lines', fn ($lines) => $lines->whereColumn('base_quantity', '>', 'returned_base_quantity'));

        if ($search !== '') {
            $query->whereLike('number', '%'.addcslashes($search, '\\%_').'%');
        }

        $records = $query
            ->orderByDesc('id')
            ->simplePaginate(20, ['*'], 'page', $page);

        $data = [
            'results' => $records->items(),
            'pagination' => [
                'more' => $records->hasMorePages(),
            ],
        ];

        return response()->json($data);
    }

    public function lots(Request $request): Response
    {
        $variantId = $request->integer('variant_id');
        $search = $request->string('search')->trim()->toString();
        $page = $request->integer('page', 1);

        $query = StockLot::where('variant_id', $variantId)
            ->whereNull('reversed_at')
            ->where('remaining_quantity', '>', 0);

        if ($search !== '') {
            $query->whereLike('batch_no', '%'.addcslashes($search, '\\%_').'%');
        }

        $records = $query
            ->orderBy('received_at')
            ->orderBy('id')
            ->simplePaginate(20, ['id', 'batch_no', 'expiry_date', 'remaining_quantity'], 'page', $page);

        $data = [
            'records' => $records,
        ];

        return response()->view('pharmacy::selectors.lots', $data, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
