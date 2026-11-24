<?php
    $field = 'lines.'.$index;
    $inputId = 'journal-line-'.$index;
    $debit = is_object($line) ? moneyDecimal($line->debit) : data_get($line, 'debit', '');
    $credit = is_object($line) ? moneyDecimal($line->credit) : data_get($line, 'credit', '');
?>

<tr class="accounting-journal-line">
    <td>
        <label class="visually-hidden" for="<?php echo e($inputId); ?>-account">Account head</label>
        <select class="<?php echo e(inputClass($field.'.account_head_id', 'form-select form-select-sm')); ?>"
            id="<?php echo e($inputId); ?>-account" name="lines[<?php echo e($index); ?>][account_head_id]" required
            <?php echo e(inputAttrs($field.'.account_head_id', $inputId.'-account')); ?>>
            <option value="">Select account</option>
            <?php $__currentLoopData = $accountHeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $head): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($head->id); ?>" <?php if((string) data_get($line, 'account_head_id') === (string) $head->id): echo 'selected'; endif; ?>><?php echo e($head->code); ?> — <?php echo e($head->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = [$field.'.account_head_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($inputId); ?>-account-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td>
        <label class="visually-hidden" for="<?php echo e($inputId); ?>-debit">Debit</label>
        <input class="<?php echo e(inputClass($field.'.debit', 'form-control form-control-sm text-end')); ?> journal-debit"
            id="<?php echo e($inputId); ?>-debit" name="lines[<?php echo e($index); ?>][debit]" inputmode="decimal" autocomplete="off"
            value="<?php echo e($debit === '0.00' ? '' : $debit); ?>" placeholder="0.00" maxlength="13"
            <?php echo e(inputAttrs($field.'.debit', $inputId.'-debit')); ?>>
        <?php $__errorArgs = [$field.'.debit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($inputId); ?>-debit-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td>
        <label class="visually-hidden" for="<?php echo e($inputId); ?>-credit">Credit</label>
        <input class="<?php echo e(inputClass($field.'.credit', 'form-control form-control-sm text-end')); ?> journal-credit"
            id="<?php echo e($inputId); ?>-credit" name="lines[<?php echo e($index); ?>][credit]" inputmode="decimal" autocomplete="off"
            value="<?php echo e($credit === '0.00' ? '' : $credit); ?>" placeholder="0.00" maxlength="13"
            <?php echo e(inputAttrs($field.'.credit', $inputId.'-credit')); ?>>
        <?php $__errorArgs = [$field.'.credit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($inputId); ?>-credit-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td>
        <label class="visually-hidden" for="<?php echo e($inputId); ?>-remarks">Remarks</label>
        <input class="<?php echo e(inputClass($field.'.remarks', 'form-control form-control-sm')); ?>"
            id="<?php echo e($inputId); ?>-remarks" name="lines[<?php echo e($index); ?>][remarks]" maxlength="255"
            value="<?php echo e(data_get($line, 'remarks')); ?>" <?php echo e(inputAttrs($field.'.remarks', $inputId.'-remarks')); ?>>
        <?php $__errorArgs = [$field.'.remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($inputId); ?>-remarks-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td class="text-end">
        <button class="btn btn-outline-danger btn-sm accounting-remove-line" type="button" aria-label="Remove voucher line" title="Remove line">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </td>
</tr>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\journal-entries\line.blade.php ENDPATH**/ ?>