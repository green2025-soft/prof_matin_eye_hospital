<?php $__env->startSection('report-filters'); ?>
    <form method="GET" action="<?php echo e(url()->current()); ?>" class="row g-3 align-items-end">
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="from_date">From date</label>
            <div class="input-group" data-datepicker>
                <input class="<?php echo e(inputClass('from_date')); ?>" id="from_date" name="from_date" type="text" value="<?php echo e(getValue('from_date', null, $filters['from_date'])); ?>" placeholder="YYYY-MM-DD" autocomplete="off" required data-input <?php echo e(inputAttrs('from_date')); ?>>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose from date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
            </div>
            <?php $__errorArgs = ['from_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="from_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="to_date">To date</label>
            <div class="input-group" data-datepicker>
                <input class="<?php echo e(inputClass('to_date')); ?>" id="to_date" name="to_date" type="text" value="<?php echo e(getValue('to_date', null, $filters['to_date'])); ?>" placeholder="YYYY-MM-DD" autocomplete="off" required data-input <?php echo e(inputAttrs('to_date')); ?>>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose to date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
            </div>
            <?php $__errorArgs = ['to_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="to_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="module">Module</label>
            <select class="form-select" id="module" name="module">
                <?php $__currentLoopData = ['all' => 'All modules', 'general' => 'General income / expense', 'hospital' => 'Hospital', 'medicine' => 'Medicine', 'optic' => 'Optics']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php if($filters['module'] === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <button class="btn btn-primary" type="submit">Show report</button>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <div class="app-income-expense-columns row g-3 py-3">
        <section class="col-12 col-md-6" aria-labelledby="income-heading">
            <div class="border rounded h-100">
                <h4 class="h6 text-success bg-success-subtle p-3 mb-0" id="income-heading">Income</h4>
                <table class="app-report-table table table-sm align-middle mb-0">
                    <thead>
                        <tr><th scope="col" class="ps-3">Income Particular</th><th scope="col" class="text-end pe-3">Amount</th></tr>
                    </thead>
                    <tbody>
                        <?php echo $__env->make('tenantcore::reports.partials.head-rows', ['side' => 'received'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-semibold"><th scope="row" class="ps-3">Total Income</th><td class="text-end text-nowrap pe-3"><?php echo e($received); ?></td></tr>
                    </tfoot>
                </table>
            </div>
        </section>
        <section class="col-12 col-md-6" aria-labelledby="expense-heading">
            <div class="border rounded h-100">
                <h4 class="h6 text-danger bg-danger-subtle p-3 mb-0" id="expense-heading">Expense</h4>
                <table class="app-report-table table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col" class="ps-3">Expense Particular</th>
                            <th scope="col" class="text-end pe-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $__env->make('tenantcore::reports.partials.head-rows', ['side' => 'paid'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-semibold">
                            <th scope="row" class="ps-3">Total Expense</th>
                            <td class="text-end text-nowrap pe-3"><?php echo e($paid); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </div>
    <div class="app-report-balance d-flex flex-wrap justify-content-between gap-2 bg-primary-subtle rounded p-3">
        <span class="fw-semibold">Balance <span class="small fw-normal">(Income − Expense)</span></span>
        <strong class="fs-5"><?php echo e($net); ?></strong>
    </div>
    <p class="small text-body-secondary mt-2 mb-0">Income and expense show received and paid amounts. Unpaid invoice amounts remain due.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenantcore::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\reports\daily.blade.php ENDPATH**/ ?>