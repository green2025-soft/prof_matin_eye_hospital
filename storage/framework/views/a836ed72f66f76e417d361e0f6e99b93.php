

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="app-card p-3 p-md-4">
        <dl class="row gy-2 mb-0">
            <dt class="col-sm-3">Organization</dt><dd class="col-sm-9 mb-0"><?php echo e($recharge->tenant->name); ?></dd>
            <dt class="col-sm-3">Amount</dt><dd class="col-sm-9 fw-semibold mb-0">BDT <?php echo e(moneyDecimal($recharge->amount)); ?></dd>
            <dt class="col-sm-3">Payment method</dt><dd class="col-sm-9 mb-0"><?php echo e($recharge->payment_method); ?></dd>
            <dt class="col-sm-3">Reference</dt><dd class="col-sm-9 text-break mb-0"><?php echo e($recharge->payment_reference); ?></dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9 mb-0"><?php echo e($statusLabel); ?></dd>
            <dt class="col-sm-3">Review note</dt><dd class="col-sm-9 text-break mb-0"><?php echo e($recharge->review_note ?? '-'); ?></dd>
        </dl>
        <?php if($recharge->proof_path): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewProof', $recharge)): ?>
                <a class="btn btn-outline-primary mt-3" href="<?php echo e(route('core.sms-recharges.proof', $recharge)); ?>">Download payment proof</a>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($recharge->status === 'pending'): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reviewRecharge', $recharge)): ?>
                <form class="border-top mt-4 pt-3" method="POST" action="<?php echo e(route('core.sms-recharges.update', $recharge)); ?>" data-confirm="Save this recharge decision after checking the payment?">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <label class="form-label" for="review_note">Review note</label>
                    <textarea class="<?php echo e(inputClass('review_note')); ?>" id="review_note" name="review_note" rows="2" maxlength="1000"><?php echo e(getValue('review_note')); ?></textarea>
                    <?php $__errorArgs = ['review_note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-success" name="decision" value="approve" type="submit">Approve and credit balance</button>
                        <button class="btn btn-danger" name="decision" value="reject" type="submit">Reject</button>
                    </div>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\sms-recharges\show.blade.php ENDPATH**/ ?>