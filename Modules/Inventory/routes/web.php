<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\AdjustmentController;
use Modules\Inventory\Http\Controllers\CustomerController;
use Modules\Inventory\Http\Controllers\DosageController;
use Modules\Inventory\Http\Controllers\InventorySelectorController;
use Modules\Inventory\Http\Controllers\OpeningStockController;
use Modules\Inventory\Http\Controllers\PaymentController;
use Modules\Inventory\Http\Controllers\ProductCategoryController;
use Modules\Inventory\Http\Controllers\ProductController;
use Modules\Inventory\Http\Controllers\ProductUnitController;
use Modules\Inventory\Http\Controllers\ProductVariantController;
use Modules\Inventory\Http\Controllers\PurchaseController;
use Modules\Inventory\Http\Controllers\PurchaseReturnController;
use Modules\Inventory\Http\Controllers\Reports\CustomerDueReportController;
use Modules\Inventory\Http\Controllers\Reports\DailyReportController;
use Modules\Inventory\Http\Controllers\Reports\ExpiryReportController;
use Modules\Inventory\Http\Controllers\Reports\ProfitLossReportController;
use Modules\Inventory\Http\Controllers\Reports\PurchaseDiscountReportController;
use Modules\Inventory\Http\Controllers\Reports\StockMovementReportController;
use Modules\Inventory\Http\Controllers\Reports\StockReportController;
use Modules\Inventory\Http\Controllers\Reports\SupplierDueReportController;
use Modules\Inventory\Http\Controllers\SaleController;
use Modules\Inventory\Http\Controllers\SaleReturnController;
use Modules\Inventory\Http\Controllers\ShelfController;
use Modules\Inventory\Http\Controllers\SupplierController;
use Modules\Inventory\Http\Controllers\TransferController;
use Modules\Inventory\Http\Controllers\UnitController;
use Modules\Inventory\Http\Controllers\WastageController;
use Modules\TenantCore\Http\Controllers\ModuleDashboardController;

Route::middleware(['web', 'auth', 'tenant'])->prefix('inventory')->name('inventory.')->group(function (): void {
    Route::get('selectors/categories', [ProductCategoryController::class, 'options'])->name('categories.options');
    Route::get('selectors/dosages', [DosageController::class, 'options'])->name('dosages.options');
    Route::get('selectors/units', [UnitController::class, 'options'])->name('units.options');
    Route::get('selectors/shelves', [ShelfController::class, 'options'])->name('shelves.options');
    Route::get('selectors/suppliers', [SupplierController::class, 'options'])->name('suppliers.options');
    Route::get('selectors/products', [ProductController::class, 'options'])->name('products.options');
    Route::get('selectors/variants', [ProductVariantController::class, 'options'])->name('variants.options');
    Route::get('selectors/product-units', [ProductUnitController::class, 'options'])->name('product-units.options');

    Route::middleware(['subscribed', 'permission:inventory.view', 'branch:required'])->group(function (): void {
        Route::get('/', [ModuleDashboardController::class, 'show'])->defaults('module', 'Inventory')->name('dashboard');
        Route::get('selectors/items', [InventorySelectorController::class, 'items'])->name('items.options');
        Route::get('selectors/report-products', [InventorySelectorController::class, 'reportProducts'])->name('reports.products');
        Route::get('selectors/lots', [InventorySelectorController::class, 'lots'])->name('lots.options');
        Route::get('selectors/purchase-sources', [InventorySelectorController::class, 'purchaseSources'])->name('purchase-sources.options');
        Route::get('selectors/sale-sources', [InventorySelectorController::class, 'saleSources'])->name('sale-sources.options');
        Route::get('selectors/customers', [CustomerController::class, 'choices'])->name('customers.options');
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');

        Route::resource('categories', ProductCategoryController::class)->parameters(['categories' => 'product_category'])->except('show');
        Route::resource('dosages', DosageController::class)->parameters(['dosages' => 'dosage'])->except('show');
        Route::resource('units', UnitController::class)->parameters(['units' => 'unit'])->except('show');
        Route::resource('shelves', ShelfController::class)->parameters(['shelves' => 'shelf'])->except('show');
        Route::resource('suppliers', SupplierController::class)->parameters(['suppliers' => 'supplier'])->except('show');
        Route::resource('products', ProductController::class)->parameters(['products' => 'product'])->except('show');
        Route::resource('variants', ProductVariantController::class)->parameters(['variants' => 'product_variant'])->except('show');
        Route::resource('product-units', ProductUnitController::class)->parameters(['product-units' => 'product_unit'])->except('show');

        Route::post('sales/{sale}/void', [SaleController::class, 'reverse'])->name('sales.void');
        Route::resource('sales', SaleController::class);
        Route::post('purchases/{purchase}/void', [PurchaseController::class, 'reverse'])->name('purchases.void');
        Route::resource('purchases', PurchaseController::class);
        Route::post('sale-returns/{sale_return}/void', [SaleReturnController::class, 'reverse'])->name('sale-returns.void');
        Route::resource('sale-returns', SaleReturnController::class);
        Route::post('purchase-returns/{purchase_return}/void', [PurchaseReturnController::class, 'reverse'])->name('purchase-returns.void');
        Route::resource('purchase-returns', PurchaseReturnController::class);
        Route::post('opening-stock/{opening_stock}/void', [OpeningStockController::class, 'reverse'])->name('opening-stock.void');
        Route::resource('opening-stock', OpeningStockController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('adjustments/{adjustment}/void', [AdjustmentController::class, 'reverse'])->name('adjustments.void');
        Route::resource('adjustments', AdjustmentController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('damage/{wastage}/void', [WastageController::class, 'reverse'])->name('damage.void');
        Route::resource('damage', WastageController::class)->parameters(['damage' => 'wastage'])->only(['index', 'create', 'store', 'show']);
        Route::post('transfers/{transfer}/void', [TransferController::class, 'reverse'])->name('transfers.void');
        Route::resource('transfers', TransferController::class)->only(['index', 'create', 'store', 'show']);

        Route::get('payments/invoices', [PaymentController::class, 'invoices'])->name('payments.invoices');
        Route::get('selectors/payment-customers', [PaymentController::class, 'customers'])->name('payments.customers');
        Route::get('selectors/payment-suppliers', [PaymentController::class, 'suppliers'])->name('payments.suppliers');
        Route::post('payments/{payment}/void', [PaymentController::class, 'reverse'])->name('payments.void');
        Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'show']);
        Route::prefix('reports')->name('reports.')->group(function (): void {
            Route::get('/', [StockReportController::class, 'index'])->name('index');
            Route::get('stock', [StockReportController::class, 'index'])->name('stock');
            Route::get('movements', [StockMovementReportController::class, 'index'])->name('movements');
            Route::get('expiry', [ExpiryReportController::class, 'index'])->name('expiry');
            Route::get('profit-loss', [ProfitLossReportController::class, 'index'])->name('profit-loss');
            Route::get('purchase-discounts', [PurchaseDiscountReportController::class, 'index'])->name('purchase-discounts');
            Route::get('daily', [DailyReportController::class, 'index'])->name('daily');
            Route::get('customer-dues', [CustomerDueReportController::class, 'index'])->name('customer-dues');
            Route::get('supplier-dues', [SupplierDueReportController::class, 'index'])->name('supplier-dues');
        });
    });
});
