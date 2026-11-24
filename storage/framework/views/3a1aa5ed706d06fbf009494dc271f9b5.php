

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Immutable receipts for manual and verified online payments.',
        'create' => [
            'model' => resourceModel('core.payments.create'),
            'url' => route('core.payments.create'),
            'label' => 'Record payment',
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="<?php echo e(route('core.payments.index')); ?>" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label" for="search">Provider reference</label>
                    <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search"
                        value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" placeholder="Reference starts with..."
                        <?php echo e(inputAttrs('search')); ?>>
                    <?php $__errorArgs = ['search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="search-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => 'core.payments'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>

        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', [
                'title' => 'No payments found',
                'description' => 'Recorded payments will appear here.',
                'icon' => 'money-bill-transfer',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Receipt</th>
                            <th scope="col">Invoice</th>
                            <th scope="col">Workspace</th>
                            <th scope="col">Provider</th>
                            <th scope="col">Reference</th>
                            <th class="text-end" scope="col">Invoice amount</th>
                            <th class="text-end" scope="col">Extra charge</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row">
                                    <a href="<?php echo e(route('core.payments.show', $payment)); ?>">#<?php echo e($payment->id); ?></a>
                                    <div class="small fw-normal text-body-secondary">
                                        <?php echo e($payment->paid_at->format('d M Y')); ?>

                                    </div>
                                </th>
                                <td><?php echo e($payment->invoice->number); ?></td>
                                <td><?php echo e($payment->tenant->name); ?></td>
                                <td><?php echo e($payment->provider); ?></td>
                                <td class="text-break"><?php echo e($payment->provider_reference ?? '-'); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($payment->currency); ?> <?php echo e($payment->amount); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($payment->currency); ?> <?php echo e($payment->extra_charge); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\payments\index.blade.php ENDPATH**/ ?>