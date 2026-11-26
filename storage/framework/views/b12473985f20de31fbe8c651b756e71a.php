<p class="app-nav-label">Prescription</p>
<?php if(($moduleMenu['permissions']['prescriptions.view'] ?? false) && $moduleMenu['settings']['general_enabled']): ?>
    <a class="app-nav-link<?php echo e(request()->routeIs('prescription.prescriptions.*') ? ' active' : ''); ?>" href="<?php echo e(route('prescription.prescriptions.index')); ?>">
        <span class="app-nav-icon"><i class="fa-solid fa-prescription" aria-hidden="true"></i></span>
        <span class="app-nav-copy">General Prescription</span>
    </a>
<?php endif; ?>
<?php if(($moduleMenu['permissions']['glass-prescriptions.view'] ?? false) && $moduleMenu['settings']['glass_enabled']): ?>
    <a class="app-nav-link<?php echo e(request()->routeIs('prescription.glass-prescriptions.*') ? ' active' : ''); ?>" href="<?php echo e(route('prescription.glass-prescriptions.index')); ?>">
        <span class="app-nav-icon"><i class="fa-solid fa-glasses" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Glass Prescription</span>
    </a>
<?php endif; ?>
<?php if($moduleMenu['permissions']['prescription-options.view'] ?? false): ?>
    <a class="app-nav-link<?php echo e(request()->routeIs('prescription.options.*') ? ' active' : ''); ?>" href="<?php echo e(route('prescription.options.index')); ?>">
        <span class="app-nav-icon"><i class="fa-solid fa-list-check" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Prescription Content</span>
    </a>
<?php endif; ?>
<?php if($moduleMenu['permissions']['prescription-dose.view'] ?? false): ?>
    <a class="app-nav-link<?php echo e(request()->routeIs('prescription.prescription-dose.*') ? ' active' : ''); ?>"
        href="<?php echo e(route('prescription.prescription-dose.index')); ?>">
        <span class="app-nav-icon"><i class="fa-solid fa-pills" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Create Dose</span>
    </a>
<?php endif; ?>


<?php echo $__env->make('pharmacy::partials.catalog-menu', [
    'catalogType'   => 'medicine',
    'catalogTitle'  => 'Medicine Catalog',
    'catalogLabel'  => 'Medicine/Product',
    'productLabel'  => 'Medicine/Product',
    'productIcon'   => 'pills',
    'categoryLabel' => 'Generic/Category',
    'shelfLabel'    => 'Shelf List',
    'catalogParameters' => ['menu' => 'prescription'],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php if($moduleMenu['permissions']['settings.view'] ?? false): ?>
    <a class="app-nav-link<?php echo e(request()->routeIs('prescription.settings.*') ? ' active' : ''); ?>" href="<?php echo e(route('prescription.settings.index')); ?>">
        <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Settings</span>
    </a>
<?php endif; ?>

<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\partials\menu.blade.php ENDPATH**/ ?>