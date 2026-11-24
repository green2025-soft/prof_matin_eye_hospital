<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <nav class="d-flex flex-wrap gap-2 mb-4" aria-label="Module navigation">
        <a class="btn btn-outline-secondary btn-sm" href="<?php echo e($moduleDashboardUrl); ?>"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>All modules</a>
        <a class="btn btn-outline-secondary btn-sm" href="<?php echo e($workspaceDashboardUrl); ?>"><i class="fa-solid fa-table-cells-large me-2" aria-hidden="true"></i>Workspace overview</a>
        <?php if($canSelectBranch): ?>
            <a class="btn btn-outline-secondary btn-sm" href="<?php echo e($branchDashboardUrl); ?>"><i class="fa-solid fa-location-dot me-2" aria-hidden="true"></i>Choose branch</a>
        <?php endif; ?>
    </nav>

    <section class="app-card p-3 p-md-4" aria-labelledby="module-title">
        <div class="d-flex align-items-start gap-3">
            <span class="app-selection-mark d-inline-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden">
                <i class="fa-solid fa-<?php echo e($module->get('tenant_dashboard')['icon']); ?>" aria-hidden="true"></i>
            </span>
            <div class="overflow-hidden">
                <h1 class="h4 fw-semibold mb-2 text-break" id="module-title"><?php echo e($pageTitle); ?></h1>
                <p class="text-body-secondary mb-0"><?php echo e($module->getDescription()); ?></p>
            </div>
        </div>
        <?php if($canSelectBranch): ?>
            <div class="border-top mt-4 pt-4">
                <h2 class="h6">Working in this module</h2>
                <p class="small text-body-secondary mb-0">Choose a branch when opening a branch-based feature.</p>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\module-dashboard\show.blade.php ENDPATH**/ ?>