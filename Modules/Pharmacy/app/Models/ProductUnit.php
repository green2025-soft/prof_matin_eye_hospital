<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['product_id', 'unit_id', 'factor'])]
class ProductUnit extends BaseModel
{
    protected $table = 'phr_product_units';

    protected string $crudPermission = 'pharmacy-products';

    protected array $exposed = ['id', 'product_id', 'unit_id', 'factor', 'created_at'];

    protected array $searchable = [];

    protected array $sortable = ['id', 'factor'];

    protected string $defaultSort = 'id';

    protected array $filterableIds = ['product_id' => 'product_id'];

    protected array $selectorFilters = ['product_id'];

    protected array $listWith = ['product:id,name', 'unit:id,name,symbol'];

    protected array $exposedRelations = ['product' => ['id', 'name'], 'unit' => ['id', 'name', 'symbol']];

    protected function casts(): array
    {
        return ['factor' => 'integer'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function purchaseReturnItems(): HasMany
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    public function saleReturnItems(): HasMany
    {
        return $this->hasMany(SaleReturnItem::class);
    }

    public function adjustmentItems(): HasMany
    {
        return $this->hasMany(AdjustmentItem::class);
    }

    public function openingStockItems(): HasMany
    {
        return $this->hasMany(OpeningStockItem::class);
    }

    public function wastageItems(): HasMany
    {
        return $this->hasMany(WastageItem::class);
    }

    public function transferItems(): HasMany
    {
        return $this->hasMany(TransferItem::class);
    }

    public function listQuery(): Builder
    {
        return parent::listQuery()
            ->withExists([
                'purchaseItems', 'saleItems', 'purchaseReturnItems', 'saleReturnItems',
                'adjustmentItems', 'openingStockItems', 'wastageItems', 'transferItems',
            ]);
    }

    public function hasReferences(): bool
    {
        return ($this->purchase_items_exists ?? $this->purchaseItems()->exists())
            || ($this->sale_items_exists ?? $this->saleItems()->exists())
            || ($this->purchase_return_items_exists ?? $this->purchaseReturnItems()->exists())
            || ($this->sale_return_items_exists ?? $this->saleReturnItems()->exists())
            || ($this->adjustment_items_exists ?? $this->adjustmentItems()->exists())
            || ($this->opening_stock_items_exists ?? $this->openingStockItems()->exists())
            || ($this->wastage_items_exists ?? $this->wastageItems()->exists())
            || ($this->transfer_items_exists ?? $this->transferItems()->exists());
    }
}
