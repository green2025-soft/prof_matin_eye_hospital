<div class="col-lg-4">
    <label class="form-label small" for="line_<?php echo e($lineIndex); ?>_variant">Product / SKU</label>
    <select class="<?php echo e(inputClass('lines.'.$lineIndex.'.variant_id', 'form-select')); ?>" id="line_<?php echo e($lineIndex); ?>_variant" name="lines[<?php echo e($lineIndex); ?>][variant_id]" data-ajax-select data-url="<?php echo e(route('pharmacy.items.options')); ?>" data-parent-select="pharmacy_product_type" data-parent-field="product_type" data-placeholder="Search product or SKU" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.variant_id', 'line_'.$lineIndex.'_variant')); ?>>
        <option value=""></option>
        <?php if($variant): ?>
            <option value="<?php echo e($variant->id); ?>" data-purchase-price="<?php echo e($variant->product->purchase_price); ?>" selected>
                <?php echo e($variant->product->name); ?> / <?php echo e($variant->sku); ?>

            </option>
        <?php endif; ?>
    </select>
    <?php $__errorArgs = ['lines.'.$lineIndex.'.variant_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="line_<?php echo e($lineIndex); ?>_variant-error"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<div class="col-6 col-lg-2">
    <label class="form-label small" for="line_<?php echo e($lineIndex); ?>_unit">Unit</label>
    <select class="<?php echo e(inputClass('lines.'.$lineIndex.'.product_unit_id', 'form-select')); ?>" id="line_<?php echo e($lineIndex); ?>_unit" name="lines[<?php echo e($lineIndex); ?>][product_unit_id]" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.product_unit_id', 'line_'.$lineIndex.'_unit')); ?>>
        <option value="">Unit</option>
        <?php $__currentLoopData = $variant?->product->units ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($unit->id); ?>" data-factor="<?php echo e($unit->factor); ?>" <?php if((string) ($lineValues['product_unit_id'] ?? '') === (string) $unit->id): echo 'selected'; endif; ?>>
                <?php echo e($unit->unit->symbol ?: $unit->unit->name); ?> × <?php echo e($unit->factor); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <?php $__errorArgs = ['lines.'.$lineIndex.'.product_unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="line_<?php echo e($lineIndex); ?>_unit-error"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<div class="col-6 col-lg-2">
    <label class="form-label small" for="line_<?php echo e($lineIndex); ?>_quantity">Quantity</label>
    <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.quantity', 'form-control')); ?>" id="line_<?php echo e($lineIndex); ?>_quantity" name="lines[<?php echo e($lineIndex); ?>][quantity]" type="number" min="1" step="1" value="<?php echo e($lineValues['quantity'] ?? 1); ?>" required <?php echo e(inputAttrs('lines.'.$lineIndex.'.quantity', 'line_'.$lineIndex.'_quantity')); ?>>
    <?php $__errorArgs = ['lines.'.$lineIndex.'.quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="line_<?php echo e($lineIndex); ?>_quantity-error"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\entries\partials\stock-product-fields.blade.php ENDPATH**/ ?>