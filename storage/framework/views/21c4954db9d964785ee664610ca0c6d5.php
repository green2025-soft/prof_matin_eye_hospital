<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Manage references registered at the selected branch.',
        'create' => ['model' => resourceModel($routePrefix.'.create'), 'url' => route($routePrefix.'.create'), 'label' => 'New reference'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-address-card text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">References</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter references">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-xl-7">
                    <label class="form-label small" for="search">Search references</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80" placeholder="Reference ID (any part), name or contact..." <?php echo e(inputAttrs('search')); ?>>
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
                <div class="col-12 col-xl-5">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>

        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No references found', 'description' => 'Try another reference ID, name, or contact number, or clear the filters.', 'icon' => 'address-card'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">References registered at the selected branch</caption>
                    <thead>
                        <tr>
                            <th scope="col">Reference ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Designation</th>
                            <th scope="col">Contact no.</th>
                            <th scope="col">Address</th>
                            <th class="text-end" scope="col">Consultation %</th>
                            <th class="text-end" scope="col">Investigation %</th>
                            <th class="text-end" scope="col">Operation %</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reference): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-nowrap"><?php echo e($reference->reference_id); ?></td>
                                <th class="fw-semibold text-break" scope="row"><?php echo e($reference->name); ?></th>
                                <td class="small text-break"><?php echo e($reference->designation ?: '-'); ?></td>
                                <td class="text-nowrap"><?php echo e($reference->contact_no); ?></td>
                                <td class="small text-break"><?php echo e($reference->address ?: '-'); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($reference->consultation_percent); ?>%</td>
                                <td class="text-end text-nowrap"><?php echo e($reference->investigation_percent); ?>%</td>
                                <td class="text-end text-nowrap"><?php echo e($reference->operation_percent); ?>%</td>
                                <td><?php echo $__env->make('core::partials.row-actions', ['model' => $reference, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\references\index.blade.php ENDPATH**/ ?>