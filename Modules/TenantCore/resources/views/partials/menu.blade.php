@if (array_intersect(array_keys($layout['permissions']), [
    'accounts.view', 'branches.view', 'payment-types.view',
    'divisions.view', 'districts.view', 'thanas.view',
    'roles.view', 'audit-logs.view', 'permissions.view',
    'billing.view', 'settings.view',
]))
    <p class="app-nav-label">Management</p>
@endif

@if ($layout['permissions']['accounts.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Users', 'icon' => 'users',
        'url' => route('tenantcore.accounts.index'),
        'active' => request()->routeIs('tenantcore.accounts.*'),
    ])
@endif
@if ($layout['permissions']['branches.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Branches', 'icon' => 'building',
        'url' => route('tenantcore.branches.index'),
        'active' => request()->routeIs('tenantcore.branches.*'),
    ])
@endif
@if ($layout['permissions']['payment-types.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Payment Types', 'icon' => 'money-bill-wave',
        'url' => route('tenantcore.payment-types.index'),
        'active' => request()->routeIs('tenantcore.payment-types.*'),
    ])
@endif
@if ($layout['permissions']['divisions.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Divisions', 'icon' => 'map-location-dot',
        'url' => route('tenantcore.divisions.index'),
        'active' => request()->routeIs('tenantcore.divisions.*'),
    ])
@endif
@if ($layout['permissions']['districts.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Districts', 'icon' => 'map',
        'url' => route('tenantcore.districts.index'),
        'active' => request()->routeIs('tenantcore.districts.*'),
    ])
@endif
@if ($layout['permissions']['thanas.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Thanas', 'icon' => 'location-dot',
        'url' => route('tenantcore.thanas.index'),
        'active' => request()->routeIs('tenantcore.thanas.*'),
    ])
@endif
@if ($layout['permissions']['roles.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Roles', 'icon' => 'shield-halved',
        'url' => route('core.roles.index'),
        'active' => request()->routeIs('core.roles.*'),
    ])
@endif
@if ($layout['permissions']['audit-logs.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Audit Logs', 'icon' => 'clock-rotate-left',
        'url' => route('tenantcore.audit-logs.index'),
        'active' => request()->routeIs('tenantcore.audit-logs.*'),
    ])
@endif
@if ($layout['permissions']['permissions.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Permissions', 'icon' => 'key',
        'url' => route('core.permissions.index'),
        'active' => request()->routeIs('core.permissions.*'),
    ])
@endif
@if ($layout['permissions']['billing.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Billing', 'icon' => 'wallet',
        'url' => route('tenantcore.billing.index'),
        'active' => request()->routeIs('tenantcore.billing.*'),
    ])
@endif
@if ($layout['permissions']['settings.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Settings', 'icon' => 'gear',
        'url' => route('tenantcore.settings.index'),
        'active' => request()->routeIs('tenantcore.settings.*'),
    ])
@endif
