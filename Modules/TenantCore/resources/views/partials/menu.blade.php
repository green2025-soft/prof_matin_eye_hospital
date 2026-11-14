@php
    $permissions = $layout['permissions'];
    $canViewBranches = $foundationHasBranches && ($permissions['branches.view'] ?? false);
    $incomeOpen = request()->routeIs('tenantcore.incomes.*', 'tenantcore.income-heads.*');
    $expensesOpen = request()->routeIs('tenantcore.expenses.*', 'tenantcore.expense-heads.*');
    $managementOpen = request()->routeIs(
        'tenantcore.accounts.*', 'tenantcore.branches.*', 'core.roles.*', 'core.permissions.*',
    );
    $locationOpen = request()->routeIs(
        'tenantcore.divisions.*', 'tenantcore.districts.*', 'tenantcore.thanas.*',
    );
    $showManagement = $canViewBranches
        || ($permissions['accounts.view'] ?? false)
        || ($permissions['roles.view'] ?? false)
        || ($permissions['permissions.view'] ?? false);
    $showLocation = ($permissions['divisions.view'] ?? false)
        || ($permissions['districts.view'] ?? false)
        || ($permissions['thanas.view'] ?? false);
@endphp

@if (($permissions['reports-daily.view'] ?? false) || ($permissions['reports-cash.view'] ?? false) || ($permissions['reports-monthly-cash.view'] ?? false))
    <details class="app-nav-group" @if (request()->routeIs('tenantcore.reports.*')) open @endif>
        <summary class="app-nav-link app-nav-group-toggle"><span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span><span class="app-nav-copy">Reports</span><i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i></summary>
        <div class="app-nav-submenu ps-3">
            @if ($permissions['reports-daily.view'] ?? false)
                @include('core::partials.navigation-link', ['label' => 'Income / Expense Report', 'icon' => 'calendar-day', 'url' => route('tenantcore.reports.daily'), 'active' => request()->routeIs('tenantcore.reports.daily')])
            @endif
            @if ($permissions['reports-cash.view'] ?? false)
                @include('core::partials.navigation-link', ['label' => 'Cash Report', 'icon' => 'money-bill-wave', 'url' => route('tenantcore.reports.cash'), 'active' => request()->routeIs('tenantcore.reports.cash')])
            @endif
            @if ($permissions['reports-monthly-cash.view'] ?? false)
                @include('core::partials.navigation-link', ['label' => 'Monthly Cash Report', 'icon' => 'calendar-days', 'url' => route('tenantcore.reports.monthly-cash'), 'active' => request()->routeIs('tenantcore.reports.monthly-cash')])
            @endif
        </div>
    </details>
@endif

@if (($permissions['incomes.view'] ?? false) || ($permissions['income-heads.view'] ?? false))
    <details class="app-nav-group" @if ($incomeOpen) open @endif>
        <summary class="app-nav-link app-nav-group-toggle{{ $incomeOpen ? ' active' : '' }}" title="Income" aria-label="Income">
            <span class="app-nav-icon"><i class="fa-solid fa-circle-arrow-down" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Income</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @if ($permissions['incomes.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Income', 'icon' => 'circle-arrow-down',
                    'url' => route('tenantcore.incomes.index'),
                    'active' => request()->routeIs('tenantcore.incomes.*'),
                ])
            @endif
            @if ($permissions['income-heads.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Income Heads', 'icon' => 'list',
                    'url' => route('tenantcore.income-heads.index'),
                    'active' => request()->routeIs('tenantcore.income-heads.*'),
                ])
            @endif
        </div>
    </details>
@endif

@if (($permissions['expenses.view'] ?? false) || ($permissions['expense-heads.view'] ?? false))
    <details class="app-nav-group" @if ($expensesOpen) open @endif>
        <summary class="app-nav-link app-nav-group-toggle{{ $expensesOpen ? ' active' : '' }}" title="Expenses" aria-label="Expenses">
            <span class="app-nav-icon"><i class="fa-solid fa-circle-arrow-up" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Expenses</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @if ($permissions['expenses.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Expenses', 'icon' => 'circle-arrow-up',
                    'url' => route('tenantcore.expenses.index'),
                    'active' => request()->routeIs('tenantcore.expenses.*'),
                ])
            @endif
            @if ($permissions['expense-heads.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Expense Heads', 'icon' => 'list',
                    'url' => route('tenantcore.expense-heads.index'),
                    'active' => request()->routeIs('tenantcore.expense-heads.*'),
                ])
            @endif
        </div>
    </details>
@endif

@if ($showManagement)
    <details class="app-nav-group" @if ($managementOpen) open @endif>
        <summary class="app-nav-link app-nav-group-toggle{{ $managementOpen ? ' active' : '' }}" title="Management" aria-label="Management">
            <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Management</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @if ($permissions['accounts.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Users', 'icon' => 'users',
                    'url' => route('tenantcore.accounts.index'),
                    'active' => request()->routeIs('tenantcore.accounts.*'),
                ])
            @endif
            @if ($canViewBranches)
                @include('core::partials.navigation-link', [
                    'label' => 'Branches', 'icon' => 'building',
                    'url' => route('tenantcore.branches.index'),
                    'active' => request()->routeIs('tenantcore.branches.*'),
                ])
            @endif
            @if ($permissions['roles.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Roles', 'icon' => 'shield-halved',
                    'url' => route('core.roles.index'),
                    'active' => request()->routeIs('core.roles.*'),
                ])
            @endif
            @if ($permissions['permissions.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Permissions', 'icon' => 'key',
                    'url' => route('core.permissions.index'),
                    'active' => request()->routeIs('core.permissions.*'),
                ])
            @endif
        </div>
    </details>
@endif

@if ($showLocation)
    <details class="app-nav-group" @if ($locationOpen) open @endif>
        <summary class="app-nav-link app-nav-group-toggle{{ $locationOpen ? ' active' : '' }}" title="Location" aria-label="Location">
            <span class="app-nav-icon"><i class="fa-solid fa-map-location-dot" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Location</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @if ($permissions['divisions.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Divisions', 'icon' => 'map-location-dot',
                    'url' => route('tenantcore.divisions.index'),
                    'active' => request()->routeIs('tenantcore.divisions.*'),
                ])
            @endif
            @if ($permissions['districts.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Districts', 'icon' => 'map',
                    'url' => route('tenantcore.districts.index'),
                    'active' => request()->routeIs('tenantcore.districts.*'),
                ])
            @endif
            @if ($permissions['thanas.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Thanas', 'icon' => 'location-dot',
                    'url' => route('tenantcore.thanas.index'),
                    'active' => request()->routeIs('tenantcore.thanas.*'),
                ])
            @endif
        </div>
    </details>
@endif

@if ($permissions['payment-types.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Payment Types', 'icon' => 'money-bill-wave',
        'url' => route('tenantcore.payment-types.index'),
        'active' => request()->routeIs('tenantcore.payment-types.*'),
    ])
@endif
@if ($permissions['audit-logs.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Audit Logs', 'icon' => 'clock-rotate-left',
        'url' => route('tenantcore.audit-logs.index'),
        'active' => request()->routeIs('tenantcore.audit-logs.*'),
    ])
@endif
@if ($permissions['billing.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Billing', 'icon' => 'wallet',
        'url' => route('tenantcore.billing.index'),
        'active' => request()->routeIs('tenantcore.billing.*'),
    ])
@endif
@if ($permissions['settings.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Settings', 'icon' => 'gear',
        'url' => route('tenantcore.settings.index'),
        'active' => request()->routeIs('tenantcore.settings.*'),
    ])
@endif
@if ($permissions['sms.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'SMS wallet', 'icon' => 'comment-sms',
        'url' => route('tenantcore.sms.index'),
        'active' => request()->routeIs('tenantcore.sms.*', 'tenantcore.sms-recharges.*'),
    ])
@endif
