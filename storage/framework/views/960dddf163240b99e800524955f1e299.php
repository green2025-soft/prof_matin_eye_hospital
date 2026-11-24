<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Manage doctors, departments, and consultation fees at the selected branch.',
        'create' => isset($permissions['doctors.create'])
            ? ['model' => resourceModel($routePrefix.'.create'), 'url' => route($routePrefix.'.create'), 'label' => 'New doctor']
            : null,
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-user-doctor text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Doctors</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter doctors">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-xl-7">
                    <label class="form-label small" for="search">Search doctors</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80" placeholder="ID (any part), name, mobile or email starts with..." <?php echo e(inputAttrs('search')); ?>>
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
            <?php echo $__env->make('core::partials.empty', ['title' => 'No doctors found', 'description' => 'Try another doctor name or clear the filters.', 'icon' => 'user-doctor'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Doctors, contact details, departments, and consultation fees</caption>
                    <thead>
                        <tr>
                            <th scope="col">Doctor</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Designation</th>
                            <th scope="col">Department</th>
                            <th class="text-end" scope="col">Consultation fee</th>
                            <th class="text-end" scope="col">Follow-up fee</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row">
                                    <div class="app-table-person d-flex align-items-center gap-3">
                                        <?php if($doctor->picture_url): ?>
                                            <img class="app-table-avatar rounded-circle object-fit-cover" src="<?php echo e($doctor->picture_url); ?>" alt="" width="40" height="40">
                                        <?php else: ?>
                                            <span class="app-table-avatar" aria-hidden="true"><i class="fa-solid fa-user-doctor"></i></span>
                                        <?php endif; ?>
                                        <div class="text-break">
                                            <div class="fw-semibold">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $doctor)): ?>
                                                    <a href="<?php echo e(route($routePrefix.'.show', $doctor)); ?>"><?php echo e($doctor->name); ?></a>
                                                <?php else: ?>
                                                    <?php echo e($doctor->name); ?>

                                                <?php endif; ?>
                                                <?php if($doctor->doctor_id !== null): ?>
                                                    <span class="small fw-normal text-body-secondary ms-2">ID: <?php echo e($doctor->doctor_id); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if($doctor->bn_name): ?>
                                                <div class="small fw-normal text-body-secondary" lang="bn"><?php echo e($doctor->bn_name); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </th>
                                <td class="small text-break">
                                    <div><?php echo e($doctor->mobile_no); ?></div>
                                    <div class="text-body-secondary"><?php echo e($doctor->email); ?></div>
                                </td>
                                <td class="small text-break"><?php echo e($doctor->designation ?? '-'); ?></td>
                                <td class="small text-break"><?php echo e($doctor->department?->name); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($doctor->consultation_fee); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($doctor->follow_up_fee); ?></td>
                                <td>
                                    <?php echo $__env->make('core::partials.row-actions', [
                                        'model' => $doctor,
                                        'routePrefix' => $routePrefix,
                                        'rowPermissions' => [
                                            'update' => isset($permissions['doctors.update']),
                                            'delete' => isset($permissions['doctors.delete']),
                                        ],
                                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\doctors\index.blade.php ENDPATH**/ ?>