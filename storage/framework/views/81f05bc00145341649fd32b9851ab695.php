<?php
    $permissions = $moduleMenu['permissions'];
    $hasSalesMenu = ($permissions['pharmacy-sales.view'] ?? false)
        || ($permissions['pharmacy-sale-returns.view'] ?? false);
    $hasPurchasesMenu = ($permissions['pharmacy-purchases.view'] ?? false)
        || ($permissions['pharmacy-purchase-returns.view'] ?? false);
    $hasStockMenu = ($permissions['pharmacy-opening-stock.view'] ?? false)
        || ($permissions['pharmacy-adjustments.view'] ?? false)
        || ($permissions['pharmacy-damage.view'] ?? false)
        || ($permissions['pharmacy-transfers.view'] ?? false);
    $hasPeopleMenu = ($permissions['pharmacy-suppliers.view'] ?? false)
        || ($permissions['pharmacy-payments.view'] ?? false);
    $hasSharedSetup = ($permissions['pharmacy-customers.view'] ?? false)
        || ($permissions['pharmacy-dosages.view'] ?? false)
        || ($permissions['pharmacy-units.view'] ?? false)
        || ($permissions['settings.view'] ?? false);
    $hasCatalogMenu = ($permissions['pharmacy-products.view'] ?? false)
        || ($permissions['pharmacy-categories.view'] ?? false)
        || ($permissions['pharmacy-shelf.view'] ?? false);
    $hasModuleMenu = $hasSalesMenu || $hasPurchasesMenu || $hasStockMenu || $hasPeopleMenu || $hasCatalogMenu
        || ($permissions['pharmacy-reports.view'] ?? false);

    $isMedicine = ($moduleMenu['productType'] ?? null) === 'medicine';
    $isOptic = ($moduleMenu['productType'] ?? null) === 'optic';
    $isSalePayment = request()->routeIs('pharmacy.sale-payments.*');
    $isPurchasePayment = request()->routeIs('pharmacy.purchase-payments.*');
?>

<?php if($hasModuleMenu): ?>
    <p class="app-nav-label">Medicine</p>

    <?php if($hasSalesMenu): ?>
        <details class="app-nav-group" <?php if(($isMedicine && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*'))): ?> open <?php endif; ?>>
            <summary class="app-nav-link app-nav-group-toggle<?php echo e(($isMedicine && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*')) ? ' active' : ''); ?>" title="Medicine Sales">
                <span class="app-nav-icon"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Sales</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <?php if($permissions['pharmacy-sales.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Sales', 'icon' => 'receipt',
                        'url' => route('pharmacy.sales.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.sales.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php if($permissions['pharmacy-sale-returns.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Sales Return',
                        'icon' => 'rotate-left',
                        'url' => route('pharmacy.sale-returns.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.sale-returns.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>
        </details>
    <?php endif; ?>

    <?php if($hasPurchasesMenu): ?>
        <details class="app-nav-group" <?php if(($isMedicine && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*'))): ?> open <?php endif; ?>>
            <summary class="app-nav-link app-nav-group-toggle<?php echo e(($isMedicine && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*')) ? ' active' : ''); ?>" title="Medicine Purchases">
                <span class="app-nav-icon"><i class="fa-solid fa-truck-ramp-box" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Purchase</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <?php if($permissions['pharmacy-purchases.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Purchase',
                        'icon' => 'file-invoice',
                        'url' => route('pharmacy.purchases.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.purchases.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php if($permissions['pharmacy-purchase-returns.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Purchase returns', 'icon' => 'rotate-left',
                        'url' => route('pharmacy.purchase-returns.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.purchase-returns.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>
        </details>
    <?php endif; ?>

    <?php if($hasStockMenu): ?>
        <details class="app-nav-group" <?php if(($isMedicine && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*'))): ?> open <?php endif; ?>>
            <summary class="app-nav-link app-nav-group-toggle<?php echo e(($isMedicine && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*')) ? ' active' : ''); ?>" title="Medicine Stock">
                <span class="app-nav-icon"><i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Stock</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <?php if($permissions['pharmacy-opening-stock.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Opening Stock',
                        'icon' => 'box-open',
                        'url' => route('pharmacy.opening-stock.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.opening-stock.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php if($permissions['pharmacy-adjustments.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Adjustment',
                        'icon' => 'sliders',
                        'url' => route('pharmacy.adjustments.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.adjustments.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php if($permissions['pharmacy-damage.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Damage / Wastage',
                        'icon' => 'box-archive',
                        'url' => route('pharmacy.damage.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.damage.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php if($permissions['pharmacy-transfers.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Branch Transfer',
                        'icon' => 'arrow-right-arrow-left',
                        'url' => route('pharmacy.transfers.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.transfers.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>
        </details>
    <?php endif; ?>

    <?php if($hasPeopleMenu): ?>
        <details class="app-nav-group" <?php if(($isMedicine && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.sale-payments.*', 'pharmacy.purchase-payments.*'))): ?> open <?php endif; ?>>
            <summary class="app-nav-link app-nav-group-toggle<?php echo e(($isMedicine && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.sale-payments.*', 'pharmacy.purchase-payments.*')) ? ' active' : ''); ?>" title="Medicine People & payments">
                <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Payment</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">

                <?php if($permissions['pharmacy-payments.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Customer Collection',
                         'icon' => 'money-bill-transfer',
                        'url' => route('pharmacy.sale-payments.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && $isSalePayment),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Supplier Payment', 'icon' => 'money-bill-transfer',
                        'url' => route('pharmacy.purchase-payments.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && $isPurchasePayment),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                 <?php if($permissions['pharmacy-suppliers.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Create Supplier',
                        'icon' => 'truck',
                        'url' => route('pharmacy.suppliers.index', ['type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.suppliers.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>
        </details>
    <?php endif; ?>

    <?php echo $__env->make('pharmacy::partials.catalog-menu', [
        'catalogType'   => 'medicine',
        'catalogTitle'  => 'Medicine Catalog',
        'catalogLabel'  => 'Medicine/Product',
        'productLabel'  => 'Medicine/Product',
        'productIcon'   => 'pills',
        'categoryLabel' => 'Generic/Category',
        'shelfLabel'    => 'Shelf',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if($permissions['pharmacy-reports.view'] ?? false): ?>
        <details class="app-nav-group" <?php if(($isMedicine && request()->routeIs('pharmacy.reports.*'))): ?> open <?php endif; ?>>
            <summary class="app-nav-link app-nav-group-toggle<?php echo e(($isMedicine && request()->routeIs('pharmacy.reports.*')) ? ' active' : ''); ?>" title="Medicine reports">
                <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Report</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Stock Report',
                    'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.stock', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.stock', 'pharmacy.reports.index')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Stock Movement',
                    'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.movements', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.movements')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Expiry Report',
                    'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.expiry', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.expiry')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Profit / Loss',
                    'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.profit-loss', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.profit-loss')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Purchase Discount',
                    'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.purchase-discounts', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.purchase-discounts')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Daily Report',
                    'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.daily', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.daily')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Customer Due',
                    'icon' => 'file-invoice-dollar',
                    'url' => route('pharmacy.reports.customer-dues', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.customer-dues')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Supplier Due',
                    'icon' => 'file-invoice-dollar',
                    'url' => route('pharmacy.reports.supplier-dues', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.supplier-dues')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </details>
    <?php endif; ?>

    <p class="app-nav-label">Optic</p>

    <?php if($hasSalesMenu): ?>
        <details class="app-nav-group" <?php if(($isOptic && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*'))): ?> open <?php endif; ?>>
            <summary class="app-nav-link app-nav-group-toggle<?php echo e(($isOptic && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*')) ? ' active' : ''); ?>" title="Optic Sales">
                <span class="app-nav-icon"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Sales</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <?php if($permissions['pharmacy-sales.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Sales',
                        'icon' => 'receipt',
                        'url' => route('pharmacy.sales.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.sales.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php if($permissions['pharmacy-sale-returns.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Sales Return',
                        'icon' => 'rotate-left',
                        'url' => route('pharmacy.sale-returns.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.sale-returns.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>
        </details>
    <?php endif; ?>

    <?php if($hasPurchasesMenu): ?>
        <details class="app-nav-group" <?php if(($isOptic && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*'))): ?> open <?php endif; ?>>
            <summary class="app-nav-link app-nav-group-toggle<?php echo e(($isOptic && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*')) ? ' active' : ''); ?>" title="Optic Purchases">
                <span class="app-nav-icon"><i class="fa-solid fa-truck-ramp-box" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Purchase</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <?php if($permissions['pharmacy-purchases.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Purchase',
                        'icon' => 'file-invoice',
                        'url' => route('pharmacy.purchases.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.purchases.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php if($permissions['pharmacy-purchase-returns.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Purchase Return',
                        'icon' => 'rotate-left',
                        'url' => route('pharmacy.purchase-returns.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.purchase-returns.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>
        </details>
    <?php endif; ?>

    <?php if($hasStockMenu): ?>
        <details class="app-nav-group" <?php if(($isOptic && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*'))): ?> open <?php endif; ?>>
            <summary class="app-nav-link app-nav-group-toggle<?php echo e(($isOptic && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*')) ? ' active' : ''); ?>" title="Optic Stock">
                <span class="app-nav-icon"><i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Stock</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <?php if($permissions['pharmacy-opening-stock.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Opening Stock',
                        'icon' => 'box-open',
                        'url' => route('pharmacy.opening-stock.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.opening-stock.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php if($permissions['pharmacy-adjustments.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Adjustment',
                        'icon' => 'sliders',
                        'url' => route('pharmacy.adjustments.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.adjustments.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php if($permissions['pharmacy-damage.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Damage / Wastage',
                        'icon' => 'box-archive',
                        'url' => route('pharmacy.damage.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.damage.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php if($permissions['pharmacy-transfers.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Branch Transfer',
                        'icon' => 'arrow-right-arrow-left',
                        'url' => route('pharmacy.transfers.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.transfers.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>
        </details>
    <?php endif; ?>

    <?php if($hasPeopleMenu): ?>
        <details class="app-nav-group" <?php if(($isOptic && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.sale-payments.*', 'pharmacy.purchase-payments.*'))): ?> open <?php endif; ?>>
            <summary class="app-nav-link app-nav-group-toggle<?php echo e(($isOptic && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.sale-payments.*', 'pharmacy.purchase-payments.*')) ? ' active' : ''); ?>" title="Optic People & payments">
                <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Payment</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <?php if($permissions['pharmacy-suppliers.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Supplier',
                        'icon' => 'truck',
                        'url' => route('pharmacy.suppliers.index', ['type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.suppliers.*')),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
                <?php if($permissions['pharmacy-payments.view'] ?? false): ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Customer Payment',
                        'icon' => 'money-bill-transfer',
                        'url' => route('pharmacy.sale-payments.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && $isSalePayment),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('core::partials.navigation-link', [
                        'label' => 'Supplier Payment',
                        'icon' => 'money-bill-transfer',
                        'url' => route('pharmacy.purchase-payments.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && $isPurchasePayment),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            </div>
        </details>
    <?php endif; ?>

    <?php echo $__env->make('pharmacy::partials.catalog-menu', [
        'catalogType'       => 'optic',
        'catalogTitle'      => 'Optic Catalog',
        'catalogLabel'      => 'Medicine/Product',
        'productLabel'      => 'Products',
        'productIcon'       => 'glasses',
        'categoryLabel'     => 'Generic/Category',
        'shelfLabel'        => 'Shelf',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if($permissions['pharmacy-reports.view'] ?? false): ?>
        <details class="app-nav-group" <?php if(($isOptic && request()->routeIs('pharmacy.reports.*'))): ?> open <?php endif; ?>>
            <summary class="app-nav-link app-nav-group-toggle<?php echo e(($isOptic && request()->routeIs('pharmacy.reports.*')) ? ' active' : ''); ?>" title="Optic reports">
                <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Report</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Stock Report',
                    'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.stock', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.stock', 'pharmacy.reports.index')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Stock Movement',
                    'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.movements', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.movements')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Profit / Loss',
                    'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.profit-loss', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.profit-loss')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Purchase Discount',
                    'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.purchase-discounts', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.purchase-discounts')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Daily Report',
                    'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.daily', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.daily')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Customer Due',
                    'icon' => 'file-invoice-dollar',
                    'url' => route('pharmacy.reports.customer-dues', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.customer-dues')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => 'Supplier Due',
                    'icon' => 'file-invoice-dollar',
                    'url' => route('pharmacy.reports.supplier-dues', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.supplier-dues')),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </details>
    <?php endif; ?>

<?php endif; ?>



<?php if($permissions['pharmacy-customers.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Customer / Patient',
        'icon' => 'users',
        'url' => route('pharmacy.customers.index'),
        'active' => request()->routeIs('pharmacy.customers.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($permissions['pharmacy-dosages.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Dosage',
        'icon' => 'capsules',
        'url' => route('pharmacy.dosages.index'),
        'active' => request()->routeIs('pharmacy.dosages.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($permissions['pharmacy-units.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Unit',
        'icon' => 'ruler',
        'url' => route('pharmacy.units.index'),
        'active' => request()->routeIs('pharmacy.units.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php if($permissions['settings.view'] ?? false): ?>
    <?php echo $__env->make('core::partials.navigation-link', [
        'label' => 'Pharmacy Settings',
        'icon' => 'gear',
        'url' => route('pharmacy.settings.index'),
        'active' => request()->routeIs('pharmacy.settings.*'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>


<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Pharmacy\resources/views/partials/menu.blade.php ENDPATH**/ ?>