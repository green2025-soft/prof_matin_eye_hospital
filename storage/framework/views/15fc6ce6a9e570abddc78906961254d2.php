

<?php $pageTitle = 'Create Medicine/Product' ?>

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('pharmacy::products.navigation', ['selectedProduct' => $product->exists ? $product : null, 'productId' => $product->exists ? $product->id : null, 'productType' => $defaultType, 'activeSection' => 'details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="row g-3 align-items-start">
        <div class="<?php echo e($canViewSetup ? 'col-xl-9' : 'col-12'); ?>">
            <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST" data-pharmacy-product-form>
                <?php echo csrf_field(); ?>
                <input id="type" name="type" type="hidden" value="<?php echo e($defaultType); ?>">
                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="type-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php if($formMethod !== 'POST'): ?>
                    <?php echo method_field($formMethod); ?>
                <?php endif; ?>

                <section class="app-form-section" aria-labelledby="product-details-heading">
                    <h2 class="h6 mb-3" id="product-details-heading"><i class="fa-solid fa-box-open text-body-secondary me-2" aria-hidden="true"></i>Product details</h2>
                    <div class="row g-3">
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $product)); ?>" maxlength="255" required autofocus <?php echo e(inputAttrs('name')); ?>>
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
                            <label class="form-label" for="product_sku">ID / SKU</label>
                            <input class="form-control" id="product_sku" type="text" value="<?php echo e($product->exists ? ($product->primaryVariant?->sku ?? $product->code) : ''); ?>" placeholder="Auto" readonly>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="barcode">Barcode</label>
                            <input class="<?php echo e(inputClass('barcode')); ?>" id="barcode" name="barcode" type="text"
                                value="<?php echo e(getValue('barcode', $product)); ?>" maxlength="100" autocomplete="off" autocapitalize="off" spellcheck="false"
                                <?php echo e(inputAttrs('barcode')); ?>>
                            <?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block" id="barcode-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="category_id">Category / generic</label>
                            <select class="<?php echo e(inputClass('category_id', 'form-select')); ?>" id="category_id" name="category_id"
                                data-ajax-select data-url="<?php echo e(route('pharmacy.categories.options')); ?>"
                                data-parent-select="type" data-parent-field="type"
                                data-placeholder="Select category / generic"
                                <?php echo e(inputAttrs('category_id')); ?>>
                                <option value=""></option>
                                <?php if($selectedCategory !== null): ?>
                                    <option value="<?php echo e($selectedCategory->id); ?>" selected><?php echo e($selectedCategory->name); ?></option>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="category_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6 col-xl-3" data-pharmacy-medicine-field <?php if($defaultType !== 'medicine'): ?> hidden <?php endif; ?>>
                            <label class="form-label" for="dosage_id">Dose Type</label>
                            <select class="<?php echo e(inputClass('dosage_id', 'form-select')); ?>" id="dosage_id" name="dosage_id"
                                data-ajax-select data-url="<?php echo e(route('pharmacy.dosages.options')); ?>"
                                data-placeholder="Select dose type"
                                <?php echo e(inputAttrs('dosage_id')); ?>>
                                <option value=""></option>
                                <?php if($selectedDosage !== null): ?>
                                    <option value="<?php echo e($selectedDosage->id); ?>" selected><?php echo e($selectedDosage->name); ?></option>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['dosage_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="dosage_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6 col-xl-3" data-pharmacy-medicine-field <?php if($defaultType !== 'medicine'): ?> hidden <?php endif; ?>>
                            <label class="form-label" for="strength">Strength</label>
                            <input class="<?php echo e(inputClass('strength')); ?>" id="strength" name="strength" type="text" value="<?php echo e(getValue('strength', $product)); ?>" maxlength="255"  <?php echo e(inputAttrs('strength')); ?>>
                            <?php $__errorArgs = ['strength'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="strength-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="supplier_id">Supplier</label>
                            <select class="<?php echo e(inputClass('supplier_id', 'form-select')); ?>" id="supplier_id" name="supplier_id"
                                data-ajax-select data-url="<?php echo e(route('pharmacy.suppliers.options')); ?>"
                                data-parent-select="type" data-parent-field="type"
                                data-placeholder="Select supplier"
                                <?php echo e(inputAttrs('supplier_id')); ?>>
                                <option value=""></option>
                                <?php if($selectedSupplier !== null): ?>
                                    <option value="<?php echo e($selectedSupplier->id); ?>" selected><?php echo e($selectedSupplier->name); ?></option>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="supplier_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="shelf_id">Shelf Location</label>
                            <select class="<?php echo e(inputClass('shelf_id', 'form-select')); ?>" id="shelf_id" name="shelf_id"
                                data-ajax-select data-url="<?php echo e(route('pharmacy.shelf.options')); ?>"
                                data-parent-select="type" data-parent-field="type"
                                data-placeholder="Select shelf location"
                                <?php echo e(inputAttrs('shelf_id')); ?>>
                                <option value=""></option>
                                <?php if($selectedShelf !== null): ?>
                                    <option value="<?php echo e($selectedShelf->id); ?>" selected><?php echo e($selectedShelf->name); ?></option>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['shelf_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="shelf_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </section>

                <section class="app-form-section mt-4" aria-labelledby="product-prices-heading">
                    <h2 class="h6 mb-3" id="product-prices-heading"><i class="fa-solid fa-tags text-body-secondary me-2" aria-hidden="true"></i>Prices & stock</h2>
                    <div class="row g-3">
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="purchase_price">Purchase price <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="<?php echo e(inputClass('purchase_price')); ?>" id="purchase_price" name="purchase_price" type="text"
                                value="<?php echo e(getValue('purchase_price', $product, '0.00')); ?>" maxlength="13" required inputmode="decimal"
                                <?php echo e(inputAttrs('purchase_price')); ?>>
                            <?php $__errorArgs = ['purchase_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="purchase_price-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="sale_price">Sale price <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="<?php echo e(inputClass('sale_price')); ?>" id="sale_price" name="sale_price" type="text"
                                value="<?php echo e(getValue('sale_price', $product, '0.00')); ?>" maxlength="13" required inputmode="decimal"
                                <?php echo e(inputAttrs('sale_price')); ?>>
                            <?php $__errorArgs = ['sale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sale_price-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="reorder_level">Re-order Level (Pcs) <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="<?php echo e(inputClass('reorder_level')); ?>" id="reorder_level" name="reorder_level" type="number"
                                value="<?php echo e(getValue('reorder_level', $product, 0)); ?>" required min="0" max="1000000000" step="1" inputmode="numeric"
                                <?php echo e(inputAttrs('reorder_level')); ?>>
                            <?php $__errorArgs = ['reorder_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="reorder_level-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </section>

                <?php echo $__env->make('core::partials.form-actions', ['model' => $product, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </form>
        </div>

        <?php if($canViewSetup): ?>
            <aside class="col-xl-3">
                <?php echo $__env->make('pharmacy::products.stock-setup', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </aside>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\products\form.blade.php ENDPATH**/ ?>