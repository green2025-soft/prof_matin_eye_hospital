<?php
    $productType = request()->attributes->get('pharmacy_product_type', 'medicine');
    $paymentTitle = ucfirst($productType).' Customer Collection';
    $invoiceField = 'sale_id';
?>

<?php $__env->startSection('title', 'New '.$paymentTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $paymentTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST" data-pharmacy-payment
        data-invoices-url="<?php echo e(route('pharmacy.sale-payments.invoices')); ?>">
        <?php echo csrf_field(); ?>
        <input id="payment_product_type" name="product_type" type="hidden" value="<?php echo e($productType); ?>">
        <?php $__errorArgs = ['product_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="row g-3 mb-3">
            <div class="col-lg-6">
                <label class="form-label" for="payment_customer">Customer</label>
                <select class="<?php echo e(inputClass('customer_id', 'form-select')); ?>" id="payment_customer" name="customer_id"
                    data-pharmacy-payment-party data-ajax-select
                    data-url="<?php echo e(route('pharmacy.sale-payments.customers', ['product_type' => $productType])); ?>"
                    data-label-field="text" data-placeholder="Patient ID, name or mobile with due" required
                    <?php echo e(inputAttrs('customer_id', 'payment_customer')); ?>>
                    <option value=""></option>
                    <?php if($selectedCustomer): ?>
                        <option value="<?php echo e($selectedCustomer['id']); ?>" selected><?php echo e($selectedCustomer['text']); ?></option>
                    <?php endif; ?>
                </select>
                <?php $__errorArgs = ['customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="payment_customer-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label" for="payment_date">Date</label>
                <div class="input-group" data-datepicker>
                    <input class="<?php echo e(inputClass('payment_date')); ?>" id="payment_date" name="payment_date" type="text"
                        inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                        value="<?php echo e(getValue('payment_date', null, now()->toDateString())); ?>" required data-input
                        <?php echo e(inputAttrs('payment_date')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="payment_date"
                        aria-label="Choose payment date" title="Choose payment date">
                        <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                    </button>
                </div>
                <?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="payment_date-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label" for="payment_type_id">Payment Type</label>
                <select class="<?php echo e(inputClass('payment_type_id', 'form-select')); ?>" id="payment_type_id"
                    name="payment_type_id" <?php echo e(inputAttrs('payment_type_id')); ?>>
                    <?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($paymentType->id); ?>" <?php if((string) getValue('payment_type_id') === (string) $paymentType->id): echo 'selected'; endif; ?>>
                            <?php echo e($paymentType->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['payment_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="payment_type_id-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
        <div class="alert alert-danger d-none" data-pharmacy-payment-error role="alert"></div>
        <p class="small text-body-secondary mb-2" data-pharmacy-payment-loading role="status" hidden>Loading invoices...</p>
        <?php $__errorArgs = ['allocations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col">Select</th>
                        <th scope="col">Invoice</th>
                        <th scope="col" class="text-end">Due</th>
                        <?php if($canDiscount): ?>
                            <th scope="col">Discount</th>
                        <?php endif; ?>
                        <th scope="col">Collection</th>

                    </tr>
                </thead>
                <tbody data-pharmacy-allocations>
                    <?php $__currentLoopData = $allocationInputs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php ($invoice = $invoices->get($allocation[$invoiceField] ?? 0)); ?>
                        <?php if($invoice === null) continue; ?>
                        <?php ($due = moneyDecimal($invoice->outstandingMinor())); ?>
                        <tr data-pharmacy-allocation data-invoice-id="<?php echo e($invoice->id); ?>" data-due="<?php echo e($due); ?>">
                            <td>
                                <input class="form-check-input" type="checkbox" aria-label="Pay invoice <?php echo e($invoice->number); ?>"
                                    data-pharmacy-allocation-select checked>
                            </td>
                            <td>
                                <?php echo e($invoice->number); ?>

                                <input name="allocations[<?php echo e($index); ?>][<?php echo e($invoiceField); ?>]" type="hidden" value="<?php echo e($invoice->id); ?>">
                            </td>
                            <td class="text-end"><?php echo e($due); ?></td>
                            <?php if($canDiscount): ?>
                                <td>
                                    <input class="<?php echo e(inputClass('allocations.'.$index.'.discount')); ?>"
                                        id="allocation-<?php echo e($index); ?>-discount" name="allocations[<?php echo e($index); ?>][discount]"
                                        aria-label="Discount for invoice <?php echo e($invoice->number); ?>" type="number" min="0"
                                        max="<?php echo e($due); ?>" step="0.01" value="<?php echo e($allocation['discount'] ?? '0.00'); ?>"
                                        <?php echo e(inputAttrs('allocations.'.$index.'.discount', 'allocation-'.$index.'-discount')); ?>>
                                    <?php $__errorArgs = ['allocations.'.$index.'.discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block" id="allocation-<?php echo e($index); ?>-discount-error"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </td>
                            <?php endif; ?>
                            <td>
                                <input class="<?php echo e(inputClass('allocations.'.$index.'.amount')); ?>"
                                    id="allocation-<?php echo e($index); ?>-amount" name="allocations[<?php echo e($index); ?>][amount]"
                                    aria-label="Cash for invoice <?php echo e($invoice->number); ?>" type="number" min="0"
                                    max="<?php echo e($due); ?>" step="0.01" value="<?php echo e($allocation['amount'] ?? '0.00'); ?>"
                                    <?php echo e(inputAttrs('allocations.'.$index.'.amount', 'allocation-'.$index.'-amount')); ?>>
                                <?php $__errorArgs = ['allocations.'.$index.'.amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block" id="allocation-<?php echo e($index); ?>-amount-error"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <template data-pharmacy-allocation-template>
            <tr data-pharmacy-allocation>
                <td><input class="form-check-input" type="checkbox" data-pharmacy-allocation-select checked></td>
                <td>
                    <span data-pharmacy-allocation-number></span>
                    <input name="allocations[__INDEX__][<?php echo e($invoiceField); ?>]" type="hidden">
                </td>
                <td class="text-end" data-pharmacy-allocation-due></td>
                 <?php if($canDiscount): ?>
                    <td>
                        <input class="form-control" name="allocations[__INDEX__][discount]" type="number" min="0" step="0.01" value="0.00">
                    </td>
                <?php endif; ?>
                <td>
                    <input class="form-control" name="allocations[__INDEX__][amount]" type="number" min="0" step="0.01">
                </td>

            </tr>
        </template>
        <button class="btn btn-outline-secondary btn-sm d-none mb-3" type="button" data-pharmacy-more-invoices>Load more</button>
        <div class="row g-3 mt-0">
            <div class="col-md-4 align-self-end">
                <span class="text-body-secondary small">Received Amount</span>
                <strong class="d-block" data-pharmacy-cash-total>0.00</strong>
            </div>
            <div class="col-md-5">
                <label class="form-label" for="notes">Remarks</label>
                <textarea class="<?php echo e(inputClass('notes')); ?>" id="notes" name="notes" rows="2" maxlength="2000"
                    <?php echo e(inputAttrs('notes')); ?>><?php echo e(getValue('notes')); ?></textarea>
                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="notes-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

        </div>
        <div class="d-flex gap-2 border-top mt-3 pt-3">
            <a class="btn btn-warning" href="<?php echo e(route('pharmacy.sale-payments.index', ['product_type' => $productType])); ?>">Cancel</a>
            <button class="btn btn-primary" type="submit">Collection</button>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\sale-payments\form.blade.php ENDPATH**/ ?>