<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => '',
        'create' => ['model' => resourceModel($routePrefix.'.create'), 'url' => route($routePrefix.'.create'), 'label' => 'New Due Collection'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">

        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter due collections">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label small" for="search">Search Due Collection</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80" placeholder="Receipt number, date or remarks starts with..." <?php echo e(inputAttrs('search')); ?>>
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
            <?php echo $__env->make('core::partials.empty', ['title' => 'No due collections found', 'description' => 'Record a due payment or clear the filters.', 'icon' => 'money-bill-wave'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Due collection receipts with source invoices, patients, and payment types</caption>
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Receipt</th>
                            <th scope="col">Collection date</th>
                            <th scope="col">Service / Invoice</th>
                            <th scope="col">Patient</th>
                            <th class="text-end" scope="col">Amount</th>
                            <th scope="col">Payment type</th>
                            <th scope="col">Remarks</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php ($source = $collection->consultation ?? $collection->investigation ?? $collection->operation); ?>
                            <tr>
                                <td><?php echo e($collection->created_at?->format('d M Y, h:i A')); ?></td>
                                <th >
                                    <?php echo e($collection->invoice_no ?? '-'); ?>


                                </th>
                                <td class="small text-nowrap"><?php echo e($collection->collection_date?->format('d M Y')); ?></td>
                                <td class="small text-break">
                                    <div class="fw-semibold"><?php echo e(filled($source?->invoice_no) ? $source->invoice_no : '—'); ?></div>
                                    <div class="text-body-secondary"><?php echo e(ucfirst($collection->serviceType())); ?></div>
                                </td>
                                <td >
                                     <div ><?php echo e($collection->patient?->patient_id); ?></div>
                                    <div ><?php echo e($collection->patient?->name); ?></div>


                                </td>
                                <td class="text-end text-nowrap"><?php echo e($collection->amount); ?></td>
                                <td class="small text-break"><?php echo e($collection->paymentType?->name ?? '-'); ?></td>
                                <td class="small text-break"><?php echo e($collection->remarks ?? '-'); ?></td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2 text-nowrap">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $collection)): ?>
                                            <a class="btn btn-outline-primary btn-sm app-action-button" href="<?php echo e(route($routePrefix.'.show', $collection)); ?>" aria-label="Print due collection <?php echo e($collection->invoice_no); ?>" title="Print receipt"><i class="fa-solid fa-print" aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $collection)): ?>
                                            <a class="btn btn-outline-secondary btn-sm app-action-button" href="<?php echo e(route($routePrefix.'.edit', $collection)); ?>" aria-label="Edit due collection <?php echo e($collection->invoice_no); ?>" title="Edit due collection"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $collection)): ?>
                                            <form class="d-inline-flex" action="<?php echo e(route($routePrefix.'.destroy', $collection)); ?>" method="POST" data-confirm="Delete this due collection? This cannot be undone.">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete due collection <?php echo e($collection->invoice_no); ?>" title="Delete due collection"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
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

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\due-collections\index.blade.php ENDPATH**/ ?>