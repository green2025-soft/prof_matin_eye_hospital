<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\TenantCore\Http\Requests\IncomeRequest;
use Modules\TenantCore\Models\Income;
use Modules\TenantCore\Models\IncomeHead;
use Modules\TenantCore\Models\PaymentType;
use Modules\TenantCore\Services\IncomeService;

class IncomeController extends BaseController
{
    protected string $model = Income::class;

    protected string $title = 'Income';

    protected string $service = IncomeService::class;

    protected string $views = 'tenantcore::incomes';

    protected string $parameter = 'income';

    public function index(ListRequest $request): View
    {
        $data = [
            'selectedHead' => $this->selectedOption(
                IncomeHead::query(),
                getValue('filter_head_id', null, $request->validated('filter_head_id')),
            ),
        ];

        return $this->listData($request)
            ->with($data);
    }

    public function store(IncomeRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $paymentTypes = PaymentType::orderBy('ordering')
            ->orderBy('id')
            ->get(['id', 'name']);

        return [
            'selectedHead' => $this->selectedOption(
                IncomeHead::query(),
                getValue('head_id', $record),
            ),
            'paymentTypes' => $paymentTypes,
            'paymentTypeId' => getValue('payment_type_id', $record, $paymentTypes->firstWhere('name', 'Cash')?->id),
            'entryDate' => $record->entry_date?->toDateString() ?? now(config('app.timezone'))
                ->toDateString(),
        ];
    }
}
