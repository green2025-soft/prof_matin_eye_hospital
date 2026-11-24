<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php ($postedLines = old('lines', $journal_entry->lines)); ?>
    <?php ($lineRows = collect(is_iterable($postedLines) ? $postedLines : [])->take(100)); ?>

    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form class="app-card p-3 p-md-4" id="accounting-journal-form" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?> <?php echo method_field($formMethod); ?> <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label" for="voucher-type">Voucher Type <span class="text-danger">*</span></label>
                <select class="<?php echo e(inputClass('voucher_type_id', 'form-select')); ?>" id="voucher-type" name="voucher_type_id"
                    required <?php echo e(inputAttrs('voucher_type_id', 'voucher-type')); ?>>
                    <option value="">Select Type</option>
                    <?php $__currentLoopData = $voucherTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->id); ?>" <?php echo e(getSelected($type->id, 'voucher_type_id', $journal_entry)); ?>><?php echo e($type->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['voucher_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="voucher-type-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="entry-date">Date <span class="text-danger">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="<?php echo e(inputClass('entry_date')); ?>" id="entry-date" name="entry_date" type="text"
                        value="<?php echo e(getValue('entry_date', null, $entryDate)); ?>" placeholder="YYYY-MM-DD" autocomplete="off"
                        required data-input <?php echo e(inputAttrs('entry_date', 'entry-date')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose voucher date" aria-controls="entry-date">
                        <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                    </button>
                </div>
                <?php $__errorArgs = ['entry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="entry-date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="reference">Reference</label>
                <input class="<?php echo e(inputClass('reference')); ?>" id="reference" name="reference" maxlength="100"
                    value="<?php echo e(getValue('reference', $journal_entry)); ?>" <?php echo e(inputAttrs('reference')); ?>>
                <?php $__errorArgs = ['reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="reference-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
            <h2 class="h6 mb-0">Voucher lines</h2>
            <button class="btn btn-outline-primary btn-sm" id="accounting-add-line" type="button">
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>Add line
            </button>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-2">
                <thead>
                    <tr>
                        <th scope="col">Account head</th>
                        <th class="text-end" scope="col">Debit (BDT)</th>
                        <th class="text-end" scope="col">Credit (BDT)</th>
                        <th scope="col">Remarks</th>
                        <th scope="col"><span class="visually-hidden">Actions</span></th>
                    </tr>
                </thead>
                <tbody id="accounting-journal-lines">
                    <?php $__empty_1 = true; $__currentLoopData = $lineRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php echo $__env->make('accounting::journal-entries.line', ['index' => $index, 'line' => $line], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php echo $__env->make('accounting::journal-entries.line', ['index' => 0, 'line' => []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('accounting::journal-entries.line', ['index' => 1, 'line' => []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="row">Total</th>
                        <td class="text-end fw-semibold" id="accounting-debit-total">0.00</td>
                        <td class="text-end fw-semibold" id="accounting-credit-total">0.00</td>
                        <td colspan="2"><span class="small" id="accounting-balance-state" aria-live="polite"></span></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php $__errorArgs = ['lines'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block mb-3"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <div class="mt-3">
            <label class="form-label" for="narration">Narration</label>
            <textarea class="<?php echo e(inputClass('narration')); ?>" id="narration" name="narration" rows="2" maxlength="2000"
                <?php echo e(inputAttrs('narration')); ?>><?php echo e(getValue('narration', $journal_entry)); ?></textarea>
            <?php $__errorArgs = ['narration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="narration-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <?php echo $__env->make('core::partials.form-actions', ['model' => $journal_entry], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <template id="accounting-journal-line-template">
            <?php echo $__env->make('accounting::journal-entries.line', ['index' => '__INDEX__', 'line' => []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </template>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\journal-entries\form.blade.php ENDPATH**/ ?>