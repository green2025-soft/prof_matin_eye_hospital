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
    <h2 class="h6 mb-3"><?php echo e($pageTitle); ?></h2>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $supplier)); ?>" maxlength="255" required <?php echo e(inputAttrs('name')); ?>>
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
            <label class="form-label" for="contact_no">Contact no.</label>
            <input class="<?php echo e(inputClass('contact_no')); ?>" id="contact_no" name="contact_no" type="text" value="<?php echo e(getValue('contact_no', $supplier, null)); ?>" maxlength="100" <?php echo e(inputAttrs('contact_no')); ?>>
            <?php $__errorArgs = ['contact_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="contact_no-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-12">
            <label class="form-label" for="address">Address</label>
            <textarea class="<?php echo e(inputClass('address')); ?>" id="address" name="address" rows="2" maxlength="2000" <?php echo e(inputAttrs('address')); ?>><?php echo e(getValue('address', $supplier, null)); ?></textarea>
            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="address-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-12">
            <label class="form-label" for="active">Status <span class="text-danger" aria-hidden="true">*</span></label>
            <select class="<?php echo e(inputClass('active', 'form-select')); ?>" id="active" name="active" required <?php echo e(inputAttrs('active')); ?>>
                <option value="1" <?php if((int) getValue('active', $supplier, 1) === 1): echo 'selected'; endif; ?>>Active</option>
                <option value="0" <?php if((int) getValue('active', $supplier, 1) === 0): echo 'selected'; endif; ?>>Inactive</option>
            </select>
            <?php $__errorArgs = ['active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="active-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <?php echo $__env->make('core::partials.form-actions', ['model' => $supplier, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\suppliers\form.blade.php ENDPATH**/ ?>