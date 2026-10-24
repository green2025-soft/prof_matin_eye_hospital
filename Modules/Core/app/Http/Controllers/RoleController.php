<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Modules\Core\Http\Requests\RoleRequest;
use Modules\Core\Models\Role;
use Modules\Core\Services\RoleService;

class RoleController extends BaseController
{
    protected string $model = Role::class;

    protected string $title = 'Role';

    protected string $service = RoleService::class;

    protected string $views = 'core::roles';

    protected string $parameter = 'role';

    public string $routePrefix = 'core.roles';

    public function store(RoleRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
