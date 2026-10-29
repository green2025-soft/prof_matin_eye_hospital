<?php

namespace Modules\Core\View\Composers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;

class LayoutComposer
{
    public function __construct(private Request $request, private readonly AccessManager $access) {}

    public function compose(View $view): void
    {
        $actor = $this->request->user('web');
        abort_unless($actor === null || $actor instanceof Account, 403);

        $central = $this->request->attributes->get('application_scope') === 'central';
        $setting = $view->getData()['setting'] ?? null;
        $connection = (new Setting)->getConnection();
        $branding = $setting instanceof Setting && $setting->exists && $setting->group === 'branding'
            && $setting->getConnection()->getName() === $connection->getName()
            && $setting->getConnection()->getDatabaseName() === $connection->getDatabaseName()
                ? $setting->publicValues() : Setting::values('branding');
        $user = null;
        $navigation = [];
        $permissions = [];
        $accountPrefix = $central ? 'core.accounts' : 'tenantcore.accounts';

        if ($actor !== null) {
            $subscription = $central ? null : ($this->request->attributes->has('application_subscription')
                ? $this->request->attributes->get('application_subscription')
                : Subscription::forTenant(Tenant::current()));
            $plan = $subscription?->isUsable() ? $subscription->plan : null;
            $permissions = $this->access->navigationPermissions($actor, $plan);
            $gate = Gate::forUser($actor);

            $user = [
                'name' => $actor->name,
                'email' => $actor->email,
                'initial' => Str::upper(Str::substr($actor->name, 0, 1)),
                'label' => $central ? 'Platform account' : 'Workspace account',
                'editUrl' => $gate->allows('update', $actor) && ($central || $plan?->allowsRoutePermission($actor->profileUpdatePermission()))
                    ? route($actor->profileRoutePrefix().'.edit', $actor->getKey()) : null,
            ];

            $navigation = [
                'dashboard' => [
                    'active' => $this->request->routeIs($central ? 'home' : 'tenantcore.dashboard'),
                    'url' => route($central ? 'home' : 'tenantcore.dashboard'),
                ],
                'modules' => [
                    'allowed' => ! $central,
                    'url' => route('tenantcore.module-dashboard.index'),
                    'active' => $this->request->routeIs('tenantcore.module-dashboard.*'),
                ],
            ];
        }

        $view->with('layout', [
            'appName' => $branding['app_name'],
            'tagline' => $branding['tagline'],
            'appInitial' => Str::upper(Str::substr($branding['app_name'], 0, 1)),
            'logoUrl' => $branding['logo_path'] !== '' ? Storage::disk('public')->url($branding['logo_path']) : null,
            'faviconUrl' => $branding['favicon_path'] !== '' ? Storage::disk('public')->url($branding['favicon_path']) : null,
            'locale' => str_replace('_', '-', app()->getLocale()),
            'year' => now()->year,
            'user' => $user,
            'central' => $central,
            'accountRoutePrefix' => $accountPrefix,
            'accountLabel' => $central ? 'Administrators' : 'Users',
            'settingsRoutePrefix' => $central ? 'core.settings' : 'tenantcore.settings',
            'workspaceName' => $this->request->attributes->get('application_name', 'Workspace'),
            'workspaceLabel' => $central ? 'Platform administration' : 'Tenant workspace',
            'branchName' => $this->request->attributes->get('branch_name'),
            'canSwitchBranch' => (bool) $this->request->attributes->get('branch_can_switch') && $this->request->attributes->get('branch_id') !== null,
            'switchBranchUrl' => route('tenantcore.branch-dashboard.destroy'),
            'navigation' => $navigation,
            'permissions' => $permissions,
            'homeUrl' => route('home'),
            'loginUrl' => route('auth.login'),
            'logoutUrl' => route('auth.logout'),
        ]);
    }
}
