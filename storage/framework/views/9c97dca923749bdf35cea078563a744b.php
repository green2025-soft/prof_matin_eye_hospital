
<?php $__env->startSection('title', $pageTitle); ?>
<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" method="GET" action="<?php echo e(route('pharmacy.customers.index')); ?>">
            <input name="per_page" type="hidden" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
            <div class="row g-2 align-items-end">
                <div class="col-sm-8 col-lg-5">
                    <label class="form-label" for="customer_search">Patient ID, name or mobile</label>
                    <input class="form-control" id="customer_search" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" maxlength="80">
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-primary" type="submit">
                        Search
                    </button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Patient ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($record['patient_id']); ?></td>
                            <td><?php echo e($record['name']); ?></td>
                            <td><?php echo e($record['mobile_no']); ?></td>
                            <td><?php echo e(ucfirst($record['type'])); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center text-body-secondary py-4" colspan="4">No customers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => 'pharmacy.customers'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\customers\index.blade.php ENDPATH**/ ?>