<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Manage patients registered at the selected branch.',
        'create' => ['model' => resourceModel($routePrefix.'.create'), 'url' => route($routePrefix.'.create'), 'label' => 'New patient'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-hospital-user text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Patients</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter patients">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-xl-7">
                    <label class="form-label small" for="search">Search patients</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80" placeholder="Patient ID (any part), name or mobile..." <?php echo e(inputAttrs('search')); ?>>
                    </div>
                    <?php $__errorArgs = ['search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="search-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12 col-xl-5">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>

        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No patients found', 'description' => 'Try another patient ID, name, or mobile number, or clear the filters.', 'icon' => 'hospital-user'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Patients registered at the selected branch</caption>
                    <thead>
                        <tr>
                            <th scope="col">Patient ID</th>
                            <th scope="col">Patient name</th>
                            <th scope="col">Age</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Blood group</th>
                            <th scope="col">Mobile no.</th>
                            <th scope="col">Address</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-nowrap"><?php echo e($patient->patient_id); ?></td>
                                <th class="fw-semibold text-break" scope="row">
                                    <?php echo e($patient->name); ?>

                                    <?php if($patient->father_husband_name): ?>
                                        <div class="small fw-normal text-body-secondary"><?php echo e($patient->father_husband_name); ?></div>
                                    <?php endif; ?>
                                </th>
                                <td class="text-nowrap"><?php echo e($patient->ageLabel()); ?></td>
                                <td><?php echo e($genders[$patient->gender] ?? '-'); ?></td>
                                <td><?php echo e($bloodGroups[$patient->blood_group] ?? '-'); ?></td>
                                <td class="text-nowrap"><?php echo e($patient->mobile_no); ?></td>
                                <td class="small text-break">
                                    <?php if($patient->address): ?>
                                        <div><?php echo e($patient->address); ?></div>
                                    <?php endif; ?>
                                    <div class="text-body-secondary"><?php echo e($patient->thana?->name); ?><?php if($patient->thana && $patient->district): ?>, <?php endif; ?><?php echo e($patient->district?->name); ?><?php if($patient->district && $patient->division): ?>, <?php endif; ?><?php echo e($patient->division?->name); ?></div>
                                </td>
                                <td><?php echo $__env->make('core::partials.row-actions', ['model' => $patient, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Hospital\resources/views/patients/index.blade.php ENDPATH**/ ?>