<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\TenantCore\Http\Requests\DivisionRequest;
use Modules\TenantCore\Models\Division;

class DivisionController extends BaseController
{
    protected string $model = Division::class;

    protected string $title = 'Division';

    protected string $views = 'tenantcore::divisions';

    protected string $parameter = 'division';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(DivisionRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
