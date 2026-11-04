<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'unit_id', 'factor'])]
class ProductUnit extends BaseModel
{
    protected $table = 'inv_product_units';

    protected string $crudPermission = 'inventory-products';

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

    public function hasReferences(): bool
    {
        return PurchaseItem::query()->where('product_unit_id', $this->getKey())->exists()
            || SaleItem::query()->where('product_unit_id', $this->getKey())->exists()
            || PurchaseReturnItem::query()->where('product_unit_id', $this->getKey())->exists()
            || SaleReturnItem::query()->where('product_unit_id', $this->getKey())->exists()
            || AdjustmentItem::query()->where('product_unit_id', $this->getKey())->exists()
            || OpeningStockItem::query()->where('product_unit_id', $this->getKey())->exists()
            || WastageItem::query()->where('product_unit_id', $this->getKey())->exists()
            || TransferItem::query()->where('product_unit_id', $this->getKey())->exists();
    }
}
