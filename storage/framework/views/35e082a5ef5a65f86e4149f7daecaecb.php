<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Select an invoice to adjust its due and refund any remaining paid value.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST" data-refund-form
        data-record-exists="<?php echo e($refund->exists ? '1' : '0'); ?>"
        data-preview-url="<?php echo e(route('hospital.refunds.invoice')); ?>"
        data-initial-preview="<?php echo e(json_encode($refundPreview)); ?>"
        data-selected-item-ids="<?php echo e(json_encode($selectedItemIds)); ?>"
        data-fixed-amount="<?php echo e($refund->amount ?? '0.00'); ?>"
        data-fixed-due-adjustment="<?php echo e($refund->due_adjustment ?? '0.00'); ?>"
    >
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="refund_service">Service <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('service', 'form-select')); ?>" id="refund_service" name="service" required <?php if($refund->exists): echo 'disabled'; endif; ?> <?php echo e(inputAttrs('service', 'refund_service')); ?>>
                    <?php $__currentLoopData = $serviceOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php if($selectedService === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['service'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="refund_service-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-8">
                <label class="form-label" for="refund_invoice">Invoice <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('invoice', 'form-select')); ?>" id="refund_invoice" name="invoice" data-refund-invoice required <?php if($refund->exists): echo 'disabled'; endif; ?>
                    <?php if(! $refund->exists): ?>
                        data-ajax-select data-url="<?php echo e(route('hospital.refunds.invoices')); ?>" data-value-field="id" data-label-field="text" data-placeholder="Search invoice or patient" data-parent-select="refund_service" data-parent-field="service" data-no-results-text="No matching refundable invoice is available."
                    <?php endif; ?>
                    <?php echo e(inputAttrs('invoice', 'refund_invoice', 'refund_invoice-help refund_preview-error')); ?>

                >
                    <option value=""></option>
                    <?php if($selectedInvoice !== null): ?>
                        <option value="<?php echo e($selectedInvoice['id']); ?>" data-service="<?php echo e($selectedInvoice['service']); ?>" selected><?php echo e($selectedInvoice['text']); ?></option>
                    <?php endif; ?>
                </select>
                <div class="form-text" id="refund_invoice-help"><?php echo e($refund->exists ? 'The service, invoice, and refunded items are fixed after saving.' : 'Search by invoice number, patient name, or mobile. Select the investigation items to refund.'); ?></div>
                <?php $__errorArgs = ['invoice'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="refund_invoice-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="form-text" data-refund-status role="status" aria-live="polite" hidden></div>
                <div class="alert alert-danger py-2 mt-2 mb-0" id="refund_preview-error" data-refund-error role="alert" hidden></div>
            </div>
            <div class="col-12" data-refund-items-section <?php if(($refundPreview['service'] ?? null) !== 'investigation'): ?> hidden <?php endif; ?>>
                <div class="table-responsive">
                    <table class="table app-table align-middle mb-0">
                        <caption class="caption-top pt-0">Investigation items</caption>
                        <thead>
                            <tr>
                                <th scope="col">Select</th>
                                <th scope="col">Test</th>
                                <th scope="col">Room</th>
                                <th scope="col">Eye</th>
                                <th class="text-end" scope="col">Refund value</th>
                            </tr>
                        </thead>
                        <tbody data-refund-items>
                            <?php $__currentLoopData = $refundPreview['items'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <input class="<?php echo e(inputClass('item_ids', 'form-check-input')); ?>" id="refund_item_<?php echo e($item['id']); ?>" name="item_ids[]" type="checkbox"
                                            value="<?php echo e($item['id']); ?>" data-refund-item data-amount="<?php echo e($item['amount']); ?>" <?php if(in_array((int) $item['id'], $selectedItemIds, true)): echo 'checked'; endif; ?> <?php if($refund->exists): echo 'disabled'; endif; ?> aria-describedby="refund_items-help">
                                    </td>
                                    <th class="fw-normal text-break" scope="row"><label class="mb-0" for="refund_item_<?php echo e($item['id']); ?>"><?php echo e($item['name']); ?></label></th>
                                    <td><?php echo e(filled($item['room']) ? $item['room'] : '-'); ?></td>
                                    <td><?php echo e($item['eye_label']); ?></td>
                                    <td class="text-end text-nowrap"><?php echo e($item['amount']); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-text" id="refund_items-help">Select one or more items. Values include the invoice discounts.</div>
                <?php $__errorArgs = ['item_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="item_ids-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php $__errorArgs = ['item_ids.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="refund_credit_amount">Sales Value</label>
                <input class="form-control" id="refund_credit_amount" type="text" value="0.00" readonly data-refund-credit>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="refund_due_adjustment">Due Adjustment</label>
                <input class="form-control" id="refund_due_adjustment" type="text" value="<?php echo e($refund->exists ? $refund->due_adjustment : '0.00'); ?>" readonly data-refund-due-adjustment>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="refund_amount">Refund Amount</label>
                <input class="form-control" id="refund_amount" type="text" value="<?php echo e($refund->exists ? $refund->amount : '0.00'); ?>" readonly data-refund-amount aria-describedby="refund_amount-help">
                <div class="form-text" id="refund_amount-help">The selected value reduces the outstanding due first. Any balance is refunded in cash.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="cause_id">Refund Cause <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="<?php echo e(inputClass('cause_id', 'form-select')); ?>" id="cause_id" name="cause_id" required <?php echo e(inputAttrs('cause_id')); ?>>
                    <option value="">Select a refund cause</option>
                    <?php $__currentLoopData = $refundCauses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cause): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cause->id); ?>" <?php echo e(getSelected($cause->id, 'cause_id', $refund)); ?>><?php echo e($cause->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['cause_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="cause_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-8">
                <label class="form-label" for="refund_remarks">Remarks</label>
                <textarea class="<?php echo e(inputClass('remarks')); ?>" id="refund_remarks" name="remarks" rows="2" maxlength="2000" <?php echo e(inputAttrs('remarks', 'refund_remarks')); ?>><?php echo e(getValue('remarks', $refund)); ?></textarea>
                <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="refund_remarks-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <?php echo $__env->make('core::partials.form-actions', ['model' => $refund, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\refunds\form.blade.php ENDPATH**/ ?>