<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => 'Payment type ledgers'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="row g-3 align-items-start">
        <?php if($form !== null): ?>
            <aside class="<?php echo e($records !== null ? 'col-lg-4' : 'col-12'); ?>"><?php echo $__env->make('accounting::payment-accounts.form', $form, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></aside>
        <?php endif; ?>
        <?php if($records !== null): ?>
            <div class="<?php echo e($form !== null ? 'col-lg-8' : 'col-12'); ?>">
                <section class="app-card overflow-hidden">
                    <div class="table-responsive">
                        <table class="table app-table align-middle mb-0">
                            <thead><tr><th>Payment type</th><th>Ledger</th><th class="text-end">Actions</th></tr></thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapping): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr><th scope="row"><?php echo e($mapping->paymentType->name); ?></th><td><?php echo e($mapping->accountHead->code); ?> · <?php echo e($mapping->accountHead->name); ?></td><td><?php echo $__env->make('core::partials.row-actions', ['model' => $mapping, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td></tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td class="text-center text-body-secondary py-4" colspan="3">No payment ledgers configured.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </section>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\payment-accounts\index.blade.php ENDPATH**/ ?>