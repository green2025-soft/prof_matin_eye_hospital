<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Inventory\Http\Requests\DosageRequest;
use Modules\Inventory\Models\Dosage;

class DosageController extends BaseController
{
    protected string $model = Dosage::class;

    protected string $title = 'Dosage';

    protected string $views = 'inventory::dosages';

    protected string $parameter = 'dosage';

    protected bool $inlineForm = true;

    protected array $selectorFields = ['id', 'name'];

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(DosageRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
