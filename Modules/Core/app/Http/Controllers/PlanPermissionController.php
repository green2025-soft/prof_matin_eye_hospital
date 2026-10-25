<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Requests\PlanPermissionPageRequest;
use Modules\Core\Http\Requests\PlanPermissionRequest;
use Modules\Core\Models\Plan;
use Modules\Core\Services\PermissionCatalog;

class PlanPermissionController extends BaseController
{
    protected string $model = Plan::class;

    protected string $title = 'Plan permission';

    protected string $views = 'core::plan-permissions';

    protected string $parameter = 'plan';

    public function index(PlanPermissionPageRequest $request, PermissionCatalog $catalog): View
    {
        $filters = $request->validated();
        $search = $filters['plan_search'] ?? '';
        $query = Plan::query()->select(['id', 'name']);

        if ($search !== '') {
            $prefix = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $search).'%';
            $query->whereRaw("name LIKE ? ESCAPE '!'", [$prefix]);
        }

        $matches = $query->orderBy('name')->orderBy('id')->limit(101)->get();
        $plans = $matches->take(100)->values();
        $selected = empty($filters['plan']) ? null : Plan::query()->findOrFail($filters['plan'], ['id', 'name', 'route_permissions']);

        if ($selected !== null && ! $plans->contains('id', $selected->getKey())) {
            $plans->push($selected);
        }

        $permissions = array_values(array_unique(config('permissions.tenant_routes')));
        $unrestricted = $selected !== null && $selected->route_permissions === null;

        return $this->listData($request, [
            'plans' => $plans,
            'selectedPlan' => $selected,
            'planSearch' => $search,
            'hasMorePlans' => $matches->count() > 100,
            'areas' => $catalog->areas(array_combine($permissions, $permissions)),
            'selectedPermissions' => $unrestricted ? $permissions : ($selected?->route_permissions ?? []),
            'permissionCount' => count($permissions),
            'unrestrictedRouteAccess' => $unrestricted,
            'canManagePermissions' => $selected !== null && Gate::allows('update', $selected),
            'pageTitle' => 'Plan permissions',
        ]);
    }

    public function store(PlanPermissionRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        return redirect()->route($this->routePrefix().'.index', ['plan' => $record->getKey()]);
    }
}
