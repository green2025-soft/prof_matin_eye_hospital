<?php

namespace Modules\Core\View\Composers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Core\Models\Account;
use Modules\Core\Models\Invoice;
use Modules\Core\Models\Payment;
use Modules\Core\Models\Plan;
use Modules\Core\Models\Role;
use Modules\Core\Models\Setting;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Policies\BillingPolicy;

class LayoutComposer
{
    public function __construct(private Request $request) {}

    public function compose(View $view): void
    {
        $actor = $this->request->user('web');
        abort_unless($actor === null || $actor instanceof Account, 403);

        $central = $this->request->attributes->get('application_scope') === 'central';
        $branding = Setting::values('branding');
        $user = null;
        $navigation = [];
        $showManagement = false;

        if ($actor !== null) {
            $subscription = $central ? null : ($this->request->attributes->has('application_subscription')
                ? $this->request->attributes->get('application_subscription')
                : Subscription::forTenant(Tenant::current()));
            $plan = $subscription?->isUsable() ? $subscription->plan : null;
            $actor->loadMissing(['roles.permissions', 'permissions']);
            $gate = Gate::forUser($actor);
            $accountModel = Account::modelForProvider($this->request->attributes->get('application_provider'));
            $accountPrefix = $central ? 'core.accounts' : 'tenantcore.accounts';

            $user = [
                'name' => $actor->name,
                'email' => $actor->email,
                'initial' => Str::upper(Str::substr($actor->name, 0, 1)),
                'label' => $central ? 'Platform account' : 'Workspace account',
                'editUrl' => $gate->allows('update', $actor) && ($central || $plan?->allowsRoutePermission('accounts.update'))
                    ? route($accountPrefix.'.edit', $actor->getKey()) : null,
            ];

            $navigation = [
                'dashboard' => ['active' => $this->request->routeIs('home')],
                'tenants' => [
                    'allowed' => $gate->allows('viewAny', Tenant::class),
                    'url' => route('core.tenants.index'),
                    'active' => $this->request->routeIs('core.tenants.*'),
                ],
                'accounts' => [
                    'allowed' => $gate->allows('viewAny', $accountModel) && ($central || $plan?->allowsRoutePermission('accounts.view')),
                    'label' => $central ? 'Administrators' : 'Team members',
                    'url' => route($accountPrefix.'.index'),
                    'active' => $this->request->routeIs($accountPrefix.'.*'),
                ],
                'roles' => [
                    'allowed' => $gate->allows('viewAny', Role::class) && ($central || $plan?->allowsRoutePermission('roles.view')),
                    'url' => route('core.roles.index'),
                    'active' => $this->request->routeIs('core.roles.*'),
                ],
                'permissions' => [
                    'allowed' => $gate->allows('viewPermissions', Role::class) && ($central || $plan?->allowsRoutePermission('permissions.view')),
                    'url' => route('core.permissions.index'),
                    'active' => $this->request->routeIs('core.permissions.*'),
                ],
                'plans' => [
                    'allowed' => $central && $gate->allows('viewAny', Plan::class),
                    'url' => route('core.plans.index'),
                    'active' => $this->request->routeIs('core.plans.*'),
                ],
                'planPermissions' => [
                    'allowed' => $central && $gate->allows('viewAny', Plan::class),
                    'url' => route('core.plan-permissions.index'),
                    'active' => $this->request->routeIs('core.plan-permissions.*'),
                ],
                'subscriptions' => [
                    'allowed' => $central && $gate->allows('viewAny', Subscription::class),
                    'url' => route('core.subscriptions.index'),
                    'active' => $this->request->routeIs('core.subscriptions.*'),
                ],
                'invoices' => [
                    'allowed' => $central && $gate->allows('viewAny', Invoice::class),
                    'url' => route('core.invoices.index'),
                    'active' => $this->request->routeIs('core.invoices.*'),
                ],
                'payments' => [
                    'allowed' => $central && $gate->allows('viewAny', Payment::class),
                    'url' => route('core.payments.index'),
                    'active' => $this->request->routeIs('core.payments.*'),
                ],
                'billing' => [
                    'allowed' => ! $central && app(BillingPolicy::class)->view($actor),
                    'url' => route('tenantcore.billing.index'),
                    'active' => $this->request->routeIs('tenantcore.billing.*'),
                ],
                'settings' => [
                    'allowed' => $gate->allows('viewAny', Setting::class) && ($central || $plan?->allowsRoutePermission('settings.view')),
                    'url' => route($central ? 'core.settings.index' : 'tenantcore.settings.index'),
                    'active' => $this->request->routeIs('core.settings.*', 'tenantcore.settings.*'),
                ],
            ];

            $showManagement = $navigation['tenants']['allowed'] || $navigation['accounts']['allowed']
                || $navigation['roles']['allowed'] || $navigation['permissions']['allowed']
                || $navigation['plans']['allowed'] || $navigation['subscriptions']['allowed']
                || $navigation['planPermissions']['allowed']
                || $navigation['invoices']['allowed'] || $navigation['payments']['allowed']
                || $navigation['billing']['allowed'] || $navigation['settings']['allowed'];
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
            'workspaceName' => $this->request->attributes->get('application_name', 'Workspace'),
            'workspaceLabel' => $central ? 'Platform administration' : 'Tenant workspace',
            'navigation' => $navigation,
            'showManagement' => $showManagement,
            'homeUrl' => route('home'),
            'loginUrl' => route('auth.login'),
            'logoutUrl' => route('auth.logout'),
        ]);
    }
}
