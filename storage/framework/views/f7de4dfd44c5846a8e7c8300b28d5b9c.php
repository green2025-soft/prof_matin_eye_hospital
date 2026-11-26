

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form class="nav nav-tabs flex-wrap gap-1 mb-2" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Settings sections">
        <button class="nav-link<?php echo e($activeGroup === 'company' ? ' active' : ''); ?>" type="submit" name="group" value="company" aria-pressed="<?php echo e($activeGroup === 'company' ? 'true' : 'false'); ?>">Company</button>
        <button class="nav-link<?php echo e($activeGroup === 'branding' ? ' active' : ''); ?>" type="submit" name="group" value="branding" aria-pressed="<?php echo e($activeGroup === 'branding' ? 'true' : 'false'); ?>">Branding</button>
        <button class="nav-link<?php echo e($activeGroup === 'general' ? ' active' : ''); ?>" type="submit" name="group" value="general" aria-pressed="<?php echo e($activeGroup === 'general' ? 'true' : 'false'); ?>">General</button>
        <button class="nav-link<?php echo e($activeGroup === 'email' ? ' active' : ''); ?>" type="submit" name="group" value="email" aria-pressed="<?php echo e($activeGroup === 'email' ? 'true' : 'false'); ?>">Email</button>
        <?php if($isPlatform): ?>
            <button class="nav-link<?php echo e($activeGroup === 'billing' ? ' active' : ''); ?>" type="submit" name="group" value="billing" aria-pressed="<?php echo e($activeGroup === 'billing' ? 'true' : 'false'); ?>">Billing</button>
            <button class="nav-link<?php echo e($activeGroup === 'payments' ? ' active' : ''); ?>" type="submit" name="group" value="payments" aria-pressed="<?php echo e($activeGroup === 'payments' ? 'true' : 'false'); ?>">Payments</button>
            <button class="nav-link<?php echo e($activeGroup === 'sms' ? ' active' : ''); ?>" type="submit" name="group" value="sms" aria-pressed="<?php echo e($activeGroup === 'sms' ? 'true' : 'false'); ?>">SMS</button>
        <?php endif; ?>
    </form>

    <?php if($activeGroup === 'payments'): ?>
        <form class="d-flex flex-wrap gap-2 mb-4" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET"
            aria-label="Payment providers">
            <input name="group" type="hidden" value="payments">
            <button class="btn <?php echo e($activeProvider === 'manual' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="provider" value="manual" aria-pressed="<?php echo e($activeProvider === 'manual' ? 'true' : 'false'); ?>">Manual</button>
            <button class="btn <?php echo e($activeProvider === 'sslcommerz' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="provider" value="sslcommerz" aria-pressed="<?php echo e($activeProvider === 'sslcommerz' ? 'true' : 'false'); ?>">SSLCommerz</button>
            <button class="btn <?php echo e($activeProvider === 'bkash' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="provider" value="bkash" aria-pressed="<?php echo e($activeProvider === 'bkash' ? 'true' : 'false'); ?>">bKash</button>
            <button class="btn <?php echo e($activeProvider === 'nagad' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="provider" value="nagad" aria-pressed="<?php echo e($activeProvider === 'nagad' ? 'true' : 'false'); ?>">Nagad</button>
            <button class="btn <?php echo e($activeProvider === 'eps' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="provider" value="eps" aria-pressed="<?php echo e($activeProvider === 'eps' ? 'true' : 'false'); ?>">EPS</button>
        </form>
    <?php endif; ?>

    <?php echo $__env->make('core::settings.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\app\Providers/../../resources/views/settings/index.blade.php ENDPATH**/ ?>