

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if($account?->enabled): ?>
        <form class="app-card p-3 p-md-4" method="POST" action="<?php echo e(route('tenantcore.sms-recharges.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="amount">Amount (BDT)</label>
                    <input class="<?php echo e(inputClass('amount')); ?>" id="amount" name="amount" inputmode="decimal" value="<?php echo e(getValue('amount')); ?>" required autofocus>
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="payment_method">Payment method</label>
                    <input class="<?php echo e(inputClass('payment_method')); ?>" id="payment_method" name="payment_method" value="<?php echo e(getValue('payment_method')); ?>" maxlength="100" placeholder="Bank / bKash / Nagad" required>
                    <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="payment_reference">Transaction reference</label>
                    <input class="<?php echo e(inputClass('payment_reference')); ?>" id="payment_reference" name="payment_reference" value="<?php echo e(getValue('payment_reference')); ?>" maxlength="150" required>
                    <?php $__errorArgs = ['payment_reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="proof">Payment proof (optional)</label>
                    <input class="<?php echo e(inputClass('proof')); ?>" id="proof" name="proof" type="file" accept=".png,.jpg,.jpeg,.webp,.pdf">
                    <div class="form-text">Image or PDF, up to 5 MB.</div>
                    <?php $__errorArgs = ['proof'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2 border-top mt-4 pt-3">
                <a class="btn btn-warning" href="<?php echo e($cancelUrl); ?>">Cancel</a>
                <button class="btn btn-primary" type="submit">Submit request</button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-secondary">SMS is not enabled. Contact the platform administrator.</div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\sms-recharges\form.blade.php ENDPATH**/ ?>