<?php $__env->startSection('report-filters'); ?>
    <form method="GET" action="<?php echo e(url()->current()); ?>" class="row g-3 align-items-end">
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="year">Year</label>
            <select class="<?php echo e(inputClass('year', 'form-select')); ?>" id="year" name="year" <?php echo e(inputAttrs('year')); ?>>
                <?php for($year = 2000; $year <= 2100; $year++): ?>
                    <option value="<?php echo e($year); ?>" <?php if((int) $filters['year'] === $year): echo 'selected'; endif; ?>><?php echo e($year); ?></option>
                <?php endfor; ?>
            </select>
            <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="year-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="month">Month</label>
            <select class="<?php echo e(inputClass('month', 'form-select')); ?>" id="month" name="month" <?php echo e(inputAttrs('month')); ?>>
                <?php $__currentLoopData = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $number => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($number); ?>" <?php if((int) $filters['month'] === $number): echo 'selected'; endif; ?>><?php echo e($name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['month'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="month-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="module">Module</label>
            <select class="form-select" id="module" name="module">
                <?php $__currentLoopData = ['all' => 'All modules', 'general' => 'General income / expense', 'hospital' => 'Hospital', 'medicine' => 'Medicine', 'optic' => 'Optic']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
    <?php echo $__env->make('tenantcore::reports.partials.period-totals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="app-report-table table table-sm align-middle mb-0">
            <caption class="visually-hidden">Daily income, expense and net balance for the selected month</caption>
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col" class="text-end">Income</th>
                    <th scope="col" class="text-end">Expense</th>
                    <th scope="col" class="text-end">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = \Carbon\CarbonPeriodImmutable::create($fromDate, $toDate); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $day = $days[$date->toDateString()] ?? [];
                    ?>
                    <tr>
                        <th scope="row" class="text-nowrap"><?php echo e($date->toDateString()); ?></th>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) ($day['received'] ?? 0))); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) ($day['paid'] ?? 0))); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) ($day['net'] ?? 0))); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr class="fw-semibold">
                    <th scope="row">Total</th>
                    <td class="text-end text-nowrap"><?php echo e($received); ?></td>
                    <td class="text-end text-nowrap"><?php echo e($paid); ?></td>
                    <td class="text-end text-nowrap"><?php echo e($net); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <p class="small text-body-secondary mt-2">Each date's Balance = that day's Income − Expense.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenantcore::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\reports\monthly-cash.blade.php ENDPATH**/ ?>