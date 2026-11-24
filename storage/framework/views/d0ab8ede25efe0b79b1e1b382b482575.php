<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Review refunds at the selected branch.',
        'create' => ['model' => resourceModel($routePrefix.'.create'), 'url' => route($routePrefix.'.create'), 'label' => 'New refund'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-rotate-left text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Refunds</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter refunds">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label small" for="search">Search refunds</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80" placeholder="Refund ID or remarks starts with..." <?php echo e(inputAttrs('search')); ?>>
                    </div>
                    <?php $__errorArgs = ['search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="search-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>

        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No refunds found', 'description' => 'Create a refund or clear the filters.', 'icon' => 'rotate-left'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Refunds with source invoices, patients, and refund causes</caption>
                    <thead>
                        <tr>
                            <th scope="col">Refund</th>
                            <th scope="col">Service / Invoice</th>
                            <th scope="col">Patient</th>
                            <th class="text-end" scope="col">Cash refund</th>
                            <th class="text-end" scope="col">Due adjustment</th>
                            <th scope="col">Refund cause</th>
                            <th scope="col">Remarks</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php ($source = $refund->consultation ?? $refund->investigation ?? $refund->operation); ?>
                            <tr>
                                <th class="small fw-normal text-nowrap" scope="row">
                                    <div class="fw-semibold"><?php echo e($refund->invoice_no ?: '#'.$refund->id); ?></div>
                                    <div class="text-body-secondary"><?php echo e($refund->created_at?->format('d M Y, h:i A')); ?></div>
                                </th>
                                <td class="small text-break">
                                    <div class="fw-semibold"><?php echo e(filled($source?->invoice_no) ? $source->invoice_no : '#'.$source?->getKey()); ?></div>
                                    <div class="text-body-secondary"><?php echo e(ucfirst($refund->serviceType())); ?></div>
                                </td>
                                <td class="small text-break">
                                    <div class="fw-semibold"><?php echo e($refund->patient?->patient_id ?? '-'); ?></div>
                                    <div><?php echo e($refund->patient?->name ?? '-'); ?></div>
                                    <div class="text-body-secondary"><?php echo e($refund->patient?->mobile_no); ?></div>
                                </td>
                                <td class="text-end text-nowrap"><?php echo e($refund->amount); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($refund->due_adjustment); ?></td>
                                <td class="small text-break"><?php echo e($refund->cause?->name ?? '-'); ?></td>
                                <td class="small text-break"><?php echo e($refund->remarks ?? '-'); ?></td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2 text-nowrap">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $refund)): ?>
                                            <a class="btn btn-outline-primary btn-sm app-action-button" href="<?php echo e(route($routePrefix.'.show', $refund)); ?>" aria-label="Print refund #<?php echo e($refund->id); ?>" title="Print refund"><i class="fa-solid fa-print" aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $refund)): ?>
                                            <a class="btn btn-outline-secondary btn-sm app-action-button" href="<?php echo e(route($routePrefix.'.edit', $refund)); ?>" aria-label="Edit refund #<?php echo e($refund->id); ?>" title="Edit refund"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $refund)): ?>
                                            <form class="d-inline-flex" action="<?php echo e(route($routePrefix.'.destroy', $refund)); ?>" method="POST" data-confirm="Delete this refund record? This cannot be undone.">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete refund #<?php echo e($refund->id); ?>" title="Delete refund"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\refunds\index.blade.php ENDPATH**/ ?>