<?php
    $savedDiscountInput = $savedLine?->discount_calculation ?? 'amount';
    $savedDiscount = match ($savedDiscountInput) {
        'percent' => $savedLine?->discount_percent ?? '0',
        'unit' => moneyDecimal($savedLine?->discount_unit_amount ?? 0),
        default => moneyDecimal($savedLine?->discount ?? 0),
    };
    $discountInput = $itemFields['discount'] && $hasOldLines ? ($lineValues['discount_input'] ?? 'amount') : $savedDiscountInput;
    $discountInput = in_array($discountInput, ['percent', 'unit', 'amount'], true) ? $discountInput : 'amount';
    $discountValue = $itemFields['discount'] && $hasOldLines ? ($lineValues['discount'] ?? '')
        : ($savedLine === null && $itemFields['discount'] ? '' : $savedDiscount);

    $savedVatInput = $savedLine?->vat_calculation ?? 'amount';
    $vatInput = $itemFields['vat'] && $hasOldLines ? ($lineValues['vat_input'] ?? 'percent')
        : ($savedLine === null ? 'percent' : $savedVatInput);
    $vatInput = in_array($vatInput, ['percent', 'unit', 'amount'], true) ? $vatInput : 'percent';
    $vatMode = $savedLine?->vat_mode ?? $purchaseVatMode;
    $vatPercent = $savedLine?->vat_percent ?? $purchaseVatPercent;
    $vatAmount = moneyDecimal($savedVatInput === 'unit'
        ? ($savedLine?->vat_unit_amount ?? 0) : ($savedLine?->vat ?? 0));
    if ($itemFields['vat'] && $hasOldLines) {
        $vatMode = array_key_exists('vat_mode', $lineValues) ? $lineValues['vat_mode'] : $purchaseVatMode;
        $vatPercent = array_key_exists('vat_percent', $lineValues) ? $lineValues['vat_percent'] : $purchaseVatPercent;
        $vatAmount = $lineValues['vat_amount'] ?? '';
    }
    if (! $itemFields['vat'] && $savedLine === null) {
        $vatMode = 'none';
        $vatPercent = '0';
    }
?>

<fieldset class="col-sm-6 col-lg-4 col-xl-2" <?php if (! ($itemFields['discount'])): ?> disabled hidden data-pharmacy-hidden-item-field="discount" <?php endif; ?>>
    <legend class="visually-hidden">Discount</legend>
    <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_discount">Discount</label>

    <label class="visually-hidden" for="trade_<?php echo e($lineIndex); ?>_discount_input">Discount calculation for <?php echo e($variant?->product?->name ?? 'product'); ?></label>
    <div class="input-group">
        <select class="<?php echo e(inputClass('lines.'.$lineIndex.'.discount_input', 'form-select')); ?>" id="trade_<?php echo e($lineIndex); ?>_discount_input" name="lines[<?php echo e($lineIndex); ?>][discount_input]" <?php echo e(inputAttrs('lines.'.$lineIndex.'.discount_input', 'trade_'.$lineIndex.'_discount_input')); ?>>
            <option value="percent" <?php if($discountInput === 'percent'): echo 'selected'; endif; ?>>%</option>
            <option value="unit" <?php if($discountInput === 'unit'): echo 'selected'; endif; ?>><?php echo e($discountUnitLabel); ?></option>
            <option value="amount" <?php if($discountInput === 'amount'): echo 'selected'; endif; ?>>Total</option>
        </select>
        <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.discount', 'form-control text-end')); ?>" id="trade_<?php echo e($lineIndex); ?>_discount" name="lines[<?php echo e($lineIndex); ?>][discount]" type="number" inputmode="decimal" min="0" step="0.01" placeholder="0" value="<?php echo e($discountValue); ?>"
            <?php if($savedLine !== null && $savedDiscountInput === 'unit'): ?>
                data-original-unit-factor="<?php echo e($savedLine->factor); ?>"
                data-original-unit-amount="<?php echo e(moneyDecimal($savedLine->discount_unit_amount ?? 0)); ?>"
            <?php endif; ?>
            <?php echo e(inputAttrs('lines.'.$lineIndex.'.discount', 'trade_'.$lineIndex.'_discount')); ?>>
    </div>
    <?php $__errorArgs = ['lines.'.$lineIndex.'.discount_input'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_discount_input-error"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <?php $__errorArgs = ['lines.'.$lineIndex.'.discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_discount-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</fieldset>

<?php if (! ($itemFields['vat'])): ?>
    <fieldset disabled hidden data-pharmacy-hidden-item-field="vat">
<?php endif; ?>
<div class="col-sm-6 col-lg-4 col-xl-2">
    <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_vat_mode">Price VAT</label>
    <select class="<?php echo e(inputClass('lines.'.$lineIndex.'.vat_mode', 'form-select')); ?>" id="trade_<?php echo e($lineIndex); ?>_vat_mode" name="lines[<?php echo e($lineIndex); ?>][vat_mode]" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.vat_mode', 'trade_'.$lineIndex.'_vat_mode')); ?>>
        <option value="inclusive" <?php if($vatMode === 'inclusive'): echo 'selected'; endif; ?>>Included</option>
        <option value="exclusive" <?php if($vatMode === 'exclusive'): echo 'selected'; endif; ?>>Add VAT</option>
        <option value="none" <?php if($vatMode === 'none'): echo 'selected'; endif; ?>>No VAT</option>
    </select>
    <?php $__errorArgs = ['lines.'.$lineIndex.'.vat_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_vat_mode-error"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<div class="col-sm-6 col-lg-4 col-xl-2">
    <label class="visually-hidden" for="trade_<?php echo e($lineIndex); ?>_vat_input">VAT calculation for <?php echo e($variant?->product?->name ?? 'product'); ?></label>
    <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_vat_percent">VAT</label>
    <label class="visually-hidden" for="trade_<?php echo e($lineIndex); ?>_vat_amount">VAT amount per selected unit or whole line for <?php echo e($variant?->product?->name ?? 'product'); ?></label>
    <div class="input-group">
        <select class="<?php echo e(inputClass('lines.'.$lineIndex.'.vat_input', 'form-select')); ?>" id="trade_<?php echo e($lineIndex); ?>_vat_input" name="lines[<?php echo e($lineIndex); ?>][vat_input]" <?php echo e(inputAttrs('lines.'.$lineIndex.'.vat_input', 'trade_'.$lineIndex.'_vat_input')); ?>>
            <option value="percent" <?php if($vatInput === 'percent'): echo 'selected'; endif; ?>>%</option>
            <option value="unit" <?php if($vatInput === 'unit'): echo 'selected'; endif; ?>><?php echo e($discountUnitLabel); ?></option>
            <option value="amount" <?php if($vatInput === 'amount'): echo 'selected'; endif; ?>>Total</option>
        </select>
        <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.vat_percent', 'form-control text-end')); ?>" id="trade_<?php echo e($lineIndex); ?>_vat_percent" name="lines[<?php echo e($lineIndex); ?>][vat_percent]" type="number" inputmode="decimal" min="0" max="100" step="0.01" placeholder="0" value="<?php echo e($vatPercent == 0 ? '' : $vatPercent); ?>" <?php if($vatInput !== 'percent'): ?> hidden disabled <?php endif; ?> <?php echo e(inputAttrs('lines.'.$lineIndex.'.vat_percent', 'trade_'.$lineIndex.'_vat_percent')); ?>>
        <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.vat_amount', 'form-control text-end')); ?>" id="trade_<?php echo e($lineIndex); ?>_vat_amount" name="lines[<?php echo e($lineIndex); ?>][vat_amount]" type="number" inputmode="decimal" min="0" step="0.01" placeholder="0" value="<?php echo e($vatInput !== 'percent' || $vatAmount != 0 ? $vatAmount : ''); ?>"
            <?php if($savedLine !== null && $savedVatInput === 'unit'): ?>
                data-original-unit-factor="<?php echo e($savedLine->factor); ?>"
                data-original-unit-amount="<?php echo e(moneyDecimal($savedLine->vat_unit_amount ?? 0)); ?>"
            <?php endif; ?>
            <?php if($vatInput === 'percent'): ?> hidden disabled <?php else: ?> required <?php endif; ?> <?php echo e(inputAttrs('lines.'.$lineIndex.'.vat_amount', 'trade_'.$lineIndex.'_vat_amount')); ?>>
    </div>
    <?php $__errorArgs = ['lines.'.$lineIndex.'.vat_input'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_vat_input-error"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <?php $__errorArgs = ['lines.'.$lineIndex.'.vat_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_vat_percent-error"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <?php $__errorArgs = ['lines.'.$lineIndex.'.vat_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="trade_<?php echo e($lineIndex); ?>_vat_amount-error"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<div class="col-sm-6 col-lg-4 col-xl-2">
    <label class="form-label" for="trade_<?php echo e($lineIndex); ?>_vat_total">VAT total</label>
    <output class="form-control text-end" id="trade_<?php echo e($lineIndex); ?>_vat_total" data-pharmacy-line-vat-total>0.00</output>
</div>
<?php if (! ($itemFields['vat'])): ?>
    </fieldset>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\purchases\line-pricing.blade.php ENDPATH**/ ?>