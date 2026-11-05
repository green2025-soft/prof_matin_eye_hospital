@if (($moduleMenu['permissions']['pharmacy-sales.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-sale-returns.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-purchases.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-purchase-returns.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-opening-stock.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-adjustments.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-damage.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-transfers.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-suppliers.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-payments.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-products.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-categories.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-shelves.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-reports.view'] ?? false))
    <p class="app-nav-label">Medicine</p>

    @if (($moduleMenu['permissions']['pharmacy-sales.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-sale-returns.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*')) ? ' active' : '' }}" title="Medicine Sales">
                <span class="app-nav-icon"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Sales</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['pharmacy-sales.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.sales.*')) ? ' active' : '' }}" href="{{ route('pharmacy.sales.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.sales.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-receipt" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Sales</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-sale-returns.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.sale-returns.*')) ? ' active' : '' }}" href="{{ route('pharmacy.sale-returns.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.sale-returns.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Sales returns</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['pharmacy-purchases.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-purchase-returns.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*')) ? ' active' : '' }}" title="Medicine Purchases">
                <span class="app-nav-icon"><i class="fa-solid fa-truck-ramp-box" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Purchases</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['pharmacy-purchases.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.purchases.*')) ? ' active' : '' }}" href="{{ route('pharmacy.purchases.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.purchases.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-file-invoice" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Purchases</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-purchase-returns.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.purchase-returns.*')) ? ' active' : '' }}" href="{{ route('pharmacy.purchase-returns.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.purchase-returns.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Purchase returns</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['pharmacy-opening-stock.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-adjustments.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-damage.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-transfers.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*')) ? ' active' : '' }}" title="Medicine Stock">
                <span class="app-nav-icon"><i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Stock</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['pharmacy-opening-stock.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.opening-stock.*')) ? ' active' : '' }}" href="{{ route('pharmacy.opening-stock.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.opening-stock.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-box-open" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Opening stock</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-adjustments.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.adjustments.*')) ? ' active' : '' }}" href="{{ route('pharmacy.adjustments.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.adjustments.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Adjustments</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-damage.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.damage.*')) ? ' active' : '' }}" href="{{ route('pharmacy.damage.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.damage.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-box-archive" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Damage / wastage</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-transfers.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.transfers.*')) ? ' active' : '' }}" href="{{ route('pharmacy.transfers.index', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.transfers.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Branch transfers</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['pharmacy-suppliers.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-payments.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.payments.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.payments.*')) ? ' active' : '' }}" title="Medicine People & payments">
                <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                <span class="app-nav-copy">People & payments</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['pharmacy-suppliers.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.suppliers.*')) ? ' active' : '' }}" href="{{ route('pharmacy.suppliers.index', ['type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.suppliers.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-truck" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Suppliers</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-payments.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.payments.*') && request()->attributes->get('pharmacy_payment_direction', request('direction', 'customer')) === 'customer') ? ' active' : '' }}" href="{{ route('pharmacy.payments.index', ['product_type' => 'medicine', 'direction' => 'customer']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.payments.*') && request()->attributes->get('pharmacy_payment_direction', request('direction', 'customer')) === 'customer')) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-money-bill-transfer" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Customer payments</span>
                    </a>
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.payments.*') && request()->attributes->get('pharmacy_payment_direction', request('direction', 'customer')) === 'supplier') ? ' active' : '' }}" href="{{ route('pharmacy.payments.index', ['product_type' => 'medicine', 'direction' => 'supplier']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.payments.*') && request()->attributes->get('pharmacy_payment_direction', request('direction', 'customer')) === 'supplier')) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-money-bill-transfer" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Supplier payments</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['pharmacy-products.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-categories.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-shelves.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.products.*', 'pharmacy.categories.*', 'pharmacy.shelves.*', 'pharmacy.variants.*', 'pharmacy.product-units.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.products.*', 'pharmacy.categories.*', 'pharmacy.shelves.*', 'pharmacy.variants.*', 'pharmacy.product-units.*')) ? ' active' : '' }}" title="Medicine Catalog">
                <span class="app-nav-icon"><i class="fa-solid fa-list" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Catalog</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['pharmacy-products.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.products.*')) ? ' active' : '' }}" href="{{ route('pharmacy.products.index', ['type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.products.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-pills" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Products</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-categories.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.categories.*')) ? ' active' : '' }}" href="{{ route('pharmacy.categories.index', ['type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.categories.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Category / generic</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-shelves.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.shelves.*')) ? ' active' : '' }}" href="{{ route('pharmacy.shelves.index', ['type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.shelves.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-warehouse" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Shelves</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if ($moduleMenu['permissions']['pharmacy-reports.view'] ?? false)
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.*')) ? ' active' : '' }}" title="Medicine reports">
                <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Reports</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.stock', 'pharmacy.reports.index')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.stock', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.stock', 'pharmacy.reports.index'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Stock report</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.movements')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.movements', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.movements'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Stock movements</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.expiry')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.expiry', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.expiry'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Expiry report</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.profit-loss')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.profit-loss', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.profit-loss'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Profit / loss</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.purchase-discounts')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.purchase-discounts', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.purchase-discounts'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Purchase discounts</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.daily')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.daily', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.daily'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Daily report</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.customer-dues')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.customer-dues', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.customer-dues'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Customer dues</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.supplier-dues')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.supplier-dues', ['product_type' => 'medicine']) }}" @if ((($moduleMenu['productType'] ?? null) === 'medicine' && request()->routeIs('pharmacy.reports.supplier-dues'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Supplier dues</span>
                </a>
            </div>
        </details>
    @endif

    <p class="app-nav-label">Optic</p>

    @if (($moduleMenu['permissions']['pharmacy-sales.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-sale-returns.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.sales.*', 'pharmacy.sale-returns.*')) ? ' active' : '' }}" title="Optic Sales">
                <span class="app-nav-icon"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Sales</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['pharmacy-sales.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.sales.*')) ? ' active' : '' }}" href="{{ route('pharmacy.sales.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.sales.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-receipt" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Sales</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-sale-returns.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.sale-returns.*')) ? ' active' : '' }}" href="{{ route('pharmacy.sale-returns.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.sale-returns.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Sales returns</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['pharmacy-purchases.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-purchase-returns.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.purchases.*', 'pharmacy.purchase-returns.*')) ? ' active' : '' }}" title="Optic Purchases">
                <span class="app-nav-icon"><i class="fa-solid fa-truck-ramp-box" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Purchases</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['pharmacy-purchases.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.purchases.*')) ? ' active' : '' }}" href="{{ route('pharmacy.purchases.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.purchases.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-file-invoice" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Purchases</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-purchase-returns.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.purchase-returns.*')) ? ' active' : '' }}" href="{{ route('pharmacy.purchase-returns.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.purchase-returns.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Purchase returns</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['pharmacy-opening-stock.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-adjustments.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-damage.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-transfers.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.opening-stock.*', 'pharmacy.adjustments.*', 'pharmacy.damage.*', 'pharmacy.transfers.*')) ? ' active' : '' }}" title="Optic Stock">
                <span class="app-nav-icon"><i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Stock</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['pharmacy-opening-stock.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.opening-stock.*')) ? ' active' : '' }}" href="{{ route('pharmacy.opening-stock.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.opening-stock.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-box-open" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Opening stock</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-adjustments.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.adjustments.*')) ? ' active' : '' }}" href="{{ route('pharmacy.adjustments.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.adjustments.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Adjustments</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-damage.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.damage.*')) ? ' active' : '' }}" href="{{ route('pharmacy.damage.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.damage.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-box-archive" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Damage / wastage</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-transfers.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.transfers.*')) ? ' active' : '' }}" href="{{ route('pharmacy.transfers.index', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.transfers.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Branch transfers</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['pharmacy-suppliers.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-payments.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.payments.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.suppliers.*', 'pharmacy.payments.*')) ? ' active' : '' }}" title="Optic People & payments">
                <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                <span class="app-nav-copy">People & payments</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['pharmacy-suppliers.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.suppliers.*')) ? ' active' : '' }}" href="{{ route('pharmacy.suppliers.index', ['type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.suppliers.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-truck" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Suppliers</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-payments.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.payments.*') && request()->attributes->get('pharmacy_payment_direction', request('direction', 'customer')) === 'customer') ? ' active' : '' }}" href="{{ route('pharmacy.payments.index', ['product_type' => 'optic', 'direction' => 'customer']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.payments.*') && request()->attributes->get('pharmacy_payment_direction', request('direction', 'customer')) === 'customer')) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-money-bill-transfer" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Customer payments</span>
                    </a>
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.payments.*') && request()->attributes->get('pharmacy_payment_direction', request('direction', 'customer')) === 'supplier') ? ' active' : '' }}" href="{{ route('pharmacy.payments.index', ['product_type' => 'optic', 'direction' => 'supplier']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.payments.*') && request()->attributes->get('pharmacy_payment_direction', request('direction', 'customer')) === 'supplier')) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-money-bill-transfer" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Supplier payments</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if (($moduleMenu['permissions']['pharmacy-products.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-categories.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-shelves.view'] ?? false))
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.products.*', 'pharmacy.categories.*', 'pharmacy.shelves.*', 'pharmacy.variants.*', 'pharmacy.product-units.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.products.*', 'pharmacy.categories.*', 'pharmacy.shelves.*', 'pharmacy.variants.*', 'pharmacy.product-units.*')) ? ' active' : '' }}" title="Optic Catalog">
                <span class="app-nav-icon"><i class="fa-solid fa-list" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Catalog</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                @if ($moduleMenu['permissions']['pharmacy-products.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.products.*')) ? ' active' : '' }}" href="{{ route('pharmacy.products.index', ['type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.products.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-glasses" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Products</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-categories.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.categories.*')) ? ' active' : '' }}" href="{{ route('pharmacy.categories.index', ['type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.categories.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Category / generic</span>
                    </a>
                @endif
                @if ($moduleMenu['permissions']['pharmacy-shelves.view'] ?? false)
                    <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.shelves.*')) ? ' active' : '' }}" href="{{ route('pharmacy.shelves.index', ['type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.shelves.*'))) aria-current="page" @endif>
                        <span class="app-nav-icon"><i class="fa-solid fa-warehouse" aria-hidden="true"></i></span>
                        <span class="app-nav-copy">Shelves</span>
                    </a>
                @endif
            </div>
        </details>
    @endif

    @if ($moduleMenu['permissions']['pharmacy-reports.view'] ?? false)
        <details class="app-nav-group" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.*'))) open @endif>
            <summary class="app-nav-link app-nav-group-toggle{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.*')) ? ' active' : '' }}" title="Optic reports">
                <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                <span class="app-nav-copy">Reports</span>
                <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
            </summary>
            <div class="app-nav-submenu ps-3">
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.stock', 'pharmacy.reports.index')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.stock', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.stock', 'pharmacy.reports.index'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Stock report</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.movements')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.movements', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.movements'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Stock movements</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.profit-loss')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.profit-loss', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.profit-loss'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Profit / loss</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.purchase-discounts')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.purchase-discounts', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.purchase-discounts'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Purchase discounts</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.daily')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.daily', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.daily'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Daily report</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.customer-dues')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.customer-dues', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.customer-dues'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Customer dues</span>
                </a>
                <a class="app-nav-link{{ (($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.supplier-dues')) ? ' active' : '' }}" href="{{ route('pharmacy.reports.supplier-dues', ['product_type' => 'optic']) }}" @if ((($moduleMenu['productType'] ?? null) === 'optic' && request()->routeIs('pharmacy.reports.supplier-dues'))) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Supplier dues</span>
                </a>
            </div>
        </details>
    @endif

@endif

@if (($moduleMenu['permissions']['pharmacy-customers.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-dosages.view'] ?? false)
    || ($moduleMenu['permissions']['pharmacy-units.view'] ?? false)
    || ($moduleMenu['permissions']['settings.view'] ?? false))
    <p class="app-nav-label">Shared setup</p>
@endif
@if ($moduleMenu['permissions']['pharmacy-customers.view'] ?? false)
    <a class="app-nav-link{{ request()->routeIs('pharmacy.customers.*') ? ' active' : '' }}" href="{{ route('pharmacy.customers.index') }}" @if (request()->routeIs('pharmacy.customers.*')) aria-current="page" @endif>
        <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Customers / patients</span>
    </a>
@endif
@if ($moduleMenu['permissions']['pharmacy-dosages.view'] ?? false)
    <a class="app-nav-link{{ request()->routeIs('pharmacy.dosages.*') ? ' active' : '' }}" href="{{ route('pharmacy.dosages.index') }}" @if (request()->routeIs('pharmacy.dosages.*')) aria-current="page" @endif>
        <span class="app-nav-icon"><i class="fa-solid fa-capsules" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Dosages</span>
    </a>
@endif
@if ($moduleMenu['permissions']['pharmacy-units.view'] ?? false)
    <a class="app-nav-link{{ request()->routeIs('pharmacy.units.*') ? ' active' : '' }}" href="{{ route('pharmacy.units.index') }}" @if (request()->routeIs('pharmacy.units.*')) aria-current="page" @endif>
        <span class="app-nav-icon"><i class="fa-solid fa-ruler" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Units</span>
    </a>
@endif
@if ($moduleMenu['permissions']['settings.view'] ?? false)
    <a class="app-nav-link" href="{{ route('tenantcore.settings.index', ['group' => 'pharmacy']) }}">
        <span class="app-nav-icon"><i class="fa-solid fa-gear" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Pharmacy settings</span>
    </a>
@endif
