<?php
    $originalLineId = $lineValues['original_line_id'] ?? $savedLine?->id;
    $productUnitId = $hasOldLines ? ($lineValues['product_unit_id'] ?? '') : ($savedLine?->product_unit_id ?? '');
    $quantity = $hasOldLines ? ($lineValues['quantity'] ?? '') : ($savedLine?->quantity ?? '');
    $unitPrice = $hasOldLines ? ($lineValues['unit_price'] ?? '') : ($savedLine === null ? '' : moneyDecimal($savedLine->unit_price));
    $discountValue = $itemFields['discount'] && $hasOldLines ? ($lineValues['discount'] ?? '')
        : ($savedLine === null && $itemFields['discount'] ? '' : moneyDecimal($savedLine?->discount ?? 0));
    $availableStock = ($stockQuantities->get($variant?->id) ?? 0) + ($revisionStock->get($variant?->id) ?? 0);
?>

<fieldset
    disabled
    data-pharmacy-line
    data-sale-price="<?php echo e($variant?->product?->sale_price ?? '0.00'); ?>"
>
    <legend class="visually-hidden">Item details</legend>
    <div>
        <input name="lines[<?php echo e($lineIndex); ?>][variant_id]" type="hidden" value="<?php echo e($lineValues['variant_id'] ?? $variant?->id ?? ''); ?>">
        <input name="lines[<?php echo e($lineIndex); ?>][original_line_id]" type="hidden" value="<?php echo e($originalLineId); ?>">
        <strong class="app-pharmacy-entry-name" hidden><?php echo e($variant?->product?->name ?? 'Select an available product'); ?></strong>
        <small class="app-pharmacy-entry-code text-body-secondary" hidden><?php echo e($variant?->description()); ?></small>
        <?php $__errorArgs = ['lines.'.$lineIndex.'.variant_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <?php $__errorArgs = ['lines.'.$lineIndex.'.original_line_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="row g-2">
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_unit">Unit <span class="text-danger" aria-hidden="true">*</span></label>
            <select class="<?php echo e(inputClass('lines.'.$lineIndex.'.product_unit_id', 'form-select')); ?>" id="trade_<?php echo e($lineIndex); ?>_unit" name="lines[<?php echo e($lineIndex); ?>][product_unit_id]" aria-keyshortcuts="F4 Alt+B Alt+P" title="Unit (F4, Alt+B Box, Alt+P Pcs)" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.product_unit_id', 'trade_'.$lineIndex.'_unit')); ?>>
                <option value="">Unit</option>
                <?php $__currentLoopData = $variant?->product?->units ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($unit->id); ?>" data-factor="<?php echo e($unit->factor); ?>" data-unit-name="<?php echo e($unit->unit->symbol ?: $unit->unit->name); ?>" data-symbol="<?php echo e($unit->unit->symbol); ?>" <?php if((string) $productUnitId === (string) $unit->id): echo 'selected'; endif; ?>><?php echo e($unit->unit->symbol ?: $unit->unit->name); ?> × <?php echo e($unit->factor); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['lines.'.$lineIndex.'.product_unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_unit-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_quantity">Qty <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.quantity', 'form-control text-end')); ?>" id="trade_<?php echo e($lineIndex); ?>_quantity" name="lines[<?php echo e($lineIndex); ?>][quantity]" type="number" inputmode="numeric" min="1" max="1000000000" step="1" value="<?php echo e($quantity); ?>" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.quantity', 'trade_'.$lineIndex.'_quantity')); ?>>
            <?php $__errorArgs = ['lines.'.$lineIndex.'.quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_quantity-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_stock">Stock (Pcs)</label>
            <output class="form-control text-end" id="trade_<?php echo e($lineIndex); ?>_stock" data-pharmacy-product-stock><?php echo e($availableStock); ?></output>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_price">Rate <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.unit_price', 'form-control text-end')); ?>" id="trade_<?php echo e($lineIndex); ?>_price" name="lines[<?php echo e($lineIndex); ?>][unit_price]" type="number" inputmode="decimal" min="0" step="0.01" value="<?php echo e($unitPrice); ?>" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.unit_price', 'trade_'.$lineIndex.'_price')); ?>>
            <?php $__errorArgs = ['lines.'.$lineIndex.'.unit_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_price-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <fieldset class="col-sm-6 col-lg-4 col-xl-2" <?php if (! ($itemFields['discount'])): ?> disabled hidden data-pharmacy-hidden-item-field <?php endif; ?>>
            <legend class="visually-hidden">Discount</legend>
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_discount">Discount</label>
            <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.discount', 'form-control text-end')); ?>" id="trade_<?php echo e($lineIndex); ?>_discount" name="lines[<?php echo e($lineIndex); ?>][discount]" type="number" inputmode="decimal" min="0" step="0.01" placeholder="0" value="<?php echo e($discountValue); ?>" <?php echo e(inputAttrs('lines.'.$lineIndex.'.discount', 'trade_'.$lineIndex.'_discount')); ?>>
            <?php $__errorArgs = ['lines.'.$lineIndex.'.discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_discount-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </fieldset>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_amount">Amount</label>
            <output class="form-control text-end fw-semibold" id="trade_<?php echo e($lineIndex); ?>_amount" data-pharmacy-row-amount>0.00</output>
        </div>
    </div>
</fieldset>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\sales\line.blade.php ENDPATH**/ ?>