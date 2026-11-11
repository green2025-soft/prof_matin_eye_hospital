<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Pharmacy\Http\Requests\AdjustmentRequest;
use Modules\Pharmacy\Models\Adjustment;
use Modules\Pharmacy\Models\StockLot;
use Modules\Pharmacy\Services\AdjustmentService;
use Modules\Pharmacy\Services\ItemService;

class AdjustmentController extends BaseController
{
    protected string $model = Adjustment::class;

    protected string $service = AdjustmentService::class;

    protected string $title = 'Stock adjustment';

    protected string $views = 'pharmacy::adjustments';

    protected string $parameter = 'adjustment';

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
            'resourceParameter' => 'adjustment',
            'canCreate'         => isset($permissions['pharmacy-adjustments.create']),
        ];

        return $view->with($data);
    }

    public function store(AdjustmentRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, Adjustment $adjustment, AdjustmentService $entries): View
    {
        $request->attributes->set('pharmacy_product_type', $adjustment->product_type);

        $data = $entries->invoiceData($request->user('web'), $adjustment) + [
            'routePrefix'       => $this->routePrefix(),
            'resourceParameter' => 'adjustment',
        ];

        return view('pharmacy::adjustments.show', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $request        = request();
        $productType    = $record->exists ? $record->product_type : $request->query('product_type', 'medicine');
        $productType    = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';

        $request->attributes->set('pharmacy_product_type', $productType);

        $lineInputs     = $this->oldRows('lines');
        $variants       = app(ItemService::class)->selectedVariants($lineInputs);

        $lotIds         = collect($lineInputs)->pluck('stock_lot_id')
                        ->filter(fn ($id): bool => is_scalar($id) && ctype_digit((string) $id))
                        ->unique()->all();

        $stockLots      = $lotIds === [] ? collect() : StockLot::whereIn('id', $lotIds)->get()->keyBy('id');

        $data = [
            'entry'             => $record,
            'lineInputs'        => $lineInputs,
            'variants'          => $variants,
            'pharmacySettings'  => Setting::values('pharmacy'),
            'stockLots'         => $stockLots,
        ];

        return $data;
    }

    public function reverse(Request $request, Adjustment $adjustment, AdjustmentService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $adjustment);

        return redirect()
            ->route('pharmacy.adjustments.show', ['adjustment' => $adjustment, 'product_type' => $adjustment->product_type])
            ->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
