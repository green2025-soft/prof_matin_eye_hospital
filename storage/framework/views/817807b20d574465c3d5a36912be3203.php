<?php
    $headField = 'items.'.$index.'.head_id';
    $amountField = 'items.'.$index.'.amount';
    $headId = getValue($headField, null, data_get($item, 'head_id'));
    $head = $selectedHeads->get($headId) ?? data_get($item, 'head');
?>

<tr class="income-expense-row">
    <td>
        <label class="visually-hidden" for="items-<?php echo e($index); ?>-head_id">Head</label>
        <select class="<?php echo e(inputClass($headField, 'form-select')); ?>" id="items-<?php echo e($index); ?>-head_id" name="items[<?php echo e($index); ?>][head_id]"
            data-ajax-select data-url="<?php echo e($headOptionsUrl); ?>" data-placeholder="Select a head"
            required <?php echo e(inputAttrs($headField)); ?>>
            <option value=""></option>
            <?php if($head !== null): ?>
                <option value="<?php echo e($head->id); ?>" selected><?php echo e($head->name); ?></option>
            <?php endif; ?>
        </select>
        <?php $__errorArgs = [$headField];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="items-<?php echo e($index); ?>-head_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td class="col-4 col-sm-3">
        <label class="visually-hidden" for="items-<?php echo e($index); ?>-amount">Amount</label>
        <input class="<?php echo e(inputClass($amountField, 'form-control text-end income-expense-amount')); ?>" id="items-<?php echo e($index); ?>-amount"
            name="items[<?php echo e($index); ?>][amount]" type="text" inputmode="decimal" maxlength="13"
            value="<?php echo e(getValue($amountField, null, data_get($item, 'amount'))); ?>" required <?php echo e(inputAttrs($amountField)); ?>>
        <?php $__errorArgs = [$amountField];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="items-<?php echo e($index); ?>-amount-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td class="text-end">
        <button class="btn btn-outline-danger btn-sm income-expense-remove-row" type="button" aria-label="Remove head">
            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
        </button>
    </td>
</tr>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\partials\income-expense-row.blade.php ENDPATH**/ ?>