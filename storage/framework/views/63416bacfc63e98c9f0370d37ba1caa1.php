<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="d-flex flex-wrap gap-2 mb-3" action="<?php echo e(route('pharmacy.settings.index')); ?>" method="GET" aria-label="Pharmacy settings sections">
        <button class="btn btn-sm <?php echo e($activePharmacySection === 'general' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="general" aria-pressed="<?php echo e($activePharmacySection === 'general' ? 'true' : 'false'); ?>">General</button>
        <button class="btn btn-sm <?php echo e($activePharmacySection === 'numbering' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="numbering" aria-pressed="<?php echo e($activePharmacySection === 'numbering' ? 'true' : 'false'); ?>">Numbering</button>
        <button class="btn btn-sm <?php echo e($activePharmacySection === 'items' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="items" aria-pressed="<?php echo e($activePharmacySection === 'items' ? 'true' : 'false'); ?>">Item fields</button>
        <?php if($smsNotifications !== []): ?>
            <button class="btn btn-sm <?php echo e($activePharmacySection === 'sms' ? 'btn-primary' : 'btn-outline-secondary'); ?>" type="submit" name="section" value="sms" aria-pressed="<?php echo e($activePharmacySection === 'sms' ? 'true' : 'false'); ?>">SMS</button>
        <?php endif; ?>
    </form>

    <?php echo $__env->make('pharmacy::settings.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Pharmacy\resources/views/settings/index.blade.php ENDPATH**/ ?>