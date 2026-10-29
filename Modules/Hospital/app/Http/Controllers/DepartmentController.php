<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Hospital\Http\Requests\DepartmentRequest;
use Modules\Hospital\Models\Department;

class DepartmentController extends BaseController
{
    protected string $model = Department::class;

    protected string $title = 'Doctor Department';

    protected string $views = 'hospital::departments';

    protected string $parameter = 'department';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(DepartmentRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
