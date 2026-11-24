<?php
    $productType = request()->attributes->get('pharmacy_product_type', 'medicine');
?>


<?php $__env->startSection('title', ucfirst($productType).' Purchase'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $savedLines = $entry->exists ? $entry->lines->keyBy('id') : collect();
        $defaultVatMode = $pharmacySettings['vat_enabled'] ? $pharmacySettings['vat_mode'] : ($pharmacyPurchase ? 'exclusive' : 'none');
        $purchaseVatMode = getValue('vat_mode', null, $defaultVatMode);
        $purchaseVatMode = in_array($purchaseVatMode, ['none', 'inclusive', 'exclusive'], true) ? $purchaseVatMode : 'exclusive';
        $purchaseVatPercent = $pharmacySettings['vat_enabled'] ? $pharmacySettings['vat_percent'] : '0.00';
        $showPurchaseSalePrice = $canUpdateProductPrices && $itemFields['sale_price'];
        $editingPurchase = $formMethod !== 'POST';
        $showDiscountColumn = $itemFields['discount'] || $editingPurchase;
        $showVatColumn = $itemFields['vat'] || $editingPurchase;
        $showExpiryColumn = $pharmacyPurchase && ($itemFields['expiry'] || $editingPurchase);
        $lineColumnCount = 5 + (int) $showDiscountColumn + (int) $showVatColumn + (int) $showExpiryColumn;
    ?>

    <form
        class="app-card p-3 app-pharmacy-entry app-pharmacy-trade-layout"
        action="<?php echo e($formAction); ?>"
        method="POST"
        data-pharmacy-entry
        data-pharmacy-trade
        data-type="purchase"
    >
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>
        <input id="pharmacy_product_type" name="product_type" type="hidden" value="<?php echo e($productType); ?>">

        <div class="row g-3 align-items-start">
            <div class="col-12 col-xl-9 app-pharmacy-trade-main">
                <section class="app-form-section" aria-labelledby="purchase-invoice-heading">
                    <h2 class="app-form-section-title" id="purchase-invoice-heading">
                        <span class="app-form-section-icon"><i class="fa-solid fa-file-invoice" aria-hidden="true"></i></span>
                        Purchase Details
                    </h2>
                    <div class="row g-2">
                        <div class="col-md-6 col-xl-4">
                            <label class="form-label" for="pharmacy_supplier">Supplier <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="<?php echo e(inputClass('supplier_id', 'form-select')); ?>" id="pharmacy_supplier" name="supplier_id" data-ajax-select data-url="<?php echo e(route('pharmacy.suppliers.options', ['type' => $productType])); ?>" data-placeholder="Select supplier" required <?php echo e(inputAttrs('supplier_id', 'pharmacy_supplier')); ?>>
                                <option value=""></option>
                                <?php if($selectedSupplier): ?>
                                    <option value="<?php echo e($selectedSupplier->id); ?>" selected><?php echo e($selectedSupplier->name); ?></option>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block" id="pharmacy_supplier-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <label class="form-label" for="supplier_invoice_no">Supplier Invoice No.</label>
                            <input class="<?php echo e(inputClass('supplier_invoice_no')); ?>" id="supplier_invoice_no" name="supplier_invoice_no" type="text" maxlength="80" autocomplete="off" value="<?php echo e(getValue('supplier_invoice_no', $entry)); ?>" <?php echo e(inputAttrs('supplier_invoice_no')); ?>>
                            <?php $__errorArgs = ['supplier_invoice_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block" id="supplier_invoice_no-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-6 col-xl-3">
                            <?php echo $__env->make('pharmacy::entries.partials.date-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <div class="col-6 col-xl-2">
                            <?php echo $__env->make('pharmacy::entries.partials.payment-type-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                </section>

                <?php echo $__env->make('pharmacy::purchases.items', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <aside class="col-12 col-xl-3 app-pharmacy-trade-sidebar" aria-label="Payment and summary">
                <?php echo $__env->make('pharmacy::purchases.payment-summary', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('pharmacy::entries.partials.form-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </aside>
        </div>
    </form>

    <?php if($pharmacyPurchase): ?>
        <?php echo $__env->make('pharmacy::purchases.pack-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\purchases\form.blade.php ENDPATH**/ ?>