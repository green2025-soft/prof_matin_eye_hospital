<?php
    $originalLineId = $lineValues['original_line_id'] ?? $savedLine?->id;
    $selectedProfile = $variant?->product?->units->firstWhere('id', $lineValues['product_unit_id'] ?? null);
    $opticUnitId = $lineValues['unit_id'] ?? ($hasOldLines ? ($selectedProfile?->unit_id ?? '') : $savedLine?->productUnit?->unit_id);
    $opticUnitFactor = $lineValues['unit_factor'] ?? ($hasOldLines ? ($selectedProfile?->factor ?? 1) : ($savedLine?->factor ?? 1));
    $opticUnit = $purchaseUnits->firstWhere('symbol', 'Pcs');
    if ($savedLine !== null) {
        $opticUnit = $purchaseUnits->firstWhere('id', $opticUnitId)
            ?? $variant?->product?->units->firstWhere('unit_id', $opticUnitId)?->unit;
    } else {
        $opticUnitId = $opticUnit?->id;
        $opticUnitFactor = 1;
    }
    $opticUnitLabel = $opticUnit?->symbol ?: ($opticUnit?->name ?: ($savedLine === null ? 'Pcs' : 'Saved unit'));
    $discountUnitLabel = '/ '.$opticUnitLabel;
    $latestPurchaseItem = $variant?->latestPurchaseItem;
    $quantity = $hasOldLines ? ($lineValues['quantity'] ?? '') : ($savedLine?->quantity ?? '');
    $unitPrice = $hasOldLines ? ($lineValues['unit_price'] ?? '') : ($savedLine === null ? '' : moneyDecimal($savedLine->unit_price));
    $quantityHelpId = 'trade_'.$lineIndex.'_quantity-unit';
    foreach (['unit_id' => 'unit', 'unit_factor' => 'factor', 'product_unit_id' => 'product-unit'] as $field => $suffix) {
        if ($errors->has('lines.'.$lineIndex.'.'.$field)) {
            $quantityHelpId .= ' trade_'.$lineIndex.'_'.$suffix.'-error';
        }
    }
    $showOpticOptions = $itemFields['discount'] || $itemFields['vat'];
    $opticOptionsLabel = $itemFields['discount'] ? 'Discount' : 'VAT';
    if ($itemFields['discount'] && $itemFields['vat']) {
        $opticOptionsLabel = 'Discount / VAT';
    }
    $optionDiscount = $hasOldLines ? ($lineValues['discount'] ?? 0) : match ($savedLine?->discount_calculation ?? 'amount') {
        'percent' => $savedLine?->discount_percent ?? 0,
        'unit' => $savedLine?->discount_unit_amount ?? 0,
        default => $savedLine?->discount ?? 0,
    };
    $optionVatMode = $hasOldLines ? ($lineValues['vat_mode'] ?? $purchaseVatMode)
        : ($savedLine?->vat_mode ?? $purchaseVatMode);
    $optionVatPercent = $hasOldLines ? ($lineValues['vat_percent'] ?? $purchaseVatPercent)
        : ($savedLine?->vat_percent ?? $purchaseVatPercent);
    $optionVatAmount = $hasOldLines ? ($lineValues['vat_amount'] ?? 0) : ($savedLine?->vat ?? 0);
    $opticOptionsOpen = $errors->hasAny([
        'lines.'.$lineIndex.'.discount', 'lines.'.$lineIndex.'.discount_input',
        'lines.'.$lineIndex.'.vat_mode', 'lines.'.$lineIndex.'.vat_input',
        'lines.'.$lineIndex.'.vat_percent', 'lines.'.$lineIndex.'.vat_amount',
    ]) || ($itemFields['discount'] && $optionDiscount > 0)
        || ($itemFields['vat'] && $optionVatMode !== 'none' && ($optionVatPercent > 0 || $optionVatAmount > 0));
?>

<fieldset
    disabled
    data-pharmacy-line
    data-index="<?php echo e($lineIndex); ?>"
    data-pharmacy-fixed-unit="<?php echo e($opticUnitLabel); ?>"
    data-last-purchase-rate="<?php echo e($latestPurchaseItem === null ? '' : moneyDecimal($latestPurchaseItem->unit_price)); ?>"
    data-last-purchase-factor="<?php echo e($latestPurchaseItem?->factor ?? ''); ?>"
    data-preserve-rate-on-edit="<?php echo e($hasOldLines ? '1' : '0'); ?>"
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
        <input name="lines[<?php echo e($lineIndex); ?>][unit_id]" type="hidden" value="<?php echo e($opticUnitId); ?>">
        <input name="lines[<?php echo e($lineIndex); ?>][unit_factor]" type="hidden" value="<?php echo e($opticUnitFactor); ?>">
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_quantity">Qty <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="input-group">
                <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.quantity', 'form-control text-end')); ?>" id="trade_<?php echo e($lineIndex); ?>_quantity" name="lines[<?php echo e($lineIndex); ?>][quantity]" type="number" inputmode="numeric" min="1" max="1000000000" step="1" value="<?php echo e($quantity); ?>" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.quantity', 'trade_'.$lineIndex.'_quantity', $quantityHelpId)); ?>>
                <span class="input-group-text" id="trade_<?php echo e($lineIndex); ?>_quantity-unit" data-pharmacy-quantity-unit><?php echo e($opticUnitLabel); ?></span>
            </div>
            <?php $__errorArgs = ['lines.'.$lineIndex.'.quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_quantity-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['lines.'.$lineIndex.'.unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_unit-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['lines.'.$lineIndex.'.unit_factor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_factor-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['lines.'.$lineIndex.'.product_unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_product-unit-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_price">Rate <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="input-group">
                <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.unit_price', 'form-control text-end')); ?>" id="trade_<?php echo e($lineIndex); ?>_price" name="lines[<?php echo e($lineIndex); ?>][unit_price]" type="number" inputmode="decimal" min="0" step="0.01" value="<?php echo e($unitPrice); ?>" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.unit_price', 'trade_'.$lineIndex.'_price', 'trade_'.$lineIndex.'_price-unit')); ?>>
                <span class="input-group-text" id="trade_<?php echo e($lineIndex); ?>_price-unit">/ <span class="ms-1" data-pharmacy-rate-unit><?php echo e($opticUnitLabel); ?></span></span>
            </div>
            <?php $__errorArgs = ['lines.'.$lineIndex.'.unit_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_price-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <?php if($showPurchaseSalePrice): ?>
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_sale_price">Sale Price <span class="visually-hidden">per piece</span></label>
                <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.sale_price_per_piece', 'form-control text-end')); ?>" id="trade_<?php echo e($lineIndex); ?>_sale_price" name="lines[<?php echo e($lineIndex); ?>][sale_price_per_piece]" type="number" inputmode="decimal" min="0" step="0.01" placeholder="<?php echo e($variant?->product?->sale_price > 0 ? $variant->product->sale_price : '0'); ?>" value="<?php echo e($lineValues['sale_price_per_piece'] ?? ''); ?>" <?php echo e(inputAttrs('lines.'.$lineIndex.'.sale_price_per_piece', 'trade_'.$lineIndex.'_sale_price')); ?>>
                <?php $__errorArgs = ['lines.'.$lineIndex.'.sale_price_per_piece'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_sale_price-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        <?php endif; ?>
        <div class="col-12 order-last" <?php if (! ($showOpticOptions)): ?> hidden <?php endif; ?>>
            <details <?php if($opticOptionsOpen): ?> open <?php endif; ?>>
                <summary class="small fw-semibold"><?php echo e($opticOptionsLabel); ?></summary>
                <div class="row g-2 mt-1">
                    <?php echo $__env->make('pharmacy::purchases.line-pricing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </details>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_amount">Amount</label>
            <output class="form-control text-end fw-semibold" id="trade_<?php echo e($lineIndex); ?>_amount" data-pharmacy-row-amount>0.00</output>
        </div>
    </div>
</fieldset>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\purchases\optic-line.blade.php ENDPATH**/ ?>