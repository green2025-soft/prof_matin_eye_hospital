<p class="app-nav-label">Accounting</p>
@if ($moduleMenu['permissions']['accounting-accounts.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Chart of Accounts',
        'icon' => 'sitemap',
        'url' => route('accounting.chart-of-accounts.index'),
        'active' => request()->routeIs('accounting.chart-of-accounts.*'),
    ])
@endif
@if ($moduleMenu['permissions']['accounting-journals.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Vouchers',
        'icon' => 'receipt',
        'url' => route('accounting.journal-entries.index'),
        'active' => request()->routeIs('accounting.journal-entries.*'),
    ])
@endif
@if ($moduleMenu['permissions']['accounting-reports.view'] ?? false)
    <details class="app-nav-group" @if (request()->routeIs('accounting.reports.*')) open @endif>
        <summary class="app-nav-link app-nav-group-toggle">
            <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Reports</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @include('core::partials.navigation-link', [
                'label' => 'Ledger',
                'icon' => 'book',
                'url' => route('accounting.reports.ledger'),
                'active' => request()->routeIs('accounting.reports.ledger'),
            ])
            @include('core::partials.navigation-link', [
                'label' => 'Trial Balance',
                'icon' => 'scale-balanced',
                'url' => route('accounting.reports.trial-balance'),
                'active' => request()->routeIs('accounting.reports.trial-balance'),
            ])
            @include('core::partials.navigation-link', [
                'label' => 'Income–Expense',
                'icon' => 'chart-line',
                'url' => route('accounting.reports.income-expense'),
                'active' => request()->routeIs('accounting.reports.income-expense'),
            ])
            @include('core::partials.navigation-link', [
                'label' => 'Balance Sheet',
                'icon' => 'file-invoice-dollar',
                'url' => route('accounting.reports.balance-sheet'),
                'active' => request()->routeIs('accounting.reports.balance-sheet'),
            ])
            @include('core::partials.navigation-link', [
                'label' => 'Cash Flow',
                'icon' => 'money-bill-transfer',
                'url' => route('accounting.reports.cash-flow'),
                'active' => request()->routeIs('accounting.reports.cash-flow'),
            ])
            @include('core::partials.navigation-link', [
                'label' => 'Cash & Bank Book',
                'icon' => 'building-columns',
                'url' => route('accounting.reports.cash-bank-book'),
                'active' => request()->routeIs('accounting.reports.cash-bank-book'),
            ])
            @include('core::partials.navigation-link', [
                'label' => 'Changes in Equity',
                'icon' => 'chart-pie',
                'url' => route('accounting.reports.changes-in-equity'),
                'active' => request()->routeIs('accounting.reports.changes-in-equity'),
            ])
            @include('core::partials.navigation-link', [
                'label' => 'Receipts & Payments',
                'icon' => 'cash-register',
                'url' => route('accounting.reports.receipts-payments'),
                'active' => request()->routeIs('accounting.reports.receipts-payments'),
            ])
        </div>
    </details>
@endif
@if ($moduleMenu['permissions']['accounting-mappings.view'] ?? false)
    <details class="app-nav-group" @if (request()->routeIs('accounting.account-modules.*', 'accounting.payment-accounts.*')) open @endif>
        <summary class="app-nav-link app-nav-group-toggle">
            <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Setup</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @include('core::partials.navigation-link', [
                'label' => 'Account mappings',
                'icon' => 'arrows-to-circle',
                'url' => route('accounting.account-modules.index'),
                'active' => request()->routeIs('accounting.account-modules.*'),
            ])
            @include('core::partials.navigation-link', [
                'label' => 'Payment accounts',
                'icon' => 'money-bill-transfer',
                'url' => route('accounting.payment-accounts.index'),
                'active' => request()->routeIs('accounting.payment-accounts.*'),
            ])
        </div>
    </details>
@endif
