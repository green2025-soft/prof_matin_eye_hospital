<?php

use Illuminate\Support\Facades\Route;
use Modules\Pharmacy\Http\Controllers\AdjustmentController;
use Modules\Pharmacy\Http\Controllers\CustomerController;
use Modules\Pharmacy\Http\Controllers\DosageController;
use Modules\Pharmacy\Http\Controllers\PharmacySelectorController;
use Modules\Pharmacy\Http\Controllers\OpeningStockController;
use Modules\Pharmacy\Http\Controllers\PaymentController;
use Modules\Pharmacy\Http\Controllers\ProductCategoryController;
use Modules\Pharmacy\Http\Controllers\ProductController;
use Modules\Pharmacy\Http\Controllers\ProductUnitController;
use Modules\Pharmacy\Http\Controllers\ProductVariantController;
use Modules\Pharmacy\Http\Controllers\PurchaseController;
use Modules\Pharmacy\Http\Controllers\PurchaseReturnController;
use Modules\Pharmacy\Http\Controllers\Reports\CustomerDueReportController;
use Modules\Pharmacy\Http\Controllers\Reports\DailyReportController;
use Modules\Pharmacy\Http\Controllers\Reports\ExpiryReportController;
use Modules\Pharmacy\Http\Controllers\Reports\ProfitLossReportController;
use Modules\Pharmacy\Http\Controllers\Reports\PurchaseDiscountReportController;
use Modules\Pharmacy\Http\Controllers\Reports\StockMovementReportController;
use Modules\Pharmacy\Http\Controllers\Reports\StockReportController;
use Modules\Pharmacy\Http\Controllers\Reports\SupplierDueReportController;
use Modules\Pharmacy\Http\Controllers\SaleController;
use Modules\Pharmacy\Http\Controllers\SaleReturnController;
use Modules\Pharmacy\Http\Controllers\ShelfController;
use Modules\Pharmacy\Http\Controllers\SupplierController;
use Modules\Pharmacy\Http\Controllers\TransferController;
use Modules\Pharmacy\Http\Controllers\UnitController;
use Modules\Pharmacy\Http\Controllers\WastageController;
use Modules\TenantCore\Http\Controllers\ModuleDashboardController;

Route::middleware(['web', 'auth', 'tenant'])->prefix('pharmacy')->name('pharmacy.')->group(function (): void {
    Route::get('selectors/categories', [ProductCategoryController::class, 'options'])->name('categories.options');
    Route::get('selectors/dosages', [DosageController::class, 'options'])->name('dosages.options');
    Route::get('selectors/units', [UnitController::class, 'options'])->name('units.options');
    Route::get('selectors/shelves', [ShelfController::class, 'options'])->name('shelves.options');
    Route::get('selectors/suppliers', [SupplierController::class, 'options'])->name('suppliers.options');
    Route::get('selectors/products', [ProductController::class, 'options'])->name('products.options');
    Route::get('selectors/variants', [ProductVariantController::class, 'options'])->name('variants.options');
    Route::get('selectors/product-units', [ProductUnitController::class, 'options'])->name('product-units.options');

    Route::middleware(['subscribed', 'permission:pharmacy.view', 'branch:required'])->group(function (): void {
        Route::get('/', [ModuleDashboardController::class, 'show'])->defaults('module', 'Pharmacy')->name('dashboard');
        Route::get('selectors/items', [PharmacySelectorController::class, 'items'])->name('items.options');
        Route::get('selectors/report-products', [PharmacySelectorController::class, 'reportProducts'])->name('reports.products');
        Route::get('selectors/lots', [PharmacySelectorController::class, 'lots'])->name('lots.options');
        Route::get('selectors/purchase-sources', [PharmacySelectorController::class, 'purchaseSources'])->name('purchase-sources.options');
        Route::get('selectors/sale-sources', [PharmacySelectorController::class, 'saleSources'])->name('sale-sources.options');
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
