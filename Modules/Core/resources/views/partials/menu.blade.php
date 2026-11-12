@if (array_intersect(array_keys($layout['permissions']), [
    'tenants.view', 'accounts.view', 'roles.view',
    'permissions.view', 'plans.view', 'subscriptions.view',
    'invoices.view', 'payments.view', 'settings.view',
]))
    <p class="app-nav-label">Management</p>
@endif

@if ($layout['permissions']['tenants.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Tenants', 'icon' => 'building',
        'url' => route('core.tenants.index'),
        'active' => request()->routeIs('core.tenants.*'),
    ])
@endif
@if ($layout['permissions']['accounts.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Administrators', 'icon' => 'users',
        'url' => route('core.accounts.index'),
        'active' => request()->routeIs('core.accounts.*'),
    ])
@endif
@if ($layout['permissions']['roles.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Roles', 'icon' => 'shield-halved',
        'url' => route('core.roles.index'),
        'active' => request()->routeIs('core.roles.*'),
    ])
@endif
@if ($layout['permissions']['permissions.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Permissions', 'icon' => 'key',
        'url' => route('core.permissions.index'),
        'active' => request()->routeIs('core.permissions.*'),
    ])
@endif
@if ($layout['permissions']['plans.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Plans', 'icon' => 'layer-group',
        'url' => route('core.plans.index'),
        'active' => request()->routeIs('core.plans.*'),
    ])
    @include('core::partials.navigation-link', [
        'label' => 'Plan Permissions', 'icon' => 'route',
        'url' => route('core.plan-permissions.index'),
        'active' => request()->routeIs('core.plan-permissions.*'),
    ])
@endif
@if ($layout['permissions']['subscriptions.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Subscriptions', 'icon' => 'calendar-check',
        'url' => route('core.subscriptions.index'),
        'active' => request()->routeIs('core.subscriptions.*'),
    ])
@endif
@if ($layout['permissions']['invoices.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Invoices', 'icon' => 'file-invoice-dollar',
        'url' => route('core.invoices.index'),
        'active' => request()->routeIs('core.invoices.*'),
    ])
@endif
@if ($layout['permissions']['payments.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Payments', 'icon' => 'credit-card',
        'url' => route('core.payments.index'),
        'active' => request()->routeIs('core.payments.*'),
    ])
@endif
@if ($layout['permissions']['settings.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Settings', 'icon' => 'gear',
        'url' => route('core.settings.index'),
        'active' => request()->routeIs('core.settings.*'),
    ])
@endif

@if (($layout['permissions']['sms-accounts.view'] ?? false) || ($layout['permissions']['sms-recharges.view'] ?? false) || ($layout['permissions']['sms-history.view'] ?? false))
    <p class="app-nav-label">SMS</p>
@endif
@if ($layout['permissions']['sms-accounts.view'] ?? false)
    @include('core::partials.navigation-link', ['label' => 'SMS accounts', 'icon' => 'wallet', 'url' => route('core.sms-accounts.index'), 'active' => request()->routeIs('core.sms-accounts.*')])
@endif
@if ($layout['permissions']['sms-recharges.view'] ?? false)
    @include('core::partials.navigation-link', ['label' => 'SMS recharges', 'icon' => 'money-bill-transfer', 'url' => route('core.sms-recharges.index'), 'active' => request()->routeIs('core.sms-recharges.*')])
@endif
@if ($layout['permissions']['sms-history.view'] ?? false)
    @include('core::partials.navigation-link', ['label' => 'SMS history', 'icon' => 'comment-sms', 'url' => route('core.sms-history.index'), 'active' => request()->routeIs('core.sms-history.*')])
@endif
