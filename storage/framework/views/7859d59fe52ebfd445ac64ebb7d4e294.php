<?php $__env->startSection('title', ucfirst($entry->product_type).' Purchase Invoice'); ?>

<?php $__env->startSection('content'); ?>
    <div class="app-print-hidden">
        <?php echo $__env->make('core::partials.heading', ['title' => ucfirst($entry->product_type).' '.$entry->number], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route($routePrefix.'.index', ['product_type' => $entry->product_type])); ?>">
                <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back
            </a>
            <button class="btn btn-primary btn-sm" type="button" data-print>
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print
            </button>
            <?php if($canEditPurchase && ! $entry->voided_at): ?>
                <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route($routePrefix.'.edit', [$resourceParameter => $entry, 'product_type' => $entry->product_type])); ?>">
                    <i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit
                </a>
            <?php endif; ?>
            <?php if($canDeletePurchase): ?>
                <form action="<?php echo e(route($routePrefix.'.destroy', [$resourceParameter => $entry, 'product_type' => $entry->product_type])); ?>" method="POST" data-confirm="Delete this purchase and reverse its stock and amounts?">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-outline-danger btn-sm" type="submit">
                        <i class="fa-solid fa-trash-can me-2" aria-hidden="true"></i>Delete
                    </button>
                </form>
            <?php elseif($canVoid): ?>
                <form action="<?php echo e(route($routePrefix.'.void', [$resourceParameter => $entry, 'product_type' => $entry->product_type])); ?>" method="POST" data-confirm="Void this purchase and reverse its stock and amounts?">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline-danger btn-sm" type="submit">Void</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php
        $invoiceTitle = 'Purchase Invoice';
        $outstandingMinor = $entry->voided_at ? 0 : $entry->outstandingMinor();
        $invoiceStatus = $entry->voided_at ? 'VOID' : ($outstandingMinor > 0 ? 'DUE' : 'PAID');
        $showSalePrice = $entry->lines->contains(fn ($line) => $line->sale_price_per_piece !== null);
        $itemDiscountTotal = $entry->lines->sum('discount');
        $invoiceDiscountTotal = $entry->discount - $itemDiscountTotal;
    ?>

    <article class="app-card app-invoice-document app-clinical-invoice app-pharmacy-invoice" aria-labelledby="pharmacy-invoice-title">
        <?php echo $__env->make('pharmacy::entries.partials.invoice-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="app-pharmacy-invoice-parties">
            <div>
                <h2 class="app-clinical-invoice-section-title">Supplier</h2>
                <p class="fw-semibold mb-1"><?php echo e($entry->supplier->name); ?></p>
                <?php if($entry->supplier->contact_no): ?>
                    <p class="mb-1">Contact: <?php echo e($entry->supplier->contact_no); ?></p>
                <?php endif; ?>
                <?php if($entry->supplier->address): ?>
                    <p class="app-pharmacy-invoice-note mb-0"><?php echo e($entry->supplier->address); ?></p>
                <?php endif; ?>
            </div>
            <div>

                   <?php if($entry->paymentType): ?>
                  <dl class="mb-1">
                        <div><b>Date</b> : <?php echo e($entry->entry_date->format('d-m-Y')); ?></div>
                    </dl>
                <?php endif; ?>
                <?php if($entry->paymentType): ?>
                  <dl class="mb-1">
                        <div><b>Payment Type</b> : <?php echo e($entry->paymentType->name); ?></div>
                    </dl>
                <?php endif; ?>
                <?php if($entry->supplier_invoice_no): ?>
                    <dl class="mb-0">
                        <div><b>Supplier invoice no.</b> : <?php echo e($entry->supplier_invoice_no); ?></div>
                    </dl>
                <?php endif; ?>
            </div>
        </div>

        <?php echo $__env->make('pharmacy::purchases.invoice-items', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="app-pharmacy-invoice-summary">
            <div>
                <div class="app-pharmacy-invoice-words mb-3">
                    <h2 class="app-clinical-invoice-section-title">Paid amount in words</h2>
                    <p class="mb-0"><?php echo e($paidAmountWords); ?></p>
                </div>
                <?php if($entry->notes): ?>
                    <h2 class="app-clinical-invoice-section-title">Notes</h2>
                    <p class="app-pharmacy-invoice-note"><?php echo e($entry->notes); ?></p>
                <?php endif; ?>
            </div>
            <div class="app-clinical-invoice-totals">
                <dl class="mb-0">
                    <div>
                        <dt>Subtotal</dt>
                        <dd><?php echo e(moneyDecimal($entry->subtotal)); ?></dd>
                    </div>
                    <div>
                        <dt>Item discount</dt>
                        <dd><?php echo e(moneyDecimal($itemDiscountTotal)); ?></dd>
                    </div>
                    <div>
                        <dt>Invoice discount</dt>
                        <dd><?php echo e(moneyDecimal($invoiceDiscountTotal)); ?></dd>
                    </div>
                    <?php
                        $addedVatMinor = $entry->lines->where('vat_mode', 'exclusive')->sum('vat');
                        $includedVatMinor = $entry->lines->where('vat_mode', 'inclusive')->sum('vat');
                    ?>
                    <?php if($addedVatMinor > 0): ?>
                        <div><dt>VAT added</dt><dd><?php echo e(moneyDecimal($addedVatMinor)); ?></dd></div>
                    <?php endif; ?>
                    <?php if($includedVatMinor > 0): ?>
                        <div><dt>VAT included</dt><dd><?php echo e(moneyDecimal($includedVatMinor)); ?></dd></div>
                    <?php endif; ?>
                    <?php echo $__env->make('pharmacy::entries.partials.invoice-totals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </dl>
            </div>
        </div>

        <?php echo $__env->make('pharmacy::entries.partials.invoice-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Pharmacy\resources/views/purchases/show.blade.php ENDPATH**/ ?>