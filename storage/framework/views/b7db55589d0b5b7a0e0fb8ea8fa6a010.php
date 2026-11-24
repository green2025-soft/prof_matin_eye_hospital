<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage the permissions shared by all doctors.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div
        data-permission-editor
        data-permission-readonly="<?php echo e($canManagePermissions ? '0' : '1'); ?>"
        data-permission-total="<?php echo e($permissionCount); ?>"
        data-permission-initial="<?php echo e(implode(',', $selectedPermissions)); ?>"
    >
        <?php $__env->startComponent('core::permissions.matrix', [
            'recordName' => 'All doctors',
            'formAction' => route('hospital.doctors.permissions-update'),
            'fieldName' => 'permissions',
            'markerName' => 'permissions_present',
            'canManagePermissions' => $canManagePermissions,
            'selectedPermissions' => $selectedPermissions,
            'permissionCount' => $permissionCount,
            'areas' => $areas,
        ]); ?>
        <?php echo $__env->renderComponent(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\doctors\permissions.blade.php ENDPATH**/ ?>