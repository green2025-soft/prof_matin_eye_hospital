<form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <input id="type" name="type" type="hidden" value="<?php echo e($defaultType); ?>">
    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="type-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <?php if($formMethod !== 'POST'): ?>
        <?php echo method_field($formMethod); ?>
    <?php endif; ?>
    
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label" for="name">Generic/Category Name <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $product_category)); ?>" maxlength="255" required <?php echo e(inputAttrs('name')); ?>>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-12">
            <label class="form-label" for="ordering">Ordering <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="<?php echo e(inputClass('ordering')); ?>" id="ordering" name="ordering" type="number" value="<?php echo e(getValue('ordering', $product_category, 0)); ?>" min="0" step="1" inputmode="numeric" required <?php echo e(inputAttrs('ordering')); ?>>
            <?php $__errorArgs = ['ordering'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="ordering-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <?php echo $__env->make('core::partials.form-actions', ['model' => $product_category, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\categories\form.blade.php ENDPATH**/ ?>