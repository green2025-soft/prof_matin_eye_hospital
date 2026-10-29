<?php

namespace Modules\TenantCore\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\TenantCore\Http\Requests\BranchRequest;
use Modules\TenantCore\Models\Branch;

class BranchController extends BaseController
{
    protected string $model = Branch::class;

    protected string $title = 'Branch';

    protected string $views = 'tenantcore::branches';

    protected string $parameter = 'branch';

    protected array $uploads = ['logo' => 'logo_path'];

    protected string $uploadDirectory = 'branches';

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(BranchRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
