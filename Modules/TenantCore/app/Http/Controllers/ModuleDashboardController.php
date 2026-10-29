<?php

namespace Modules\TenantCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Nwidart\Modules\Facades\Module;

class ModuleDashboardController extends Controller
{
    public function index(Request $request): View
    {
        return view('tenantcore::module-dashboard.index', $this->navigation($request) + [
            'pageTitle' => 'Modules',
            'modules' => $this->availableModules($request),
        ]);
    }

    public function show(Request $request): View
    {
        $module = $this->availableModules($request)[$request->route('module')] ?? abort(404);
        abort_unless($module['route'] === $request->route()->getName(), 404);

        return view('tenantcore::module-dashboard.show', $this->navigation($request) + [
            'pageTitle' => $module['label'],
            'module' => $module,
        ]);
    }

    private function availableModules(Request $request): array
    {
        $actor = $request->user('web');
        $actor->loadMissing(['roles.permissions', 'permissions']);
        $gate = Gate::forUser($actor);
        $subscription = $request->attributes->has('application_subscription')
            ? $request->attributes->get('application_subscription') : Subscription::forTenant(Tenant::current());
        $request->attributes->set('application_subscription', $subscription);
        $plan = $subscription?->isUsable() ? $subscription->plan : null;
        $cards = [];

        foreach (Module::getOrdered('desc') as $module) {
            $dashboard = $module->get('tenant_dashboard');
            if ($dashboard === null || ! Route::has($dashboard['route'])) {
                continue;
            }

            $permission = $dashboard['permission'] ?? null;
            if ($permission !== null && (! $plan?->allowsRoutePermission($permission) || ! $gate->allows($permission))) {
                continue;
            }

            $cards[$module->getName()] = [
                'name' => $module->getName(), 'label' => $dashboard['label'],
                'description' => $module->getDescription(), 'icon' => $dashboard['icon'],
                'route' => $dashboard['route'], 'url' => route($dashboard['route']),
            ];
        }

        return $cards;
    }

    private function navigation(Request $request): array
    {
        return [
            'canSelectBranch' => (bool) $request->attributes->get('branch_can_switch'),
            'branchDashboardUrl' => route('tenantcore.branch-dashboard.index'),
            'moduleDashboardUrl' => route('tenantcore.module-dashboard.index'),
            'workspaceDashboardUrl' => route('tenantcore.dashboard'),
        ];
    }
}
