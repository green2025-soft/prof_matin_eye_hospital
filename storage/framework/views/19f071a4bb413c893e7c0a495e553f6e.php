<?php $__env->startSection('report-filters'); ?>
    <form method="GET" action="<?php echo e(url()->current()); ?>" class="row g-3 align-items-end">
        <div class="col-12 col-md-3">
            <label class="form-label" for="from_date">From date</label>
            <div class="input-group" data-datepicker>
                <input class="<?php echo e(inputClass('from_date')); ?>" id="from_date" name="from_date" type="text"
                    value="<?php echo e(getValue('from_date', null, $filters['from_date'])); ?>" placeholder="YYYY-MM-DD"
                    autocomplete="off" required data-input <?php echo e(inputAttrs('from_date')); ?>>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose from date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
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
        <div class="col-12 col-md-3">
            <label class="form-label" for="to_date">To date</label>
            <div class="input-group" data-datepicker>
                <input class="<?php echo e(inputClass('to_date')); ?>" id="to_date" name="to_date" type="text"
                    value="<?php echo e(getValue('to_date', null, $filters['to_date'])); ?>" placeholder="YYYY-MM-DD"
                    autocomplete="off" required data-input <?php echo e(inputAttrs('to_date')); ?>>
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
        <div class="col-12 col-md-3">
            <label class="form-label" for="patient_id">Patient</label>
            <select class="<?php echo e(inputClass('patient_id', 'form-select')); ?>" id="patient_id" name="patient_id"
                data-ajax-select data-url="<?php echo e(route('hospital.patients.options')); ?>"
                data-value-field="id" data-label-field="text" data-placeholder="All patients"
                <?php echo e(inputAttrs('patient_id')); ?>>
                <option value=""></option>
                <?php if($selectedPatient !== null): ?>
                    <option value="<?php echo e($selectedPatient->id); ?>" selected>
                        <?php echo e($selectedPatient->patient_id); ?> — <?php echo e($selectedPatient->name); ?> — <?php echo e($selectedPatient->mobile_no); ?>

                    </option>
                <?php endif; ?>
            </select>
            <?php $__errorArgs = ['patient_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="patient_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-12 col-md-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Show report</button>
            <a class="btn btn-outline-secondary" href="<?php echo e(route('hospital.reports.patient-due')); ?>">Reset</a>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="app-report-table table app-table table-sm align-middle mb-0">
            <caption class="visually-hidden">Patient clinical invoices and current outstanding balances</caption>
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Patient</th>
                    <th scope="col">Particular</th>
                    <th class="text-end" scope="col">Amount</th>
                    <th class="text-end" scope="col">Discount</th>
                    <th class="text-end" scope="col">Due Collection</th>
                    <th class="text-end" scope="col">Total Collection</th>
                    <th class="text-end" scope="col">Rest Due</th>
                </tr>
            </thead>
            <tbody>
                <?php if($records): ?>
                <?php
                    $totalAmount        = 0;
                    $totalDiscount      = 0;
                    $totalDuePayment    = 0;
                    $totalPaid          = 0;
                    $totalDue           = 0;
                ?>
                <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                 <?php
                    $totalAmount        += $invoice->getAttribute('report_amount')??0;
                    $totalDiscount      += $invoice->getAttribute('report_discount')??0;
                    $totalDuePayment    += $invoice->getAttribute('report_due_payment')??0;
                    $totalPaid          += $invoice->getAttribute('report_paid')??0;
                    $totalDue           += $invoice->getAttribute('report_due')??0;
                ?>
                    <tr>
                        <th class="text-nowrap" scope="row"><?php echo e($invoice->entry_date->toDateString()); ?></th>
                        <td>
                            <div class="fw-semibold"><?php echo e($invoice->patient?->patient_id); ?> — <?php echo e($invoice->patient?->name); ?></div>
                            <div class="small text-body-secondary"><?php echo e($invoice->patient?->mobile_no); ?></div>
                        </td>
                        <td>
                            <div><?php echo e(clinicalServiceOptions()[$invoice::SERVICE]); ?></div>
                            <div class="small text-body-secondary"><?php echo e($invoice->invoice_no ?: '#'.$invoice->getKey()); ?></div>
                        </td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $invoice->getAttribute('report_amount'))); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $invoice->getAttribute('report_discount'))); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $invoice->getAttribute('report_due_payment'))); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $invoice->getAttribute('report_paid'))); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $invoice->getAttribute('report_due'))); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th colspan="3" class="text-end">Total :</th>
                    <th class="text-end"><?php echo e(moneyDecimal((string) $totalAmount)); ?></th>
                    <th class="text-end"><?php echo e(moneyDecimal((string) $totalDiscount)); ?></th>
                    <th class="text-end"><?php echo e(moneyDecimal((string) $totalDuePayment)); ?></th>
                    <th class="text-end"><?php echo e(moneyDecimal((string) $totalPaid)); ?></th>
                    <th class="text-end"><?php echo e(moneyDecimal((string) $totalDue)); ?></th>
                </tr>
                <?php else: ?>
                 <tr>
                        <td class="text-center text-body-secondary py-4" colspan="8">No patient due records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenantcore::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\reports\patient-due.blade.php ENDPATH**/ ?>