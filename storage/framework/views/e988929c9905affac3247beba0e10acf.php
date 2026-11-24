

<?php $__env->startSection('title', 'Permission'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => 'Permission', 'description' => 'Select a role, review its access, and save permission changes separately.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div data-permission-editor data-permission-readonly="<?php echo e($canManagePermissions ? '0' : '1'); ?>"
        data-permission-total="<?php echo e($permissionCount); ?>" data-permission-initial="<?php echo e(implode(',', $selectedPermissions)); ?>">
        <?php echo $__env->make('core::permissions.picker', [
            'recordLabel' => 'role',
            'recordIcon' => 'shield-halved',
            'records' => $roles,
            'selectedRecord' => $selectedRole,
            'searchValue' => $roleSearch,
            'hasMoreRecords' => $hasMoreRoles,
            'pickerAction' => route('core.permissions.index'),
            'searchField' => 'role_search',
            'recordField' => 'role',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if($selectedRole === null): ?>
            <section class="app-card">
                <?php echo $__env->make('core::partials.empty', ['title' => 'No role selected', 'description' => 'Choose a role above to view its permissions.', 'icon' => 'key'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </section>
        <?php else: ?>
            <?php $__env->startComponent('core::permissions.matrix', [
                'recordName' => $selectedRole->name,
                'formAction' => route('core.permissions.update', $selectedRole),
                'fieldName' => 'permissions',
                'markerName' => 'permissions_present',
                'canManagePermissions' => $canManagePermissions,
                'selectedPermissions' => $selectedPermissions,
                'permissionCount' => $permissionCount,
                'areas' => $areas,
            ]); ?>
            <?php echo $__env->renderComponent(); ?>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\permissions\index.blade.php ENDPATH**/ ?>