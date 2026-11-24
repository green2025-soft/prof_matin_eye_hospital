<section class="app-form-section" aria-labelledby="pharmacy-payment-heading">
    <h2 class="app-form-section-title" id="pharmacy-payment-heading">
        <span class="app-form-section-icon"><i class="fa-solid fa-wallet" aria-hidden="true"></i></span>
        Payment Details
    </h2>

    <div class="border rounded bg-body-tertiary p-2 mt-3 g-2">
        <dl class="mb-0">
            <div class="d-flex justify-content-between gap-2 py-1">
                <dt class="small text-body-secondary fw-normal">Subtotal</dt>
                <dd class="mb-0 text-end" data-pharmacy-subtotal>0.00</dd>
            </div>
            <div class="d-flex justify-content-between gap-2 py-1">
                <dt class="small text-body-secondary fw-normal">Item Discount</dt>
                <dd class="mb-0 text-end" data-pharmacy-line-discount>0.00</dd>
            </div>
            <div class="d-flex justify-content-between gap-2 py-1">
                <dt class="small text-body-secondary fw-normal">Invoice Discount</dt>
                <dd class="mb-0 text-end" data-pharmacy-invoice-discount>0.00</dd>
            </div>
            <?php if($showVatColumn): ?>
                <div class="d-flex justify-content-between gap-2 py-1">
                    <dt class="small text-body-secondary fw-normal">VAT <?php echo e($pharmacySettings['vat_percent']); ?>% <small>(<?php echo e($pharmacySettings['vat_mode'] === 'inclusive' ? 'included' : 'added'); ?>)</small></dt>
                    <dd class="mb-0 text-end" data-pharmacy-vat>0.00</dd>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between gap-2 py-1">
                <dt class="small text-body-secondary fw-normal">Due</dt>
                <dd class="mb-0 text-end" data-pharmacy-due>0.00</dd>
            </div>
            <div class="d-flex justify-content-between gap-2 py-1 d-none">
                <dt class="small text-body-secondary fw-normal">Paid</dt>
                <dd class="mb-0 text-end" data-pharmacy-paid>0.00</dd>
            </div>
            <div class="d-flex justify-content-between gap-2 bg-primary-subtle text-primary rounded px-2 py-1 mt-1 fw-semibold">
                <dt class="small fw-semibold">Amount</dt>
                <dd class="mb-0 text-end" data-pharmacy-total>0.00</dd>
            </div>
        </dl>
    </div>
    <div class="row g-2">
        <div class="col-6">
            <label class="form-label" for="discount">Discount</label>
            <input class="<?php echo e(inputClass('discount')); ?>" id="discount" name="discount" type="number"
                inputmode="decimal" min="0" step="0.01" placeholder="0"
                value="<?php echo e(getValue('discount', null, $entry->exists ? moneyDecimal($entry->discount - $entry->lines->sum('discount')) : '')); ?>"
                <?php echo e(inputAttrs('discount')); ?>>
            <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="discount-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-6">
            <label class="form-label" for="paid_amount">Paid</label>
            <input class="<?php echo e(inputClass('paid_amount')); ?>" id="paid_amount" name="paid_amount" type="number"
                inputmode="decimal" min="0" step="0.01" placeholder="0"
                value="<?php echo e(getValue('paid_amount', null, $entry->exists ? moneyDecimal($entry->initial_paid) : '')); ?>"
                <?php echo e(inputAttrs('paid_amount')); ?>>
            <?php $__errorArgs = ['paid_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="paid_amount-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="mt-3">
        <label class="form-label" for="notes">Remarks</label>
        <textarea class="<?php echo e(inputClass('notes')); ?>" id="notes" name="notes" rows="1" maxlength="2000"
            <?php echo e(inputAttrs('notes')); ?>><?php echo e(getValue('notes', $entry)); ?></textarea>
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
</section>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Pharmacy\resources/views/sales/payment-summary.blade.php ENDPATH**/ ?>