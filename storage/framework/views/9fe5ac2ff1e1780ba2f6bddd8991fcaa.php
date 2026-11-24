<?php ($paginator->appends(menuParameters())); ?>
<div class="app-pagination d-flex flex-wrap align-items-center justify-content-between gap-3 border-top px-3 px-md-4 py-3">
    <form class="d-flex flex-wrap align-items-center gap-2" action="<?php echo e($paginator->path()); ?>" method="GET">
        <?php $__currentLoopData = \Illuminate\Support\Arr::except($filters + menuParameters(), ['page', 'per_page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_scalar($value) && $value !== ''): ?>
                <input name="<?php echo e($key); ?>" type="hidden" value="<?php echo e($value); ?>">
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <label class="visually-hidden" for="page-size">Entries per page</label>
        <select class="form-select form-select-sm w-auto" id="page-size" name="per_page" data-page-size>
            <?php $__currentLoopData = config('pagination.options'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($size); ?>" <?php echo e(getSelected($size, 'per_page', null, $filters['per_page'] ?? config('pagination.default'))); ?>><?php echo e($size === 'all' ? 'All' : $size); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <noscript><button class="btn btn-outline-secondary btn-sm" type="submit">Apply</button></noscript>
        <?php if($paginator->total() > 0): ?>
            <p class="small text-body-secondary mb-0">Showing <span class="fw-semibold"><?php echo e($paginator->firstItem() ?? 0); ?></span> to <span class="fw-semibold"><?php echo e($paginator->lastItem() ?? 0); ?></span> of <span class="fw-semibold"><?php echo e(number_format($paginator->total())); ?></span> entries</p>
        <?php endif; ?>
    </form>
    <?php echo $__env->make('core::partials.page-links', ['paginator' => $paginator], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\partials\pagination.blade.php ENDPATH**/ ?>