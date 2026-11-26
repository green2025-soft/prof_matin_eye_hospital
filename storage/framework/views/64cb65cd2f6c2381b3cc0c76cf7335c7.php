<div class="col-md-6 col-lg-4">
    <label class="form-label small" for="report_product">Medicine/Products</label>
    <select class="<?php echo e(inputClass('variant_id', 'form-select')); ?>" id="report_product" name="variant_id"
        data-ajax-select data-url="<?php echo e(route('pharmacy.reports.products', ['product_type' => $filters['product_type']])); ?>"
        data-placeholder="All Medicine/Products"
        <?php echo e(inputAttrs('variant_id', 'report_product')); ?>>
        <option value=""></option>
        <?php if($selectedProduct !== null): ?>
            <option value="<?php echo e($selectedProduct->id); ?>" selected><?php echo e($selectedProduct->product->name); ?> / <?php echo e($selectedProduct->description()); ?></option>
        <?php endif; ?>
    </select>
    <?php $__errorArgs = ['variant_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="report_product-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\reports\product-filter.blade.php ENDPATH**/ ?>