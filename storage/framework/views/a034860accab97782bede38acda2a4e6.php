<?php
    $productType = request()->attributes->get('pharmacy_product_type', 'medicine');
?>


<?php $__env->startSection('title', ucfirst($productType).' Sale'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $savedLines = $savedLines->keyBy('id');
        $itemRows = $hasOldLines ? $lineInputs : $savedLines->take(100);

        // Existing invoices keep their saved VAT settings when edited.
        if ($entry->exists) {
            $pharmacySettings['vat_enabled']    = $entry->vat_mode !== 'none';
            $pharmacySettings['vat_mode']       = $entry->vat_mode;
            $pharmacySettings['vat_percent']    = $entry->vat_percent;
        }
        $showDiscountColumn = $itemFields['discount'] || $entry->exists;
        $showVatColumn      = $pharmacySettings['vat_enabled'];
        $lineColumnCount    = 5 + (int) $showDiscountColumn + (int) $showVatColumn;

        $saleOptions = [
            'type'              => 'sale',
            'revisionStock'     => $revisionStock,
            'allowWithoutStock' => (bool) $pharmacySettings[$productType.'_sale_without_stock_enabled'],
            'vatMode'           => $showVatColumn ? $pharmacySettings['vat_mode'] : 'none',
            'vatPercent'        => $pharmacySettings['vat_percent'],
        ];
    ?>

    <form
        class="app-card p-3 app-pharmacy-entry app-pharmacy-trade-layout"
        action="<?php echo e($formAction); ?>"
        method="POST"
    >
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>
        <input id="pharmacy_product_type" name="product_type" type="hidden" value="<?php echo e($productType); ?>">
        <script type="application/json" id="pharmacy-sale-options"><?php echo htmlJson($saleOptions); ?></script>

        <div class="row g-3 align-items-start">
            <div class="col-12 col-xl-9 app-pharmacy-trade-main">
                <section class="app-form-section" aria-labelledby="sale-invoice-heading">
                    
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label" for="pharmacy_customer">Customer <span class="text-danger" aria-hidden="true">*</span></label>
                            <div class="input-group flex-nowrap app-pharmacy-customer-group">
                                <select class="<?php echo e(inputClass('customer_id', 'form-select')); ?>" id="pharmacy_customer" name="customer_id" data-ajax-select data-url="<?php echo e(route('pharmacy.customers.options', ['new_sale' => 1])); ?>" data-label-field="text" data-placeholder="Patient ID, name or mobile" required <?php echo e(inputAttrs('customer_id', 'pharmacy_customer')); ?>>
                                    <?php if($selectedCustomer): ?>
                                        <option value="<?php echo e($selectedCustomer['id']); ?>" selected><?php echo e($selectedCustomer['text']); ?></option>
                                    <?php endif; ?>
                                </select>
                                <?php if($canCreateCustomer): ?>
                                    <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="modal"
                                        data-bs-target="#pharmacy-customer-modal" aria-label="Add customer" title="Add customer">
                                        <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <?php $__errorArgs = ['customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block" id="pharmacy_customer-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-6 col-md-3">
                            <?php echo $__env->make('pharmacy::entries.partials.date-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <div class="col-6 col-md-3">
                            <?php echo $__env->make('pharmacy::entries.partials.payment-type-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                </section>

                <?php echo $__env->make('pharmacy::sales.items', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <aside class="col-12 col-xl-3 app-pharmacy-trade-sidebar" aria-label="Payment and summary">
                <?php echo $__env->make('pharmacy::sales.payment-summary', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('pharmacy::entries.partials.form-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </aside>
        </div>
    </form>

    <?php if($canCreateCustomer): ?>
        <?php echo $__env->make('pharmacy::customers.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\sales\form.blade.php ENDPATH**/ ?>