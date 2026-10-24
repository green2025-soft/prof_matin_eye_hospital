<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Requests\PermissionPageRequest;
use Modules\Core\Http\Requests\RolePermissionRequest;
use Modules\Core\Models\Role;
use Modules\Core\Services\RolePermissionService;

class PermissionController extends Controller
{
    public function index(PermissionPageRequest $request, RolePermissionService $permissions): View
    {
        $values = $request->validated();

        return view('core::permissions.index', $permissions->pageData($request->user('web'), $values['role'] ?? null, $values['role_search'] ?? ''));
    }

    public function store(RolePermissionRequest $request, Role $role, RolePermissionService $permissions): RedirectResponse
    {
        $permissions->sync($request->user('web'), $role, $request->validated('permissions'));

        return redirect()->route('core.permissions.index', ['role' => $role->getKey()])->with('status', 'Role permissions updated.');
    }
}
