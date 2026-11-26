<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Collect part or all of an invoice’s outstanding due.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST" data-due-collection-form
        data-record-exists="<?php echo e($due_collection->exists ? '1' : '0'); ?>"
        data-preview-url="<?php echo e(route('hospital.due-collections.invoice')); ?>"
        data-initial-preview="<?php echo e(json_encode($collectionPreview)); ?>"
    >
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <div class="row g-3">


            <div class="col-md-3">
                <label class="form-label" for="due_collection_service">Service Type <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('service', 'form-select')); ?>" id="due_collection_service" name="service" required <?php if($due_collection->exists): echo 'disabled'; endif; ?> <?php echo e(inputAttrs('service', 'due_collection_service')); ?>>
                    <?php $__currentLoopData = $serviceOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php if($selectedService === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['service'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="due_collection_service-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-5">
                <label class="form-label" for="due_collection_invoice">Select Invoice <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('invoice', 'form-select')); ?>" id="due_collection_invoice" name="invoice" data-due-collection-invoice required <?php if($due_collection->exists): echo 'disabled'; endif; ?>
                    <?php if(! $due_collection->exists): ?>
                        data-ajax-select data-url="<?php echo e(route('hospital.due-collections.invoices')); ?>" data-value-field="id" data-label-field="text" data-placeholder="Search invoice or patient" data-parent-select="due_collection_service" data-parent-field="service" data-no-results-text="No matching invoice has an outstanding due."
                    <?php endif; ?>
                    <?php echo e(inputAttrs('invoice', 'due_collection_invoice', 'due_collection_invoice-help due_collection_preview-error')); ?>

                >
                    <option value=""></option>
                    <?php if($selectedInvoice !== null): ?>
                        <option value="<?php echo e($selectedInvoice['id']); ?>" data-service="<?php echo e($selectedInvoice['service']); ?>" selected><?php echo e($selectedInvoice['text']); ?></option>
                    <?php endif; ?>
                </select>
                <div class="form-text" id="due_collection_invoice-help"><?php echo e($due_collection->exists ? 'The service and invoice are fixed after saving.' : 'Search by invoice number, patient name, or mobile.'); ?></div>
                <?php $__errorArgs = ['invoice'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="due_collection_invoice-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="form-text" data-due-collection-status role="status" aria-live="polite" hidden></div>
                <div class="alert alert-danger py-2 mt-2 mb-0" id="due_collection_preview-error" data-due-collection-error role="alert" hidden></div>
            </div>
             <div class="col-md-4">
                <label class="form-label" for="due_collection_date">Collection Date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="<?php echo e(inputClass('collection_date')); ?>" id="due_collection_date" name="collection_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                        value="<?php echo e(getValue('collection_date', null, $collectionDate)); ?>" required data-input
                        <?php echo e(inputAttrs('collection_date', 'due_collection_date')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="due_collection_date" aria-label="Choose collection date" title="Choose collection date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                <?php $__errorArgs = ['collection_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="due_collection_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-3">
                <label class="form-label" for="due_collection_current_due"><?php echo e($due_collection->exists ? 'Available due for this collection' : 'Current Due'); ?></label>
                <input class="form-control" id="due_collection_current_due" type="text" value="<?php echo e($collectionPreview['available_due'] ?? '0.00'); ?>" readonly data-due-collection-current-due>
            </div>
             <div class="col-md-3">
                <label class="form-label" for="due_collection_discount">Discount</label>
                <input class="<?php echo e(inputClass('discount')); ?>" id="due_collection_discount" name="discount" type="text" inputmode="decimal" maxlength="11" pattern="[0-9]{1,8}([.][0-9]{1,2})?"
                    value="<?php echo e(getValue('discount', $due_collection)); ?>"
                    data-due-collection-discount
                    <?php echo e(inputAttrs('discount', 'due_collection_discount', 'due_collection_discount-help due_collection_discount-client-error')); ?>>

                <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="due_collection_discount-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="invalid-feedback d-block" id="due_collection_discount-client-error" data-due-collection-discount-error aria-live="polite" aria-atomic="true"></div>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="due_collection_amount">Paid Amount <span class="text-danger" aria-hidden="true">*</span></label>
                <input class="<?php echo e(inputClass('amount')); ?>" id="due_collection_amount" name="amount" type="text" inputmode="decimal" maxlength="13" pattern="[0-9]{1,10}([.][0-9]{1,2})?"
                    value="<?php echo e(getValue('amount', $due_collection)); ?>" required data-due-collection-amount
                    <?php echo e(inputAttrs('amount', 'due_collection_amount', 'due_collection_amount-help due_collection_amount-client-error')); ?>>
                <div class="form-text" id="due_collection_amount-help">Enter at least 0.01, up to the current due.</div>
                <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="due_collection_amount-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="invalid-feedback d-block" id="due_collection_amount-client-error" data-due-collection-amount-error aria-live="polite" aria-atomic="true"></div>
            </div>

            <div class="col-md-3">
                <label class="form-label" for="due_collection_payment_type_id">Payment Type <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('payment_type_id', 'form-select')); ?>" id="due_collection_payment_type_id" name="payment_type_id" required <?php echo e(inputAttrs('payment_type_id', 'due_collection_payment_type_id')); ?>>

                    <?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($paymentType->id); ?>" <?php echo e(getSelected($paymentType->id, 'payment_type_id', $due_collection)); ?>><?php echo e($paymentType->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['payment_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="due_collection_payment_type_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-6">
                <label class="form-label" for="due_collection_remarks">Remarks</label>
                <textarea class="<?php echo e(inputClass('remarks')); ?>" id="due_collection_remarks" name="remarks" rows="2" maxlength="2000" <?php echo e(inputAttrs('remarks', 'due_collection_remarks')); ?>><?php echo e(getValue('remarks', $due_collection)); ?></textarea>
                <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="due_collection_remarks-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <?php echo $__env->make('core::partials.form-actions', ['model' => $due_collection, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\due-collections\form.blade.php ENDPATH**/ ?>