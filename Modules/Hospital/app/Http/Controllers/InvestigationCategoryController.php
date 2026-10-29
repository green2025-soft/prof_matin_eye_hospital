<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Hospital\Http\Requests\InvestigationCategoryRequest;
use Modules\Hospital\Models\InvestigationCategory;

class InvestigationCategoryController extends BaseController
{
    protected string $model = InvestigationCategory::class;

    protected string $title = 'Investigation category';

    protected string $views = 'hospital::investigation-categories';

    protected string $parameter = 'investigation_category';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(InvestigationCategoryRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
