<?php
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
?>

<?php if(($permissions['reports-daily.view'] ?? false) || ($permissions['reports-cash.view'] ?? false) || ($permissions['reports-monthly-cash.view'] ?? false)): ?>
    <details class="app-nav-group" <?php if(request()->routeIs('tenantcore.reports.*')): ?> open <?php endif; ?>>
        <summary class="app-nav-link app-nav-group-toggle"><span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span><span class="app-nav-copy">Reports</span><i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i></summary>
        <div class="app-nav-submenu ps-3">
            <?php if($permissions['reports-daily.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', ['label' => 'Income / Expense Report', 'icon' => 'calendar-day', 'url' => route('tenantcore.reports.daily'), 'active' => request()->routeIs('tenantcore.reports.daily')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['reports-cash.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', ['label' => 'Cash Report', 'icon' => 'money-bill-wave', 'url' => route('tenantcore.reports.cash'), 'active' => request()->routeIs('tenantcore.reports.cash')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['reports-monthly-cash.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', ['label' => 'Monthly Cash Report', 'icon' => 'calendar-days', 'url' => route('tenantcore.reports.monthly-cash'), 'active' => request()->routeIs('tenantcore.reports.monthly-cash')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>
    </details>
<?php endif; ?>

<?php if(($permissions['incomes.view'] ?? false) || ($permissions['income-heads.view'] ?? false)): ?>
    <details class="app-nav-group" <?php if($incomeOpen): ?> open <?php endif; ?>>
        <summary class="app-nav-link app-nav-group-toggle<?php echo e($incomeOpen ? ' active' : ''); ?>" title="Income" aria-label="Income">
            <span class="app-nav-icon"><i class="fa-solid fa-circle-arrow-down" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Income</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            <?php if($permissions['incomes.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Income', 'icon' => 'circle-arrow-down',
                    'url' => route('tenantcore.incomes.index'),
                    'active' => request()->routeIs('tenantcore.incomes.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['income-heads.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Income Heads', 'icon' => 'list',
                    'url' => route('tenantcore.income-heads.index'),
                    'active' => request()->routeIs('tenantcore.income-heads.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>
    </details>
<?php endif; ?>

<?php if(($permissions['expenses.view'] ?? false) || ($permissions['expense-heads.view'] ?? false)): ?>
    <details class="app-nav-group" <?php if($expensesOpen): ?> open <?php endif; ?>>
        <summary class="app-nav-link app-nav-group-toggle<?php echo e($expensesOpen ? ' active' : ''); ?>" title="Expenses" aria-label="Expenses">
            <span class="app-nav-icon"><i class="fa-solid fa-circle-arrow-up" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Expense</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            <?php if($permissions['expenses.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Expense',
                    'icon' => 'circle-arrow-up',
                    'url' => route('tenantcore.expenses.index'),
                    'active' => request()->routeIs('tenantcore.expenses.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['expense-heads.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Expense Head',
                    'icon' => 'list',
                    'url' => route('tenantcore.expense-heads.index'),
                    'active' => request()->routeIs('tenantcore.expense-heads.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>
    </details>
<?php endif; ?>

<?php if($showManagement): ?>
    <details class="app-nav-group" <?php if($managementOpen): ?> open <?php endif; ?>>
        <summary class="app-nav-link app-nav-group-toggle<?php echo e($managementOpen ? ' active' : ''); ?>" title="Management" aria-label="Management">
            <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Management</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            <?php if($permissions['accounts.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'User',
                    'icon' => 'users',
                    'url' => route('tenantcore.accounts.index'),
                    'active' => request()->routeIs('tenantcore.accounts.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($canViewBranches): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Branche',
                    'icon' => 'building',
                    'url' => route('tenantcore.branches.index'),
                    'active' => request()->routeIs('tenantcore.branches.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['roles.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Role',
                    'icon' => 'shield-halved',
                    'url' => route('core.roles.index'),
                    'active' => request()->routeIs('core.roles.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['permissions.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Permission',
                    'icon' => 'key',
                    'url' => route('core.permissions.index'),
                    'active' => request()->routeIs('core.permissions.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>
    </details>
<?php endif; ?>

<?php if($showLocation): ?>
    <details class="app-nav-group" <?php if($locationOpen): ?> open <?php endif; ?>>
        <summary class="app-nav-link app-nav-group-toggle<?php echo e($locationOpen ? ' active' : ''); ?>" title="Location" aria-label="Location">
            <span class="app-nav-icon"><i class="fa-solid fa-map-location-dot" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Location</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            <?php if($permissions['divisions.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Division',
                    'icon' => 'map-location-dot',
                    'url' => route('tenantcore.divisions.index'),
                    'active' => request()->routeIs('tenantcore.divisions.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['districts.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'District',
                    'icon' => 'map',
                    'url' => route('tenantcore.districts.index'),
                    'active' => request()->routeIs('tenantcore.districts.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['thanas.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Thana',
                    'icon' => 'location-dot',
                    'url' => route('tenantcore.thanas.index'),
                    'active' => request()->routeIs('tenantcore.thanas.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>
    </details>
<?php endif; ?>

<?php if($permissions['payment-types.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Payment Type',
        'icon' => 'money-bill-wave',
        'url' => route('tenantcore.payment-types.index'),
        'active' => request()->routeIs('tenantcore.payment-types.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($permissions['audit-logs.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Audit Log',
        'icon' => 'clock-rotate-left',
        'url' => route('tenantcore.audit-logs.index'),
        'active' => request()->routeIs('tenantcore.audit-logs.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($permissions['billing.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Billing',
        'icon' => 'wallet',
        'url' => route('tenantcore.billing.index'),
        'active' => request()->routeIs('tenantcore.billing.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($permissions['settings.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Settings',
        'icon' => 'gear',
        'url' => route('tenantcore.settings.index'),
        'active' => request()->routeIs('tenantcore.settings.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($permissions['sms.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'SMS wallet',
        'icon' => 'comment-sms',
        'url' => route('tenantcore.sms.index'),
        'active' => request()->routeIs('tenantcore.sms.*', 'tenantcore.sms-recharges.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\partials\menu.blade.php ENDPATH**/ ?>