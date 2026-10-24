<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Requests\PermissionPageRequest;
use Modules\Core\Http\Requests\RolePermissionRequest;
use Modules\Core\Models\Role;
use Modules\Core\Services\RolePermissionService;

class PermissionController extends BaseController
{
    protected string $model = Role::class;

    protected string $title = 'Role permission';

    protected string $service = RolePermissionService::class;

    protected string $views = 'core::permissions';

    protected string $parameter = 'role';

    public function index(PermissionPageRequest $request): View
    {
        return $this->listData($request, app($this->service)->pageData($request->user('web'), $request->validated()));
    }

    public function store(RolePermissionRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        return redirect()->route($this->routePrefix().'.index', ['role' => $record->getKey()]);
    }
}
