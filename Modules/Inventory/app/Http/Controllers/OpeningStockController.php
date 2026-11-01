<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Inventory\Http\Requests\OpeningStockRequest;
use Modules\Inventory\Models\OpeningStock;
use Modules\Inventory\Services\InventoryAccess;
use Modules\Inventory\Services\ItemService;
use Modules\Inventory\Services\OpeningStockService;

class OpeningStockController extends BaseController
{
    protected string $model = OpeningStock::class;

    protected string $service = OpeningStockService::class;

    protected string $title = 'Opening stock';

    protected string $views = 'inventory::opening-stock';

    protected string $parameter = 'opening_stock';

    protected array $routeFilters = ['product_type' => 'product_type'];

    protected array $defaultFilters = ['product_type' => 'medicine'];

    public function index(ListRequest $request): View
    {
        $view = $this->listData($request);
        $request->attributes->set('inventory_product_type', $view->getData()['filters']['product_type']);

        return $view->with([
            'resourceParameter' => 'opening_stock',
            'canCreate' => app(InventoryAccess::class)->allows($request->user('web'), 'inventory-opening-stock.create'),
        ]);
    }

    public function store(OpeningStockRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, OpeningStock $openingStock, OpeningStockService $entries): View
    {
        $request->attributes->set('inventory_product_type', $openingStock->product_type);

        return view('inventory::opening-stock.show', $entries->invoiceData($request->user('web'), $openingStock) + [
            'routePrefix' => $this->routePrefix(),
            'resourceParameter' => 'opening_stock',
        ]);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $productType = $record->exists ? $record->product_type : request()->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        request()->attributes->set('inventory_product_type', $productType);
        $lineInputs = $this->oldRows('lines');
        $variants = app(ItemService::class)->selectedVariants($lineInputs);

        return [
            'document' => $record,
            'lineInputs' => $lineInputs,
            'variants' => $variants,
        ];
    }

    public function reverse(Request $request, OpeningStock $openingStock, OpeningStockService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $openingStock);

        return redirect()->route('inventory.opening-stock.show', [
            'opening_stock' => $openingStock,
            'product_type' => $openingStock->product_type,
        ])->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
