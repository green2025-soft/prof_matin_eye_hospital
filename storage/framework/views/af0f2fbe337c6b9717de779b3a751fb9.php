

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Record money already received. This does not initiate an online charge.',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input name="idempotency_key" type="hidden" value="<?php echo e(getValue('idempotency_key', null, $idempotencyKey)); ?>">

        <section class="app-form-section" aria-labelledby="payment-details-heading">
            <h2 class="app-form-section-title" id="payment-details-heading">Payment details</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="invoice-id">Invoice ID</label>
                    <input class="<?php echo e(inputClass('invoice_id')); ?>" id="invoice-id" name="invoice_id" type="text"
                        inputmode="numeric" list="payment-invoices" value="<?php echo e(getValue('invoice_id')); ?>" required
                        <?php echo e(inputAttrs('invoice_id', 'invoice-id', 'invoice-id-help')); ?>>
                    <datalist id="payment-invoices">
                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($invoice->id); ?>">
                                <?php echo e($invoice->number); ?> - <?php echo e($invoice->currency); ?> <?php echo e($invoice->balance_amount); ?> due
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </datalist>
                    <div class="form-text" id="invoice-id-help">
                        Choose an unpaid invoice suggestion or enter its ID. Currency comes from that invoice.
                    </div>
                    <?php $__errorArgs = ['invoice_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="invoice-id-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="amount">Amount received</label>
                    <input class="<?php echo e(inputClass('amount')); ?>" id="amount" name="amount" type="text" inputmode="decimal"
                        value="<?php echo e(getValue('amount')); ?>" placeholder="0.00" required <?php echo e(inputAttrs('amount')); ?>>
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="amount-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>

        <p class="small text-body-secondary">
            Payments are final once recorded. The server checks the remaining balance before saving.
        </p>
        <?php echo $__env->make('core::partials.form-actions', [
            'model' => $payment,
            'routePrefix' => 'core.payments',
            'submitLabel' => 'Save',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\payments\form.blade.php ENDPATH**/ ?>