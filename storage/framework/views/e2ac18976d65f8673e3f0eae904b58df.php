

<?php $pageTitle = 'Create Medicine/Product Variant' ?>
<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('pharmacy::products.navigation', ['selectedProduct' => $selectedProduct, 'productId' => $selectedProduct?->id, 'productType' => $selectedProduct?->type ?? 'medicine', 'activeSection' => 'variants'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST" data-pharmacy-variant-form data-product-type="<?php echo e($selectedProduct?->type ?? ''); ?>">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <section class="app-form-section" aria-labelledby="variant-details-heading">
            <h2 class="h6 mb-3" id="variant-details-heading"><i class="fa-solid fa-barcode me-2 text-body-secondary" aria-hidden="true"></i>SKU details</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="sku">SKU <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('sku')); ?>" id="sku" name="sku" type="text" value="<?php echo e(getValue('sku', $product_variant)); ?>" maxlength="100" required autofocus <?php echo e(inputAttrs('sku')); ?>>
                    <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sku-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="name">Variant name</label>
                    <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $product_variant)); ?>" maxlength="255" <?php echo e(inputAttrs('name')); ?>>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="product_id">Product <span class="text-danger" aria-hidden="true">*</span></label>
                    <?php if($product_variant->exists): ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product_variant->product_id); ?>">
                    <?php endif; ?>
                    <select class="<?php echo e(inputClass('product_id', 'form-select')); ?>" id="product_id" name="product_id"
                        data-ajax-select data-url="<?php echo e(route('pharmacy.products.options')); ?>"
                        data-placeholder="Select product" required <?php if($product_variant->exists): echo 'disabled'; endif; ?>
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
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="active">Status <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="<?php echo e(inputClass('active', 'form-select')); ?>" id="active" name="active" required <?php echo e(inputAttrs('active')); ?>>
                        <option value="1" <?php if((int) getValue('active', $product_variant, 1) === 1): echo 'selected'; endif; ?>>Active</option>
                        <option value="0" <?php if((int) getValue('active', $product_variant, 1) === 0): echo 'selected'; endif; ?>>Inactive</option>
                    </select>
                    <?php $__errorArgs = ['active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="active-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>

        <section class="app-form-section mt-4" aria-labelledby="variant-optic-heading" data-pharmacy-optic-field <?php if($selectedProduct?->type !== 'optic'): ?> hidden <?php endif; ?>>
            <h2 class="h6 mb-3" id="variant-optic-heading"><i class="fa-solid fa-glasses me-2 text-body-secondary" aria-hidden="true"></i>Frame / lens details</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="color">Color</label>
                    <input class="<?php echo e(inputClass('color')); ?>" id="color" name="color" type="text" value="<?php echo e(getValue('color', $product_variant)); ?>" maxlength="100" <?php echo e(inputAttrs('color')); ?>>
                    <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="color-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="size">Size</label>
                    <input class="<?php echo e(inputClass('size')); ?>" id="size" name="size" type="text" value="<?php echo e(getValue('size', $product_variant)); ?>" maxlength="100" <?php echo e(inputAttrs('size')); ?>>
                    <?php $__errorArgs = ['size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="size-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="sph">SPH</label>
                    <input class="<?php echo e(inputClass('sph')); ?>" id="sph" name="sph" type="number" value="<?php echo e(getValue('sph', $product_variant)); ?>" inputmode="decimal" min="-9999.99" max="9999.99" step="0.01" <?php echo e(inputAttrs('sph')); ?>>
                    <?php $__errorArgs = ['sph'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sph-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="cyl">CYL</label>
                    <input class="<?php echo e(inputClass('cyl')); ?>" id="cyl" name="cyl" type="number" value="<?php echo e(getValue('cyl', $product_variant)); ?>" inputmode="decimal" min="-9999.99" max="9999.99" step="0.01" <?php echo e(inputAttrs('cyl')); ?>>
                    <?php $__errorArgs = ['cyl'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="cyl-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <p class="form-text mb-0 mt-2">Leave unknown powers blank; zero means zero power.</p>
        </section>
        <?php echo $__env->make('core::partials.form-actions', ['model' => $product_variant, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\variants\form.blade.php ENDPATH**/ ?>