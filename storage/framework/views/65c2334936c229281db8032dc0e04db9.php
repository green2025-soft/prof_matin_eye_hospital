<div class="border rounded p-3 mb-2" data-pharmacy-line data-index="<?php echo e($lineIndex); ?>">
    <fieldset class="border-0 p-0 m-0">
        <legend class="visually-hidden">Item <?php echo e($lineIndex); ?></legend>
        <div class="row g-2 align-items-end">
            <?php echo $__env->make('pharmacy::entries.partials.stock-product-fields', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="col-6 col-lg-2">
                <button class="btn btn-outline-danger w-100" type="button" data-pharmacy-remove-line>
                    Remove
                </button>
            </div>
            <?php echo $__env->make('pharmacy::entries.partials.stock-lot-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </fieldset>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\wastage\item-line.blade.php ENDPATH**/ ?>