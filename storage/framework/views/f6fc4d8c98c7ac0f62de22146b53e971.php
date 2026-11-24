<?php
    $clinicalField = 'section_details.'.$pickerField.'.'.$clinicalIndex;
    $clinicalName = 'section_details['.$pickerField.']['.$clinicalIndex.']';
    $clinicalErrorId = str_replace('.', '-', $clinicalField);
    $clinicalNameValue = data_get($clinicalItem, 'name');
    $durationUnits = [
        'hours' => 'Hours',
        'days' => 'Days',
        'weeks' => 'Weeks',
        'months' => 'Month(s)',
        'years' => 'Year',
        'long_ago' => 'Long time ago',
    ];
    $durationUnit = data_get($clinicalItem, 'duration_unit') ?? 'days';
    $durationUnit = array_key_exists($durationUnit, $durationUnits) ? $durationUnit : 'days';
?>

<div class="app-prescription-clinical-item">
    <div class="app-prescription-clinical-item-name">
        <span class="prescription-clinical-item-number" aria-hidden="true"></span>
        <span class="prescription-clinical-item-title"><?php echo e($clinicalNameValue); ?></span>
        <input type="hidden" name="<?php echo e($clinicalName); ?>[name]" form="prescription-form"
            value="<?php echo e($clinicalNameValue); ?>">
        <?php $__errorArgs = [$clinicalField.'.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback d-block" id="<?php echo e($clinicalErrorId); ?>-name-error"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <label class="app-prescription-clinical-item-duration d-flex align-items-center">
        <span>For</span>
        <input name="<?php echo e($clinicalName); ?>[duration]" form="prescription-form" maxlength="30"
            class="<?php echo e(inputClass($clinicalField.'.duration', 'form-control form-control-sm')); ?>"
            value="<?php echo e(data_get($clinicalItem, 'duration')); ?>" placeholder="Duration" inputmode="decimal"
            <?php echo e(inputAttrs($clinicalField.'.duration')); ?>>
        <?php $__errorArgs = [$clinicalField.'.duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback" id="<?php echo e($clinicalErrorId); ?>-duration-error"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </label>
    <label class="app-prescription-clinical-item-unit">
        <span class="visually-hidden">Duration unit</span>
        <select name="<?php echo e($clinicalName); ?>[duration_unit]" form="prescription-form"
            class="<?php echo e(inputClass($clinicalField.'.duration_unit', 'form-select form-select-sm')); ?>"
            <?php echo e(inputAttrs($clinicalField.'.duration_unit')); ?>>
            <?php $__currentLoopData = $durationUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unitValue => $unitLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($unitValue); ?>" <?php if($durationUnit === $unitValue): echo 'selected'; endif; ?>><?php echo e($unitLabel); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = [$clinicalField.'.duration_unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback" id="<?php echo e($clinicalErrorId); ?>-duration_unit-error"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </label>
    <label class="app-prescription-clinical-item-notes">
        <span class="visually-hidden">Note</span>
        <textarea name="<?php echo e($clinicalName); ?>[notes]" form="prescription-form" rows="1" maxlength="1000" placeholder="Note"
            class="<?php echo e(inputClass($clinicalField.'.notes', 'form-control form-control-sm')); ?>"
            <?php echo e(inputAttrs($clinicalField.'.notes')); ?>><?php echo e(data_get($clinicalItem, 'notes')); ?></textarea>
        <?php $__errorArgs = [$clinicalField.'.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback" id="<?php echo e($clinicalErrorId); ?>-notes-error"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </label>
    <button class="btn btn-sm prescription-clinical-item-remove" type="button"
        aria-label="Remove <?php echo e($clinicalNameValue); ?>">
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Prescription\resources/views/prescriptions/clinical-item-row.blade.php ENDPATH**/ ?>