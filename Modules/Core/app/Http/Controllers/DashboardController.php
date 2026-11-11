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
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;

class DashboardController extends Controller
{
    public function index(Request $request, AccessManager $access): View|RedirectResponse
    {
        if ($request->attributes->get('application_provider') === 'users' && $request->routeIs('home')) {
            return redirect()
                ->route('tenantcore.module-dashboard.index');
        }

        $actor = $request->user('web');
        abort_unless($actor instanceof Account, 403);
        $actor->loadMissing(['roles.permissions', 'permissions']);

        $central = $request->attributes->get('application_scope') === 'central';
        $accountModel = Account::modelForProvider($central ? 'admins' : 'users');
        $subscription = $central ? null : Subscription::forTenant(Tenant::current());
        $request->attributes->set('application_subscription', $subscription);
        $plan = $subscription?->isUsable() ? $subscription->plan : null;
        $permissions = $request->attributes->get('navigation_permissions')
            ?? $access->navigationPermissions($actor, $plan);
        $request->attributes->set('navigation_permissions', $permissions);
        $accountRoutePrefix = $central ? 'core.accounts' : 'tenantcore.accounts';
        $canViewTenants = $central && Gate::allows('viewAny', Tenant::class);
        $canViewAccounts = ($central || $plan?->allowsRoutePermission('accounts.view')) && Gate::allows('viewAny', $accountModel);
        $canViewRoles = ($central || $plan?->allowsRoutePermission('roles.view')) && Gate::allows('viewAny', Role::class);
        $canUpdateAccounts = $central || $plan?->allowsRoutePermission('accounts.update');
        $metrics = [];

        if ($canViewTenants) {
            $metrics[] = [
                'label' => 'Tenants',
                'value' => Tenant::count(),
                'caption' => 'Registered workspaces',
                'icon' => 'building',
                'tone' => 'primary',
                'url' => route('core.tenants.index'),
            ];
        }

        if ($canViewAccounts) {
            $metrics[] = [
                'label' => $central ? 'Administrators' : 'Users',
                'value' => $accountModel::count(),
                'caption' => $central ? 'Platform accounts' : 'Workspace accounts',
                'icon' => 'users',
                'tone' => 'success',
                'url' => route($accountRoutePrefix.'.index'),
            ];
        }

        if ($canViewRoles) {
            $metrics[] = [
                'label' => 'Roles',
                'value' => Role::where('guard_name', 'web')
                    ->count(),
                'caption' => 'Access roles in this workspace',
                'icon' => 'shield-halved',
                'tone' => 'warning',
                'url' => route('core.roles.index'),
            ];
        }

        $metrics[] = [
            'label' => 'Your permissions',
            'value' => $actor->getAllPermissions()
                ->where('guard_name', 'web')
                ->unique('id')
                ->count(),
            'caption' => 'Assigned access permissions',
            'icon' => 'key',
            'tone' => 'info',
            'url' => null,
        ];

        $canViewRecent = $central ? $canViewTenants : $canViewAccounts;
        $recentRecords = collect();
        $recentRoutePrefix = $central ? 'core.tenants' : $accountRoutePrefix;

        if ($canViewRecent) {
            $query = $central
                ? Tenant::select(['id', 'name', 'slug', 'status', 'created_at'])
                : $accountModel::select(['id', 'name', 'email', 'created_at']);

            if (! $central && $canUpdateAccounts && ! $access->isOwner($actor)) {
                $query->with(['roles.permissions', 'permissions']);
            }

            $recentRecords = $query->orderByDesc('id')
                ->limit(5)
                ->get()
                ->map(function ($record) use ($central, $recentRoutePrefix, $canUpdateAccounts): array {
                    return [
                        'name' => $record->name,
                        'detail' => $central ? $record->slug : $record->email,
                        'status' => $central ? $record->status : null,
                        'statusLabel' => $central ? (Tenant::STATUSES[$record->status] ?? $record->status) : null,
                        'created' => $record->created_at?->format('d M Y') ?? '—',
                        'createdIso' => $record->created_at?->toDateString(),
                        'url' => $canUpdateAccounts && Gate::allows('update', $record) ? route($recentRoutePrefix.'.edit', $record) : null,
                    ];
                });
        }

        $quickActions = [];

        if ($central && Gate::allows('create', Tenant::class)) {
            $quickActions[] = [
                'label' => 'Create tenant',
                'icon' => 'building',
                'url' => route('core.tenants.create'),
                'tone' => 'primary',
            ];
        }

        if (($central || $plan?->allowsRoutePermission('accounts.create')) && Gate::allows('create', $accountModel)) {
            $quickActions[] = [
                'label' => $central ? 'Add administrator' : 'Add user',
                'icon' => 'user-plus',
                'url' => route($accountRoutePrefix.'.create'),
                'tone' => 'success',
            ];
        }

        if (($central || $plan?->allowsRoutePermission('roles.create')) && Gate::allows('create', Role::class)) {
            $quickActions[] = [
                'label' => 'Create role',
                'icon' => 'shield-halved',
                'url' => route('core.roles.create'),
                'tone' => 'warning',
            ];
        }

        if ($canViewTenants) {
            $quickActions[] = [
                'label' => 'Tenants',
                'icon' => 'building',
                'url' => route('core.tenants.index'),
                'tone' => 'danger',
            ];
        }

        if ($canViewAccounts) {
            $quickActions[] = [
                'label' => $central ? 'Administrators' : 'Users',
                'icon' => 'users',
                'url' => route($accountRoutePrefix.'.index'),
                'tone' => 'primary',
            ];
        }

        if ($canViewRoles) {
            $quickActions[] = [
                'label' => 'Roles',
                'icon' => 'shield-halved',
                'url' => route('core.roles.index'),
                'tone' => 'warning',
            ];
        }

        if (isset($permissions['permissions.view'])) {
            $quickActions[] = [
                'label' => 'Permissions',
                'icon' => 'key',
                'url' => route('core.permissions.index'),
                'tone' => 'info',
            ];
        }

        if (! $central && isset($permissions['audit-logs.view'])) {
            $quickActions[] = [
                'label' => 'Audit logs',
                'icon' => 'clock-rotate-left',
                'url' => route('tenantcore.audit-logs.index'),
                'tone' => 'primary',
            ];
        }

        if (! $central && Tenant::current()?->has_branches && isset($permissions['branches.view'])) {
            $quickActions[] = [
                'label' => 'Branches',
                'icon' => 'building',
                'url' => route('tenantcore.branches.index'),
                'tone' => 'success',
            ];
        }

        if (! $central && isset($permissions['billing.view'])) {
            $quickActions[] = [
                'label' => 'Billing',
                'icon' => 'file-invoice-dollar',
                'url' => route('tenantcore.billing.index'),
                'tone' => 'danger',
            ];
        }

        if (isset($permissions['settings.view'])) {
            $quickActions[] = [
                'label' => 'Settings',
                'icon' => 'gear',
                'url' => route($central ? 'core.settings.index' : 'tenantcore.settings.index'),
                'tone' => 'secondary',
            ];
        }

        $date = now();

        $data = [
            'metrics' => $metrics,
            'contextName' => $request->attributes->get('application_name', 'Workspace'),
            'dateLabel' => $date->format('l, d F Y'),
            'dateIso' => $date->toDateString(),
            'recentTitle' => $central ? 'Recent tenants' : 'Recently added users',
            'recentDescription' => $central ? 'The newest workspaces on your platform.' : 'The newest accounts in your workspace.',
            'recentRecords' => $recentRecords,
            'recentListUrl' => $canViewRecent ? route($recentRoutePrefix.'.index') : null,
            'canViewRecent' => $canViewRecent,
            'central' => $central,
            'quickActions' => $quickActions,
            'accountName' => $actor->name,
            'accountEmail' => $actor->email,
            'accountInitials' => Str::upper(Str::substr(trim($actor->name), 0, 2)),
            'accountRoles' => $actor->roles->where('guard_name', 'web')
                ->pluck('name')
                ->all(),
            'accountEditUrl' => ($central || $plan?->allowsRoutePermission($actor->profileUpdatePermission())) && Gate::allows('update', $actor)
                ? route($actor->profileRoutePrefix().'.edit', $actor) : null,
        ];

        return view('core::home', $data);
    }
}
