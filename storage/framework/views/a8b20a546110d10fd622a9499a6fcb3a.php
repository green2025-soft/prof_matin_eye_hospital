

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="app-card p-3 p-md-4">
        <dl class="row gy-2 mb-3">
            <dt class="col-sm-3">Organization</dt><dd class="col-sm-9 mb-0"><?php echo e($message->tenant->name); ?></dd>
            <dt class="col-sm-3">Feature</dt><dd class="col-sm-9 mb-0"><?php echo e($featureLabel); ?></dd>
            <dt class="col-sm-3">Recipient</dt><dd class="col-sm-9 mb-0"><?php echo e($message->recipient); ?></dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9 mb-0"><?php echo e($statusLabel); ?></dd>
            <dt class="col-sm-3">Parts / Attempts</dt><dd class="col-sm-9 mb-0"><?php echo e($message->segments); ?> / <?php echo e($message->attempts); ?></dd>
            <dt class="col-sm-3">Charge</dt><dd class="col-sm-9 mb-0">BDT <?php echo e(moneyDecimal($message->charge)); ?></dd>
        </dl>
        <p class="text-break border rounded p-3 mb-2"><?php echo e($message->message); ?></p>
        <?php if($message->error): ?><p class="small text-danger text-break mb-2"><?php echo e($message->error); ?></p><?php endif; ?>
        <p class="small text-body-secondary mb-0">Submitted means accepted by the gateway, not confirmed delivery.</p>
        <?php if($message->status === 'uncertain'): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('resolveMessage', $message)): ?>
                <form class="d-flex flex-wrap gap-2 border-top mt-3 pt-3" method="POST" action="<?php echo e(route('core.sms-history.resolve', $message)); ?>" data-confirm="Have you checked this result with the SMS provider?">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-success" type="submit" name="decision" value="submitted">Provider accepted</button>
                    <button class="btn btn-danger" type="submit" name="decision" value="rejected">Provider rejected — refund</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\sms-history\show.blade.php ENDPATH**/ ?>