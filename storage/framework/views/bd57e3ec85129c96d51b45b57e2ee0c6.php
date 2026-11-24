<div class="d-flex gap-2">
    <?php $__currentLoopData = menuParameters(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <input name="<?php echo e($key); ?>" type="hidden" value="<?php echo e($value); ?>">
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <input name="per_page" type="hidden" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter me-2" aria-hidden="true"></i>Apply</button>
    <a class="btn btn-outline-secondary" href="<?php echo e(route($routePrefix.'.index', ($routeParameters ?? []) + ['per_page' => $filters['per_page'] ?? config('pagination.default')])); ?>" title="Clear filters"><i class="fa-solid fa-rotate-left me-2" aria-hidden="true"></i>Reset</a>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\app\Providers/../../resources/views/partials/filter-actions.blade.php ENDPATH**/ ?>