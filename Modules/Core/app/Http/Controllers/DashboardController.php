<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Core\Models\Account;
use Modules\Core\Models\Role;
use Modules\Core\Models\Tenant;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->attributes->get('application_provider') === 'users' && $request->routeIs('home')) {
            return redirect()->route('tenantcore.module-dashboard.index');
        }

        $actor = $request->user('web');
        $permissions = $request->attributes->get('navigation_permissions', []);
        $central = $request->attributes->get('application_scope') === 'central';
        $accountModel = Account::modelForProvider($request->attributes->get('application_provider'));
        $accountRoutePrefix = $central ? 'core.accounts' : 'tenantcore.accounts';

        $tenantCount = $central && isset($permissions['tenants.view']) ? Tenant::count() : null;
        $accountCount = isset($permissions['accounts.view']) ? $accountModel::count() : null;
        $roleCount = isset($permissions['roles.view'])
            ? Role::where('guard_name', 'web')
                ->count()
            : null;
        $canViewRecent = $central ? $tenantCount !== null : $accountCount !== null;
        $recentRecords = null;

        if ($canViewRecent) {
            $query = $central
                ? Tenant::select(['id', 'name', 'slug', 'status', 'created_at'])
                : $accountModel::select(['id', 'name', 'email', 'created_at']);

            if (! $central && isset($permissions['accounts.update']) && ! $actor->isOwner()) {
                $query->with(['roles.permissions', 'permissions']);
            }

            $recentRecords = $query
                ->orderByDesc('id')
                ->limit(5)
                ->get();
        }

        $data = [
            'actor' => $actor,
            'central' => $central,
            'permissions' => $permissions,
            'hasBranches' => (bool) Tenant::current()?->has_branches,
            'contextName' => $request->attributes->get('application_name', 'Workspace'),
            'today' => now(),
            'accountRoutePrefix' => $accountRoutePrefix,
            'tenantCount' => $tenantCount,
            'accountCount' => $accountCount,
            'roleCount' => $roleCount,
            'permissionCount' => $actor->permissionNames()
                ->count(),
            'recentRecords' => $recentRecords,
            'recentStatusOptions' => Tenant::STATUSES,
            'accountInitials' => Str::upper(Str::substr(trim($actor->name), 0, 2)),
            'accountRoles' => $actor->getRoleNames()
                ->all(),
            'accountEditUrl' => isset($permissions[$actor->profileUpdatePermission()]) && Gate::allows('update', $actor)
                ? route($actor->profileRoutePrefix().'.edit', $actor) : null,
        ];

        return view('core::home', $data);
    }
}
