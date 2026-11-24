<div class="border rounded p-3 mb-2" data-pharmacy-line data-index="<?php echo e($lineIndex); ?>">
    <fieldset class="border-0 p-0 m-0">
        <legend class="visually-hidden">Item <?php echo e($lineIndex); ?></legend>
        <div class="row g-2 align-items-end">
            <?php echo $__env->make('pharmacy::entries.partials.stock-product-fields', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="col-6 col-lg-2">
                <label class="form-label small" for="line_<?php echo e($lineIndex); ?>_price">Unit price</label>
                <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.unit_price', 'form-control')); ?>" id="line_<?php echo e($lineIndex); ?>_price" name="lines[<?php echo e($lineIndex); ?>][unit_price]" type="number" min="0" step="0.01" value="<?php echo e($lineValues['unit_price'] ?? '0.00'); ?>" <?php echo e(inputAttrs('lines.'.$lineIndex.'.unit_price', 'line_'.$lineIndex.'_price')); ?>>
                <?php $__errorArgs = ['lines.'.$lineIndex.'.unit_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="line_<?php echo e($lineIndex); ?>_price-error">
                        <?php echo e($message); ?>

                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-6 col-lg-2">
                <button class="btn btn-outline-danger w-100" type="button" data-pharmacy-remove-line>
                    Remove
                </button>
            </div>
            <?php echo $__env->make('pharmacy::entries.partials.stock-receiving-fields', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </fieldset>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\opening-stock\item-line.blade.php ENDPATH**/ ?>