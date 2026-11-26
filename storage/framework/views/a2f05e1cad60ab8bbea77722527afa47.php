<?php $__env->startSection('report-page-class', 'app-report-wide'); ?>

<?php $__env->startSection('report-filters'); ?>
    <form class="p-3" action="<?php echo e(route('pharmacy.reports.profit-loss')); ?>" method="GET" aria-label="Filter product profit and loss">
        <input name="product_type" type="hidden" value="<?php echo e($filters['product_type']); ?>">
        <input name="per_page" type="hidden" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
        <div class="row g-2 align-items-end">
            <?php echo $__env->make('pharmacy::reports.product-filter', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('pharmacy::reports.date-range', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('pharmacy.reports.profit-loss', ['product_type' => $filters['product_type']])); ?>">Reset</a>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <span>Period: <strong><?php echo e($filters['from_date']); ?></strong> to <strong><?php echo e($filters['to_date']); ?></strong></span>
    <span>Product: <?php echo e($selectedProduct?->product?->name ?? 'All products'); ?> <?php echo e($selectedProduct?->description()); ?></span>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <div class="app-report-summary row row-cols-2 row-cols-lg-4 g-3">
        <div class="col"><span class="d-block">Products / SKUs</span><strong class="d-block"><?php echo e($summary->product_count); ?></strong></div>
        <div class="col"><span class="d-block">Net sales (BDT)</span><strong class="d-block"><?php echo e(moneyDecimal($summary->net_revenue)); ?></strong></div>
        <div class="col"><span class="d-block">Cost (BDT)</span><strong class="d-block"><?php echo e(moneyDecimal($summary->cost)); ?></strong></div>
        <div class="col"><span class="d-block">Product profit / loss (BDT)</span><strong class="d-block"><?php echo e(moneyDecimal($summary->profit)); ?></strong></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Product sales, returns, saved costs and profit for the selected period. Amounts in BDT.</caption>
            <thead>
                <tr>
                    <th scope="col">Product / SKU</th>
                    <th class="text-end" scope="col">Sales qty</th>
                    <th class="text-end" scope="col">Sales amount</th>
                    <th class="text-end" scope="col">Return qty</th>
                    <th class="text-end" scope="col">Return amount</th>
                    <th class="text-end" scope="col">Net sales</th>
                    <th class="text-end" scope="col">Cost</th>
                    <th class="text-end" scope="col">Gross profit</th>
                    <th class="text-end" scope="col">Other +/-</th>
                    <th class="text-end" scope="col">Profit / loss</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <th scope="row">
                            <a class="text-decoration-none"
                                href="<?php echo e(route('pharmacy.reports.movements', ['product_type' => $filters['product_type'], 'variant_id' => $record->id, 'from_date' => $filters['from_date'], 'to_date' => $filters['to_date']])); ?>"><?php echo e($record->product->name); ?></a>
                            <small class="d-block fw-normal text-body-secondary"><?php echo e($record->description()); ?></small>
                        </th>
                        <td class="text-end"><?php echo e($record->sale_quantity); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->sale_amount)); ?></td>
                        <td class="text-end"><?php echo e($record->return_quantity); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->return_amount)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->net_revenue)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->cost)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->gross_profit)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->other)); ?></td>
                        <td class="text-end fw-semibold"><?php echo e(moneyDecimal($record->profit)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-center text-body-secondary py-4" colspan="10">No product activity matches this period.</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row">Grand total - all filtered products</th>
                    <td class="text-end"><?php echo e($summary->sale_quantity); ?></td>
                    <td class="text-end"><?php echo e(moneyDecimal($summary->sale_amount)); ?></td>
                    <td class="text-end"><?php echo e($summary->return_quantity); ?></td>
                    <td class="text-end"><?php echo e(moneyDecimal($summary->return_amount)); ?></td>
                    <td class="text-end"><?php echo e(moneyDecimal($summary->net_revenue)); ?></td>
                    <td class="text-end"><?php echo e(moneyDecimal($summary->cost)); ?></td>
                    <td class="text-end"><?php echo e(moneyDecimal($summary->gross_profit)); ?></td>
                    <td class="text-end"><?php echo e(moneyDecimal($summary->other)); ?></td>
                    <td class="text-end"><?php echo e(moneyDecimal($summary->profit)); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <p class="small px-3 py-2 mb-0">Other +/-: stock adjustments + cost variance - damage / wastage. Product profit excludes payment settlement discounts.</p>
    <?php if($showInvoiceAdjustments): ?>
        <div class="app-report-summary row row-cols-1 row-cols-md-3 g-3 border-top">
            <div class="col"><span class="d-block">Customer settlement discounts (-)</span><strong class="d-block"><?php echo e(moneyDecimal($summary->customer_discount)); ?></strong></div>
            <div class="col"><span class="d-block">Supplier settlement discounts (+)</span><strong class="d-block"><?php echo e(moneyDecimal($summary->supplier_discount)); ?></strong></div>
            <div class="col"><span class="d-block">Net profit / loss (BDT)</span><strong class="d-block"><?php echo e(moneyDecimal($summary->net_profit)); ?></strong></div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pharmacy::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\reports\profit-loss.blade.php ENDPATH**/ ?>