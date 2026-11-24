<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    
    <?php echo $__env->make('hospital::partials.clinical-tabs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form
        class="app-card p-3 p-md-4"
        action="<?php echo e($formAction); ?>"
        method="POST"
        data-clinical-entry="investigation"
        data-default-payment="<?php echo e(! $patient_investigation->exists && ! session()->hasOldInput('paid_amount') ? $paymentDefault : 'manual'); ?>"
        data-record-exists="<?php echo e($patient_investigation->exists ? '1' : '0'); ?>"
        data-initial-amount="<?php echo e($patient_investigation->amount ?? '0.00'); ?>"
        data-original-source-ids="<?php echo e(json_encode(($patient_investigation->exists ? $patient_investigation->investigations->modelKeys() : []))); ?>"
        data-original-eye-type="<?php echo e($patient_investigation->eye_type ?? 'left'); ?>"
        data-investigation-eyes="<?php echo e(json_encode(getValue('investigation_eyes', null, $investigationEyes))); ?>"
        data-original-investigation-eyes="<?php echo e(json_encode($investigationEyes)); ?>"
        data-original-investigation-rates="<?php echo e(json_encode($investigationRates)); ?>"
    >
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <?php if($patient_investigation->exists && $patient_investigation->invoice_no !== null): ?>
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <label class="form-label small mb-1" for="investigation_invoice_no">Invoice No.</label>
                    <input class="form-control form-control-sm" id="investigation_invoice_no" type="text" value="<?php echo e($patient_investigation->invoice_no); ?>" readonly>
                </div>
            </div>
        <?php endif; ?>

        <div class="row g-3">
            <div class="col-md-4">
                <?php echo $__env->make('hospital::patients.selector', ['selectedPatient' => $selectedPatient, 'selectorId' => 'investigation_patient_id'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="investigation_entry_date">Entry date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="<?php echo e(inputClass('entry_date')); ?>" id="investigation_entry_date" name="entry_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                        value="<?php echo e(getValue('entry_date', null, $entryDate)); ?>" required data-input
                        <?php echo e(inputAttrs('entry_date', 'investigation_entry_date')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="investigation_entry_date" aria-label="Choose entry date" title="Choose entry date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                <?php $__errorArgs = ['entry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_entry_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <fieldset class="col-md-4" <?php echo e(inputAttrs('eye_type', 'investigation_eye_type', 'investigation_eye_type-help')); ?>>
                <legend class="form-label mb-2">Eye type <span class="text-danger" aria-hidden="true">*</span></legend>
                <div class="btn-group w-100" role="group" aria-label="Eye type">
                    <?php $__currentLoopData = $eyeTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input class="<?php echo e(inputClass('eye_type', 'btn-check')); ?>" id="investigation_eye_type_<?php echo e($value); ?>" name="eye_type" type="radio" value="<?php echo e($value); ?>" <?php echo e(getChecked($value, 'eye_type', $patient_investigation, 'left')); ?> required>
                        <label class="btn btn-outline-primary" for="investigation_eye_type_<?php echo e($value); ?>"><i class="fa-solid fa-eye me-2" aria-hidden="true"></i><?php echo e($label); ?></label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php $__errorArgs = ['eye_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_eye_type-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </fieldset>
        </div>

        <div class="row g-3 mt-0">
            <div class="col-md-4">
                <label class="form-label" for="investigation_doctor_id">Doctor <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('doctor_id', 'form-select')); ?>" id="investigation_doctor_id" name="doctor_id"
                    data-ajax-select data-url="<?php echo e(route('hospital.doctors.options')); ?>"
                    data-value-field="id" data-label-field="text" data-placeholder="Select a doctor" required
                    <?php echo e(inputAttrs('doctor_id', 'investigation_doctor_id')); ?>>
                    <option value=""></option>
                    <?php if($selectedDoctor !== null): ?>
                        <option value="<?php echo e($selectedDoctor->id); ?>" data-consultation-fee="<?php echo e($selectedDoctor->consultation_fee); ?>" data-follow-up-fee="<?php echo e($selectedDoctor->follow_up_fee); ?>" selected><?php echo e($selectedDoctor->doctor_id ?: $selectedDoctor->id); ?> — <?php echo e($selectedDoctor->name); ?></option>
                    <?php endif; ?>
                </select>
                <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_doctor_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="investigation_payment_type_id">Payment Type <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('payment_type_id', 'form-select')); ?>" id="investigation_payment_type_id" name="payment_type_id" required <?php echo e(inputAttrs('payment_type_id', 'investigation_payment_type_id')); ?>>
                    
                    <?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($paymentType->id); ?>" <?php echo e(getSelected($paymentType->id, 'payment_type_id', $patient_investigation)); ?>><?php echo e($paymentType->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['payment_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_payment_type_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="investigation_delivery_date">Delivery Date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="<?php echo e(inputClass('delivery_date')); ?>" id="investigation_delivery_date" name="delivery_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                        value="<?php echo e(getValue('delivery_date', null, $deliveryDate)); ?>" required data-input
                        <?php echo e(inputAttrs('delivery_date', 'investigation_delivery_date')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="investigation_delivery_date" aria-label="Choose delivery date" title="Choose delivery date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                <?php $__errorArgs = ['delivery_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_delivery_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
             <div class="col-md-6">
                <label class="form-label" for="investigation_investigation_ids">Investigations <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('investigation_ids', 'form-select')); ?>" id="investigation_investigation_ids" data-investigation-picker
                    data-ajax-select data-url="<?php echo e(route('hospital.investigations.options')); ?>"
                    data-value-field="id" data-label-field="name" data-placeholder="Select a test to add" <?php if($selectedDoctor === null): echo 'disabled'; endif; ?>
                    <?php echo e(inputAttrs('investigation_ids', 'investigation_investigation_ids', 'investigation_doctor-help investigation_picker-help')); ?>>
                    <option value=""></option>
                </select>
                <select name="investigation_ids[]" data-investigation-items multiple hidden>
                    <?php $__currentLoopData = $selectedInvestigations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $investigation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($investigation->id); ?>" data-single-rate="<?php echo e($investigation->single_rate); ?>" data-both-rate="<?php echo e($investigation->both_rate); ?>"
                            data-category-name="<?php echo e($investigation->category?->name); ?>"
                            data-entry-rate="<?php echo e($investigationRates[$investigation->id] ?? ''); ?>" data-entry-eye="<?php echo e($investigationEyes[$investigation->id] ?? $patient_investigation->eye_type ?? 'left'); ?>" selected><?php echo e($investigation->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="form-text mb-0" id="investigation_doctor-help" data-investigation-doctor-help <?php if($selectedDoctor !== null): ?> hidden <?php endif; ?>></p>
                <p class="form-text mb-0" id="investigation_picker-help" data-investigation-picker-help aria-live="polite"></p>
                <?php $__errorArgs = ['investigation_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_investigation_ids-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php $__errorArgs = ['investigation_ids.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
             </div>
               <?php if($referenceEnabled): ?>
               <div class="col-md-4">
                    <label class="form-label" for="investigation_reference_id">Reference</label>
                    <select class="<?php echo e(inputClass('reference_id', 'form-select')); ?>" id="investigation_reference_id" name="reference_id"
                        data-ajax-select data-url="<?php echo e(route('hospital.references.options')); ?>"
                        data-value-field="id" data-label-field="text" data-placeholder="Select a reference"
                        <?php echo e(inputAttrs('reference_id', 'investigation_reference_id')); ?>>
                        <option value=""></option>
                        <?php if($selectedReference !== null): ?>
                            <option value="<?php echo e($selectedReference->id); ?>" selected><?php echo e($selectedReference->reference_id); ?> — <?php echo e($selectedReference->name); ?><?php echo e($selectedReference->contact_no ? ' ('.$selectedReference->contact_no.')' : ''); ?></option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['reference_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_reference_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
               <?php endif; ?>

        </div>

        <div class="mt-3">

        </div>
        <div class="table-responsive border rounded mt-3">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Selected investigations, doctor, categories, eye types, and rates</caption>
                <thead>
                    <tr>
                        <th scope="col">Doctor</th>
                        <th scope="col">Test</th>
                        <th scope="col">Category</th>
                        <th scope="col">Eye</th>
                        <th class="text-end" scope="col">Rate</th>
                        <th class="text-end" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody data-clinical-investigation-items></tbody>
            </table>
        </div>



        <input name="discount_mode" type="hidden" value="<?php echo e(getValue('discount_mode', $patient_investigation, 'percent')); ?>" data-clinical-discount-mode>

        <section class="app-form-section mt-3" aria-labelledby="investigation_payment_heading">
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_amount">Amount</label>
                    <input class="form-control" id="investigation_amount" type="text" value="<?php echo e($patient_investigation->amount ?? '0.00'); ?>" readonly data-clinical-amount>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_discount_percent">Discount % <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('discount_percent')); ?>" id="investigation_discount_percent" name="discount_percent" type="number" inputmode="decimal" min="0" max="100" step="0.01"
                        value="<?php echo e(getValue('discount_percent', $patient_investigation, '0.00')); ?>" required data-clinical-discount-percent
                        <?php echo e(inputAttrs('discount_percent', 'investigation_discount_percent')); ?>>
                    <?php $__errorArgs = ['discount_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_discount_percent-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_discount_amount">Discount Amount <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('discount_amount')); ?>" id="investigation_discount_amount" name="discount_amount" type="text" inputmode="decimal" maxlength="13"
                        value="<?php echo e(getValue('discount_amount', $patient_investigation, '0.00')); ?>" required data-clinical-discount-amount
                        <?php echo e(inputAttrs('discount_amount', 'investigation_discount_amount')); ?>>
                    <?php $__errorArgs = ['discount_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_discount_amount-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3 ">
                    <label class="form-label" for="investigation_extra_discount_percent">Extra Discount % <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('extra_discount_percent')); ?>" id="investigation_extra_discount_percent" name="extra_discount_percent" type="number" inputmode="decimal" min="0" max="100" step="0.01"
                        value="<?php echo e(getValue('extra_discount_percent', $patient_investigation, '0.00')); ?>" required data-clinical-extra-discount-percent
                        <?php echo e(inputAttrs('extra_discount_percent', 'investigation_extra_discount_percent')); ?>>
                    <?php $__errorArgs = ['extra_discount_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_extra_discount_percent-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_payable_amount">Payable </label>
                    <input class="form-control" id="investigation_payable_amount" type="text" value="<?php echo e($patient_investigation->payable_amount ?? '0.00'); ?>" readonly data-clinical-payable>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_paid_amount">Paid  <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('paid_amount')); ?>" id="investigation_paid_amount" name="paid_amount" type="text" inputmode="decimal" maxlength="13"
                        value="<?php echo e(getValue('paid_amount', $patient_investigation, '0.00')); ?>" required data-clinical-paid
                        <?php echo e(inputAttrs('paid_amount', 'investigation_paid_amount')); ?>>
                    <?php $__errorArgs = ['paid_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_paid_amount-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_due_amount">Due</label>
                    <input class="form-control" id="investigation_due_amount" type="text" value="<?php echo e($patient_investigation->due_amount ?? '0.00'); ?>" readonly data-clinical-due>
                </div>
                <div class="col-md-3 d-none">
                    <label class="form-label" for="investigation_due_date">Due Date <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('due_date')); ?>" id="investigation_due_date" name="due_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('due_date', null, $dueDate)); ?>" required data-input <?php echo e(inputAttrs('due_date', 'investigation_due_date')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="investigation_due_date" aria-label="Choose due date" title="Choose due date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_due_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" >Remarks</label>
                    <input class="form-control" type="text" value="<?php echo e($patient_investigation->remarks ?? ''); ?>"name="remarks" >
                </div>
            </div>
        </section>

        <?php echo $__env->make('core::partials.form-actions', ['model' => $patient_investigation, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>

    <?php echo $__env->make('hospital::patients.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\patient-investigations\form.blade.php ENDPATH**/ ?>