
<?php $pageTitle = 'Create Medicine/Product Unit' ?>
<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('pharmacy::products.navigation', ['selectedProduct' => $selectedProduct, 'productId' => $selectedProduct?->id, 'productType' => $selectedProduct?->type ?? 'medicine', 'activeSection' => 'units'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <section class="app-form-section" aria-labelledby="unit-conversion-heading">
            <h2 class="h6 mb-3" id="unit-conversion-heading"><i class="fa-solid fa-boxes-stacked me-2 text-body-secondary" aria-hidden="true"></i>Unit conversion</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-4">
                    <label class="form-label" for="product_id">Product <span class="text-danger" aria-hidden="true">*</span></label>
                    <?php if($product_unit->exists): ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product_unit->product_id); ?>">
                    <?php endif; ?>
                    <select class="<?php echo e(inputClass('product_id', 'form-select')); ?>" id="product_id" name="product_id"
                        data-ajax-select data-url="<?php echo e(route('pharmacy.products.options')); ?>"
                        data-placeholder="Select product" required <?php if($product_unit->exists): echo 'disabled'; endif; ?>
                        <?php echo e(inputAttrs('product_id')); ?>>
                        <option value=""></option>
                        <?php if($selectedProduct !== null): ?>
                            <option value="<?php echo e($selectedProduct->id); ?>" selected><?php echo e($selectedProduct->name); ?></option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="product_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <label class="form-label" for="unit_id">Unit <span class="text-danger" aria-hidden="true">*</span></label>
                    <?php if($product_unit->exists): ?>
                        <input type="hidden" name="unit_id" value="<?php echo e($product_unit->unit_id); ?>">
                    <?php endif; ?>
                    <select class="<?php echo e(inputClass('unit_id', 'form-select')); ?>" id="unit_id" name="unit_id"
                        data-ajax-select data-url="<?php echo e(route('pharmacy.units.options')); ?>"
                        data-placeholder="Select unit" required <?php if($product_unit->exists): echo 'disabled'; endif; ?>
                        <?php echo e(inputAttrs('unit_id')); ?>>
                        <option value=""></option>
                        <?php if($selectedUnit !== null): ?>
                            <option value="<?php echo e($selectedUnit->id); ?>" selected><?php echo e($selectedUnit->name); ?></option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="unit_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <label class="form-label" for="factor">Pieces per unit <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('factor')); ?>" id="factor" name="factor" type="number"
                        value="<?php echo e($unitLocked ? $product_unit->factor : getValue('factor', $product_unit, 1)); ?>" min="1" max="1000000" step="1" inputmode="numeric" required <?php if($unitLocked): echo 'readonly'; endif; ?>
                        <?php echo e(inputAttrs('factor', 'factor', 'factor-help')); ?>>
                    <div class="form-text" id="factor-help"><?php echo e($unitLocked ? 'Used conversions are locked.' : 'Piece: 1 · Strip of 10: 10.'); ?></div>
                    <?php $__errorArgs = ['factor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="factor-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>
        <?php echo $__env->make('core::partials.form-actions', ['model' => $product_unit, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\product-units\form.blade.php ENDPATH**/ ?>