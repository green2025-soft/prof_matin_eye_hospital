<form class="app-card app-appointment-form p-3" action="<?php echo e($formAction); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php if($formMethod !== 'POST'): ?>
        <?php echo method_field($formMethod); ?>
    <?php endif; ?>

    <section class="app-form-section" aria-labelledby="appointment-form-heading">
        <h2 class="h6 mb-2" id="appointment-form-heading"><?php echo e($pageTitle); ?></h2>
        <?php if($appointment->exists && ($appointment->serial_no !== null || $appointment->invoice_no !== null)): ?>
            <p class="small text-body-secondary d-flex flex-wrap gap-3 mb-2">
                <?php if($appointment->serial_no !== null): ?>
                    <span>Serial: <strong class="text-body"><?php echo e($appointment->serial_no); ?></strong></span>
                <?php endif; ?>
                <?php if($appointment->invoice_no !== null): ?>
                    <span>Invoice No.: <strong class="text-body"><?php echo e($appointment->invoice_no); ?></strong></span>
                <?php endif; ?>
            </p>
        <?php endif; ?>
        <div class="row g-2 align-items-end">
            <div class="col-md-6 col-xl-3">
                <label class="form-label" for="appointment_doctor_id">Doctor <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('doctor_id', 'form-select')); ?>" id="appointment_doctor_id" name="doctor_id"
                    data-ajax-select data-url="<?php echo e(route('hospital.doctors.options')); ?>"
                    data-value-field="id" data-label-field="text" data-placeholder="Select a doctor" required
                    <?php echo e(inputAttrs('doctor_id', 'appointment_doctor_id')); ?>>
                    <option value=""></option>
                    <?php if($selectedDoctor !== null): ?>
                        <option value="<?php echo e($selectedDoctor->id); ?>" selected><?php echo e($selectedDoctor->doctor_id ?: $selectedDoctor->id); ?> — <?php echo e($selectedDoctor->name); ?></option>
                    <?php endif; ?>
                </select>
                <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="appointment_doctor_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6 col-xl-4">
                <?php echo $__env->make('hospital::patients.selector', ['selectedPatient' => $selectedPatient, 'selectorId' => 'appointment_patient_id'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <div class="col-sm-6 col-md-4 col-xl-2">
                <label class="form-label" for="appointment_date">Appointment date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="<?php echo e(inputClass('appointment_date')); ?>" id="appointment_date" name="appointment_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('appointment_date', null, $appointmentDate)); ?>" required data-input <?php echo e(inputAttrs('appointment_date')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="appointment_date" aria-label="Choose appointment date" title="Choose appointment date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                <?php $__errorArgs = ['appointment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="appointment_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <label class="form-label" for="appointment_time">Appointment time</label>
                <div class="input-group" data-timepicker>
                    <input class="<?php echo e(inputClass('appointment_time')); ?>" id="appointment_time" name="appointment_time" type="text" autocomplete="off" placeholder="hh:mm AM/PM" value="<?php echo e(getValue('appointment_time', null, $appointmentTime)); ?>" maxlength="5" data-input <?php echo e(inputAttrs('appointment_time')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="appointment_time" aria-label="Choose appointment time" title="Choose appointment time"><i class="fa-solid fa-clock" aria-hidden="true"></i></button>
                    <button class="btn btn-outline-secondary" type="button" data-clear aria-controls="appointment_time" aria-label="Clear appointment time" title="Clear appointment time"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                </div>
                <?php $__errorArgs = ['appointment_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="appointment_time-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    </section>

    <?php echo $__env->make('core::partials.form-actions', ['model' => $appointment, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\appointments\form.blade.php ENDPATH**/ ?>