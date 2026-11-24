<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => 'Voucher',
        'create' => [
            'model' => resourceModel($routePrefix.'.create'),
            'url' => route($routePrefix.'.create'),
            'label' => 'New Voucher',
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label" for="search">Voucher number / Reference</label>
                    <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" maxlength="80"
                        value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" <?php echo e(inputAttrs('search')); ?>>
                    <?php $__errorArgs = ['search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="search-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4"><?php echo $__env->make('core::partials.filter-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Voucher no.</th>
                        <th scope="col">Date</th>
                        <th scope="col">Type / Reference</th>
                        <th scope="col">Status</th>
                        <th class="text-end" scope="col">Amount (BDT)</th>
                        <th class="text-end" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <th scope="row"><a href="<?php echo e(route($routePrefix.'.show', $entry)); ?>"><?php echo e($entry->number); ?></a></th>
                            <td class="text-nowrap"><?php echo e($entry->entry_date->format('d M Y')); ?></td>
                            <td><?php echo e($entry->voucherType->name); ?><div class="small text-body-secondary"><?php echo e($entry->reference); ?></div></td>
                            <td>
                                <?php if($entry->voided_at): ?>
                                    <span class="badge text-bg-secondary">Voided</span>
                                <?php elseif($entry->isAutomatic()): ?>
                                    <span class="badge text-bg-info">Automatic</span>
                                <?php else: ?>
                                    <span class="badge text-bg-success">Manual</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal($entry->total)); ?></td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a class="btn btn-outline-primary btn-sm app-action-button"
                                        href="<?php echo e(route($routePrefix.'.show', $entry)); ?>" aria-label="View <?php echo e($entry->number); ?>" title="View voucher">
                                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                    </a>
                                    <?php echo $__env->make('core::partials.row-actions', ['model' => $entry], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-center text-body-secondary py-4" colspan="6">No vouchers found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\journal-entries\index.blade.php ENDPATH**/ ?>