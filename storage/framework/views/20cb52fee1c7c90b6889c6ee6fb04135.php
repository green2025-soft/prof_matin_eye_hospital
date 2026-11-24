<?php if(array_intersect(array_keys($layout['permissions']), [
    'tenants.view', 'accounts.view', 'roles.view',
    'permissions.view', 'plans.view', 'subscriptions.view',
    'invoices.view', 'payments.view', 'settings.view',
])): ?>
    <p class="app-nav-label">Management</p>
<?php endif; ?>

<?php if($layout['permissions']['tenants.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Tenant',
        'icon' => 'building',
        'url' => route('core.tenants.index'),
        'active' => request()->routeIs('core.tenants.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($layout['permissions']['accounts.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Administrator',
        'icon' => 'users',
        'url' => route('core.accounts.index'),
        'active' => request()->routeIs('core.accounts.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($layout['permissions']['roles.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Role',
        'icon' => 'shield-halved',
        'url' => route('core.roles.index'),
        'active' => request()->routeIs('core.roles.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($layout['permissions']['permissions.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Permission',
        'icon' => 'key',
        'url' => route('core.permissions.index'),
        'active' => request()->routeIs('core.permissions.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($layout['permissions']['plans.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Plan',
        'icon' => 'layer-group',
        'url' => route('core.plans.index'),
        'active' => request()->routeIs('core.plans.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Plan Permission',
        'icon' => 'route',
        'url' => route('core.plan-permissions.index'),
        'active' => request()->routeIs('core.plan-permissions.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($layout['permissions']['subscriptions.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Subscription',
        'icon' => 'calendar-check',
        'url' => route('core.subscriptions.index'),
        'active' => request()->routeIs('core.subscriptions.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($layout['permissions']['invoices.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Invoice',
        'icon' => 'file-invoice-dollar',
        'url' => route('core.invoices.index'),
        'active' => request()->routeIs('core.invoices.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($layout['permissions']['payments.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Payment',
        'icon' => 'credit-card',
        'url' => route('core.payments.index'),
        'active' => request()->routeIs('core.payments.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($layout['permissions']['settings.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Settings',
        'icon' => 'gear',
        'url' => route('core.settings.index'),
        'active' => request()->routeIs('core.settings.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<?php if($layout['navigation']['deployment']['allowed']): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Server Update',
        'icon' => 'arrows-rotate',
        'url' => $layout['navigation']['deployment']['url'],
        'active' => $layout['navigation']['deployment']['active'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<?php if(($layout['permissions']['sms-accounts.view'] ?? false) || ($layout['permissions']['sms-recharges.view'] ?? false) || ($layout['permissions']['sms-history.view'] ?? false)): ?>
    <p class="app-nav-label">SMS</p>
<?php endif; ?>
<?php if($layout['permissions']['sms-accounts.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', ['label' => 'SMS accounts', 'icon' => 'wallet', 'url' => route('core.sms-accounts.index'), 'active' => request()->routeIs('core.sms-accounts.*')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($layout['permissions']['sms-recharges.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', ['label' => 'SMS recharges', 'icon' => 'money-bill-transfer', 'url' => route('core.sms-recharges.index'), 'active' => request()->routeIs('core.sms-recharges.*')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($layout['permissions']['sms-history.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', ['label' => 'SMS history', 'icon' => 'comment-sms', 'url' => route('core.sms-history.index'), 'active' => request()->routeIs('core.sms-history.*')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\app\Providers/../../resources/views/partials/menu.blade.php ENDPATH**/ ?>