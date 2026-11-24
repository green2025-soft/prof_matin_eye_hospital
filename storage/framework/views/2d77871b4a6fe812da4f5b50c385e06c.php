<?php ($unitAmount = data_get($item, 'unit_amount') ?? moneyDecimal(data_get($item, 'unit_amount_minor', 0))); ?>

<tr data-invoice-item>
    <td>
        <label class="visually-hidden" for="invoice-item-<?php echo e($index); ?>-description">Description</label>
        <input class="<?php echo e(inputClass('items.'.$index.'.description')); ?>" id="invoice-item-<?php echo e($index); ?>-description"
            name="items[<?php echo e($index); ?>][description]" type="text"
            value="<?php echo e(getValue('items.'.$index.'.description', null, data_get($item, 'description', ''))); ?>" maxlength="1000" required
            <?php echo e(inputAttrs('items.'.$index.'.description', 'invoice-item-'.$index.'-description')); ?>>
        <?php $__errorArgs = ['items.'.$index.'.description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block" id="invoice-item-<?php echo e($index); ?>-description-error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td>
        <label class="visually-hidden" for="invoice-item-<?php echo e($index); ?>-quantity">Quantity</label>
        <input class="<?php echo e(inputClass('items.'.$index.'.quantity')); ?>" id="invoice-item-<?php echo e($index); ?>-quantity"
            name="items[<?php echo e($index); ?>][quantity]" type="number" min="1" max="100000" step="1"
            value="<?php echo e(getValue('items.'.$index.'.quantity', null, data_get($item, 'quantity', 1))); ?>" required
            <?php echo e(inputAttrs('items.'.$index.'.quantity', 'invoice-item-'.$index.'-quantity')); ?>>
        <?php $__errorArgs = ['items.'.$index.'.quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block" id="invoice-item-<?php echo e($index); ?>-quantity-error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td>
        <label class="visually-hidden" for="invoice-item-<?php echo e($index); ?>-unit-amount">Unit amount</label>
        <input class="<?php echo e(inputClass('items.'.$index.'.unit_amount')); ?>" id="invoice-item-<?php echo e($index); ?>-unit-amount"
            name="items[<?php echo e($index); ?>][unit_amount]" type="text" inputmode="decimal"
            value="<?php echo e(getValue('items.'.$index.'.unit_amount', null, $unitAmount)); ?>" required
            <?php echo e(inputAttrs('items.'.$index.'.unit_amount', 'invoice-item-'.$index.'-unit-amount')); ?>>
        <?php $__errorArgs = ['items.'.$index.'.unit_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block" id="invoice-item-<?php echo e($index); ?>-unit-amount-error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td class="text-end">
        <button class="btn btn-outline-danger btn-sm app-action-button" type="button" data-invoice-remove
            aria-label="Remove invoice item" title="Remove item">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </td>
</tr>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\invoices\item-row.blade.php ENDPATH**/ ?>