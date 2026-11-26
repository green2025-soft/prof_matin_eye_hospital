<div>
    <label class="form-label" for="<?php echo e($selectorId); ?>">Select Patient <span class="text-danger" aria-hidden="true">*</span></label>
    <div class="row g-2">
        <div class="col">
            <select class="<?php echo e(inputClass('patient_id', 'form-select')); ?>" id="<?php echo e($selectorId); ?>" name="patient_id" data-patient-select
                data-ajax-select data-url="<?php echo e(route('hospital.patients.options')); ?>"
                data-value-field="id" data-label-field="text" data-placeholder="Search patient ID, name, or mobile" required
                <?php echo e(inputAttrs('patient_id', $selectorId)); ?>>
                <option value=""></option>
                <?php if($selectedPatient !== null): ?>
                    <option value="<?php echo e($selectedPatient->id); ?>" selected><?php echo e($selectedPatient->patient_id); ?> — <?php echo e($selectedPatient->name); ?> — <?php echo e($selectedPatient->mobile_no); ?></option>
                <?php endif; ?>
            </select>
        </div>
        <?php if($canCreatePatient): ?>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#patient-create-modal" data-patient-target="<?php echo e($selectorId); ?>" aria-label="New patient" title="New patient"><i class="fa-solid fa-plus" aria-hidden="true"></i></button>
            </div>
        <?php endif; ?>
    </div>
    <?php $__errorArgs = ['patient_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($selectorId); ?>-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\patients\selector.blade.php ENDPATH**/ ?>