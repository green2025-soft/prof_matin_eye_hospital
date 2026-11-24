<div class="app-form-actions d-flex flex-wrap justify-content-end gap-2 border-top pt-3 mt-4">
    <?php if($listAllowed ?? auth()->user()->can('viewAny', is_string($model) ? $model : get_class($model))): ?>
        <a class="btn btn-danger" href="<?php echo e(route($routePrefix.'.index', $routeParameters ?? [])); ?>">Cancel</a>
    <?php else: ?>
        <a class="btn btn-outline-secondary" href="<?php echo e(route('home')); ?>">Dashboard</a>
    <?php endif; ?>
    <?php if($canSubmit ?? true): ?>
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check me-2" aria-hidden="true"></i><?php echo e($submitLabel); ?></button>
    <?php endif; ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\app\Providers/../../resources/views/partials/form-actions.blade.php ENDPATH**/ ?>