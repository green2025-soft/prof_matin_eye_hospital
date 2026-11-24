<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter commissions">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-lg-2">
                    <label class="form-label small" for="date_from">From Date</label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('date_from')); ?>" id="date_from" name="date_from" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('date_from', null, $filters['date_from'] ?? '')); ?>" data-input <?php echo e(inputAttrs('date_from')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_from" aria-label="Choose start date" title="Choose start date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
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
                <div class="col-6 col-lg-2">
                    <label class="form-label small" for="date_to">To Date</label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('date_to')); ?>" id="date_to" name="date_to" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('date_to', null, $filters['date_to'] ?? '')); ?>" data-input <?php echo e(inputAttrs('date_to')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_to" aria-label="Choose end date" title="Choose end date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
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
                <div class="col-12 col-lg-5">
                    <label class="form-label small" for="filter_reference_id">Reference By</label>
                    <select class="<?php echo e(inputClass('filter_reference_id', 'form-select')); ?>" id="filter_reference_id" name="filter_reference_id"
                        data-ajax-select data-url="<?php echo e(route('hospital.references.options')); ?>"
                        data-value-field="id" data-label-field="text" data-placeholder="All references"
                        <?php echo e(inputAttrs('filter_reference_id')); ?>>
                        <option value=""></option>
                        <?php if($selectedReference !== null): ?>
                            <option value="<?php echo e($selectedReference->id); ?>" selected><?php echo e($selectedReference->reference_id); ?> — <?php echo e($selectedReference->name); ?> (<?php echo e($selectedReference->contact_no); ?>)</option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['filter_reference_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="filter_reference_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12 col-lg-3">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>

        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No commissions found', 'description' => '', 'icon' => 'hand-holding-dollar'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Reference commissions and payment status</caption>
                    <thead>
                        <tr>
                            <th scope="col">Invoice</th>
                            <th scope="col">Reference</th>
                            <th class="text-end" scope="col">%</th>
                            <th class="text-end" scope="col">Commission</th>
                            <th class="text-end" scope="col">Eligible</th>
                            <th class="text-end" scope="col">Paid amount</th>
                            <th scope="col">Status / Paid date</th>
                            <?php if($canManageCommissions): ?>
                                <th class="text-end" scope="col">Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th class="small fw-normal text-nowrap" scope="row">
                                    <div class="fw-semibold"><?php echo e($commission['invoice_no'] ?: '#'.$commission['source_id']); ?></div>
                                    <div class="text-body-secondary"><?php echo e($commission['entry_date']); ?> · <?php echo e($commission['service_label']); ?></div>
                                </th>
                                <td class="small text-break">
                                    <div class="fw-semibold"><?php echo e($commission['reference_code']); ?></div>
                                    <div><?php echo e($commission['reference_name']); ?></div>
                                </td>
                                <td class="text-end text-nowrap"><?php echo e($commission['percent']); ?>%</td>
                                <td class="text-end text-nowrap"><?php echo e($commission['commission']); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($commission['eligible']); ?></td>
                                <td class="text-end text-nowrap">
                                    <?php echo e($commission['paid_amount']); ?>

                                    <?php if(isset($commission['overpaid']) && $commission['overpaid'] !== '0.00'): ?>
                                        <div class="small text-danger">Excess: <?php echo e($commission['overpaid']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-nowrap">
                                    <span class="badge <?php echo e($commission['status'] === 'paid' ? 'text-bg-success' : 'text-bg-warning'); ?>"><?php echo e(ucfirst($commission['status'])); ?></span>
                                    <?php if($commission['paid_at'] !== null): ?>
                                        <div class="text-body-secondary mt-1"><?php echo e($commission['paid_at']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <?php if($canManageCommissions): ?>
                                    <td class="text-end">
                                        <?php if($commission['can_pay'] || $commission['can_unpay']): ?>
                                            <?php ($targetStatus = $commission['can_unpay'] ? 'unpaid' : 'paid'); ?>
                                            <form class="d-inline-flex" action="<?php echo e(route($routePrefix.'.update', ['service' => $commission['service'], 'invoice' => $commission['source_id']] + $filters)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <input name="target_status" type="hidden" value="<?php echo e($targetStatus); ?>">
                                                <button class="btn btn-sm text-nowrap <?php echo e($commission['can_unpay'] ? 'btn-outline-secondary' : 'btn-success'); ?>" type="submit" aria-label="Mark <?php echo e($targetStatus); ?>: <?php echo e($commission['invoice_no'] ?: '#'.$commission['source_id']); ?>">
                                                    <i class="fa-solid <?php echo e($commission['can_unpay'] ? 'fa-rotate-left' : 'fa-check'); ?> me-1" aria-hidden="true"></i><?php echo e(ucfirst($targetStatus)); ?>

                                                </button>
                                            </form>
                                        <?php elseif($commission['status'] === 'unpaid' && $commission['pending'] !== '0.00'): ?>
                                            <span class="small text-body-secondary">Pending</span>
                                        <?php else: ?>
                                            <span class="text-body-secondary">—</span>
                                        <?php endif; ?>
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

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\commissions\index.blade.php ENDPATH**/ ?>