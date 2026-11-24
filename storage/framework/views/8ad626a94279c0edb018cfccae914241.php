

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="app-card overflow-hidden">
        <form class="row g-2 align-items-end p-3 border-bottom" method="GET" action="<?php echo e(route('core.sms-history.index')); ?>">
            <div class="col-md-8">
                <label class="form-label" for="status">Status</label>
                <select class="form-select" id="status" name="status"><option value="">All statuses</option><?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'status', null, $filters['status'] ?? '')); ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
            </div>
            <div class="col-md-4"><?php echo $__env->make('core::partials.filter-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
        </form>
        <div class="table-responsive overflow-y-hidden">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Organization / Feature</th>
                        <th>Recipient</th>
                        <th>Status</th>
                        <th class="text-end">Parts</th>
                        <th class="text-end">Charge (BDT)</th>
                        <th>Date</th>
                        <th class="text-end">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <th scope="row"><?php echo e($message->tenant->name); ?><div class="small fw-normal text-body-secondary"><?php echo e($features[$message->feature] ?? $message->feature); ?></div></th>
                            <td><?php echo e($message->recipient); ?></td>
                            <td><?php echo e($statuses[$message->status]); ?></td>
                            <td class="text-end"><?php echo e($message->segments); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($message->charge)); ?></td>
                            <td class="small text-nowrap"><?php echo e($message->created_at->format('d M Y H:i')); ?></td>
                            <td class="text-end"><a class="btn btn-outline-primary btn-sm" href="<?php echo e(route('core.sms-history.show', $message)); ?>">Details</a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-body-secondary py-4">No SMS history.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\sms-history\index.blade.php ENDPATH**/ ?>