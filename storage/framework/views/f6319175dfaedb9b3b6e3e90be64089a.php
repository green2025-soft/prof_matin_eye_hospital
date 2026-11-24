<p class="small text-end mb-2">Amounts in BDT</p>
<div class="table-responsive">
    <table class="table app-clinical-invoice-table app-pharmacy-invoice-table">
        <caption class="visually-hidden">Saved <?php echo e(strtolower($invoiceTitle)); ?> items and stock values.</caption>
        <thead>
            <tr>
                <th scope="col" class="app-clinical-invoice-number">SL</th>
                <th scope="col">Item / SKU</th>
                <th scope="col" class="text-end">Qty / Unit</th>
                <th scope="col" class="text-end app-clinical-invoice-amount">Stock value</th>
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
                    <td class="text-end fw-semibold"><?php echo e(moneyDecimal($line->cost)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\transfers\invoice-items.blade.php ENDPATH**/ ?>