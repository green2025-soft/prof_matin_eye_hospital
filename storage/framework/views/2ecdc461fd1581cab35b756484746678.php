<?php
    $showLineVat = $entry->lines->contains(fn ($line) => $line->vat_mode !== 'none' || $line->vat > 0);
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
                <?php if($showSalePrice): ?>
                    <th scope="col" class="text-end" aria-label="Sale price per piece">S.Price</th>
                <?php endif; ?>
                <th scope="col" class="text-end">Discount</th>
                <?php if($showLineVat): ?>
                    <th scope="col" class="text-end">VAT</th>
                <?php endif; ?>
                <?php if($entry->product_type === 'medicine'): ?>
                    <th scope="col">Expiry</th>
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
                    <td class="text-end">
                        <?php echo e($line->quantity); ?> <?php echo e($line->productUnit->unit->symbol ?: $line->productUnit->unit->name); ?>

                        <?php if($line->factor !== 1): ?>
                            <small class="d-block text-body-secondary"><?php echo e($line->base_quantity); ?> Pcs</small>
                        <?php endif; ?>
                    </td>
                    <td class="text-end"><?php echo e(moneyDecimal($line->unit_price)); ?></td>
                    <?php if($showSalePrice): ?>
                        <td class="text-end">
                            <?php echo e($line->sale_price_per_piece !== null ? moneyDecimal($line->sale_price_per_piece) : '—'); ?>

                        </td>
                    <?php endif; ?>
                    <td class="text-end">
                        <?php echo e(moneyDecimal($line->discount)); ?>

                        <?php if($line->discount_calculation === 'percent'): ?>
                            <small class="d-block text-body-secondary"><?php echo e($line->discount_percent); ?>%</small>
                        <?php elseif($line->discount_calculation === 'unit' && $line->discount_unit_amount !== null): ?>
                            <small class="d-block text-body-secondary">
                                <?php echo e(moneyDecimal($line->discount_unit_amount)); ?> / <?php echo e($line->productUnit->unit->symbol ?: $line->productUnit->unit->name); ?>

                            </small>
                        <?php endif; ?>
                    </td>
                    <?php if($showLineVat): ?>
                        <td class="text-end">
                            <?php echo e(moneyDecimal($line->vat)); ?>

                            <?php if($line->vat_calculation === 'percent' && $line->vat_percent > 0): ?>
                                <small class="d-block text-body-secondary"><?php echo e($line->vat_percent); ?>%</small>
                            <?php elseif($line->vat_calculation === 'unit' && $line->vat_unit_amount !== null): ?>
                                <small class="d-block text-body-secondary">
                                    <?php echo e(moneyDecimal($line->vat_unit_amount)); ?> / <?php echo e($line->productUnit->unit->symbol ?: $line->productUnit->unit->name); ?>

                                </small>
                            <?php endif; ?>
                            <small class="d-block text-body-secondary">
                                <?php echo e(match ($line->vat_mode) { 'inclusive' => 'Included', 'exclusive' => 'Added', default => 'No VAT' }); ?>

                            </small>
                        </td>
                    <?php endif; ?>
                    <?php if($entry->product_type === 'medicine'): ?>
                        <td>
                            <?php echo e($line->expiry_date?->format('d-m-Y') ?: '—'); ?>

                            <?php if($line->batch_no): ?>
                                <small class="d-block text-body-secondary">Batch: <?php echo e($line->batch_no); ?></small>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <td class="text-end fw-semibold">
                        <?php echo e(moneyDecimal($line->total + $line->invoice_discount)); ?>

                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\purchases\invoice-items.blade.php ENDPATH**/ ?>