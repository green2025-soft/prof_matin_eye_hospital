<?php

namespace Modules\Inventory\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Inventory\Models\Adjustment;
use Modules\Inventory\Models\AdjustmentItem;
use Modules\Inventory\Models\OpeningStock;
use Modules\Inventory\Models\OpeningStockItem;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\PurchaseItem;
use Modules\Inventory\Models\PurchaseReturn;
use Modules\Inventory\Models\PurchaseReturnItem;
use Modules\Inventory\Models\Sale;
use Modules\Inventory\Models\SaleItem;
use Modules\Inventory\Models\SaleReturn;
use Modules\Inventory\Models\SaleReturnItem;
use Modules\Inventory\Models\Transfer;
use Modules\Inventory\Models\TransferItem;
use Modules\Inventory\Models\Wastage;
use Modules\Inventory\Models\WastageItem;
use Modules\Inventory\View\Composers\MenuComposer;
use Modules\Inventory\View\Composers\ReportComposer;

class InventoryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
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
        View::composer('inventory::reports.layout', ReportComposer::class);
        $this->loadViewsFrom(module_path('Inventory', 'resources/views'), 'inventory');
        $this->loadRoutesFrom(module_path('Inventory', 'routes/web.php'));
    }
}
