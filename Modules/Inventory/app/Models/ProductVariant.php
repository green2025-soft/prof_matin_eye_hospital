<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

#[Fillable(['product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl', 'active'])]
class ProductVariant extends BaseModel
{
    protected $table = 'inventory_product_variants';

    protected string $crudPermission = 'inventory-products';

    protected array $exposed = ['id', 'product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl', 'active', 'created_at'];

    protected array $searchable = ['sku', 'name'];

    protected array $sortable = ['sku', 'name', 'id'];

    protected string $defaultSort = 'sku';

    protected array $filterableIds = ['product_id' => 'product_id'];

    protected array $selectorFilters = ['product_id'];

    protected array $listWith = ['product:id,type,name,purchase_price,sale_price,needs_setup,active'];

    protected array $exposedRelations = ['product' => ['id', 'type', 'name', 'purchase_price', 'sale_price', 'needs_setup', 'active']];

    protected static function booted(): void
    {
        parent::booted();

        static::saving(function (self $variant): void {
            if ($variant->exists && $variant->isDirty('product_id')) {
                throw ValidationException::withMessages(['product_id' => 'A variant must retain its original product.']);
            }
            if ($variant->exists && ! $variant->isDirty('sku')) {
                return;
            }

            $connection = $variant->getConnection();
            Product::lockIdentifiers($connection);
            if (Product::identifierInUse($connection, $variant->sku, (int) $variant->product_id)) {
                throw ValidationException::withMessages(['sku' => 'This identifier belongs to another product.']);
            }
        });
    }

    protected function casts(): array
    {
        return ['sph' => 'decimal:2', 'cyl' => 'decimal:2', 'active' => 'boolean'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function hasReferences(): bool
    {
        return $this->getConnection()->table('inventory_stock_balances')->where('variant_id', $this->getKey())->exists();
    }
}
