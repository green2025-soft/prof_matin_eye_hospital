<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Record the operation and patient payment details.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('hospital::partials.clinical-tabs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form
        class="app-card p-3 p-md-4"
        action="<?php echo e($formAction); ?>"
        method="POST"
        data-clinical-entry="operation"
        data-default-payment="<?php echo e(! $patient_operation->exists && ! session()->hasOldInput('paid_amount') ? $paymentDefault : 'manual'); ?>"
        data-record-exists="<?php echo e($patient_operation->exists ? '1' : '0'); ?>"
        data-initial-amount="<?php echo e($patient_operation->amount ?? '0.00'); ?>"
        data-original-source-ids="<?php echo e(json_encode(($patient_operation->exists ? [$patient_operation->operation_id] : []))); ?>"
        data-original-patient-type="<?php echo e($patient_operation->patient_type ?? 'new'); ?>"
        data-original-eye-type="<?php echo e($patient_operation->eye_type ?? 'left'); ?>"
    >
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <?php if($patient_operation->exists && $patient_operation->invoice_no !== null): ?>
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <label class="form-label small mb-1" for="operation_invoice_no">Invoice No.</label>
                    <input class="form-control form-control-sm" id="operation_invoice_no" type="text" value="<?php echo e($patient_operation->invoice_no); ?>" readonly>
                </div>
            </div>
        <?php endif; ?>

        <div class="row g-3">
            <div class="col-md-4">
                <?php echo $__env->make('hospital::patients.selector', ['selectedPatient' => $selectedPatient, 'selectorId' => 'operation_patient_id'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="operation_entry_date">Entry date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="<?php echo e(inputClass('entry_date')); ?>" id="operation_entry_date" name="entry_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('entry_date', null, $entryDate)); ?>" required data-input <?php echo e(inputAttrs('entry_date', 'operation_entry_date')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="operation_entry_date" aria-label="Choose entry date" title="Choose entry date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                <?php $__errorArgs = ['entry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_entry_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <fieldset class="col-md-4" <?php echo e(inputAttrs('eye_type', 'operation_eye_type')); ?>>
                <legend class="form-label mb-2">Eye type <span class="text-danger" aria-hidden="true">*</span></legend>
                <div class="btn-group w-100" role="group" aria-label="Eye type">
                    <?php $__currentLoopData = $eyeTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input class="<?php echo e(inputClass('eye_type', 'btn-check')); ?>" id="operation_eye_type_<?php echo e($value); ?>" name="eye_type" type="radio" value="<?php echo e($value); ?>" <?php echo e(getChecked($value, 'eye_type', $patient_operation, 'left')); ?> required>
                        <label class="btn btn-outline-primary" for="operation_eye_type_<?php echo e($value); ?>"><i class="fa-solid fa-eye me-2" aria-hidden="true"></i><?php echo e($label); ?></label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php $__errorArgs = ['eye_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_eye_type-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </fieldset>
        </div>

        <div class="row g-3 mt-0">
            <div class="col-md-4">
                <label class="form-label" for="operation_doctor_id">Surgeon  Doctor <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('doctor_id', 'form-select')); ?>" id="operation_doctor_id" name="doctor_id"
                    data-ajax-select data-url="<?php echo e(route('hospital.doctors.options')); ?>"
                    data-value-field="id" data-label-field="text" data-placeholder="Select Surgeon Doctor" required
                    <?php echo e(inputAttrs('doctor_id', 'operation_doctor_id')); ?>>
                    <option value=""></option>
                    <?php if($selectedDoctor !== null): ?>
                        <option value="<?php echo e($selectedDoctor->id); ?>" data-consultation-fee="<?php echo e($selectedDoctor->consultation_fee); ?>" data-follow-up-fee="<?php echo e($selectedDoctor->follow_up_fee); ?>" selected><?php echo e($selectedDoctor->doctor_id ?: $selectedDoctor->id); ?> — <?php echo e($selectedDoctor->name); ?></option>
                    <?php endif; ?>
                </select>
                <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_doctor_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="operation_payment_type_id">Payment Type <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('payment_type_id', 'form-select')); ?>" id="operation_payment_type_id" name="payment_type_id" required <?php echo e(inputAttrs('payment_type_id', 'operation_payment_type_id')); ?>>

                    <?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($paymentType->id); ?>" <?php echo e(getSelected($paymentType->id, 'payment_type_id', $patient_operation)); ?>><?php echo e($paymentType->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['payment_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_payment_type_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="operation_consultant_id">Consultant</label>
                <select class="<?php echo e(inputClass('consultant_id', 'form-select')); ?>" id="operation_consultant_id" name="consultant_id"
                    data-ajax-select data-url="<?php echo e(route('hospital.doctors.options')); ?>"
                    data-value-field="id" data-label-field="text" data-placeholder="Select a consultant"
                    <?php echo e(inputAttrs('consultant_id', 'operation_consultant_id')); ?>>
                    <option value=""></option>
                    <?php if($selectedConsultant !== null): ?>
                        <option value="<?php echo e($selectedConsultant->id); ?>" selected><?php echo e($selectedConsultant->doctor_id ?: $selectedConsultant->id); ?> — <?php echo e($selectedConsultant->name); ?></option>
                    <?php endif; ?>
                </select>
                <?php $__errorArgs = ['consultant_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_consultant_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="row g-3 mt-0">
            <div class="col-md-4">
                <label class="form-label" for="operation_operation_id">Operation <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('operation_id', 'form-select')); ?>" id="operation_operation_id" name="operation_id"
                    data-ajax-select data-url="<?php echo e(route('hospital.operations.options')); ?>"
                    data-value-field="id" data-label-field="name" data-placeholder="Select an operation" required
                    <?php echo e(inputAttrs('operation_id', 'operation_operation_id')); ?>>
                    <option value=""></option>
                    <?php if($selectedOperation !== null): ?>
                        <option value="<?php echo e($selectedOperation->id); ?>" data-single-rate="<?php echo e($selectedOperation->single_rate); ?>" data-both-rate="<?php echo e($selectedOperation->both_rate); ?>" selected><?php echo e($selectedOperation->name); ?></option>
                    <?php endif; ?>
                </select>
                <?php $__errorArgs = ['operation_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_operation_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="operation_operation_date">Operation date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="<?php echo e(inputClass('operation_date')); ?>" id="operation_operation_date" name="operation_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                        value="<?php echo e(getValue('operation_date', null, $operationDate)); ?>" required data-input
                        <?php echo e(inputAttrs('operation_date', 'operation_operation_date')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="operation_operation_date" aria-label="Choose operation date" title="Choose operation date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                <?php $__errorArgs = ['operation_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_operation_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <?php if($referenceEnabled): ?>
            <div class="row g-3 mt-0">
                <div class="col-md-4">
                    <label class="form-label" for="operation_reference_id">Reference</label>
                    <select class="<?php echo e(inputClass('reference_id', 'form-select')); ?>" id="operation_reference_id" name="reference_id"
                        data-ajax-select data-url="<?php echo e(route('hospital.references.options')); ?>"
                        data-value-field="id" data-label-field="text" data-placeholder="Select a reference"
                        <?php echo e(inputAttrs('reference_id', 'operation_reference_id')); ?>>
                        <option value=""></option>
                        <?php if($selectedReference !== null): ?>
                            <option value="<?php echo e($selectedReference->id); ?>" selected><?php echo e($selectedReference->reference_id); ?> — <?php echo e($selectedReference->name); ?><?php echo e($selectedReference->contact_no ? ' ('.$selectedReference->contact_no.')' : ''); ?></option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['reference_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_reference_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        <?php endif; ?>

        <section class="app-form-section mt-3" aria-labelledby="operation_payment_heading">
            <h2 class="h6 mb-3" id="operation_payment_heading">Payment details</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="operation_amount">Amount</label>
                    <input class="form-control" id="operation_amount" type="text" value="<?php echo e($patient_operation->amount ?? '0.00'); ?>" readonly data-clinical-amount>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="operation_discount_amount">Discount Amount <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('discount_amount')); ?>" id="operation_discount_amount" name="discount_amount" type="text" inputmode="decimal" maxlength="13"
                        value="<?php echo e(getValue('discount_amount', $patient_operation, '0.00')); ?>" required data-clinical-discount-amount
                        <?php echo e(inputAttrs('discount_amount', 'operation_discount_amount')); ?>>
                    <?php $__errorArgs = ['discount_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_discount_amount-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="operation_payable_amount">Payable </label>
                    <input class="form-control" id="operation_payable_amount" type="text" value="<?php echo e($patient_operation->payable_amount ?? '0.00'); ?>" readonly data-clinical-payable>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="operation_paid_amount">Paid  <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('paid_amount')); ?>" id="operation_paid_amount" name="paid_amount" type="text" inputmode="decimal" maxlength="13" value="<?php echo e(getValue('paid_amount', $patient_operation, '0.00')); ?>" required data-clinical-paid <?php echo e(inputAttrs('paid_amount', 'operation_paid_amount')); ?>>
                    <?php $__errorArgs = ['paid_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_paid_amount-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="operation_due_amount">Due </label>
                    <input class="form-control" id="operation_due_amount" type="text" value="<?php echo e($patient_operation->due_amount ?? '0.00'); ?>" readonly data-clinical-due>
                </div>
                <div class="col-md-4 d-none">
                    <label class="form-label" for="operation_due_date">Due Date <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('due_date')); ?>" id="operation_due_date" name="due_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('due_date', null, $dueDate)); ?>" required data-input <?php echo e(inputAttrs('due_date', 'operation_due_date')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="operation_due_date" aria-label="Choose due date" title="Choose due date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_due_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                 <div class="col-sm-6 col-xl-6">
                     <label class="form-label" for="operation_remarks">Remarks</label>
            <textarea class="<?php echo e(inputClass('remarks')); ?>" id="operation_remarks" name="remarks" rows="1" maxlength="2000" <?php echo e(inputAttrs('remarks', 'operation_remarks')); ?>><?php echo e(getValue('remarks', $patient_operation)); ?></textarea>
            <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_remarks-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                 </div>
            </div>
        </section>



        <?php echo $__env->make('core::partials.form-actions', ['model' => $patient_operation, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>

    <?php echo $__env->make('hospital::patients.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\patient-operations\form.blade.php ENDPATH**/ ?>