

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Create or update a role name. Assign its access from Permissions.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>
        <section class="app-form-section" aria-labelledby="role-details-heading">
            <h2 class="app-form-section-title" id="role-details-heading"><span class="app-form-section-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></span>Role details</h2>
            <p class="app-form-section-note"><?php echo e($isReservedRole ? 'This administrator role is protected and cannot be changed.' : 'Use a name that describes the responsibilities of this role.'); ?></p>
            <div class="row">
                <div class="col-lg-7">
                    <label class="form-label" for="name">Role name <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="app-input-wrap">
                        <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $role)); ?>" maxlength="255" autocomplete="off" required autofocus <?php if($isReservedRole): echo 'readonly'; endif; ?> <?php echo e(inputAttrs('name')); ?>>
                    </div>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="name-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>
        <?php echo $__env->make('core::partials.form-actions', ['model' => $role, 'routePrefix' => 'core.roles', 'submitLabel' => $submitLabel, 'canSubmit' => ! $isReservedRole], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\roles\form.blade.php ENDPATH**/ ?>