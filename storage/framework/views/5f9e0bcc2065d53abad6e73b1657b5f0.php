<form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php if($formMethod !== 'POST'): ?>
        <?php echo method_field($formMethod); ?>
    <?php endif; ?>

    <section class="app-form-section" aria-labelledby="department-form-heading">
        <h2 class="app-form-section-title" id="department-form-heading"><span class="app-form-section-icon"><i class="fa-solid fa-stethoscope" aria-hidden="true"></i></span><?php echo e($pageTitle); ?></h2>
        <p class="app-form-section-note">Set the doctor department name, optional description, and display order.</p>
        <div class="mb-3">
            <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $department)); ?>" maxlength="255" autocomplete="off" required autofocus <?php echo e(inputAttrs('name')); ?>>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea class="<?php echo e(inputClass('description')); ?>" id="description" name="description" rows="4" maxlength="2000" <?php echo e(inputAttrs('description')); ?>><?php echo e(getValue('description', $department)); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="description-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label class="form-label" for="ordering">Ordering <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="<?php echo e(inputClass('ordering')); ?>" id="ordering" name="ordering" type="number" inputmode="numeric" value="<?php echo e(getValue('ordering', $department, 0)); ?>" min="0" max="65535" step="1" required <?php echo e(inputAttrs('ordering')); ?>>
            <?php $__errorArgs = ['ordering'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="ordering-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </section>

    <?php echo $__env->make('core::partials.form-actions', ['model' => $department, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\departments\form.blade.php ENDPATH**/ ?>