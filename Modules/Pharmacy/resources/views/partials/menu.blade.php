@php
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
    $paymentDirection = request()->attributes->get('pharmacy_payment_direction', request('direction', 'customer'));
@endphp

@if ($hasModuleMenu)
    <p class="app-nav-label">Medicine</p>

    @if ($hasSalesMenu)
        <details class="app-nav-group" @if (($isMedicine && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ ($isMedicine && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*')) ? ' active' : '' }}" title="Medicine Sales">
                <span class="app-nav-icon"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Sales</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($permissions['pharmacy-sales.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Sales', 'icon' => 'receipt',
                        'url' => route('pharmacy.sales.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.sales.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-sale-returns.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Sales returns', 'icon' => 'rotate-left',
                        'url' => route('pharmacy.sale-returns.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.sale-returns.*')),
                    ])
                @endif
            </div>
        </details>
    @endif

    @if ($hasPurchasesMenu)
        <details class="app-nav-group" @if (($isMedicine && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ ($isMedicine && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*')) ? ' active' : '' }}" title="Medicine Purchases">
                <span class="app-nav-icon"><i class="fa-solid fa-truck-ramp-box" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Purchases</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($permissions['pharmacy-purchases.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Purchases', 'icon' => 'file-invoice',
                        'url' => route('pharmacy.purchases.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.purchases.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-purchase-returns.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Purchase returns', 'icon' => 'rotate-left',
                        'url' => route('pharmacy.purchase-returns.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.purchase-returns.*')),
                    ])
                @endif
            </div>
        </details>
    @endif

    @if ($hasStockMenu)
        <details class="app-nav-group" @if (($isMedicine && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ ($isMedicine && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*')) ? ' active' : '' }}" title="Medicine Stock">
                <span class="app-nav-icon"><i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Stock</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($permissions['pharmacy-opening-stock.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Opening stock', 'icon' => 'box-open',
                        'url' => route('pharmacy.opening-stock.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.opening-stock.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-adjustments.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Adjustments', 'icon' => 'sliders',
                        'url' => route('pharmacy.adjustments.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.adjustments.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-damage.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Damage / wastage', 'icon' => 'box-archive',
                        'url' => route('pharmacy.damage.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.damage.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-transfers.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Branch transfers', 'icon' => 'arrow-right-arrow-left',
                        'url' => route('pharmacy.transfers.index', ['product_type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.transfers.*')),
                    ])
                @endif
            </div>
        </details>
    @endif

    @if ($hasPeopleMenu)
        <details class="app-nav-group" @if (($isMedicine && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.payments.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ ($isMedicine && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.payments.*')) ? ' active' : '' }}" title="Medicine People & payments">
                <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                <span class="app-nav-copy">People & payments</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($permissions['pharmacy-suppliers.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Suppliers', 'icon' => 'truck',
                        'url' => route('pharmacy.suppliers.index', ['type' => 'medicine']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.suppliers.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-payments.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Customer payments', 'icon' => 'money-bill-transfer',
                        'url' => route('pharmacy.payments.index', ['product_type' => 'medicine', 'direction' => 'customer']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.payments.*') && $paymentDirection === 'customer'),
                    ])
                    @include('core::partials.navigation-link', [
                        'label' => 'Supplier payments', 'icon' => 'money-bill-transfer',
                        'url' => route('pharmacy.payments.index', ['product_type' => 'medicine', 'direction' => 'supplier']),
                        'active' => ($isMedicine && request()->routeIs('pharmacy.payments.*') && $paymentDirection === 'supplier'),
                    ])
                @endif
            </div>
        </details>
    @endif

    @include('pharmacy::partials.catalog-menu', [
        'catalogType'   => 'medicine',
        'catalogTitle'  => 'Medicine Catalog',
        'catalogLabel'  => 'Medicine/Product',
        'productLabel'  => 'Medicine/Product',
        'productIcon'   => 'pills',
        'categoryLabel' => 'Generic/Category',
        'shelfLabel'    => 'Shelf',
    ])

    @if ($permissions['pharmacy-reports.view'] ?? false)
        <details class="app-nav-group" @if (($isMedicine && request()->routeIs('pharmacy.reports.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ ($isMedicine && request()->routeIs('pharmacy.reports.*')) ? ' active' : '' }}" title="Medicine reports">
                <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Reports</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @include('core::partials.navigation-link', [
                    'label' => 'Stock report', 'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.stock', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.stock', 'pharmacy.reports.index')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Stock movements', 'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.movements', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.movements')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Expiry report', 'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.expiry', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.expiry')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Profit / loss', 'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.profit-loss', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.profit-loss')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Purchase discounts', 'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.purchase-discounts', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.purchase-discounts')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Daily report', 'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.daily', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.daily')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Customer dues', 'icon' => 'file-invoice-dollar',
                    'url' => route('pharmacy.reports.customer-dues', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.customer-dues')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Supplier dues', 'icon' => 'file-invoice-dollar',
                    'url' => route('pharmacy.reports.supplier-dues', ['product_type' => 'medicine']),
                    'active' => ($isMedicine && request()->routeIs('pharmacy.reports.supplier-dues')),
                ])
            </div>
        </details>
    @endif

    <p class="app-nav-label">Optic</p>

    @if ($hasSalesMenu)
        <details class="app-nav-group" @if (($isOptic && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ ($isOptic && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*')) ? ' active' : '' }}" title="Optic Sales">
                <span class="app-nav-icon"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Sales</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($permissions['pharmacy-sales.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Sales', 'icon' => 'receipt',
                        'url' => route('pharmacy.sales.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.sales.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-sale-returns.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Sales returns', 'icon' => 'rotate-left',
                        'url' => route('pharmacy.sale-returns.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.sale-returns.*')),
                    ])
                @endif
            </div>
        </details>
    @endif

    @if ($hasPurchasesMenu)
        <details class="app-nav-group" @if (($isOptic && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ ($isOptic && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*')) ? ' active' : '' }}" title="Optic Purchases">
                <span class="app-nav-icon"><i class="fa-solid fa-truck-ramp-box" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Purchases</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($permissions['pharmacy-purchases.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Purchases', 'icon' => 'file-invoice',
                        'url' => route('pharmacy.purchases.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.purchases.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-purchase-returns.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Purchase returns', 'icon' => 'rotate-left',
                        'url' => route('pharmacy.purchase-returns.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.purchase-returns.*')),
                    ])
                @endif
            </div>
        </details>
    @endif

    @if ($hasStockMenu)
        <details class="app-nav-group" @if (($isOptic && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ ($isOptic && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*')) ? ' active' : '' }}" title="Optic Stock">
                <span class="app-nav-icon"><i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Stock</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($permissions['pharmacy-opening-stock.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Opening stock', 'icon' => 'box-open',
                        'url' => route('pharmacy.opening-stock.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.opening-stock.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-adjustments.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Adjustments', 'icon' => 'sliders',
                        'url' => route('pharmacy.adjustments.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.adjustments.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-damage.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Damage / wastage', 'icon' => 'box-archive',
                        'url' => route('pharmacy.damage.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.damage.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-transfers.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Branch transfers', 'icon' => 'arrow-right-arrow-left',
                        'url' => route('pharmacy.transfers.index', ['product_type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.transfers.*')),
                    ])
                @endif
            </div>
        </details>
    @endif

    @if ($hasPeopleMenu)
        <details class="app-nav-group" @if (($isOptic && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.payments.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ ($isOptic && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.payments.*')) ? ' active' : '' }}" title="Optic People & payments">
                <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                <span class="app-nav-copy">People & payments</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($permissions['pharmacy-suppliers.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Suppliers', 'icon' => 'truck',
                        'url' => route('pharmacy.suppliers.index', ['type' => 'optic']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.suppliers.*')),
                    ])
                @endif
                @if ($permissions['pharmacy-payments.view'] ?? false)
                    @include('core::partials.navigation-link', [
                        'label' => 'Customer payments', 'icon' => 'money-bill-transfer',
                        'url' => route('pharmacy.payments.index', ['product_type' => 'optic', 'direction' => 'customer']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.payments.*') && $paymentDirection === 'customer'),
                    ])
                    @include('core::partials.navigation-link', [
                        'label' => 'Supplier payments', 'icon' => 'money-bill-transfer',
                        'url' => route('pharmacy.payments.index', ['product_type' => 'optic', 'direction' => 'supplier']),
                        'active' => ($isOptic && request()->routeIs('pharmacy.payments.*') && $paymentDirection === 'supplier'),
                    ])
                @endif
            </div>
        </details>
    @endif

    @include('pharmacy::partials.catalog-menu', [
        'catalogType'       => 'optic',
        'catalogTitle'      => 'Optic Catalog',
        'catalogLabel'      => 'Medicine/Product',
        'productLabel'      => 'Products',
        'productIcon'       => 'glasses',
        'categoryLabel'     => 'Generic/Category',
        'shelfLabel'        => 'Shelf',
    ])

    @if ($permissions['pharmacy-reports.view'] ?? false)
        <details class="app-nav-group" @if (($isOptic && request()->routeIs('pharmacy.reports.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ ($isOptic && request()->routeIs('pharmacy.reports.*')) ? ' active' : '' }}" title="Optic reports">
                <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Reports</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @include('core::partials.navigation-link', [
                    'label' => 'Stock report', 'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.stock', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.stock', 'pharmacy.reports.index')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Stock movements', 'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.movements', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.movements')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Profit / loss', 'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.profit-loss', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.profit-loss')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Purchase discounts', 'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.purchase-discounts', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.purchase-discounts')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Daily report', 'icon' => 'chart-column',
                    'url' => route('pharmacy.reports.daily', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.daily')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Customer dues', 'icon' => 'file-invoice-dollar',
                    'url' => route('pharmacy.reports.customer-dues', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.customer-dues')),
                ])
                @include('core::partials.navigation-link', [
                    'label' => 'Supplier dues', 'icon' => 'file-invoice-dollar',
                    'url' => route('pharmacy.reports.supplier-dues', ['product_type' => 'optic']),
                    'active' => ($isOptic && request()->routeIs('pharmacy.reports.supplier-dues')),
                ])
            </div>
        </details>
    @endif

@endif

@if ($hasSharedSetup)
    <p class="app-nav-label">Shared setup</p>
@endif
@if ($permissions['pharmacy-customers.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Customers / patients', 'icon' => 'users',
        'url' => route('pharmacy.customers.index'),
        'active' => request()->routeIs('pharmacy.customers.*'),
    ])
@endif
@if ($permissions['pharmacy-dosages.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Dosages', 'icon' => 'capsules',
        'url' => route('pharmacy.dosages.index'),
        'active' => request()->routeIs('pharmacy.dosages.*'),
    ])
@endif
@if ($permissions['pharmacy-units.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Units', 'icon' => 'ruler',
        'url' => route('pharmacy.units.index'),
        'active' => request()->routeIs('pharmacy.units.*'),
    ])
@endif
@if ($permissions['settings.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Pharmacy settings', 'icon' => 'gear',
        'url' => route('pharmacy.settings.index'),
        'active' => request()->routeIs('pharmacy.settings.*'),
    ])
@endif
