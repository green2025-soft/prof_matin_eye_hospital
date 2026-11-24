<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage district names and their divisions.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="row g-4 align-items-start">
        <?php if($form !== null): ?>
            <aside class="<?php echo e($records !== null ? 'col-xl-4 col-lg-5' : 'col-12'); ?>">
                <?php echo $__env->make('tenantcore::districts.form', $form, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </aside>
        <?php endif; ?>

        <?php if($records !== null): ?>
            <div class="<?php echo e($form !== null ? 'col-xl-8 col-lg-7' : 'col-12'); ?>">
                <section class="app-card overflow-hidden">
                    <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
                        <i class="fa-solid fa-map text-body-secondary" aria-hidden="true"></i>
                        <h2 class="h6 mb-0">Districts</h2>
                    </div>
                    <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter districts">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-xl-7">
                                <label class="form-label small" for="search">Search districts</label>
                                <div class="app-input-wrap app-search-field">
                                    <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                                    <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80" placeholder="English or Bangla name starts with..." <?php echo e(inputAttrs('search')); ?>>
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
                        <?php echo $__env->make('core::partials.empty', ['title' => 'No districts found', 'description' => 'Try another district name or clear the filters.', 'icon' => 'map'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table app-table align-middle mb-0">
                                <caption class="visually-hidden">Districts and their divisions</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Name (English)</th>
                                        <th scope="col">Name (Bangla)</th>
                                        <th scope="col">Division</th>
                                        <th class="text-end" scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <th class="fw-semibold text-break" scope="row"><?php echo e($district->name); ?></th>
                                            <td class="text-break" lang="bn"><?php echo e($district->bn_name); ?></td>
                                            <td class="text-break"><?php echo e($district->division?->name); ?></td>
                                            <td><?php echo $__env->make('core::partials.row-actions', ['model' => $district, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </section>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\districts\index.blade.php ENDPATH**/ ?>