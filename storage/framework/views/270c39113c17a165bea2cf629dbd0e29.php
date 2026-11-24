<?php
    $showLineDiscount = $showDiscount ?? true;
    $showLineVat = $showVat ?? false;
?>

<p class="small text-end mb-2">Amounts in BDT</p>
<div class="table-responsive">
    <table class="table app-clinical-invoice-table app-pharmacy-invoice-table">
        <caption class="visually-hidden">Saved <?php echo e(strtolower($invoiceTitle)); ?> items and amounts.</caption>
        <thead>
            <tr>
                <th scope="col" class="app-clinical-invoice-number">SL</th>
                <th scope="col">Item / SKU</th>
                <th scope="col" class="text-end">Qty / Unit</th>
                <th scope="col" class="text-end">Rate</th>
                <?php if($showLineDiscount): ?>
                    <th scope="col" class="text-end">Discount</th>
                <?php endif; ?>
                <?php if($showLineVat): ?>
                    <th scope="col" class="text-end">VAT</th>
                <?php endif; ?>
                <th scope="col" class="text-end app-clinical-invoice-amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $entry->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td class="app-pharmacy-invoice-product">
                        <strong><?php echo e($line->variant->product->name); ?></strong>
                        <small class="d-block text-body-secondary">
                            <?php echo e($line->variant->sku); ?>

                            <?php if($line->variant->name && $line->variant->name !== 'Default'): ?>
                                / <?php echo e($line->variant->name); ?>

                            <?php endif; ?>
                        </small>
                    </td>
                    <td class="text-end" data-label="Qty / Unit">
                        <?php echo e($line->quantity); ?> <?php echo e($line->productUnit->unit->symbol ?: $line->productUnit->unit->name); ?>

                        <?php if($line->factor !== 1): ?>
                            <small class="d-block text-body-secondary"><?php echo e($line->base_quantity); ?> Pcs</small>
                        <?php endif; ?>
                    </td>
                    <td class="text-end" data-label="Rate"><?php echo e(moneyDecimal($line->unit_price)); ?></td>
                    <?php if($showLineDiscount): ?>
                        <td class="text-end" data-label="Discount">
                            <?php echo e(moneyDecimal($line->discount)); ?>

                        </td>
                    <?php endif; ?>
                    <?php if($showLineVat): ?>
                        <td class="text-end" data-label="VAT">
                            <?php echo e(moneyDecimal($line->vat)); ?>

                            <small class="d-block text-body-secondary">
                                <?php echo e(match ($line->vat_mode) { 'inclusive' => 'Included', 'exclusive' => 'Added', default => 'No VAT' }); ?>

                            </small>
                        </td>
                    <?php endif; ?>
                    <td class="text-end fw-semibold" data-label="Amount">
                        <?php echo e(moneyDecimal($line->total)); ?>

                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\entries\partials\invoice-financial-items.blade.php ENDPATH**/ ?>