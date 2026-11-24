<div class="app-prescription-picker" id="<?php echo e($pickerField); ?>-picker"
    data-url="<?php echo e($pickerUrl); ?>" data-search="#<?php echo e($pickerField); ?>-search">
    <div class="app-prescription-picker-options d-flex flex-wrap align-content-start gap-2 p-3 overflow-auto"
        aria-label="Available items" aria-live="polite"></div>
    <textarea class="<?php echo e(inputClass($pickerField, 'prescription-picker-value')); ?>" hidden
        id="<?php echo e($pickerField); ?>" name="<?php echo e($pickerField); ?>" form="prescription-form"><?php echo e($pickerValue); ?></textarea>
    <?php $__errorArgs = [$pickerField];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="<?php echo e($pickerField); ?>-error"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <p class="small text-danger prescription-picker-error mt-2 mb-0" role="alert" hidden></p>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Prescription\resources/views/prescriptions/picker.blade.php ENDPATH**/ ?>