<?php ($recordLabel = $actionLabel ?? $model->name ?? 'record'); ?>
<div class="d-flex justify-content-end gap-2 text-nowrap">
    <?php if(($rowPermissions['update'] ?? true) && auth()->user()->can('update', $model)): ?>
        <a class="btn btn-outline-secondary btn-sm app-action-button" href="<?php echo e(route($routePrefix.'.edit', [$model] + ($routeParameters ?? []))); ?>"
            aria-label="Edit <?php echo e($recordLabel); ?>" title="Edit <?php echo e($recordLabel); ?>"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
    <?php endif; ?>
    <?php if(\Illuminate\Support\Facades\Route::has($routePrefix.'.destroy')
        && ($rowPermissions['delete'] ?? true) && auth()->user()->can('delete', $model)): ?>
        <form class="d-inline-flex" action="<?php echo e(route($routePrefix.'.destroy', [$model] + ($routeParameters ?? []))); ?>" method="POST" data-confirm="<?php echo e($deleteConfirmation ?? 'Delete this record? This cannot be undone.'); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button class="btn btn-outline-danger btn-sm app-action-button" type="submit"
                aria-label="Delete <?php echo e($recordLabel); ?>" title="Delete <?php echo e($recordLabel); ?>"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
        </form>
    <?php endif; ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\partials\row-actions.blade.php ENDPATH**/ ?>