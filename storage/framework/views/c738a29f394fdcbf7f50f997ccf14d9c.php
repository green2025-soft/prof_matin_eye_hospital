<?php
    $quantityHelpId = 'trade_'.$lineIndex.'_quantity-unit';
    $discountUnitLabel = 'Unit';
    $originalLineId = $lineValues['original_line_id'] ?? $savedLine?->id;
    $selectedProfile = $variant?->product?->units->firstWhere('id', $lineValues['product_unit_id'] ?? null);
    $unitId = $lineValues['unit_id'] ?? ($hasOldLines ? $selectedProfile?->unit_id : $savedLine?->productUnit?->unit_id);
    $unitFactor = $lineValues['unit_factor'] ?? ($hasOldLines ? $selectedProfile?->factor : $savedLine?->factor);
    $latestPurchaseItem = $variant?->latestPurchaseItem;
    $quantity = $hasOldLines ? ($lineValues['quantity'] ?? '') : ($savedLine?->quantity ?? '');
    $unitPrice = $hasOldLines ? ($lineValues['unit_price'] ?? '') : ($savedLine === null ? '' : moneyDecimal($savedLine->unit_price));
    $expiryDate = $itemFields['expiry'] && $hasOldLines ? ($lineValues['expiry_date'] ?? '') : $savedLine?->expiry_date?->toDateString();
?>

<fieldset
    disabled
    data-pharmacy-line
    data-index="<?php echo e($lineIndex); ?>"
    data-product-id="<?php echo e($variant?->product_id); ?>"
    data-purchase-price="<?php echo e($variant?->product?->purchase_price ?? '0.00'); ?>"
    data-last-purchase-rate="<?php echo e($latestPurchaseItem === null ? '' : moneyDecimal($latestPurchaseItem->unit_price)); ?>"
    data-last-purchase-factor="<?php echo e($latestPurchaseItem?->factor ?? ''); ?>"
    data-preserve-rate-on-edit="<?php echo e($hasOldLines ? '1' : '0'); ?>"
    data-unit-profiles="<?php echo e($variant ? $variant->product->units->map(fn ($unit) => ['id' => $unit->id, 'unit_id' => $unit->unit_id, 'factor' => $unit->factor])->toJson() : '[]'); ?>"
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
            <?php
                $unitErrorField = $errors->has('lines.'.$lineIndex.'.unit_id') ? 'unit_id' : 'unit_factor';
                $unitErrorId = 'trade_'.$lineIndex.($unitErrorField === 'unit_id' ? '_unit' : '_factor');
            ?>
            <div class="input-group">
                <select class="<?php echo e(inputClass('lines.'.$lineIndex.'.'.$unitErrorField, 'form-select')); ?>" id="trade_<?php echo e($lineIndex); ?>_unit" data-pharmacy-purchase-unit aria-keyshortcuts="F4 Alt+B Alt+P" title="Unit (F4, Alt+B Box, Alt+P Pcs)" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.'.$unitErrorField, $unitErrorId)); ?>>
                    <option value="">Unit</option>
                    <?php $__currentLoopData = $purchaseUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="unit:<?php echo e($unit->id); ?>" data-unit-id="<?php echo e($unit->id); ?>" data-symbol="<?php echo e($unit->symbol); ?>" <?php if((string) $unitId === (string) $unit->id): echo 'selected'; endif; ?>><?php echo e($unit->symbol ?: $unit->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="btn btn-outline-secondary" type="button" data-pharmacy-edit-unit disabled aria-label="Edit pack size" aria-keyshortcuts="Alt+U" title="Edit pack size">
                    <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                </button>
            </div>
            <input name="lines[<?php echo e($lineIndex); ?>][unit_id]" type="hidden" value="<?php echo e($unitId); ?>">
            <input name="lines[<?php echo e($lineIndex); ?>][unit_factor]" type="hidden" value="<?php echo e($unitFactor); ?>">
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
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_quantity">Qty <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="input-group">
                <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.quantity', 'form-control text-end')); ?>" id="trade_<?php echo e($lineIndex); ?>_quantity" name="lines[<?php echo e($lineIndex); ?>][quantity]" type="number" inputmode="numeric" min="1" max="1000000000" step="1" value="<?php echo e($quantity); ?>" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.quantity', 'trade_'.$lineIndex.'_quantity', $quantityHelpId)); ?>>
                <span class="input-group-text" id="trade_<?php echo e($lineIndex); ?>_quantity-unit" data-pharmacy-quantity-unit>Pcs</span>
            </div>
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
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_base_quantity">Total Pcs</label>
            <output class="form-control text-end" id="trade_<?php echo e($lineIndex); ?>_base_quantity" data-pharmacy-base-quantity>&mdash;</output>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_price">Rate <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="input-group">
                <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.unit_price', 'form-control text-end')); ?>" id="trade_<?php echo e($lineIndex); ?>_price" name="lines[<?php echo e($lineIndex); ?>][unit_price]" type="number" inputmode="decimal" min="0" step="0.01" value="<?php echo e($unitPrice); ?>" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.unit_price', 'trade_'.$lineIndex.'_price', 'trade_'.$lineIndex.'_price-unit')); ?>>
                <span class="input-group-text" id="trade_<?php echo e($lineIndex); ?>_price-unit">/ <span class="ms-1" data-pharmacy-rate-unit>Pcs</span></span>
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
        <?php echo $__env->make('pharmacy::purchases.line-pricing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <fieldset class="col-sm-6 col-lg-4 col-xl-2 app-pharmacy-entry-expiry" <?php if (! ($itemFields['expiry'])): ?> disabled hidden data-pharmacy-hidden-item-field="expiry" <?php endif; ?>>
            <legend class="visually-hidden">Expiry</legend>
            <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_expiry">Expiry</label>
            <div class="input-group" data-datepicker>
                <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.expiry_date', 'form-control')); ?>" id="trade_<?php echo e($lineIndex); ?>_expiry" name="lines[<?php echo e($lineIndex); ?>][expiry_date]" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e($expiryDate); ?>" data-input <?php echo e(inputAttrs('lines.'.$lineIndex.'.expiry_date', 'trade_'.$lineIndex.'_expiry')); ?>>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="trade_<?php echo e($lineIndex); ?>_expiry" aria-label="Choose product expiry date" title="Choose expiry date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            <?php $__errorArgs = ['lines.'.$lineIndex.'.expiry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_expiry-error"><?php echo e($message); ?></div>
            <?php unset($message);
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

<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\purchases\medicine-line.blade.php ENDPATH**/ ?>