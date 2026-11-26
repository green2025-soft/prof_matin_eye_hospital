<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="row g-4 align-items-start">
        <?php if($form !== null): ?>
            <aside class="<?php echo e($records !== null ? 'col-xl-4 col-lg-5' : 'col-12'); ?>">
                <?php echo $__env->make('prescription::options.form', $form, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </aside>
        <?php endif; ?>

        <?php if($records !== null): ?>
            <div class="<?php echo e($form !== null ? 'col-xl-8 col-lg-7' : 'col-12'); ?>">
                <section class="app-card overflow-hidden">
                    <form class="p-3 p-md-4 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET"
                        aria-label="Filter prescription options">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-lg-4">
                                <label class="form-label small" for="filter-section">Type</label>
                                <select class="<?php echo e(inputClass('section', 'form-select')); ?>" id="filter-section"
                                    name="section" <?php echo e(inputAttrs('section', 'filter-section')); ?>>
                                    <option value="">All Type</option>
                                    <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>"
                                            <?php if(($filters['section'] ?? '') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block" id="filter-section-error"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-12 col-lg-4">
                                <label class="form-label small" for="search">Search</label>
                                <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search"
                                    value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80"
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
                            <div class="col-12 col-lg-4">
                                <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table app-table align-middle mb-0">
                            <caption class="visually-hidden">Prescription options by section and display order</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Content Type</th>
                                    <th scope="col">Ordering</th>
                                    <th class="text-end" scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <th class="fw-semibold text-break" scope="row"><?php echo e($option->name); ?></th>
                                        <td><?php echo e($sections[$option->section]); ?></td>
                                        <td class="small text-body-secondary"><?php echo e($option->ordering); ?></td>
                                        <td>
                                            <?php echo $__env->make('core::partials.row-actions', [
                                                'model' => $option,
                                                'routePrefix' => $routePrefix,
                                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td class="text-center text-body-secondary py-4" colspan="4">
                                            No prescription options match these filters.
                                        </td>
                                    </tr>
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

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\options\index.blade.php ENDPATH**/ ?>