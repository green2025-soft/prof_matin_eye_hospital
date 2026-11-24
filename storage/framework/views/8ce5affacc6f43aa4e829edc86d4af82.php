<section class="app-form-section mb-3" aria-labelledby="prescription-identity-heading">
    <h2 class="h6 mb-3" id="prescription-identity-heading">Patient and doctor</h2>
    <div class="row g-3">
        <div class="col-md-5">
            <label class="form-label" for="prescription_patient_id">Patient Name <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="d-flex gap-2">
                <div class="flex-grow-1">
                    <select class="<?php echo e(inputClass('patient_id', 'form-select')); ?>" id="prescription_patient_id" name="patient_id"
                        data-patient-select data-ajax-select data-url="<?php echo e(route('hospital.patients.options')); ?>"
                        data-value-field="id" data-label-field="text" data-placeholder="Search patient ID, name or mobile"
                        required <?php echo e(inputAttrs('patient_id', 'prescription_patient_id')); ?>>
                        <option value=""></option>
                        <?php if($selectedPatient !== null): ?>
                            <option value="<?php echo e($selectedPatient->id); ?>" selected><?php echo e($selectedPatient->patient_id); ?> / <?php echo e($selectedPatient->name); ?> / <?php echo e($selectedPatient->mobile_no); ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <?php if($canCreatePatient): ?>
                    <button class="btn btn-outline-primary flex-shrink-0" type="button"
                        data-bs-toggle="modal" data-bs-target="#patient-create-modal"
                        data-patient-target="prescription_patient_id" aria-label="New patient" title="New patient">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    </button>
                <?php endif; ?>
            </div>
            <?php $__errorArgs = ['patient_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="prescription_patient_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="prescription_doctor_id">Doctor Name <span class="text-danger" aria-hidden="true">*</span></label>
            <?php if($doctorLocked): ?>
                <input class="<?php echo e(inputClass('doctor_id')); ?>" id="prescription_doctor_id" type="text" readonly
                    value="<?php echo e($selectedDoctor?->doctor_id ?: $selectedDoctor?->id); ?> / <?php echo e($selectedDoctor?->name); ?>"
                    <?php echo e(inputAttrs('doctor_id', 'prescription_doctor_id')); ?>>
                <input type="hidden" name="doctor_id" value="<?php echo e($selectedDoctor?->id); ?>">
            <?php else: ?>
                <select class="<?php echo e(inputClass('doctor_id', 'form-select')); ?>" id="prescription_doctor_id" name="doctor_id"
                    data-ajax-select data-url="<?php echo e(route('hospital.doctors.options')); ?>" data-value-field="id"
                    data-label-field="text" data-placeholder="Select doctor" required
                    <?php echo e(inputAttrs('doctor_id', 'prescription_doctor_id')); ?>>
                    <option value=""></option>
                    <?php if($selectedDoctor !== null): ?>
                        <option value="<?php echo e($selectedDoctor->id); ?>" selected><?php echo e($selectedDoctor->doctor_id ?: $selectedDoctor->id); ?> / <?php echo e($selectedDoctor->name); ?></option>
                    <?php endif; ?>
                </select>
            <?php endif; ?>
            <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="prescription_doctor_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="prescription_date">Visit Date <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="input-group" data-datepicker>
                <input class="<?php echo e(inputClass('prescription_date')); ?>" id="prescription_date" name="prescription_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('prescription_date', null, $entryDate)); ?>" required data-input <?php echo e(inputAttrs('prescription_date')); ?>>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="prescription_date" aria-label="Choose prescription date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
            </div>
            <?php $__errorArgs = ['prescription_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="prescription_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
</section>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Prescription\resources/views/partials/identity.blade.php ENDPATH**/ ?>