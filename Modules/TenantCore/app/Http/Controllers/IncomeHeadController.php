<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\TenantCore\Http\Requests\IncomeHeadRequest;
use Modules\TenantCore\Models\IncomeHead;

class IncomeHeadController extends BaseController
{
    protected string $model = IncomeHead::class;

    protected string $title = 'Income head';

    protected string $views = 'tenantcore::income-heads';

    protected string $parameter = 'income_head';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(IncomeHeadRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
