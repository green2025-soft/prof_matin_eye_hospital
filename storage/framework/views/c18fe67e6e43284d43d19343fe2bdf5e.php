<?php $__env->startSection('report-page-class', 'app-report-wide'); ?>

<?php $__env->startSection('report-filters'); ?>
    <form class="p-3" action="<?php echo e(route('pharmacy.reports.stock')); ?>" method="GET" aria-label="Filter stock report">
        <input name="product_type" type="hidden" value="<?php echo e($filters['product_type']); ?>">
        <input name="per_page" type="hidden" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
        <div class="row g-2 align-items-end">
            <?php echo $__env->make('pharmacy::reports.product-filter', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('pharmacy::reports.date-range', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="col-sm-6 col-lg-4">
                <label class="form-label small" for="stock_status">Stock Status Now</label>
                <select class="<?php echo e(inputClass('stock_status', 'form-select')); ?>" id="stock_status" name="stock_status" <?php echo e(inputAttrs('stock_status')); ?>>
                    <option value="all" <?php if($filters['stock_status'] === 'all'): echo 'selected'; endif; ?>>All products</option>
                    <option value="available" <?php if($filters['stock_status'] === 'available'): echo 'selected'; endif; ?>>Available to sell</option>
                    <option value="out" <?php if($filters['stock_status'] === 'out'): echo 'selected'; endif; ?>>Out of stock</option>
                    <option value="low" <?php if($filters['stock_status'] === 'low'): echo 'selected'; endif; ?>>Low stock</option>
                    <option value="expired" <?php if($filters['stock_status'] === 'expired'): echo 'selected'; endif; ?>>Has expired stock</option>
                    <option value="future" <?php if($filters['stock_status'] === 'future'): echo 'selected'; endif; ?>>Has future-dated stock</option>
                    <option value="negative" <?php if($filters['stock_status'] === 'negative'): echo 'selected'; endif; ?>>Negative stock</option>
                </select>
                <?php $__errorArgs = ['stock_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="stock_status-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label small" for="category_id">Generic/Category </label>
                <select class="<?php echo e(inputClass('category_id', 'form-select')); ?>" id="category_id" name="category_id"
                    data-ajax-select data-url="<?php echo e(route('pharmacy.categories.options', ['type' => $filters['product_type']])); ?>"
                    data-placeholder="All categories"
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
            <div class="col-sm-6 col-lg-3">
                <label class="form-label small" for="supplier_id">Supplier</label>
                <select class="<?php echo e(inputClass('supplier_id', 'form-select')); ?>" id="supplier_id" name="supplier_id"
                    data-ajax-select data-url="<?php echo e(route('pharmacy.suppliers.options', ['type' => $filters['product_type']])); ?>"
                    data-placeholder="All suppliers"
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
            <div class="col-sm-6 col-lg-3">
                <label class="form-label small" for="shelf_id">Shelf</label>
                <select class="<?php echo e(inputClass('shelf_id', 'form-select')); ?>" id="shelf_id" name="shelf_id"
                    data-ajax-select data-url="<?php echo e(route('pharmacy.shelf.options', ['type' => $filters['product_type']])); ?>"
                    data-placeholder="All Shelf"
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
            <div class="col-sm-6 col-lg-3 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('pharmacy.reports.stock', ['product_type' => $filters['product_type']])); ?>">Reset</a>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <?php if(isset($filters['search'])): ?><span>Search: <?php echo e($filters['search']); ?></span><?php endif; ?>
    <span>Period: <strong><?php echo e($filters['from_date']); ?></strong> to <strong><?php echo e($filters['to_date']); ?></strong></span>
    <span>Product: <?php echo e($selectedProduct?->product?->name ?? 'All products'); ?> <?php echo e($selectedProduct?->description()); ?></span>
    <span>Category: <?php echo e($selectedCategory?->name ?? 'All'); ?></span>
    <span>Supplier: <?php echo e($selectedSupplier?->name ?? 'All'); ?></span>
    <span>Shelf: <?php echo e($selectedShelf?->name ?? 'All'); ?></span>
    <span>Current Stock Status: <?php echo e(ucfirst($filters['stock_status'])); ?></span>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <div class="app-report-summary row row-cols-2 row-cols-lg-5 g-3">
        <div class="col"><span class="d-block">Medicine/Products </span><strong class="d-block"><?php echo e($summary->product_count); ?></strong></div>
        <div class="col"><span class="d-block">Available Qty</span><strong class="d-block"><?php echo e((int) $summary->available_quantity); ?></strong></div>
        <div class="col"><span class="d-block">Expired Qty</span><strong class="d-block"><?php echo e((int) $summary->expired_quantity); ?></strong></div>
        <div class="col"><span class="d-block">Stock Qty </span><strong class="d-block"><?php echo e((int) $summary->closing_quantity); ?></strong></div>
        <div class="col"><span class="d-block">Stock Value </span><strong class="d-block"><?php echo e(moneyDecimal($summary->closing_cost)); ?></strong></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Stock movements for the selected period and stock available today. Quantities in Pcs.</caption>
            <thead>
                <tr>
                    <th scope="col">Product / SKU</th>
                    <th scope="col">Supplier / Shelf</th>
                    <th class="text-end" scope="col">Opening</th>
                    <th class="text-end" scope="col">Purchase</th>
                    <th class="text-end" scope="col">Sales</th>
                    <th class="text-end" scope="col">Purchase return</th>
                    <th class="text-end" scope="col">Sales return</th>
                    <th class="text-end" scope="col">Other +/-</th>
                    <th class="text-end" scope="col">Closing</th>
                    <th class="text-end" scope="col">Available now</th>
                    <th class="text-end" scope="col">Expired now</th>
                    <th class="text-end" scope="col">P.Price</th>
                    <th class="text-end" scope="col">S.Price</th>
                    <th class="text-end" scope="col">Closing value</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <th scope="row">
                            <a class="text-decoration-none"
                                href="<?php echo e(route('pharmacy.reports.movements', ['product_type' => $filters['product_type'], 'variant_id' => $record->id, 'from_date' => $filters['from_date'], 'to_date' => $filters['to_date']])); ?>"><?php echo e($record->product->name); ?></a>
                            <small class="d-block fw-normal"><?php echo e($record->description()); ?></small>
                            <small class="d-block fw-normal text-body-secondary"><?php echo e($record->product->category?->name); ?></small>
                        </th>
                        <td class="small"><?php echo e($record->product->supplier?->name ?? '--'); ?><small class="d-block text-body-secondary"><?php echo e($record->product->shelf?->name ?? '--'); ?></small></td>
                        <td class="text-end"><?php echo e((int) $record->opening_quantity); ?></td>
                        <td class="text-end"><?php echo e((int) $record->purchase_quantity); ?></td>
                        <td class="text-end"><?php echo e(-(int) $record->sale_quantity); ?></td>
                        <td class="text-end"><?php echo e(-(int) $record->purchase_return_quantity); ?></td>
                        <td class="text-end"><?php echo e((int) $record->sale_return_quantity); ?></td>
                        <td class="text-end"><?php echo e((int) $record->other_quantity); ?></td>
                        <td class="text-end fw-semibold"><?php echo e((int) $record->closing_quantity); ?></td>
                        <td class="text-end fw-semibold"><?php echo e((int) $record->available_quantity); ?></td>
                        <td class="text-end"><?php echo e((int) $record->expired_quantity); ?></td>
                        <td class="text-end"><?php echo e($record->product->purchase_price); ?></td>
                        <td class="text-end"><?php echo e($record->product->sale_price); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->closing_cost ?? 0)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-center text-body-secondary py-4" colspan="14">No products match these filters.</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row" colspan="2">Grand total - all filtered products</th>
                    <td class="text-end"><?php echo e((int) $summary->opening_quantity); ?></td>
                    <td class="text-end"><?php echo e((int) $summary->purchase_quantity); ?></td>
                    <td class="text-end"><?php echo e(-(int) $summary->sale_quantity); ?></td>
                    <td class="text-end"><?php echo e(-(int) $summary->purchase_return_quantity); ?></td>
                    <td class="text-end"><?php echo e((int) $summary->sale_return_quantity); ?></td>
                    <td class="text-end"><?php echo e((int) $summary->other_quantity); ?></td>
                    <td class="text-end"><?php echo e((int) $summary->closing_quantity); ?></td>
                    <td class="text-end"><?php echo e((int) $summary->available_quantity); ?></td>
                    <td class="text-end"><?php echo e((int) $summary->expired_quantity); ?></td>
                    <td></td><td></td>
                    <td class="text-end"><?php echo e(moneyDecimal($summary->closing_cost)); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pharmacy::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\reports\stock.blade.php ENDPATH**/ ?>