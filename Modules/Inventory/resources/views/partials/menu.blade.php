@if (($moduleMenu['permissions']['inventory-sales.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-sale-returns.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-purchases.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-purchase-returns.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-opening-stock.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-adjustments.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-damage.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-transfers.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-suppliers.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-payments.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-products.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-categories.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-shelves.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-reports.view'] ?? false))
    <p class="app-nav-label">Medicine</p>

    @if (($moduleMenu['permissions']['inventory-sales.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-sale-returns.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.sales.*', 'inventory.sale-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.sales.*', 'inventory.sale-returns.*')) ? ' active' : '' }}" title="Medicine Sales">
                <span class="app-nav-icon"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Sales</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['inventory-sales.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.sales.*')) ? ' active' : '' }}" href="{{ route('inventory.sales.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.sales.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-receipt" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Sales</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-sale-returns.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.sale-returns.*')) ? ' active' : '' }}" href="{{ route('inventory.sale-returns.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.sale-returns.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Sales returns</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['inventory-purchases.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-purchase-returns.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.purchases.*', 'inventory.purchase-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.purchases.*', 'inventory.purchase-returns.*')) ? ' active' : '' }}" title="Medicine Purchases">
                <span class="app-nav-icon"><i class="fa-solid fa-truck-ramp-box" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Purchases</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['inventory-purchases.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.purchases.*')) ? ' active' : '' }}" href="{{ route('inventory.purchases.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.purchases.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-file-invoice" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Purchases</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-purchase-returns.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.purchase-returns.*')) ? ' active' : '' }}" href="{{ route('inventory.purchase-returns.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.purchase-returns.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Purchase returns</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['inventory-opening-stock.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-adjustments.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-damage.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-transfers.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.opening-stock.*', 'inventory.adjustments.*', 'inventory.damage.*', 'inventory.transfers.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.opening-stock.*', 'inventory.adjustments.*', 'inventory.damage.*', 'inventory.transfers.*')) ? ' active' : '' }}" title="Medicine Stock">
                <span class="app-nav-icon"><i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Stock</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['inventory-opening-stock.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.opening-stock.*')) ? ' active' : '' }}" href="{{ route('inventory.opening-stock.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.opening-stock.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-box-open" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Opening stock</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-adjustments.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.adjustments.*')) ? ' active' : '' }}" href="{{ route('inventory.adjustments.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.adjustments.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Adjustments</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-damage.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.damage.*')) ? ' active' : '' }}" href="{{ route('inventory.damage.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.damage.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-box-archive" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Damage / wastage</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-transfers.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.transfers.*')) ? ' active' : '' }}" href="{{ route('inventory.transfers.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.transfers.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Branch transfers</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['inventory-suppliers.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-payments.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.suppliers.*', 'inventory.payments.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.suppliers.*', 'inventory.payments.*')) ? ' active' : '' }}" title="Medicine People & payments">
                <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                <span class="app-nav-copy">People & payments</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['inventory-suppliers.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.suppliers.*')) ? ' active' : '' }}" href="{{ route('inventory.suppliers.index', ['type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.suppliers.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-truck" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Suppliers</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-payments.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.payments.*') && request()->attributes->get('inventory_payment_direction', request('direction', 'customer')) === 'customer') ? ' active' : '' }}" href="{{ route('inventory.payments.index', ['product_type' => 'medicine', 'direction' => 'customer']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.payments.*') && request()->attributes->get('inventory_payment_direction', request('direction', 'customer')) === 'customer')) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-money-bill-transfer" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Customer payments</span>
                    </a>
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.payments.*') && request()->attributes->get('inventory_payment_direction', request('direction', 'customer')) === 'supplier') ? ' active' : '' }}" href="{{ route('inventory.payments.index', ['product_type' => 'medicine', 'direction' => 'supplier']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.payments.*') && request()->attributes->get('inventory_payment_direction', request('direction', 'customer')) === 'supplier')) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-money-bill-transfer" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Supplier payments</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['inventory-products.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-categories.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-shelves.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.products.*', 'inventory.categories.*', 'inventory.shelves.*', 'inventory.variants.*', 'inventory.product-units.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.products.*', 'inventory.categories.*', 'inventory.shelves.*', 'inventory.variants.*', 'inventory.product-units.*')) ? ' active' : '' }}" title="Medicine Catalog">
                <span class="app-nav-icon"><i class="fa-solid fa-list" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Catalog</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['inventory-products.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.products.*')) ? ' active' : '' }}" href="{{ route('inventory.products.index', ['type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.products.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-pills" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Products</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-categories.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.categories.*')) ? ' active' : '' }}" href="{{ route('inventory.categories.index', ['type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.categories.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Category / generic</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-shelves.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.shelves.*')) ? ' active' : '' }}" href="{{ route('inventory.shelves.index', ['type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.shelves.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-warehouse" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Shelves</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if ($moduleMenu['permissions']['inventory-reports.view'] ?? false)
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.*')) ? ' active' : '' }}" title="Medicine reports">
                <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Reports</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.stock', 'inventory.reports.index')) ? ' active' : '' }}" href="{{ route('inventory.reports.stock', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.stock', 'inventory.reports.index'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Stock report</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.movements')) ? ' active' : '' }}" href="{{ route('inventory.reports.movements', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.movements'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Stock movements</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.expiry')) ? ' active' : '' }}" href="{{ route('inventory.reports.expiry', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.expiry'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Expiry report</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.profit-loss')) ? ' active' : '' }}" href="{{ route('inventory.reports.profit-loss', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.profit-loss'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Profit / loss</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.purchase-discounts')) ? ' active' : '' }}" href="{{ route('inventory.reports.purchase-discounts', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.purchase-discounts'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Purchase discounts</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.daily')) ? ' active' : '' }}" href="{{ route('inventory.reports.daily', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.daily'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Daily report</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.customer-dues')) ? ' active' : '' }}" href="{{ route('inventory.reports.customer-dues', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.customer-dues'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Customer dues</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.supplier-dues')) ? ' active' : '' }}" href="{{ route('inventory.reports.supplier-dues', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('inventory.reports.supplier-dues'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Supplier dues</span>
                </a>
            </div>
        </details>
    @endif

    <p class="app-nav-label">Optic</p>

    @if (($moduleMenu['permissions']['inventory-sales.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-sale-returns.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.sales.*', 'inventory.sale-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.sales.*', 'inventory.sale-returns.*')) ? ' active' : '' }}" title="Optic Sales">
                <span class="app-nav-icon"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Sales</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['inventory-sales.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.sales.*')) ? ' active' : '' }}" href="{{ route('inventory.sales.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.sales.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-receipt" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Sales</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-sale-returns.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.sale-returns.*')) ? ' active' : '' }}" href="{{ route('inventory.sale-returns.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.sale-returns.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Sales returns</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['inventory-purchases.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-purchase-returns.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.purchases.*', 'inventory.purchase-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.purchases.*', 'inventory.purchase-returns.*')) ? ' active' : '' }}" title="Optic Purchases">
                <span class="app-nav-icon"><i class="fa-solid fa-truck-ramp-box" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Purchases</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['inventory-purchases.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.purchases.*')) ? ' active' : '' }}" href="{{ route('inventory.purchases.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.purchases.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-file-invoice" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Purchases</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-purchase-returns.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.purchase-returns.*')) ? ' active' : '' }}" href="{{ route('inventory.purchase-returns.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.purchase-returns.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Purchase returns</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['inventory-opening-stock.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-adjustments.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-damage.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-transfers.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.opening-stock.*', 'inventory.adjustments.*', 'inventory.damage.*', 'inventory.transfers.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.opening-stock.*', 'inventory.adjustments.*', 'inventory.damage.*', 'inventory.transfers.*')) ? ' active' : '' }}" title="Optic Stock">
                <span class="app-nav-icon"><i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Stock</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['inventory-opening-stock.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.opening-stock.*')) ? ' active' : '' }}" href="{{ route('inventory.opening-stock.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.opening-stock.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-box-open" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Opening stock</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-adjustments.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.adjustments.*')) ? ' active' : '' }}" href="{{ route('inventory.adjustments.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.adjustments.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Adjustments</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-damage.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.damage.*')) ? ' active' : '' }}" href="{{ route('inventory.damage.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.damage.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-box-archive" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Damage / wastage</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-transfers.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.transfers.*')) ? ' active' : '' }}" href="{{ route('inventory.transfers.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.transfers.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Branch transfers</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['inventory-suppliers.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-payments.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.suppliers.*', 'inventory.payments.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.suppliers.*', 'inventory.payments.*')) ? ' active' : '' }}" title="Optic People & payments">
                <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                <span class="app-nav-copy">People & payments</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['inventory-suppliers.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.suppliers.*')) ? ' active' : '' }}" href="{{ route('inventory.suppliers.index', ['type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.suppliers.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-truck" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Suppliers</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-payments.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.payments.*') && request()->attributes->get('inventory_payment_direction', request('direction', 'customer')) === 'customer') ? ' active' : '' }}" href="{{ route('inventory.payments.index', ['product_type' => 'optic', 'direction' => 'customer']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.payments.*') && request()->attributes->get('inventory_payment_direction', request('direction', 'customer')) === 'customer')) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-money-bill-transfer" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Customer payments</span>
                    </a>
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.payments.*') && request()->attributes->get('inventory_payment_direction', request('direction', 'customer')) === 'supplier') ? ' active' : '' }}" href="{{ route('inventory.payments.index', ['product_type' => 'optic', 'direction' => 'supplier']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.payments.*') && request()->attributes->get('inventory_payment_direction', request('direction', 'customer')) === 'supplier')) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-money-bill-transfer" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Supplier payments</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['inventory-products.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-categories.view'] ?? false)
        || ($moduleMenu['permissions']['inventory-shelves.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.products.*', 'inventory.categories.*', 'inventory.shelves.*', 'inventory.variants.*', 'inventory.product-units.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.products.*', 'inventory.categories.*', 'inventory.shelves.*', 'inventory.variants.*', 'inventory.product-units.*')) ? ' active' : '' }}" title="Optic Catalog">
                <span class="app-nav-icon"><i class="fa-solid fa-list" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Catalog</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['inventory-products.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.products.*')) ? ' active' : '' }}" href="{{ route('inventory.products.index', ['type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.products.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-glasses" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Products</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-categories.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.categories.*')) ? ' active' : '' }}" href="{{ route('inventory.categories.index', ['type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.categories.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Category / generic</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['inventory-shelves.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.shelves.*')) ? ' active' : '' }}" href="{{ route('inventory.shelves.index', ['type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.shelves.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-warehouse" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Shelves</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if ($moduleMenu['permissions']['inventory-reports.view'] ?? false)
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.*')) ? ' active' : '' }}" title="Optic reports">
                <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Reports</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.stock', 'inventory.reports.index')) ? ' active' : '' }}" href="{{ route('inventory.reports.stock', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.stock', 'inventory.reports.index'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Stock report</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.movements')) ? ' active' : '' }}" href="{{ route('inventory.reports.movements', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.movements'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Stock movements</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.profit-loss')) ? ' active' : '' }}" href="{{ route('inventory.reports.profit-loss', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.profit-loss'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Profit / loss</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.purchase-discounts')) ? ' active' : '' }}" href="{{ route('inventory.reports.purchase-discounts', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.purchase-discounts'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Purchase discounts</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.daily')) ? ' active' : '' }}" href="{{ route('inventory.reports.daily', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.daily'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Daily report</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.customer-dues')) ? ' active' : '' }}" href="{{ route('inventory.reports.customer-dues', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.customer-dues'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Customer dues</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.supplier-dues')) ? ' active' : '' }}" href="{{ route('inventory.reports.supplier-dues', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('inventory.reports.supplier-dues'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Supplier dues</span>
                </a>
            </div>
        </details>
    @endif

@endif

@if (($moduleMenu['permissions']['inventory-customers.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-dosages.view'] ?? false)
    || ($moduleMenu['permissions']['inventory-units.view'] ?? false)
    || ($moduleMenu['permissions']['settings.view'] ?? false))
    <p class="app-nav-label">Shared setup</p>
@endif
@if ($moduleMenu['permissions']['inventory-customers.view'] ?? false)
    <a class="app-nav-link{{ request()->routeIs('inventory.customers.*') ? ' active' : '' }}" href="{{ route('inventory.customers.index') }}" @if (request()->routeIs('inventory.customers.*')) aria-current="page" @endif>
        <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Customers / patients</span>
    </a>
@endif
@if ($moduleMenu['permissions']['inventory-dosages.view'] ?? false)
    <a class="app-nav-link{{ request()->routeIs('inventory.dosages.*') ? ' active' : '' }}" href="{{ route('inventory.dosages.index') }}" @if (request()->routeIs('inventory.dosages.*')) aria-current="page" @endif>
        <span class="app-nav-icon"><i class="fa-solid fa-capsules" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Dosages</span>
    </a>
@endif
@if ($moduleMenu['permissions']['inventory-units.view'] ?? false)
    <a class="app-nav-link{{ request()->routeIs('inventory.units.*') ? ' active' : '' }}" href="{{ route('inventory.units.index') }}" @if (request()->routeIs('inventory.units.*')) aria-current="page" @endif>
        <span class="app-nav-icon"><i class="fa-solid fa-ruler" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Units</span>
    </a>
@endif
@if ($moduleMenu['permissions']['settings.view'] ?? false)
    <a class="app-nav-link" href="{{ route('tenantcore.settings.index', ['group' => 'inventory']) }}">
        <span class="app-nav-icon"><i class="fa-solid fa-gear" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Inventory settings</span>
    </a>
@endif
