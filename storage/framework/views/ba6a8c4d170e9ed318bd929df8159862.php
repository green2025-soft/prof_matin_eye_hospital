<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => '',
        'create' => ['model' => resourceModel($routePrefix.'.create'), 'url' => route($routePrefix.'.create'), 'label' => 'New Investigation'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">

        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter patient investigations">
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-xxl-3">
                    <label class="form-label small" for="search">Search Patient Investigation</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80" placeholder="Invoice, ID or entry date starts with..." <?php echo e(inputAttrs('search')); ?>>
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
                    <label class="form-label small" for="filter_doctor_id">Select Doctor</label>
                    <select class="<?php echo e(inputClass('filter_doctor_id', 'form-select')); ?>" id="filter_doctor_id" name="filter_doctor_id" data-ajax-select data-url="<?php echo e(route('hospital.doctors.options')); ?>" data-value-field="id" data-label-field="text" data-placeholder="All doctors" <?php echo e(inputAttrs('filter_doctor_id')); ?>>
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
            <?php echo $__env->make('core::partials.empty', ['title' => 'No patient investigations found', 'description' => 'Try another entry ID or clear the filters.', 'icon' => 'file-invoice'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Patient investigations with patient, Doctor, and payment details</caption>
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Invoce</th>
                            <th scope="col">Patient</th>
                            <th scope="col">Doctor</th>
                            <th class="text-end" scope="col">Amount</th>
                            <th class="text-end" scope="col">Discount</th>
                            <th class="text-end" scope="col">Current payable</th>
                            <th class="text-end" scope="col">Current paid</th>
                            <th class="text-end" scope="col">Current due</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient_investigation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="small text-nowrap">

                                    <?php echo e($patient_investigation->entry_date?->format('d M Y')); ?>

                                    <div class="small text-body-secondary">Delivery <?php echo e($patient_investigation->delivery_date?->format('d M Y')); ?></div>
                                </td>
                                 <td>
                                    <?php echo e($patient_investigation->invoice_no); ?>

                                </td>
                                <td class="">
                                    <div class="fw-semibold"><?php echo e($patient_investigation->patient?->patient_id); ?></div>
                                    <div class=""><?php echo e($patient_investigation->patient?->name); ?></div>
                                </td>
                                <td class="small text-break"><?php echo e($patient_investigation->doctor?->name); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($patient_investigation->amount); ?></td>
                                <td class="text-end text-nowrap">
                                    <?php echo e($patient_investigation->discount_amount); ?>

                                    <div class="small text-body-secondary">Extra <?php echo e($patient_investigation->extra_discount_amount); ?></div>
                                </td>
                                <td class="text-end text-nowrap"><?php echo e($invoiceBalances[$patient_investigation->id]['remaining_amount']); ?></td>
                                <td class="text-end text-nowrap">
                                    <?php echo e($invoiceBalances[$patient_investigation->id]['remaining_paid']); ?>

                                    <div class="small text-body-secondary"> <?php echo e($patient_investigation->paymentType?->name); ?></div>
                                </td>
                                <td class="text-end text-nowrap">
                                    <?php echo e($invoiceBalances[$patient_investigation->id]['remaining_due']); ?>

                                    
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end gap-2 text-nowrap">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $patient_investigation)): ?>
                                            <a class="btn btn-outline-primary btn-sm app-action-button" href="<?php echo e(route($routePrefix.'.show', $patient_investigation)); ?>" aria-label="Print investigation invoice #<?php echo e($patient_investigation->id); ?>" title="Print invoice"><i class="fa-solid fa-print" aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $patient_investigation)): ?>
                                            <a class="btn btn-outline-secondary btn-sm app-action-button" href="<?php echo e(route($routePrefix.'.edit', $patient_investigation)); ?>" aria-label="Edit investigation #<?php echo e($patient_investigation->id); ?>" title="Edit"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $patient_investigation)): ?>
                                            <form class="d-inline-flex" action="<?php echo e(route($routePrefix.'.destroy', $patient_investigation)); ?>" method="POST" data-confirm="Delete this investigation? This cannot be undone.">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete investigation #<?php echo e($patient_investigation->id); ?>" title="Delete"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\patient-investigations\index.blade.php ENDPATH**/ ?>