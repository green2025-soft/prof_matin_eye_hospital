<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Inventory\Http\Requests\TransferRequest;
use Modules\Inventory\Models\Transfer;
use Modules\Inventory\Services\InventoryAccess;
use Modules\Inventory\Services\ItemService;
use Modules\Inventory\Services\TransferService;
use Modules\TenantCore\Models\Branch;

class TransferController extends BaseController
{
    protected string $model = Transfer::class;

    protected string $service = TransferService::class;

    protected string $title = 'Branch transfer';

    protected string $views = 'inventory::transfers';

    protected string $parameter = 'transfer';

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
            'resourceParameter' => 'transfer',
            'canCreate' => app(InventoryAccess::class)
                ->allows($request->user('web'), 'inventory-transfers.create'),
        ]);
    }

    public function store(TransferRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, Transfer $transfer, TransferService $entries): View
    {
        $request->attributes->set('inventory_product_type', $transfer->product_type);

        return view(
            'inventory::transfers.show',
            $entries->invoiceData($request->user('web'), $transfer) + [
                'routePrefix' => $this->routePrefix(),
                'resourceParameter' => 'transfer',
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

        return [
            'entry' => $record,
            'lineInputs' => $lineInputs,
            'variants' => $variants,
            'branches' => Branch::query()
                ->where('id', '!=', request()->attributes->get('branch_id'))
                ->orderBy('name')
                ->get(['id', 'name']),
        ];
    }

    public function reverse(Request $request, Transfer $transfer, TransferService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $transfer);

        return redirect()
            ->route('inventory.transfers.show', [
                'transfer' => $transfer,
                'product_type' => $transfer->product_type,
            ])
            ->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
