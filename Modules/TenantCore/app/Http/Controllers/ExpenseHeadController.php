<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\TenantCore\Http\Requests\ExpenseHeadRequest;
use Modules\TenantCore\Models\ExpenseHead;

class ExpenseHeadController extends BaseController
{
    protected string $model = ExpenseHead::class;

    protected string $title = 'Expense head';

    protected string $views = 'tenantcore::expense-heads';

    protected string $parameter = 'expense_head';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(ExpenseHeadRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
