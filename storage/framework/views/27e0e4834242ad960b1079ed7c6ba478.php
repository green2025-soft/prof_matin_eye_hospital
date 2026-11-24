<?php
    $quantity = $hasOldLines ? ($lineValues['quantity'] ?? 0) : ($savedLine?->quantity ?? 0);
    $productUnitId = $hasOldLines ? ($lineValues['product_unit_id'] ?? $sourceLine->product_unit_id) : ($savedLine?->product_unit_id ?? $sourceLine->product_unit_id);
?>

<tr data-pharmacy-line data-index="<?php echo e($lineIndex); ?>" data-return-remaining="<?php echo e($remainingQuantity); ?>" data-return-amount-minor="<?php echo e($remainingAmount); ?>" data-original-factor="<?php echo e($sourceLine->factor); ?>" data-original-price="<?php echo e(moneyDecimal($sourceLine->unit_price)); ?>">
    <td data-label="Qty">
        <label class="visually-hidden" for="line_<?php echo e($lineIndex); ?>_quantity">Return quantity for <?php echo e($variant->product->name); ?></label>
        <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.quantity', 'form-control form-control-sm text-end')); ?>" id="line_<?php echo e($lineIndex); ?>_quantity" name="lines[<?php echo e($lineIndex); ?>][quantity]" type="number" inputmode="numeric" min="0" step="1" value="<?php echo e($quantity); ?>" data-pharmacy-return-quantity required <?php echo e(inputAttrs('lines.'.$lineIndex.'.quantity', 'line_'.$lineIndex.'_quantity')); ?>>
        <?php $__errorArgs = ['lines.'.$lineIndex.'.quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="line_<?php echo e($lineIndex); ?>_quantity-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td class="app-pharmacy-entry-product" data-label="Product">
        <strong class="d-block small"><?php echo e($variant->product->name); ?></strong>
        <small class="text-body-secondary"><?php echo e($variant->description()); ?></small>
        <input name="lines[<?php echo e($lineIndex); ?>][variant_id]" type="hidden" value="<?php echo e($variant->id); ?>" data-pharmacy-return-field <?php if((int) $quantity <= 0): echo 'disabled'; endif; ?>>
        <input name="lines[<?php echo e($lineIndex); ?>][purchase_item_id]" type="hidden" value="<?php echo e($sourceLine->id); ?>" data-pharmacy-return-field <?php if((int) $quantity <= 0): echo 'disabled'; endif; ?>>
        <?php $__errorArgs = ['lines.'.$lineIndex.'.variant_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <?php $__errorArgs = ['lines.'.$lineIndex.'.purchase_item_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td class="text-end text-nowrap" data-label="Original (Pcs)"><?php echo e($sourceLine->base_quantity); ?></td>
    <td class="text-end" data-label="Available (Pcs)">
        <span class="text-nowrap" id="line_<?php echo e($lineIndex); ?>_remaining" data-pharmacy-return-remaining><?php echo e($remainingQuantity); ?></span>
        <?php if($sourceLine->base_quantity > $remainingQuantity): ?>
            <small class="d-block text-body-secondary">Returned: <?php echo e($sourceLine->base_quantity - $remainingQuantity); ?></small>
        <?php endif; ?>
    </td>
    <td data-label="Unit">
        <label class="visually-hidden" for="line_<?php echo e($lineIndex); ?>_unit">Return unit for <?php echo e($variant->product->name); ?></label>
        <select class="<?php echo e(inputClass('lines.'.$lineIndex.'.product_unit_id', 'form-select form-select-sm')); ?>" id="line_<?php echo e($lineIndex); ?>_unit" name="lines[<?php echo e($lineIndex); ?>][product_unit_id]" data-pharmacy-return-unit required <?php echo e(inputAttrs('lines.'.$lineIndex.'.product_unit_id', 'line_'.$lineIndex.'_unit', 'line_'.$lineIndex.'_remaining')); ?>>
            <?php $__currentLoopData = $variant->product->units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($unit->id); ?>" data-factor="<?php echo e($unit->id === $sourceLine->product_unit_id ? $sourceLine->factor : $unit->factor); ?>" <?php if((string) $productUnitId === (string) $unit->id): echo 'selected'; endif; ?>><?php echo e($unit->unit->symbol ?: $unit->unit->name); ?> × <?php echo e($unit->id === $sourceLine->product_unit_id ? $sourceLine->factor : $unit->factor); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['lines.'.$lineIndex.'.product_unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="line_<?php echo e($lineIndex); ?>_unit-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td class="text-end text-nowrap" data-label="Rate">
        <input name="lines[<?php echo e($lineIndex); ?>][unit_price]" type="hidden" value="<?php echo e(moneyDecimal($sourceLine->unit_price)); ?>" data-pharmacy-return-rate-input data-pharmacy-return-field <?php if((int) $quantity <= 0): echo 'disabled'; endif; ?>>
        <output data-pharmacy-return-rate><?php echo e(moneyDecimal($sourceLine->unit_price)); ?></output>
        <?php $__errorArgs = ['lines.'.$lineIndex.'.unit_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </td>
    <td class="text-end text-nowrap fw-semibold" data-label="Return amount"><output data-pharmacy-return-amount>0.00</output></td>
</tr>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\purchase-returns\item-line.blade.php ENDPATH**/ ?>