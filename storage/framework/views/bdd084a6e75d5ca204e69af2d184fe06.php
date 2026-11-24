<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => 'Automatic posting mappings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-sm"><label class="form-label small" for="search">Feature name</label><input class="form-control" id="search" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" maxlength="80"></div>
                <div class="col-sm-4 col-lg-3">
                    <label class="form-label small" for="module">Module</label>
                    <select class="form-select" id="module" name="module">
                        <option value="">All modules</option>
                        <?php $__currentLoopData = $filterOptions['module']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($module); ?>" <?php if(($filters['module'] ?? '') === $module): echo 'selected'; endif; ?>><?php echo e(ucfirst($module)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-sm-auto"><?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <thead><tr><th>Feature</th><th>Module</th><th>Voucher type</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapping): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <th scope="row"><?php echo e($mapping->name); ?><div class="small fw-normal text-body-secondary"><?php echo e($mapping->key); ?></div></th>
                            <td><?php echo e(ucfirst($mapping->module)); ?></td><td><?php echo e($mapping->voucherType->name); ?></td>
                            <td><span class="badge <?php echo e($mapping->active ? 'text-bg-success' : 'text-bg-secondary'); ?>"><?php echo e($mapping->active ? 'Enabled' : 'Disabled'); ?></span></td>
                            <td><?php echo $__env->make('core::partials.row-actions', ['model' => $mapping, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-center text-body-secondary py-4" colspan="5">No posting mappings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\account-modules\index.blade.php ENDPATH**/ ?>