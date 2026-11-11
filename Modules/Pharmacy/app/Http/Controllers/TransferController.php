<?php

namespace Modules\Pharmacy\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Http\Requests\TransferRequest;
use Modules\Pharmacy\Models\Transfer;
use Modules\Pharmacy\Services\ItemService;
use Modules\Pharmacy\Services\TransferService;
use Modules\TenantCore\Models\Branch;

class TransferController extends BaseController
{
    protected string $model = Transfer::class;

    protected string $service = TransferService::class;

    protected string $title = 'Branch transfer';

    protected string $views = 'pharmacy::transfers';

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
        $permissions = $request->attributes->get('navigation_permissions', []);
        $request->attributes->set('pharmacy_product_type', $view->getData()['filters']['product_type']);

        $data = [
            'resourceParameter' => 'transfer',
            'canCreate'         => isset($permissions['pharmacy-transfers.create']),
        ];

        return $view->with($data);
    }

    public function store(TransferRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, Transfer $transfer, TransferService $entries): View
    {
        $request->attributes->set('pharmacy_product_type', $transfer->product_type);

        $data = $entries->invoiceData($request->user('web'), $transfer) + [
            'routePrefix'       => $this->routePrefix(),
            'resourceParameter' => 'transfer',
        ];

        return view('pharmacy::transfers.show', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $request = request();
        $productType = $record->exists ? $record->product_type : $request->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        $request->attributes->set('pharmacy_product_type', $productType);
        $lineInputs = $this->oldRows('lines');
        $variants = app(ItemService::class)->selectedVariants($lineInputs);

        $data = [
            'entry'     => $record,
            'lineInputs' => $lineInputs,
            'variants'  => $variants,
            'branches'  => Branch::where('id', '!=', $request->attributes->get('branch_id'))->where('is_default', false)->orderBy('name')->get(['id', 'name']),
        ];

        return $data;
    }

    public function reverse(Request $request, Transfer $transfer, TransferService $entries): RedirectResponse
    {
        $entries->reverse($request->user('web'), $transfer);

        return redirect()->route('pharmacy.transfers.show', [
                'transfer'      => $transfer,
                'product_type'  => $transfer->product_type,
            ])
            ->with('status', 'Entry voided. Stock and balances have been reversed.');
    }
}
