<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Review scheduled investigation deliveries and update their delivery status.',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-file-circle-check text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Delivery investigations</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter delivery investigations">
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-xl-2">
                    <label class="form-label small" for="date_from">Delivery date from</label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('date_from')); ?>" id="date_from" name="date_from" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('date_from', null, $filters['date_from'] ?? '')); ?>" data-input <?php echo e(inputAttrs('date_from')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_from" aria-label="Choose delivery start date" title="Choose delivery start date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    <?php $__errorArgs = ['date_from'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="date_from-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 col-xl-2">
                    <label class="form-label small" for="date_to">Delivery date to</label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('date_to')); ?>" id="date_to" name="date_to" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('date_to', null, $filters['date_to'] ?? '')); ?>" data-input <?php echo e(inputAttrs('date_to')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_to" aria-label="Choose delivery end date" title="Choose delivery end date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    <?php $__errorArgs = ['date_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="date_to-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 col-xl-3">
                    <label class="form-label small" for="filter_patient_id">Patient</label>
                    <select class="<?php echo e(inputClass('filter_patient_id', 'form-select')); ?>" id="filter_patient_id" name="filter_patient_id"
                        data-ajax-select data-url="<?php echo e(route('hospital.patients.options')); ?>"
                        data-value-field="id" data-label-field="text" data-placeholder="All patients"
                        <?php echo e(inputAttrs('filter_patient_id')); ?>>
                        <option value=""></option>
                        <?php if($filterPatient !== null): ?>
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
                <div class="col-md-6 col-xl-3">
                    <label class="form-label small" for="filter_doctor_id">Doctor</label>
                    <select class="<?php echo e(inputClass('filter_doctor_id', 'form-select')); ?>" id="filter_doctor_id" name="filter_doctor_id" data-ajax-select data-url="<?php echo e(route('hospital.doctors.options')); ?>" data-value-field="id" data-label-field="text" data-placeholder="All doctors" <?php echo e(inputAttrs('filter_doctor_id')); ?>>
                        <option value=""></option>
                        <?php if($filterDoctor !== null): ?>
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
                <div class="col-md-6 col-xl-2">
                    <label class="form-label small" for="delivery_status">Status</label>
                    <select class="<?php echo e(inputClass('delivery_status', 'form-select')); ?>" id="delivery_status" name="delivery_status" <?php echo e(inputAttrs('delivery_status')); ?>>
                        <option value="">All statuses</option>
                        <?php $__currentLoopData = $deliveryStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'delivery_status', null, $filters['delivery_status'] ?? '')); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['delivery_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="delivery_status-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>

        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No delivery investigations found', 'description' => 'Choose another delivery date range or clear the filters.', 'icon' => 'file-circle-check'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Investigation invoices with patients, doctors, scheduled delivery dates, and delivery status</caption>
                    <thead>
                        <tr>
                            <th scope="col">Invoice</th>
                            <th scope="col">Patient</th>
                            <th scope="col">Doctor</th>
                            <th scope="col">Entry date</th>
                            <th scope="col">Delivery date</th>
                            <th scope="col">Status</th>
                            <?php if($canManageDelivery): ?>
                                <th class="text-end" scope="col">Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $investigation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th class="small fw-semibold text-break" scope="row"><?php echo e($investigation->invoice_no ?: '#'.$investigation->id); ?></th>
                                <td class="small text-break">
                                    <div class="fw-semibold"><?php echo e($investigation->patient?->patient_id); ?></div>
                                    <div><?php echo e($investigation->patient?->name); ?></div>
                                    <div class="text-body-secondary"><?php echo e($investigation->patient?->mobile_no); ?></div>
                                </td>
                                <td class="small text-break">
                                    <div class="fw-semibold"><?php echo e($investigation->doctor?->doctor_id ?: $investigation->doctor?->id); ?></div>
                                    <div><?php echo e($investigation->doctor?->name); ?></div>
                                </td>
                                <td class="small text-nowrap"><?php echo e($investigation->entry_date?->format('d M Y')); ?></td>
                                <td class="small text-nowrap"><?php echo e($investigation->delivery_date?->format('d M Y')); ?></td>
                                <td><span class="badge <?php echo e($investigation->delivery_status === 'delivered' ? 'text-bg-success' : 'text-bg-warning'); ?>"><?php echo e($deliveryStatuses[$investigation->delivery_status]); ?></span></td>
                                <?php if($canManageDelivery): ?>
                                    <td class="text-end">
                                        <form class="d-inline-flex" action="<?php echo e(route($routePrefix.'.update', ['patient_investigation' => $investigation] + $filters)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <input name="target_status" type="hidden" value="<?php echo e($investigation->delivery_status === 'pending' ? 'delivered' : 'pending'); ?>">
                                            <button class="btn btn-sm text-nowrap <?php echo e($investigation->delivery_status === 'pending' ? 'btn-success' : 'btn-outline-secondary'); ?>" type="submit" aria-label="<?php echo e($investigation->delivery_status === 'pending' ? 'Mark delivered' : 'Mark pending'); ?>: <?php echo e($investigation->invoice_no ?: '#'.$investigation->id); ?>">
                                                <i class="fa-solid <?php echo e($investigation->delivery_status === 'pending' ? 'fa-check' : 'fa-rotate-left'); ?> me-1" aria-hidden="true"></i><?php echo e($investigation->delivery_status === 'pending' ? 'Mark Delivered' : 'Mark Pending'); ?>

                                            </button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\delivery-investigations\index.blade.php ENDPATH**/ ?>