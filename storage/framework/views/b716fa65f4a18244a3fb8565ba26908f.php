<div class="app-page-heading d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 fw-semibold mb-1"><?php echo e($title); ?></h1>
        <?php if(($description ?? '') !== ''): ?>
            <p class="small text-body-secondary mb-0"><?php echo e($description); ?></p>
        <?php endif; ?>
    </div>
    <?php if(! empty($create)): ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', $create['model'])): ?>
            <a class="btn btn-primary btn-sm" href="<?php echo e($create['url']); ?>"><i class="fa-solid fa-plus me-2" aria-hidden="true"></i><?php echo e($create['label']); ?></a>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\partials\heading.blade.php ENDPATH**/ ?>