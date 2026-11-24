<form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php if($formMethod !== 'POST'): ?>
        <?php echo method_field($formMethod); ?>
    <?php endif; ?>

    <section class="app-form-section" aria-labelledby="division-form-heading">
        <h2 class="app-form-section-title" id="division-form-heading"><span class="app-form-section-icon"><i class="fa-solid fa-map-location-dot" aria-hidden="true"></i></span><?php echo e($pageTitle); ?></h2>
        <p class="app-form-section-note">Set the division name in English and Bangla.</p>
        <div class="mb-3">
            <label class="form-label" for="name">Name (English) <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $division)); ?>" maxlength="255" autocomplete="off" required autofocus <?php echo e(inputAttrs('name')); ?>>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label class="form-label" for="bn_name">Name (Bangla) <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="<?php echo e(inputClass('bn_name')); ?>" id="bn_name" name="bn_name" type="text" value="<?php echo e(getValue('bn_name', $division)); ?>" maxlength="255" lang="bn" autocomplete="off" required <?php echo e(inputAttrs('bn_name')); ?>>
            <?php $__errorArgs = ['bn_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="bn_name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </section>

    <?php echo $__env->make('core::partials.form-actions', ['model' => $division, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\divisions\form.blade.php ENDPATH**/ ?>