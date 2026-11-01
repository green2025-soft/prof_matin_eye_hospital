<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Inventory\Http\Requests\UnitRequest;
use Modules\Inventory\Models\Unit;

class UnitController extends BaseController
{
    protected string $model = Unit::class;

    protected string $title = 'Unit';

    protected string $views = 'inventory::units';

    protected string $parameter = 'unit';

    protected bool $inlineForm = true;

    protected array $selectorFields = ['id', 'name', 'symbol'];

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(UnitRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
