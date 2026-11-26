<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h4 fw-semibold mb-1"><?php echo e($pageTitle); ?></h1>
            <p class="small text-body-secondary mb-0">
                <?php if(filled($branchName)): ?><?php echo e($branchName); ?> &middot; <?php endif; ?>
                <time datetime="<?php echo e($todayIso); ?>"><?php echo e($todayLabel); ?></time>
            </p>
        </div>
        <nav class="btn-group" aria-label="Pharmacy product type">
            <a class="<?php echo \Illuminate\Support\Arr::toCssClasses(['btn', 'btn-primary' => $productType === 'medicine', 'btn-outline-primary' => $productType !== 'medicine']); ?>"
                href="<?php echo e(route('pharmacy.dashboard', ['product_type' => 'medicine'])); ?>"
                <?php if($productType === 'medicine'): ?> aria-current="page" <?php endif; ?>>
                <i class="fa-solid fa-pills me-2" aria-hidden="true"></i>Medicine
            </a>
            <a class="<?php echo \Illuminate\Support\Arr::toCssClasses(['btn', 'btn-primary' => $productType === 'optic', 'btn-outline-primary' => $productType !== 'optic']); ?>"
                href="<?php echo e(route('pharmacy.dashboard', ['product_type' => 'optic'])); ?>"
                <?php if($productType === 'optic'): ?> aria-current="page" <?php endif; ?>>
                <i class="fa-solid fa-glasses me-2" aria-hidden="true"></i>Optic
            </a>
        </nav>
    </header>

    <?php if($canCreateSale || $canCreatePurchase || $canViewProducts || $canCreatePayment || $canViewReports): ?>
        <nav class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-6 justify-content-center g-3 mb-4" aria-label="<?php echo e($productTypeLabel); ?> quick actions">
            <?php if($canCreateSale): ?>
                <div class="col">
                    <a class="btn btn-danger bg-gradient d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                        href="<?php echo e(route('pharmacy.sales.create', ['product_type' => $productType])); ?>">
                        <i class="fa-solid fa-cart-shopping fs-4" aria-hidden="true"></i>
                        <span class="small fw-semibold">New Sale</span>
                    </a>
                </div>
            <?php endif; ?>
            <?php if($canCreatePurchase): ?>
                <div class="col">
                    <a class="btn btn-primary bg-gradient d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                        href="<?php echo e(route('pharmacy.purchases.create', ['product_type' => $productType])); ?>">
                        <i class="fa-solid fa-truck-ramp-box fs-4" aria-hidden="true"></i>
                        <span class="small fw-semibold">New Purchase</span>
                    </a>
                </div>
            <?php endif; ?>
            <?php if($canViewProducts): ?>
                <div class="col">
                    <a class="btn btn-success bg-gradient d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                        href="<?php echo e(route('pharmacy.products.index', ['type' => $productType])); ?>">
                        <i class="fa-solid fa-box-open fs-4" aria-hidden="true"></i>
                        <span class="small fw-semibold">Product</span>
                    </a>
                </div>
            <?php endif; ?>
            <?php if($canCreatePayment): ?>
                <div class="col">
                    <a class="btn btn-success bg-gradient d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                        href="<?php echo e(route('pharmacy.sale-payments.create', ['product_type' => $productType])); ?>">
                        <i class="fa-solid fa-hand-holding-dollar fs-4" aria-hidden="true"></i>
                        <span class="small fw-semibold">Customer Collection</span>
                    </a>
                </div>
                <div class="col">
                    <a class="btn btn-primary bg-gradient d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                        href="<?php echo e(route('pharmacy.purchase-payments.create', ['product_type' => $productType])); ?>">
                        <i class="fa-solid fa-money-bill-transfer fs-4" aria-hidden="true"></i>
                        <span class="small fw-semibold">Supplier Payment</span>
                    </a>
                </div>
            <?php endif; ?>
            <?php if($canViewReports): ?>
                <div class="col">
                    <a class="btn btn-danger bg-gradient d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                        href="<?php echo e(route('pharmacy.reports.stock', ['product_type' => $productType])); ?>">
                        <i class="fa-solid fa-boxes-stacked fs-4" aria-hidden="true"></i>
                        <span class="small fw-semibold">All Stock Report</span>
                    </a>
                </div>
                <div class="col">
                    <a class="btn btn-success bg-gradient d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                        href="<?php echo e(route('pharmacy.reports.daily', ['product_type' => $productType, 'from_date' => $todayIso, 'to_date' => $todayIso])); ?>">
                        <i class="fa-solid fa-chart-column fs-4" aria-hidden="true"></i>
                        <span class="small fw-semibold">Daily Report</span>
                    </a>
                </div>
                <div class="col">
                    <a class="btn btn-danger bg-gradient d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                        href="<?php echo e(route('pharmacy.reports.customer-dues', ['product_type' => $productType])); ?>">
                        <i class="fa-solid fa-wallet fs-4" aria-hidden="true"></i>
                        <span class="small fw-semibold">Customer Due</span>
                    </a>
                </div>
                <div class="col">
                    <a class="btn btn-success bg-gradient d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                        href="<?php echo e(route('pharmacy.reports.supplier-dues', ['product_type' => $productType])); ?>">
                        <i class="fa-solid fa-file-invoice-dollar fs-4" aria-hidden="true"></i>
                        <span class="small fw-semibold">Supplier Due</span>
                    </a>
                </div>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

    <?php if($canViewProducts || $canViewSales || $canViewPurchases || $canViewReports): ?>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h2 class="h6 fw-semibold mb-0"><?php echo e($productTypeLabel); ?> Overview </h2>
            <?php if($canViewReports): ?>
                <a class="small text-decoration-none" href="<?php echo e(route('pharmacy.reports.stock', ['product_type' => $productType, 'stock_status' => 'low'])); ?>">View low stock</a>
            <?php endif; ?>
        </div>
        <section class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3 mb-4" aria-label="<?php echo e($productTypeLabel); ?> overview">
            <?php if($canViewProducts): ?>
                <div class="col">
                    <a class="app-card bg-info-subtle border-0 d-flex align-items-center gap-3 h-100 p-3 text-decoration-none text-body"
                        href="<?php echo e(route('pharmacy.products.index', ['type' => $productType])); ?>">
                        <span class="d-inline-flex flex-shrink-0 align-items-center justify-content-center rounded-circle p-3 bg-info text-dark" aria-hidden="true">
                            <i class="fa-solid fa-box-open fs-4"></i>
                        </span>
                        <div>
                            <h3 class="small fw-medium mb-1">Product</h3>
                            <p class="h4 fw-semibold mb-1"><?php echo e(number_format($productCount)); ?></p>
                            <p class="small text-body-secondary mb-0"><?php echo e($productTypeLabel); ?> Catalog</p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if($canViewSales): ?>
                <div class="col">
                    <a class="app-card bg-success-subtle border-0 d-flex align-items-center gap-3 h-100 p-3 text-decoration-none text-body"
                        href="<?php echo e(route('pharmacy.sales.index', ['product_type' => $productType, 'from_date' => $todayIso, 'to_date' => $todayIso])); ?>">
                        <span class="d-inline-flex flex-shrink-0 align-items-center justify-content-center rounded-circle p-3 bg-success text-white" aria-hidden="true">
                            <i class="fa-solid fa-receipt fs-4"></i>
                        </span>
                        <div>
                            <h3 class="small fw-medium mb-1">Today's Sales</h3>
                            <p class="h5 fw-semibold text-break mb-1"> <?php echo e(moneyDecimal($salesTotal)); ?></p>
                            <p class="small text-body-secondary mb-0"><?php echo e(number_format($salesCount)); ?> invoices</p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if($canViewPurchases): ?>
                <div class="col">
                    <a class="app-card bg-primary-subtle border-0 d-flex align-items-center gap-3 h-100 p-3 text-decoration-none text-body"
                        href="<?php echo e(route('pharmacy.purchases.index', ['product_type' => $productType, 'from_date' => $todayIso, 'to_date' => $todayIso])); ?>">
                        <span class="d-inline-flex flex-shrink-0 align-items-center justify-content-center rounded-circle p-3 bg-primary text-white" aria-hidden="true">
                            <i class="fa-solid fa-truck-ramp-box fs-4"></i>
                        </span>
                        <div>
                            <h3 class="small fw-medium mb-1">Today's Purchase</h3>
                            <p class="h5 fw-semibold text-break mb-1"> <?php echo e(moneyDecimal($purchaseTotal)); ?></p>
                            <p class="small text-body-secondary mb-0"><?php echo e(number_format($purchaseCount)); ?> Invoices</p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if($canViewReports): ?>
                <div class="col">
                    <a class="app-card bg-warning-subtle border-0 d-flex align-items-center gap-3 h-100 p-3 text-decoration-none text-body"
                        href="<?php echo e(route('pharmacy.reports.customer-dues', ['product_type' => $productType])); ?>">
                        <span class="d-inline-flex flex-shrink-0 align-items-center justify-content-center rounded-circle p-3 bg-warning text-dark" aria-hidden="true">
                            <i class="fa-solid fa-wallet fs-4"></i>
                        </span>
                        <div>
                            <h3 class="small fw-medium mb-1">Customer Due</h3>
                            <p class="h5 fw-semibold text-break mb-1"><?php echo e(moneyDecimal($customerDue)); ?></p>
                            <p class="small text-body-secondary mb-0">To Collect</p>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a class="app-card bg-danger-subtle border-0 d-flex align-items-center gap-3 h-100 p-3 text-decoration-none text-body"
                        href="<?php echo e(route('pharmacy.reports.supplier-dues', ['product_type' => $productType])); ?>">
                        <span class="d-inline-flex flex-shrink-0 align-items-center justify-content-center rounded-circle p-3 bg-danger text-white" aria-hidden="true">
                            <i class="fa-solid fa-file-invoice-dollar fs-4"></i>
                        </span>
                        <div>
                            <h3 class="small fw-medium mb-1">Supplier Due</h3>
                            <p class="h5 fw-semibold text-break mb-1"> <?php echo e(moneyDecimal($supplierDue)); ?></p>
                            <p class="small text-body-secondary mb-0">To Pay</p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if($expiryCount !== null): ?>
                <div class="col">
                    <a class="app-card bg-secondary-subtle border-0 d-flex align-items-center gap-3 h-100 p-3 text-decoration-none text-body"
                        title="Expired batches and batches expiring within 30 days"
                        href="<?php echo e(route('pharmacy.reports.expiry', ['product_type' => 'medicine', 'expiry_before' => $expiryBefore])); ?>">
                        <span class="d-inline-flex flex-shrink-0 align-items-center justify-content-center rounded-circle p-3 bg-secondary text-white" aria-hidden="true">
                            <i class="fa-solid fa-calendar-xmark fs-4"></i>
                        </span>
                        <div>
                            <h3 class="small fw-medium mb-1">Expired List</h3>
                            <p class="h4 fw-semibold mb-1"><?php echo e(number_format($expiryCount)); ?></p>
                            <p class="small text-body-secondary mb-0">Expired / Next 30 Days</p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-xl-2 g-3">
        <?php if($canViewSales): ?>
            <div class="col flex-grow-1">
                <section class="app-card h-100 overflow-hidden" aria-labelledby="pharmacy-recent-sales-heading">
                    <header class="d-flex align-items-center justify-content-between gap-3 p-3 border-bottom bg-success-subtle text-success-emphasis">
                        <div>
                            <h2 class="h6 fw-semibold mb-1" id="pharmacy-recent-sales-heading">Recent Sales</h2>
                            <p class="small text-body-secondary mb-0">Latest 5 Invoices</p>
                        </div>
                        <a class="btn btn-success btn-sm" href="<?php echo e(route('pharmacy.sales.index', ['product_type' => $productType])); ?>">View All</a>
                    </header>
                    <div class="table-responsive overflow-y-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <caption class="visually-hidden">Recent <?php echo e(strtolower($productTypeLabel)); ?> Sales, </caption>
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3 py-2" scope="col">Invoice / Customer</th>
                                    <th class="text-end px-3 py-2" scope="col">Amount</th>
                                    <th class="text-end px-3 py-2" scope="col">Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-break px-3 py-2">
                                            <a class="fw-semibold text-decoration-none" href="<?php echo e(route('pharmacy.sales.show', ['sale' => $sale, 'product_type' => $productType])); ?>"><?php echo e($sale->number); ?></a>
                                            <div class="small text-body-secondary"><?php echo e($customers[$sale->customer_id]['name'] ?? 'Walk-in customer'); ?></div>
                                            <time class="small text-body-secondary" datetime="<?php echo e($sale->entry_date->toDateString()); ?>"><?php echo e($sale->entry_date->format('d M Y')); ?></time>
                                        </td>
                                        <td class="text-end fw-semibold text-nowrap px-3 py-2"><?php echo e(moneyDecimal($sale->total)); ?></td>
                                        <td class="text-end text-nowrap px-3 py-2"><?php echo e(moneyDecimal($sale->outstanding)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="3" class="text-center text-body-secondary py-5">No <?php echo e(strtolower($productTypeLabel)); ?> Sales Yet.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        <?php endif; ?>
        <?php if($canViewPurchases): ?>
            <div class="col flex-grow-1">
                <section class="app-card h-100 overflow-hidden" aria-labelledby="pharmacy-recent-purchases-heading">
                    <header class="d-flex align-items-center justify-content-between gap-3 p-3 border-bottom bg-primary-subtle text-primary-emphasis">
                        <div>
                            <h2 class="h6 fw-semibold mb-1" id="pharmacy-recent-purchases-heading">Recent Purchase</h2>
                            <p class="small text-body-secondary mb-0">Latest 5 Invoice</p>
                        </div>
                        <a class="btn btn-primary btn-sm" href="<?php echo e(route('pharmacy.purchases.index', ['product_type' => $productType])); ?>">View All</a>
                    </header>
                    <div class="table-responsive overflow-y-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <caption class="visually-hidden">Recent <?php echo e(strtolower($productTypeLabel)); ?> Purchase </caption>
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3 py-2" scope="col">Invoice / Supplier</th>
                                    <th class="text-end px-3 py-2" scope="col">Amount</th>
                                    <th class="text-end px-3 py-2" scope="col">Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentPurchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-break px-3 py-2">
                                            <a class="fw-semibold text-decoration-none" href="<?php echo e(route('pharmacy.purchases.show', ['purchase' => $purchase, 'product_type' => $productType])); ?>"><?php echo e($purchase->number); ?></a>
                                            <div class="small text-body-secondary"><?php echo e($purchase->supplier?->name ?? 'Supplier unavailable'); ?></div>
                                            <time class="small text-body-secondary" datetime="<?php echo e($purchase->entry_date->toDateString()); ?>"><?php echo e($purchase->entry_date->format('d M Y')); ?></time>
                                        </td>
                                        <td class="text-end fw-semibold text-nowrap px-3 py-2"><?php echo e(moneyDecimal($purchase->total)); ?></td>
                                        <td class="text-end text-nowrap px-3 py-2"><?php echo e(moneyDecimal($purchase->outstanding)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="3" class="text-center text-body-secondary py-5">No <?php echo e(strtolower($productTypeLabel)); ?> Purchase Yet.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        <?php endif; ?>
    </div>

    <?php if(! $canViewProducts && ! $canViewSales && ! $canViewPurchases && ! $canViewReports): ?>
        <section class="app-card p-4 text-center">
            <i class="fa-solid fa-lock text-body-secondary fs-4 mb-3" aria-hidden="true"></i>
            <h2 class="h6 fw-semibold">No dashboard figures available</h2>
            <p class="small text-body-secondary mb-0">Your permissions determine which figures appear here.</p>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\dashboard\index.blade.php ENDPATH**/ ?>