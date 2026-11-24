<div class="col-lg-5">
    <label class="form-label small" for="line_<?php echo e($lineIndex); ?>_lot">Stock lot (optional)</label>
    <select class="<?php echo e(inputClass('lines.'.$lineIndex.'.stock_lot_id', 'form-select')); ?>" id="line_<?php echo e($lineIndex); ?>_lot" name="lines[<?php echo e($lineIndex); ?>][stock_lot_id]" data-pharmacy-lot data-ajax-select data-url="<?php echo e(route('pharmacy.lots.options')); ?>" data-parent-select="line_<?php echo e($lineIndex); ?>_variant" data-parent-field="variant_id" data-placeholder="Use <?php echo e(strtoupper($pharmacySettings['stock_method'])); ?>" <?php echo e(inputAttrs('lines.'.$lineIndex.'.stock_lot_id', 'line_'.$lineIndex.'_lot')); ?>>
        <option value=""></option>
        <?php if($stockLots->has($lineValues['stock_lot_id'] ?? null)): ?>
            <option value="<?php echo e($lineValues['stock_lot_id']); ?>" selected>
                #<?php echo e($lineValues['stock_lot_id']); ?> / <?php echo e($stockLots[$lineValues['stock_lot_id']]->batch_no); ?> / Qty <?php echo e($stockLots[$lineValues['stock_lot_id']]->remaining_quantity); ?>

            </option>
        <?php endif; ?>
    </select>
    <?php $__errorArgs = ['lines.'.$lineIndex.'.stock_lot_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="line_<?php echo e($lineIndex); ?>_lot-error">
            <?php echo e($message); ?>

        </div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\entries\partials\stock-lot-field.blade.php ENDPATH**/ ?>