<form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php if($formMethod !== 'POST'): ?>
        <?php echo method_field($formMethod); ?>
    <?php endif; ?>

    <h2 class="h6 mb-3"><?php echo e($pageTitle); ?></h2>
    <div class="mb-3">
        <label class="form-label" for="section">Section <span class="text-danger" aria-hidden="true">*</span></label>
        <select class="<?php echo e(inputClass('section', 'form-select')); ?>" id="section" name="section" required
            <?php echo e(inputAttrs('section')); ?>>
            <option value="">Select section</option>
            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value); ?>"
                    <?php if(getValue('section', $prescription_option, request()->query('section')) === $value): echo 'selected'; endif; ?>>
                    <?php echo e($label); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="section-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
        <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text"
            value="<?php echo e(getValue('name', $prescription_option)); ?>" maxlength="255" autocomplete="off" required
            <?php echo e(inputAttrs('name')); ?>>
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
        <label class="form-label" for="ordering">Ordering</label>
        <input class="<?php echo e(inputClass('ordering')); ?>" id="ordering" name="ordering" type="number" inputmode="numeric"
            value="<?php echo e(getValue('ordering', $prescription_option, 0)); ?>" min="0" max="65535" step="1" required
            <?php echo e(inputAttrs('ordering')); ?>>
        <?php $__errorArgs = ['ordering'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="ordering-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <?php echo $__env->make('core::partials.form-actions', [
        'model' => $prescription_option,
        'routePrefix' => $routePrefix,
        'submitLabel' => $submitLabel,
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\options\form.blade.php ENDPATH**/ ?>