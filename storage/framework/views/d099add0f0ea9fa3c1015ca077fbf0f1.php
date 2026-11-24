<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Prescriptions at the selected branch.',
        'create' => ['model' => \Modules\Prescription\Models\Prescription::class, 'url' => route($routePrefix.'.create'), 'label' => 'New prescription'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Search prescriptions">
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-xl-4">
                    <label class="form-label small" for="prescription_search">Search</label>
                    <input class="<?php echo e(inputClass('search')); ?>" id="prescription_search" name="search" type="search" maxlength="80" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" placeholder="Rx number, ID or date" <?php echo e(inputAttrs('search', 'prescription_search')); ?>>
                    <?php $__errorArgs = ['search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="prescription_search-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 col-xl-5">
                    <label class="form-label small" for="filter_patient_id">Patient history</label>
                    <select class="<?php echo e(inputClass('filter_patient_id', 'form-select')); ?>" id="filter_patient_id" name="filter_patient_id" data-ajax-select data-url="<?php echo e(route('hospital.patients.options')); ?>" data-value-field="id" data-label-field="text" data-placeholder="All patients" <?php echo e(inputAttrs('filter_patient_id')); ?>>
                        <option value=""></option>
                        <?php if($selectedPatient !== null): ?>
                            <option value="<?php echo e($selectedPatient->id); ?>" selected><?php echo e($selectedPatient->patient_id); ?> / <?php echo e($selectedPatient->name); ?> / <?php echo e($selectedPatient->mobile_no); ?></option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['filter_patient_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="filter_patient_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-xl-3">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Saved prescriptions with patient and doctor details</caption>
                <thead>
                    <tr>
                        <th scope="col">Prescription</th>
                        <th scope="col">Date</th>
                        <th scope="col">Patient</th>
                        <th scope="col">Doctor</th>
                        <th scope="col">Follow-up</th>
                        <th class="text-end" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <th scope="row"><?php echo e($record->rx_number ?? '#'.$record->id); ?></th>
                            <td class="text-nowrap"><?php echo e($record->prescription_date?->format('d M Y')); ?></td>
                            <td><a class="d-block fw-semibold" href="<?php echo e(route($routePrefix.'.index', ['filter_patient_id' => $record->patient_id])); ?>" title="View patient prescription history"><?php echo e($record->patient?->name); ?></a><small class="text-body-secondary"><?php echo e($record->patient?->patient_id); ?></small></td>
                            <td><?php echo e($record->doctor?->name); ?></td>
                            <td><?php echo e($record->follow_up_date?->format('d M Y') ?? '--'); ?></td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $record)): ?>
                                        <a class="btn btn-outline-primary btn-sm app-action-button" href="<?php echo e(route($routePrefix.'.show', $record)); ?>" aria-label="View prescription #<?php echo e($record->id); ?>" title="View / Print"><i class="fa-solid fa-print" aria-hidden="true"></i></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $record)): ?>
                                        <a class="btn btn-outline-secondary btn-sm app-action-button" href="<?php echo e(route($routePrefix.'.edit', $record)); ?>" aria-label="Edit prescription #<?php echo e($record->id); ?>" title="Edit"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $record)): ?>
                                        <form action="<?php echo e(route($routePrefix.'.destroy', $record)); ?>" method="POST" data-confirm="Delete this prescription? This cannot be undone.">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete prescription #<?php echo e($record->id); ?>" title="Delete"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-center text-body-secondary py-4" colspan="6">No prescriptions match these filters.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\prescriptions\index.blade.php ENDPATH**/ ?>