<?php

namespace Modules\TenantCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Modules\Core\Models\Setting;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Module as ModuleInstance;

class ModuleDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $permissions = $request->attributes->get('navigation_permissions', []);

        $data = [
            'pageTitle' => 'Modules',
            'modules' => $this->availableModules($request),
            'canArrangeModules' => ($permissions['settings.view'] ?? false) && ($permissions['settings.update'] ?? false),
        ];

        return view('tenantcore::module-dashboard.index', $data);
    }

    public function show(Request $request): View
    {
        $module = $this->availableModules($request)[strtolower((string) $request->route('module'))] ?? abort(404);
        $dashboard = $module->get('tenant_dashboard');
        abort_unless($dashboard['route'] === $request->route()->getName(), 404);

        $data = [
            'pageTitle' => $dashboard['label'],
            'module' => $module,
            'canSelectBranch' => (bool) $request->attributes->get('branch_can_switch'),
            'branchDashboardUrl' => route('tenantcore.branch-dashboard.index'),
            'moduleDashboardUrl' => route('tenantcore.module-dashboard.index'),
            'workspaceDashboardUrl' => route('tenantcore.dashboard'),
        ];

        return view('tenantcore::module-dashboard.show', $data);
    }

    private function availableModules(Request $request): array
    {
        $permissions = $request->attributes->get('navigation_permissions', []);

        $modules = array_filter(Module::getOrdered('desc'), function (ModuleInstance $module) use ($permissions): bool {
            $dashboard = $module->get('tenant_dashboard');
            if ($dashboard === null || ! Route::has($dashboard['route'])) {
                return false;
            }

            $permission = $dashboard['permission'] ?? null;

            return $permission === null || ($permissions[$permission] ?? false);
        });
        $order = Setting::values('general')['module_order'];

        // Stable sorting keeps native module order for blank or equal ranks.
        return collect($modules)
            ->sortBy(fn (ModuleInstance $module): int => (int) ($order[$module->getLowerName()] ?? PHP_INT_MAX))
            ->all();
    }
}
