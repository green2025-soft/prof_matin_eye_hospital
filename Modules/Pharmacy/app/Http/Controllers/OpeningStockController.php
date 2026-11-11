<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Http\Requests\OpeningStockRequest;
use Modules\Pharmacy\Models\OpeningStock;
use Modules\Pharmacy\Services\ItemService;
use Modules\Pharmacy\Services\OpeningStockService;

class OpeningStockController extends BaseController
{
    protected string $model = OpeningStock::class;

    protected string $service = OpeningStockService::class;

    protected string $title = 'Opening stock';

    protected string $views = 'pharmacy::opening-stock';

    protected string $parameter = 'opening_stock';

    protected array $routeFilters = [
        'product_type' => 'product_type',
    ];

    protected array $defaultFilters = [
        'product_type' => 'medicine',
    ];

    public function index(ListRequest $request): View
    {
        $view           = $this->listData($request);
        $permissions    = $request->attributes->get('navigation_permissions', []);
        $request->attributes->set('pharmacy_product_type', $view->getData()['filters']['product_type']);

        $data = [
            'resourceParameter' => 'opening_stock',
            'canCreate'         => isset($permissions['pharmacy-opening-stock.create']),
        ];

        return $view->with($data);
    }

    public function store(OpeningStockRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, OpeningStock $openingStock, OpeningStockService $entries): View
    {
        $request->attributes->set('pharmacy_product_type', $openingStock->product_type);

        $data = $entries->invoiceData($request->user('web'), $openingStock) + [
            'routePrefix'       => $this->routePrefix(),
            'resourceParameter' => 'opening_stock',
        ];

        return view('pharmacy::opening-stock.show', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $request        = request();
        $productType    = $record->exists ? $record->product_type : $request->query('product_type', 'medicine');
        $productType    = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        $request->attributes->set('pharmacy_product_type', $productType);
        $lineInputs     = $this->oldRows('lines');
        $variants       = app(ItemService::class)->selectedVariants($lineInputs);

        $data = [
            'entry' => $record,
            'lineInputs' => $lineInputs,
            'variants' => $variants,
        ];

        return $data;
    }

    public function reverse(Request $request, OpeningStock $openingStock, OpeningStockService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $openingStock);

        return redirect()->route('pharmacy.opening-stock.show', [
                'opening_stock' => $openingStock,
                'product_type'  => $openingStock->product_type,
            ])->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
