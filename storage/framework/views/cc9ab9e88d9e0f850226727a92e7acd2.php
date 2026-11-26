<?php
    $permissions = $moduleMenu['permissions'];
    $transactionsOpen = request()->routeIs('hospital.appointments.*', 'hospital.consultations.*', 'hospital.patient-investigations.*', 'hospital.delivery-investigations.*', 'hospital.patient-operations.*', 'hospital.commissions.*', 'hospital.refunds.*', 'hospital.due-collections.*');
    $peopleOpen = request()->routeIs('hospital.patients.*', 'hospital.references.*', 'hospital.doctors.*');
    $setupOpen = request()->routeIs('hospital.departments.*', 'hospital.investigation-categories.*', 'hospital.investigations.*', 'hospital.operation-categories.*', 'hospital.operations.*', 'hospital.refund-causes.*');
?>


<?php if(($permissions['appointments.view'] ?? false)
    || ($permissions['consultations.view'] ?? false)
    || ($permissions['patient-investigations.view'] ?? false)
    || ($permissions['delivery-investigations.view'] ?? false)
    || ($permissions['patient-operations.view'] ?? false)
    || ($permissions['commissions.view'] ?? false)
    || ($permissions['refunds.view'] ?? false)
    || ($permissions['due-collections.view'] ?? false)): ?>
    <details class="app-nav-group" <?php if($transactionsOpen): ?> open <?php endif; ?>>
        <summary class="app-nav-link app-nav-group-toggle<?php echo e($transactionsOpen ? ' active' : ''); ?>" title="Transactions" aria-label="Transactions">
            <span class="app-nav-icon"><i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Billing</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            <?php if($permissions['appointments.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Appointment', 'icon' => 'calendar-check',
                    'url' => route('hospital.appointments.index'),
                    'active' => request()->routeIs('hospital.appointments.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['consultations.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Consultation', 'icon' => 'notes-medical',
                    'url' => route('hospital.consultations.index'),
                    'active' => request()->routeIs('hospital.consultations.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['patient-investigations.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Test Invoice',
                    'icon' => 'eye',
                    'url' => route('hospital.patient-investigations.index'),
                    'active' => request()->routeIs('hospital.patient-investigations.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

            <?php if($permissions['patient-operations.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Operation Bill', 'icon' => 'stethoscope',
                    'url' => route('hospital.patient-operations.index'),
                    'active' => request()->routeIs('hospital.patient-operations.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['delivery-investigations.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Report Delivery', 'icon' => 'file-circle-check',
                    'url' => route('hospital.delivery-investigations.index'),
                    'active' => request()->routeIs('hospital.delivery-investigations.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

            <?php if($permissions['due-collections.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Due Collection', 'icon' => 'hand-holding-dollar',
                    'url' => route('hospital.due-collections.index'),
                    'active' => request()->routeIs('hospital.due-collections.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['refunds.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Refund', 'icon' => 'money-bill-transfer',
                    'url' => route('hospital.refunds.index'),
                    'active' => request()->routeIs('hospital.refunds.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
             <?php if($permissions['commissions.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'PC Commission', 'icon' => 'percent',
                    'url' => route('hospital.commissions.index'),
                    'active' => request()->routeIs('hospital.commissions.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>
    </details>
<?php endif; ?>

<?php if($permissions['hospital-patient-due.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', ['label' => 'Patient Due List', 'icon' => 'file-invoice-dollar', 'url' => route('hospital.reports.patient-due'), 'active' => request()->routeIs('hospital.reports.patient-due')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<?php if(($permissions['patients.view'] ?? false)
    || ($permissions['references.view'] ?? false)
    || ($permissions['doctors.view'] ?? false)
    || ($permissions['doctor-permissions.view'] ?? false)): ?>
    <details class="app-nav-group" <?php if($peopleOpen): ?> open <?php endif; ?>>
        <summary class="app-nav-link app-nav-group-toggle<?php echo e($peopleOpen ? ' active' : ''); ?>" title="People" aria-label="People">
            <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Resource</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            <?php if($permissions['patients.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Patient', 'icon' => 'hospital-user',
                    'url' => route('hospital.patients.index'),
                    'active' => request()->routeIs('hospital.patients.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

            <?php if($permissions['doctors.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Doctor',
                    'icon' => 'user-doctor',
                    'url' => route('hospital.doctors.index'),
                    'active' => request()->routeIs('hospital.doctors.*') && ! request()->routeIs('hospital.doctors.permissions*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['doctor-permissions.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Dr. Permission', 'icon' => 'key',
                    'url' => route('hospital.doctors.permissions'),
                    'active' => request()->routeIs('hospital.doctors.permissions', 'hospital.doctors.permissions-update'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['references.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Reference',
                    'icon' => 'user-group',
                    'url' => route('hospital.references.index'),
                    'active' => request()->routeIs('hospital.references.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>
    </details>
<?php endif; ?>
<?php if(($permissions['departments.view'] ?? false)
    || ($permissions['investigation-categories.view'] ?? false)
    || ($permissions['investigations.view'] ?? false)
    || ($permissions['operation-categories.view'] ?? false)
    || ($permissions['operations.view'] ?? false)
    || ($permissions['refund-causes.view'] ?? false)): ?>
    <details class="app-nav-group" <?php if($setupOpen): ?> open <?php endif; ?>>
        <summary class="app-nav-link app-nav-group-toggle<?php echo e($setupOpen ? ' active' : ''); ?>" title="Setup" aria-label="Setup">
            <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Setup</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            <?php if($permissions['departments.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Create Department',
                    'icon' => 'user-doctor',
                    'url' => route('hospital.departments.index'),
                    'active' => request()->routeIs('hospital.departments.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['investigation-categories.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Test Category', 'icon' => 'flask',
                    'url' => route('hospital.investigation-categories.index'),
                    'active' => request()->routeIs('hospital.investigation-categories.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['investigations.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Test Name',
                    'icon' => 'eye',
                    'url' => route('hospital.investigations.index'),
                    'active' => request()->routeIs('hospital.investigations.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['operation-categories.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Operation Category', 'icon' => 'tags',
                    'url' => route('hospital.operation-categories.index'),
                    'active' => request()->routeIs('hospital.operation-categories.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['operations.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Operation Name', 'icon' => 'stethoscope',
                    'url' => route('hospital.operations.index'),
                    'active' => request()->routeIs('hospital.operations.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($permissions['refund-causes.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Refund Causes', 'icon' => 'rotate-left',
                    'url' => route('hospital.refund-causes.index'),
                    'active' => request()->routeIs('hospital.refund-causes.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>
    </details>
<?php endif; ?>
<?php if($permissions['settings.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Settings', 'icon' => 'gear',
        'url' => route('hospital.settings.index'),
        'active' => request()->routeIs('hospital.settings.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\partials\menu.blade.php ENDPATH**/ ?>