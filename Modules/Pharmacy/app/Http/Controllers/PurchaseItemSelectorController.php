<?php

namespace Modules\Pharmacy\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\StockBalance;
use Modules\Pharmacy\Services\ItemService;

class PurchaseItemSelectorController extends Controller
{
    public function items(Request $request, ItemService $items): JsonResponse
    {
        $productType = $request->query('product_type', 'medicine');
        $search = $request->string('search')->trim()->toString();
        $page = $request->integer('page', 1);
        $products = Product::query()
            ->where('type', $productType)
            ->where('active', true);
        $records = $items->optionRecords($products, $search, $page);
        $records->getCollection()->load('latestPurchaseItem:id,variant_id,unit_price,factor');
        $balances = StockBalance::whereIn('variant_id', $records->getCollection()->modelKeys())
            ->pluck('quantity', 'variant_id');
        $results = $items->optionResults($records, $balances);
        $index = 0;

        foreach ($records as $variant) {
            $latestPurchaseItem = $variant->latestPurchaseItem;
            $results[$index]['last_purchase_rate'] = $latestPurchaseItem === null
                ? null
                : moneyDecimal($latestPurchaseItem->unit_price);
            $results[$index]['last_purchase_factor'] = $latestPurchaseItem?->factor;
            $index++;
        }

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $records->hasMorePages(),
            ],
        ]);
    }
}
