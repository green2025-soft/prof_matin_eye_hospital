<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\TenantCore\Http\Requests\ExpenseRequest;
use Modules\TenantCore\Models\Expense;
use Modules\TenantCore\Models\ExpenseHead;
use Modules\TenantCore\Models\PaymentType;
use Modules\TenantCore\Services\ExpenseService;

class ExpenseController extends BaseController
{
    protected string $model = Expense::class;

    protected string $title = 'Expense';

    protected string $service = ExpenseService::class;

    protected string $views = 'tenantcore::expenses';

    protected string $parameter = 'expense';

    public function index(ListRequest $request): View
    {
        $data = [
            'selectedHead' => $this->selectedOption(
                ExpenseHead::query(),
                getValue('filter_head_id', null, $request->validated('filter_head_id')),
            ),
        ];

        return $this->listData($request)
            ->with($data);
    }

    public function store(ExpenseRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $oldItems = $this->oldRows('items');
        $selectedHeads = $oldItems === [] ? collect() : ExpenseHead::whereKey(array_column($oldItems, 'head_id'))
            ->get(['id', 'name'])
            ->keyBy('id');
        $paymentTypes = PaymentType::orderBy('ordering')
            ->orderBy('id')
            ->get(['id', 'name']);

        return [
            'selectedHeads' => $selectedHeads,
            'paymentTypes' => $paymentTypes,
            'paymentTypeId' => getValue('payment_type_id', $record, $paymentTypes->firstWhere('name', 'Cash')?->id),
            'entryDate' => $record->entry_date?->toDateString() ?? now(config('app.timezone'))
                ->toDateString(),
        ];
    }
}
