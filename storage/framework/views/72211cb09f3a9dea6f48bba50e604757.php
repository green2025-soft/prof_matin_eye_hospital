<nav class="d-flex flex-wrap gap-2 mb-3" aria-label="Patient entries">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', resourceModel('hospital.consultations.create'))): ?>
        <a class="btn btn-sm <?php echo e(request()->routeIs('hospital.consultations.*') ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('hospital.consultations.create')); ?>" <?php if(request()->routeIs('hospital.consultations.*')): ?> aria-current="page" <?php endif; ?>>Consultation</a>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', resourceModel('hospital.patient-investigations.create'))): ?>
        <a class="btn btn-sm <?php echo e(request()->routeIs('hospital.patient-investigations.*') ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('hospital.patient-investigations.create')); ?>" <?php if(request()->routeIs('hospital.patient-investigations.*')): ?> aria-current="page" <?php endif; ?>>Investigation</a>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', resourceModel('hospital.patient-operations.create'))): ?>
        <a class="btn btn-sm <?php echo e(request()->routeIs('hospital.patient-operations.*') ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('hospital.patient-operations.create')); ?>" <?php if(request()->routeIs('hospital.patient-operations.*')): ?> aria-current="page" <?php endif; ?>>Operation</a>
    <?php endif; ?>
</nav>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\partials\clinical-tabs.blade.php ENDPATH**/ ?>