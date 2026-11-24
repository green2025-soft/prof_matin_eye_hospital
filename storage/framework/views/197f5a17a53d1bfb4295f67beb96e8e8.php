<header class="app-report-letterhead d-flex flex-column flex-sm-row align-items-center justify-content-center gap-2 gap-sm-3 text-center">
    <?php if(isset($reportBrand['logo_url'])): ?>
        <img class="app-report-logo flex-shrink-0 object-fit-contain" src="<?php echo e($reportBrand['logo_url']); ?>" width="76" height="76" alt="">
    <?php endif; ?>
    <div class="app-report-brand">
        <h2><?php echo e($reportBrand['name']); ?></h2>
        <p><?php echo e($reportBrand['tagline']); ?></p>
        <p><?php echo e($reportBrand['address']); ?></p>
        <p><?php echo e($reportBrand['contact']); ?></p>
        <?php if(filled($reportBrand['branch_name'])): ?>
            <p class="fw-semibold"><?php echo e($reportBrand['branch_name']); ?></p>
        <?php endif; ?>
    </div>
</header>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/TenantCore\resources/views/partials/report-letterhead.blade.php ENDPATH**/ ?>