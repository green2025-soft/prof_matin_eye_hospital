<?php

namespace Modules\Pharmacy\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Http\Middleware\EnsureModulePermission;
use Modules\Pharmacy\Models\Adjustment;
use Modules\Pharmacy\Models\AdjustmentItem;
use Modules\Pharmacy\Models\OpeningStock;
use Modules\Pharmacy\Models\OpeningStockItem;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\PurchaseItem;
use Modules\Pharmacy\Models\PurchaseReturn;
use Modules\Pharmacy\Models\PurchaseReturnItem;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\SaleItem;
use Modules\Pharmacy\Models\SaleReturn;
use Modules\Pharmacy\Models\SaleReturnItem;
use Modules\Pharmacy\Models\Transfer;
use Modules\Pharmacy\Models\TransferItem;
use Modules\Pharmacy\Models\Wastage;
use Modules\Pharmacy\Models\WastageItem;
use Modules\Pharmacy\View\Composers\MenuComposer;
use Modules\Pharmacy\View\Composers\ReportComposer;

class PharmacyServiceProvider extends ServiceProvider
{
    public function boot(Router $router): void
    {
        $router->aliasMiddleware('pharmacy.permission', EnsureModulePermission::class.':pharmacy.view');

        Relation::morphMap([
            'purchase' => Purchase::class,
            'purchase_item' => PurchaseItem::class,
            'purchase_return' => PurchaseReturn::class,
            'purchase_return_item' => PurchaseReturnItem::class,
            'sale' => Sale::class,
            'sale_item' => SaleItem::class,
            'sale_return' => SaleReturn::class,
            'sale_return_item' => SaleReturnItem::class,
            'adjustment' => Adjustment::class,
            'adjustment_item' => AdjustmentItem::class,
            'opening' => OpeningStock::class,
            'opening_stock_item' => OpeningStockItem::class,
            'damage' => Wastage::class,
            'wastage_item' => WastageItem::class,
            'transfer' => Transfer::class,
            'transfer_item' => TransferItem::class,
        ]);

        View::composer('core::layouts.app', MenuComposer::class);
        View::composer('pharmacy::reports.layout', ReportComposer::class);
        $this->loadViewsFrom(module_path('Pharmacy', 'resources/views'), 'pharmacy');
        $this->loadRoutesFrom(module_path('Pharmacy', 'routes/web.php'));
    }
}
