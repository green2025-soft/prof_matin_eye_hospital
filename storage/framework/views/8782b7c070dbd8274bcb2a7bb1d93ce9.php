<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Schedule patients with a doctor at the selected branch.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if($form !== null): ?>
        <div class="mb-3">
            <?php echo $__env->make('hospital::appointments.form', $form, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    <?php endif; ?>

    <?php if($records !== null): ?>
        <section class="app-card overflow-hidden">
            <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
                <i class="fa-solid fa-calendar-check text-body-secondary" aria-hidden="true"></i>
                <h2 class="h6 mb-0">Appointments</h2>
            </div>
            <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter appointments">
                <div class="row g-2 align-items-end">
                    <div class="col-md-6 col-xxl-3">
                        <label class="form-label small" for="search">Search appointments</label>
                        <div class="app-input-wrap app-search-field">
                            <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                            <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80" placeholder="Serial, invoice, ID or date starts with..." <?php echo e(inputAttrs('search')); ?>>
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
                    <div class="col-md-6 col-xxl-3">
                        <label class="form-label small" for="filter_doctor_id">Doctor</label>
                        <select class="<?php echo e(inputClass('filter_doctor_id', 'form-select')); ?>" id="filter_doctor_id" name="filter_doctor_id"
                            data-ajax-select data-url="<?php echo e(route('hospital.doctors.options')); ?>"
                            data-value-field="id" data-label-field="text" data-placeholder="All doctors"
                            <?php echo e(inputAttrs('filter_doctor_id')); ?>>
                            <option value=""></option>
                            <?php if(($filterDoctor ?? null) !== null): ?>
                                <option value="<?php echo e($filterDoctor->id); ?>" selected><?php echo e($filterDoctor->doctor_id ?: $filterDoctor->id); ?> — <?php echo e($filterDoctor->name); ?></option>
                            <?php endif; ?>
                        </select>
                        <?php $__errorArgs = ['filter_doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="filter_doctor_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-8 col-xxl-4">
                        <label class="form-label small" for="filter_patient_id">Patient</label>
                        <select class="<?php echo e(inputClass('filter_patient_id', 'form-select')); ?>" id="filter_patient_id" name="filter_patient_id"
                            data-ajax-select data-url="<?php echo e(route('hospital.patients.options')); ?>"
                            data-value-field="id" data-label-field="text" data-placeholder="All patients"
                            <?php echo e(inputAttrs('filter_patient_id')); ?>>
                            <option value=""></option>
                            <?php if(($filterPatient ?? null) !== null): ?>
                                <option value="<?php echo e($filterPatient->id); ?>" selected><?php echo e($filterPatient->patient_id); ?> — <?php echo e($filterPatient->name); ?> — <?php echo e($filterPatient->mobile_no); ?></option>
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
                    <div class="col-md-4 col-xxl-2">
                        <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </form>

            <?php if($records->isEmpty()): ?>
                <?php echo $__env->make('core::partials.empty', ['title' => 'No appointments found', 'description' => 'Create an appointment or clear the filters.', 'icon' => 'calendar-check'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table app-table align-middle mb-0">
                        <caption class="visually-hidden">Appointments at the selected branch</caption>
                        <thead>
                            <tr>
                                <th scope="col">Serial</th>
                                <th scope="col">Doctor</th>
                                <th scope="col">Patient</th>
                                <th scope="col">Mobile no.</th>
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th class="text-end" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th class="fw-semibold" scope="row">
                                        <?php echo e($appointment->serial_no ?? '-'); ?>

                                        <div class="small fw-normal text-body-secondary">#<?php echo e($appointment->id); ?></div>
                                        <?php if($appointment->invoice_no !== null): ?>
                                            <div class="small fw-normal text-body-secondary">Invoice <?php echo e($appointment->invoice_no); ?></div>
                                        <?php endif; ?>
                                    </th>
                                    <td class="text-break"><?php echo e($appointment->doctor?->name ?? '-'); ?></td>
                                    <td class="text-break"><?php echo e($appointment->patient?->name ?? '-'); ?><div class="small text-body-secondary"><?php echo e($appointment->patient?->patient_id); ?></div></td>
                                    <td class="text-break"><?php echo e($appointment->patient?->mobile_no ?? '-'); ?></td>
                                    <td class="text-nowrap"><?php echo e($appointment->appointment_date->toDateString()); ?></td>
                                    <td class="text-nowrap"><?php echo e($appointment->appointment_time === null ? '-' : \Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('h:i A')); ?></td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2 text-nowrap">
                                            <?php if($appointment->consultation !== null): ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $appointment->consultation)): ?>
                                                    <a class="btn btn-outline-primary btn-sm app-action-button" href="<?php echo e(route('hospital.consultations.edit', $appointment->consultation)); ?>" aria-label="Edit consultation for appointment <?php echo e($appointment->id); ?>" title="Edit consultation"><i class="fa-solid fa-notes-medical" aria-hidden="true"></i></a>
                                                <?php elseif (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', resourceModel('hospital.consultations.index'))): ?>
                                                    <a class="btn btn-outline-primary btn-sm app-action-button" href="<?php echo e(route('hospital.consultations.index', ['search' => $appointment->consultation->id])); ?>" aria-label="Find consultation for appointment <?php echo e($appointment->id); ?>" title="Find consultation"><i class="fa-solid fa-notes-medical" aria-hidden="true"></i></a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', resourceModel('hospital.consultations.create'))): ?>
                                                    <a class="btn btn-outline-primary btn-sm app-action-button" href="<?php echo e(route('hospital.consultations.create', ['appointment' => $appointment->id])); ?>" aria-label="Create consultation for appointment <?php echo e($appointment->id); ?>" title="Create consultation"><i class="fa-solid fa-notes-medical" aria-hidden="true"></i></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $appointment)): ?>
                                                <a class="btn btn-outline-secondary btn-sm app-action-button" href="<?php echo e(route($routePrefix.'.edit', $appointment)); ?>" aria-label="Edit appointment <?php echo e($appointment->id); ?>" title="Edit appointment"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $appointment)): ?>
                                                <form class="d-inline-flex" action="<?php echo e(route($routePrefix.'.destroy', $appointment)); ?>" method="POST" data-confirm="Delete this appointment? This cannot be undone.">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete appointment <?php echo e($appointment->id); ?>" title="Delete appointment"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </section>
    <?php endif; ?>

    <?php echo $__env->make('hospital::patients.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\appointments\index.blade.php ENDPATH**/ ?>