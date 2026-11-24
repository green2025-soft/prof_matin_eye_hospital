

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <a class="btn btn-outline-secondary btn-sm mb-3" href="<?php echo e(route('tenantcore.sms.index')); ?>">SMS wallet</a>
    <section class="app-card overflow-hidden">
        <div class="table-responsive overflow-y-hidden">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Reference / Method</th>
                        <th>Status</th>
                        <th class="text-end">Amount (BDT)</th>
                        <th>Review note</th>
                        <th>Date</th>
                        <th>Proof</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recharge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <th scope="row"><?php echo e($recharge->payment_reference); ?><div class="small text-body-secondary fw-normal"><?php echo e($recharge->payment_method); ?></div></th>
                            <td><?php echo e($statuses[$recharge->status]); ?></td><td class="text-end"><?php echo e(moneyDecimal($recharge->amount)); ?></td>
                            <td class="small text-break"><?php echo e($recharge->review_note ?? '-'); ?></td><td class="small text-nowrap"><?php echo e($recharge->created_at->format('d M Y H:i')); ?></td>
                            <td><?php if($recharge->proof_path): ?><a href="<?php echo e(route('tenantcore.sms-recharges.proof', $recharge)); ?>">Download</a><?php endif; ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-center text-body-secondary py-4" colspan="6">No recharge requests.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\sms-recharges\index.blade.php ENDPATH**/ ?>