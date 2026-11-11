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
use Modules\Pharmacy\Http\Requests\WastageRequest;
use Modules\Pharmacy\Models\StockLot;
use Modules\Pharmacy\Models\Wastage;
use Modules\Pharmacy\Services\ItemService;
use Modules\Pharmacy\Services\WastageService;

class WastageController extends BaseController
{
    protected string $model = Wastage::class;

    protected string $service = WastageService::class;

    protected string $title = 'Wastage';

    protected string $views = 'pharmacy::wastage';

    protected string $parameter = 'wastage';

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
            'resourceParameter' => 'wastage',
            'canCreate'         => isset($permissions['pharmacy-damage.create']),
        ];

        return $view->with($data);
    }

    public function store(WastageRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, Wastage $wastage, WastageService $entries): View
    {
        $request->attributes->set('pharmacy_product_type', $wastage->product_type);

        $data = $entries->invoiceData($request->user('web'), $wastage) + [
            'routePrefix'       => $this->routePrefix(),
            'resourceParameter' => 'wastage',
        ];

        return view('pharmacy::wastage.show', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $request        = request();
        $productType    = $record->exists ? $record->product_type : $request->query('product_type', 'medicine');
        $productType    = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        $request->attributes->set('pharmacy_product_type', $productType);
        $lineInputs     = $this->oldRows('lines');
        $variants       = app(ItemService::class)->selectedVariants($lineInputs);

        $lotIds = collect($lineInputs)->pluck('stock_lot_id')
            ->filter(fn ($id): bool => is_scalar($id) && ctype_digit((string) $id))->unique()->all();

        $stockLots = $lotIds === [] ? collect() : StockLot::whereIn('id', $lotIds)->get()->keyBy('id');

        $data = [
            'entry'             => $record,
            'lineInputs'        => $lineInputs,
            'variants'          => $variants,
            'pharmacySettings'  => Setting::values('pharmacy'),
            'stockLots'         => $stockLots,
        ];

        return $data;
    }

    public function reverse(Request $request, Wastage $wastage, WastageService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $wastage);

        return redirect()->route('pharmacy.damage.show', [
                'wastage'       => $wastage,
                'product_type'  => $wastage->product_type,
            ])
            ->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
