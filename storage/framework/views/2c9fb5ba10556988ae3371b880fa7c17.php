<?php
    $clinicalRows = old('section_details.'.$pickerField, data_get($record->section_details, $pickerField, []));
    $clinicalRows = collect(is_array($clinicalRows) ? $clinicalRows : [])->take(100);
?>

<div class="app-prescription-clinical-items" data-value="#<?php echo e($pickerField); ?>"
    data-name="section_details[<?php echo e($pickerField); ?>]">
    <?php $__errorArgs = ['section_details.'.$pickerField];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <?php $__currentLoopData = $clinicalRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clinicalIndex => $clinicalItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(! is_array($clinicalItem)) continue; ?>
        <?php
            $clinicalItem = array_filter($clinicalItem, static fn ($value) => is_scalar($value) || $value === null);
        ?>
        <?php echo $__env->make('prescription::prescriptions.clinical-item-row', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <template class="prescription-clinical-item-template">
        <?php echo $__env->make('prescription::prescriptions.clinical-item-row', [
            'clinicalIndex' => '__INDEX__',
            'clinicalItem' => [],
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </template>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Prescription\resources/views/prescriptions/clinical-items.blade.php ENDPATH**/ ?>