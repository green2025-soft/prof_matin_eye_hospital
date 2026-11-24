<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="row g-4 align-items-start">
        <?php if($form !== null): ?>
            <aside class="<?php echo e($records !== null ? 'col-lg-4' : 'col-12'); ?>">
                <?php echo $__env->make('pharmacy::units.form', $form, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </aside>
        <?php endif; ?>
        <?php if($records !== null): ?>
            <div class="<?php echo e($form !== null ? 'col-lg-8' : 'col-12'); ?>">
                <section class="app-card overflow-hidden">
                    <form class="p-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter units">
                        <div class="row g-2 align-items-end">
                            <div class="col-sm">
                                <label class="form-label small" for="search">Search</label>
                                <input class="form-control" id="search" name="search" type="search" value="<?php echo e($filters['search'] ?? ''); ?>" maxlength="80">
                            </div>
                            <div class="col-sm-auto">
                                <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table app-table align-middle mb-0">
                            <caption class="visually-hidden">Units</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Symbol</th>
                                    <th class="text-end" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <th scope="row"><?php echo e($record->name); ?></th>
                                        <td><?php echo e($record->symbol); ?></td>
                                        <td>
                                            <?php echo $__env->make('core::partials.row-actions', ['model' => $record, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td class="text-center text-body-secondary py-4" colspan="3">No records found.</td>
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

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\units\index.blade.php ENDPATH**/ ?>