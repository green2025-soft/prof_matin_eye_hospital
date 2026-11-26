<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    
    <?php echo $__env->make('hospital::partials.clinical-tabs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form
        class="app-card p-3 p-md-4"
        action="<?php echo e($formAction); ?>"
        method="POST"
        data-clinical-entry="consultation"
        data-default-payment="<?php echo e(! $consultation->exists && ! session()->hasOldInput('paid_amount') ? $paymentDefault : 'manual'); ?>"
        data-record-exists="<?php echo e($consultation->exists ? '1' : '0'); ?>"
        data-initial-amount="<?php echo e($consultation->amount ?? '0.00'); ?>"
        data-original-source-ids="<?php echo e(json_encode(($consultation->exists ? [$consultation->doctor_id] : []))); ?>"
        data-original-patient-type="<?php echo e($consultation->patient_type ?? 'new'); ?>"
    >
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>
        <?php if($appointmentId !== null): ?>
            <input name="appointment_id" type="hidden" value="<?php echo e($appointmentId); ?>">
        <?php endif; ?>
        <?php $__errorArgs = ['appointment_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="alert alert-danger"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <?php if($consultation->exists && ($consultation->serial_no !== null || $consultation->invoice_no !== null)): ?>
            <div class="row g-2 mb-3">
                <?php if($consultation->serial_no !== null): ?>
                    <div class="col-md-4">
                        <label class="form-label small mb-1" for="consultation_serial_no">Serial</label>
                        <input class="form-control form-control-sm" id="consultation_serial_no" type="text" value="<?php echo e($consultation->serial_no); ?>" readonly>
                    </div>
                <?php endif; ?>
                <?php if($consultation->invoice_no !== null): ?>
                    <div class="col-md-4">
                        <label class="form-label small mb-1" for="consultation_invoice_no">Invoice No.</label>
                        <input class="form-control form-control-sm" id="consultation_invoice_no" type="text" value="<?php echo e($consultation->invoice_no); ?>" readonly>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="row g-3">
             <div class="col-md-4">
                <?php echo $__env->make('hospital::patients.selector', ['selectedPatient' => $selectedPatient, 'selectorId' => 'consultation_patient_id'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <div class="col-md-1"></div>
            <fieldset class="col-md-3" <?php echo e(inputAttrs('patient_type', 'consultation_patient_type')); ?>>
                <legend class="form-label mb-2">Patient Type <span class="text-danger" aria-hidden="true">*</span></legend>

                <?php $__currentLoopData = $patientTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check form-check-inline">
                        <input class="<?php echo e(inputClass('patient_type', 'form-check-input')); ?>" id="consultation_patient_type_<?php echo e($value); ?>" name="patient_type" type="radio" value="<?php echo e($value); ?>" <?php echo e(getChecked($value, 'patient_type', $consultation, 'new')); ?> required>
                        <label class="form-check-label small" for="consultation_patient_type_<?php echo e($value); ?>"><?php echo e($label); ?></label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $__errorArgs = ['patient_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="consultation_patient_type-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </fieldset>
            <div class="col-md-4">
                <label class="form-label" for="consultation_entry_date">Entry Date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="<?php echo e(inputClass('entry_date')); ?>" id="consultation_entry_date" name="entry_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                        value="<?php echo e(getValue('entry_date', null, $entryDate)); ?>" required data-input
                        <?php echo e(inputAttrs('entry_date', 'consultation_entry_date')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="consultation_entry_date" aria-label="Choose entry date" title="Choose entry date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                <?php $__errorArgs = ['entry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="consultation_entry_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>


        </div>

        <div class="row g-3 mt-0">
            <div class="col-md-4">
                <label class="form-label" for="consultation_doctor_id">Doctor <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('doctor_id', 'form-select')); ?>" id="consultation_doctor_id" name="doctor_id"
                    data-ajax-select data-url="<?php echo e(route('hospital.doctors.options')); ?>"
                    data-value-field="id" data-label-field="text" data-placeholder="Select a doctor" required
                    <?php echo e(inputAttrs('doctor_id', 'consultation_doctor_id')); ?>>
                    <option value=""></option>
                    <?php if($selectedDoctor !== null): ?>
                        <option value="<?php echo e($selectedDoctor->id); ?>" data-consultation-fee="<?php echo e($selectedDoctor->consultation_fee); ?>" data-follow-up-fee="<?php echo e($selectedDoctor->follow_up_fee); ?>" selected><?php echo e($selectedDoctor->doctor_id ?: $selectedDoctor->id); ?> — <?php echo e($selectedDoctor->name); ?></option>
                    <?php endif; ?>
                </select>
                <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="consultation_doctor_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="consultation_payment_type_id">Payment Type <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('payment_type_id', 'form-select')); ?>" id="consultation_payment_type_id" name="payment_type_id" required <?php echo e(inputAttrs('payment_type_id', 'consultation_payment_type_id')); ?>>
                    <?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($paymentType->id); ?>" <?php echo e(getSelected($paymentType->id, 'payment_type_id', $consultation)); ?>><?php echo e($paymentType->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['payment_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="consultation_payment_type_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4 d-none">
                <label class="form-label" for="consultation_due_date">Due date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="<?php echo e(inputClass('due_date')); ?>" id="consultation_due_date" name="due_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('due_date', null, $dueDate)); ?>" required data-input <?php echo e(inputAttrs('due_date', 'consultation_due_date')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="consultation_due_date" aria-label="Choose due date" title="Choose due date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="consultation_due_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <?php if($referenceEnabled): ?>
            <div class="row g-3 mt-0">
                <div class="col-md-4">
                    <label class="form-label" for="consultation_reference_id">Reference</label>
                    <select class="<?php echo e(inputClass('reference_id', 'form-select')); ?>" id="consultation_reference_id" name="reference_id"
                        data-ajax-select data-url="<?php echo e(route('hospital.references.options')); ?>"
                        data-value-field="id" data-label-field="text" data-placeholder="Select a reference"
                        <?php echo e(inputAttrs('reference_id', 'consultation_reference_id')); ?>>
                        <option value=""></option>
                        <?php if($selectedReference !== null): ?>
                            <option value="<?php echo e($selectedReference->id); ?>" selected><?php echo e($selectedReference->reference_id); ?> — <?php echo e($selectedReference->name); ?><?php echo e($selectedReference->contact_no ? ' ('.$selectedReference->contact_no.')' : ''); ?></option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['reference_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="consultation_reference_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        <?php endif; ?>

        <input name="discount_mode" type="hidden" value="<?php echo e(getValue('discount_mode', $consultation, 'percent')); ?>" data-clinical-discount-mode>

        <section class="app-form-section mt-3" aria-labelledby="consultation_payment_heading">

            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_amount">Amount</label>
                    <input class="form-control" id="consultation_amount" type="text" value="<?php echo e($consultation->amount ?? '0.00'); ?>" readonly data-clinical-amount>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_discount_percent">Discount % <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('discount_percent')); ?>" id="consultation_discount_percent" name="discount_percent" type="number" inputmode="decimal" min="0" max="100" step="0.01"
                        value="<?php echo e(getValue('discount_percent', $consultation, '0.00')); ?>" required data-clinical-discount-percent
                        <?php echo e(inputAttrs('discount_percent', 'consultation_discount_percent')); ?>>
                    <?php $__errorArgs = ['discount_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="consultation_discount_percent-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_discount_amount">Discount Amount <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('discount_amount')); ?>" id="consultation_discount_amount" name="discount_amount" type="text" inputmode="decimal" maxlength="13"
                        value="<?php echo e(getValue('discount_amount', $consultation, '0.00')); ?>" required data-clinical-discount-amount
                        <?php echo e(inputAttrs('discount_amount', 'consultation_discount_amount')); ?>>
                    <?php $__errorArgs = ['discount_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="consultation_discount_amount-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_payable_amount">Payable </label>
                    <input class="form-control" id="consultation_payable_amount" type="text" value="<?php echo e($consultation->payable_amount ?? '0.00'); ?>" readonly data-clinical-payable>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_paid_amount">Paid  <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('paid_amount')); ?>" id="consultation_paid_amount" name="paid_amount" type="text" inputmode="decimal" maxlength="13" value="<?php echo e(getValue('paid_amount', $consultation, '0.00')); ?>" required data-clinical-paid <?php echo e(inputAttrs('paid_amount', 'consultation_paid_amount')); ?>>
                    <?php $__errorArgs = ['paid_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="consultation_paid_amount-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_due_amount">Due </label>
                    <input class="form-control" id="consultation_due_amount" type="text" value="<?php echo e($consultation->due_amount ?? '0.00'); ?>" readonly data-clinical-due>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" >Remarks</label>
                    <input class="form-control" type="text" value="<?php echo e($patient_investigation->remarks ?? ''); ?>"name="remarks" >
                </div>
            </div>
        </section>

        <?php echo $__env->make('core::partials.form-actions', ['model' => $consultation, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>

    <?php echo $__env->make('hospital::patients.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\consultations\form.blade.php ENDPATH**/ ?>