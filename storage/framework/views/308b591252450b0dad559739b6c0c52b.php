<label class="form-label" for="payment_type_id">Payment Type</label>
<select class="<?php echo e(inputClass('payment_type_id', 'form-select')); ?>" id="payment_type_id" name="payment_type_id" <?php echo e(inputAttrs('payment_type_id')); ?>>

    <?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($paymentType->id); ?>" <?php if((string) getValue('payment_type_id', $entry) === (string) $paymentType->id): echo 'selected'; endif; ?>><?php echo e($paymentType->name); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
<?php $__errorArgs = ['payment_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="invalid-feedback d-block" id="payment_type_id-error"><?php echo e($message); ?></div>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Pharmacy\resources/views/entries/partials/payment-type-field.blade.php ENDPATH**/ ?>