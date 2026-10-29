<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Hospital\Http\Requests\OperationCategoryRequest;
use Modules\Hospital\Models\OperationCategory;

class OperationCategoryController extends BaseController
{
    protected string $model = OperationCategory::class;

    protected string $title = 'Operation category';

    protected string $views = 'hospital::operation-categories';

    protected string $parameter = 'operation_category';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(OperationCategoryRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
