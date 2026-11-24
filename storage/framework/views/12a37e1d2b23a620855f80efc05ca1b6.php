

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage investigation category names and display order.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="row g-4 align-items-start">
        <?php if($form !== null): ?>
            <aside class="<?php echo e($records !== null ? 'col-xl-4 col-lg-5' : 'col-12'); ?>">
                <?php echo $__env->make('hospital::investigation-categories.form', $form, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </aside>
        <?php endif; ?>

        <?php if($records !== null): ?>
            <div class="<?php echo e($form !== null ? 'col-xl-8 col-lg-7' : 'col-12'); ?>">
                <section class="app-card overflow-hidden">
                    <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
                        <i class="fa-solid fa-flask text-body-secondary" aria-hidden="true"></i>
                        <h2 class="h6 mb-0">Investigation categories</h2>
                    </div>
                    <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter investigation categories">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-xl-7">
                                <label class="form-label small" for="search">Search categories</label>
                                <div class="app-input-wrap app-search-field">
                                    <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                                    <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80" placeholder="Category name starts with..." <?php echo e(inputAttrs('search')); ?>>
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
                        <?php echo $__env->make('core::partials.empty', ['title' => 'No investigation categories found', 'description' => 'Try another category name or clear the filters.', 'icon' => 'flask'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table app-table align-middle mb-0">
                                <caption class="visually-hidden">Investigation categories and their display order</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Ordering</th>
                                        <th class="text-end" scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <th class="fw-semibold text-break" scope="row"><?php echo e($category->name); ?></th>
                                            <td class="small text-body-secondary"><?php echo e($category->ordering); ?></td>
                                            <td><?php echo $__env->make('core::partials.row-actions', ['model' => $category, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
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

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\investigation-categories\index.blade.php ENDPATH**/ ?>