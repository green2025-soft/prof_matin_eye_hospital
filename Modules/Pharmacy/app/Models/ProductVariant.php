<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Validation\ValidationException;

#[Fillable(['product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl', 'active'])]
class ProductVariant extends BaseModel
{
    protected $table = 'phr_product_variants';

    protected string $crudPermission = 'pharmacy-products';

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

    public function stockBalance(): HasOne
    {
        return $this->hasOne(StockBalance::class, 'variant_id');
    }

    public function balances(): HasMany
    {
        return $this->hasMany(StockBalance::class, 'variant_id');
    }

    public function stockLots(): HasMany
    {
        return $this->hasMany(StockLot::class, 'variant_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'variant_id');
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'variant_id');
    }

    public function saleReturnItems(): HasMany
    {
        return $this->hasMany(SaleReturnItem::class, 'variant_id');
    }

    public function description(): string
    {
        return implode(' / ', array_filter([
            $this->sku,
            mb_strtolower(trim($this->name ?? '')) === 'default' ? null : $this->name,
            $this->color,
            $this->size,
            $this->sph === null ? null : 'SPH '.$this->sph,
            $this->cyl === null ? null : 'CYL '.$this->cyl,
        ], static fn ($value): bool => filled($value)));
    }

    public function hasReferences(): bool
    {
        return $this->balances_exists ?? $this->balances()->withoutGlobalScope('branch')->exists();
    }

    public function listQuery(): Builder
    {
        return parent::listQuery()
            ->withExists([
                'balances' => fn (Builder $balances) => $balances->withoutGlobalScope('branch'),
            ]);
    }
}
