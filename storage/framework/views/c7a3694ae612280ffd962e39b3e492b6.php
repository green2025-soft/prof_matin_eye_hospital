

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <h1 class="h4 mb-0"><?php echo e($pageTitle); ?></h1>
        <?php if($account?->enabled): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('requestRecharge', $rechargeModel)): ?>
                <a class="btn btn-primary" href="<?php echo e(route('tenantcore.sms-recharges.create')); ?>">Request recharge</a>
            <?php endif; ?>
        <?php endif; ?>
    </header>
    <div class="row g-3 mb-3">
        <div class="col-md-4"><section class="app-card bg-primary-subtle p-3"><p class="small mb-1">Available balance</p><p class="h3 mb-0">BDT <?php echo e(moneyDecimal($account?->balance ?? 0)); ?></p></section></div>
        <div class="col-md-4"><section class="app-card bg-success-subtle p-3"><p class="small mb-1">Rate / SMS part</p><p class="h3 mb-0">BDT <?php echo e(moneyDecimal($account?->rate ?? 0)); ?></p></section></div>
        <div class="col-md-4"><section class="app-card bg-info-subtle p-3"><p class="small mb-1">Monthly part limit</p><p class="h3 mb-0"><?php echo e($account?->monthly_limit ?? 'Unlimited'); ?></p></section></div>
    </div>
    <?php if (! ($account?->enabled)): ?><div class="alert alert-secondary">SMS is not enabled. Contact the platform administrator.</div><?php endif; ?>
    <section class="app-card mb-3 overflow-hidden">
        <header class="d-flex justify-content-between align-items-center gap-2 p-3 border-bottom"><h2 class="h6 mb-0">Recent recharges</h2><a href="<?php echo e(route('tenantcore.sms-recharges.index')); ?>">View all</a></header>
        <div class="table-responsive overflow-y-hidden">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Status</th>
                        <th class="text-end">Amount (BDT)</th>
                        <th>Requested</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recharges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recharge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr><td><?php echo e($recharge->payment_reference); ?></td><td><?php echo e($rechargeStatuses[$recharge->status]); ?></td><td class="text-end"><?php echo e(moneyDecimal($recharge->amount)); ?></td><td class="small"><?php echo e($recharge->created_at->format('d M Y H:i')); ?></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-center text-body-secondary py-3" colspan="4">No recharge requests.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <section class="app-card overflow-hidden">
        <header class="p-3 border-bottom"><h2 class="h6 mb-0">SMS history</h2></header>
        <div class="table-responsive overflow-y-hidden">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Feature / Recipient</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th class="text-end">Parts / Attempts</th>
                        <th class="text-end">Charge (BDT)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($features[$message->feature] ?? $message->feature); ?><div class="small text-body-secondary"><?php echo e($message->recipient); ?></div></td>
                            <td class="small text-break">
                                <details>
                                    <summary class="text-nowrap"><?php echo e(str($message->message)->limit(60)); ?></summary>
                                    <p class="mt-2 mb-0"><?php echo e($message->message); ?></p>
                                </details>
                            </td>
                            <td>
                                <?php echo e($statuses[$message->status]); ?>

                                <?php if($message->error): ?><div class="small text-danger text-break mt-1"><?php echo e($message->error); ?></div><?php endif; ?>
                            </td>
                            <td class="text-end"><?php echo e($message->segments); ?> / <?php echo e($message->attempts); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($message->charge)); ?></td>
                            <td class="small text-nowrap"><?php echo e($message->created_at->format('d M Y H:i')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-center text-body-secondary py-4" colspan="6">No SMS history.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\sms\index.blade.php ENDPATH**/ ?>