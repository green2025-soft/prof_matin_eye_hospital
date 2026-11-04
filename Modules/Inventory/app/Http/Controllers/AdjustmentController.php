<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Inventory\Http\Requests\AdjustmentRequest;
use Modules\Inventory\Models\Adjustment;
use Modules\Inventory\Models\StockLot;
use Modules\Inventory\Services\AdjustmentService;
use Modules\Inventory\Services\InventoryAccess;
use Modules\Inventory\Services\ItemService;

class AdjustmentController extends BaseController
{
    protected string $model = Adjustment::class;

    protected string $service = AdjustmentService::class;

    protected string $title = 'Stock adjustment';

    protected string $views = 'inventory::adjustments';

    protected string $parameter = 'adjustment';

    protected array $routeFilters = [
        'product_type' => 'product_type',
    ];

    protected array $defaultFilters = [
        'product_type' => 'medicine',
    ];

    public function index(ListRequest $request): View
    {
        $view = $this->listData($request);
        $request->attributes->set('inventory_product_type', $view->getData()['filters']['product_type']);

        return $view->with([
            'resourceParameter' => 'adjustment',
            'canCreate' => app(InventoryAccess::class)
                ->allows($request->user('web'), 'inventory-adjustments.create'),
        ]);
    }

    public function store(AdjustmentRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, Adjustment $adjustment, AdjustmentService $entries): View
    {
        $request->attributes->set('inventory_product_type', $adjustment->product_type);

        return view(
            'inventory::adjustments.show',
            $entries->invoiceData($request->user('web'), $adjustment) + [
                'routePrefix' => $this->routePrefix(),
                'resourceParameter' => 'adjustment',
            ]
        );
    }

    protected function formData(Account $actor, Model $record): array
    {
        $productType = $record->exists ? $record->product_type : request()
            ->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        request()->attributes->set('inventory_product_type', $productType);
        $lineInputs = $this->oldRows('lines');
        $variants = app(ItemService::class)
            ->selectedVariants($lineInputs);

        $lotIds = collect($lineInputs)
            ->pluck('stock_lot_id')
            ->filter(fn ($id): bool => is_scalar($id) && ctype_digit((string) $id))
            ->unique()
            ->all();
        $stockLots = $lotIds === [] ? collect() : StockLot::query()
            ->whereIn('id', $lotIds)
            ->get()
            ->keyBy('id');

        return [
            'entry' => $record,
            'lineInputs' => $lineInputs,
            'variants' => $variants,
            'inventorySettings' => Setting::values('inventory'),
            'stockLots' => $stockLots,
        ];
    }

    public function reverse(Request $request, Adjustment $adjustment, AdjustmentService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $adjustment);

        return redirect()
            ->route('inventory.adjustments.show', [
                'adjustment' => $adjustment,
                'product_type' => $adjustment->product_type,
            ])
            ->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
