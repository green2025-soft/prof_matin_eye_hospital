<?php $__env->startSection('report-filters'); ?>
    <form method="GET" action="<?php echo e(url()->current()); ?>" class="row g-3 align-items-end">
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="from_date">From date</label>
            <div class="input-group" data-datepicker><input class="<?php echo e(inputClass('from_date')); ?>" id="from_date" name="from_date" type="text" value="<?php echo e(getValue('from_date', null, $filters['from_date'])); ?>" placeholder="YYYY-MM-DD" autocomplete="off" required data-input <?php echo e(inputAttrs('from_date')); ?>><button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose from date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button></div>
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
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose to date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
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
                <?php $__currentLoopData = ['all' => 'All modules', 'general' => 'General income / expense', 'hospital' => 'Hospital', 'medicine' => 'Medicine', 'optic' => 'Optic']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php if($filters['module'] === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-12 col-md-6 col-xl-3"><button class="btn btn-primary" type="submit">Show report</button></div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <?php echo $__env->make('tenantcore::reports.partials.period-totals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <?php
        $entryGroups = collect($entries)
            ->sortBy('date')
            ->groupBy(fn ($entry) => $entry['module'].'|'.$entry['label']);
    ?>

    <?php $__empty_1 = true; $__currentLoopData = $entryGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $headEntries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $head = $headEntries->first();
            $side = $headCatalog[$head['module']][$head['label']];
            $subtotal = $headTotals[$key][$side] ?? 0;
        ?>

        <div class="table-responsive mb-4">
            <table class="app-report-table table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="colgroup" colspan="4" class="bg-body-tertiary">
                            <?php echo e($head['label']); ?> <span class="small fw-normal text-body-secondary"><?php echo e(ucfirst($head['module'])); ?></span>
                        </th>
                    </tr>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Particular</th>
                        <th scope="col">Details</th>
                        <th scope="col" class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $headEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-nowrap"><?php echo e($entry['date']); ?></td>
                            <td><?php echo e($entry['reference']); ?></td>
                            <td><?php echo e($entry['description']); ?></td>
                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $entry[$side])); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr class="fw-semibold">
                        <th scope="row" colspan="3">Total <?php echo e($head['label']); ?></th>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $subtotal)); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-center text-body-secondary py-4">No transactions in this period.</p>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenantcore::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\reports\cash.blade.php ENDPATH**/ ?>