<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Validation\ValidationException;
use LogicException;

#[Fillable(['type', 'name', 'barcode', 'category_id', 'dosage_id', 'supplier_id', 'shelf_id', 'strength', 'purchase_price', 'sale_price', 'reorder_level', 'needs_setup', 'active'])]
class Product extends BaseModel
{
    protected $table = 'inventory_products';

    protected string $crudPermission = 'inventory-products';

    protected array $exposed = ['id', 'type', 'name', 'code', 'barcode', 'category_id', 'dosage_id', 'supplier_id', 'shelf_id', 'strength', 'purchase_price', 'sale_price', 'reorder_level', 'needs_setup', 'active', 'created_at'];

    protected array $searchable = ['name', 'code', 'barcode'];

    protected array $containsSearch = ['name', 'code', 'barcode'];

    protected array $filterable = ['type' => ['medicine' => 'Medicine', 'optic' => 'Optic']];

    protected array $selectorFilters = ['type'];

    protected array $listWith = ['category:id,name', 'dosage:id,name', 'supplier:id,name', 'shelf:id,name', 'primaryVariant:id,product_id,sku'];

    protected array $formWith = ['primaryVariant:id,product_id,sku'];

    protected array $exposedRelations = ['category' => ['id', 'name'], 'dosage' => ['id', 'name'], 'supplier' => ['id', 'name'], 'shelf' => ['id', 'name'], 'primaryVariant' => ['id', 'product_id', 'sku']];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (self $product): void {
            $product->assignCode();
        });
        static::created(function (self $product): void {
            $product->createDefaultVariantAndUnit();
        });
        static::saving(function (self $product): void {
            if (! $product->exists) {
                return;
            }
            if ($product->isDirty('code')) {
                throw ValidationException::withMessages(['code' => 'The generated product code cannot be changed.']);
            }
            if (! $product->isDirty('barcode')) {
                return;
            }

            $connection = $product->getConnection();
            static::lockIdentifiers($connection);
            if (filled($product->barcode) && static::identifierInUse($connection, $product->barcode, $product->getKey())) {
                throw ValidationException::withMessages(['barcode' => 'This identifier belongs to another product.']);
            }
        });
    }

    protected function casts(): array
    {
        return ['purchase_price' => 'decimal:2', 'sale_price' => 'decimal:2', 'reorder_level' => 'integer', 'needs_setup' => 'boolean', 'active' => 'boolean'];
    }

    public static function types(): array
    {
        return ['medicine' => 'Medicine', 'optic' => 'Optic'];
    }

    public static function lockIdentifiers(Connection $connection): int
    {
        if ($connection->getName() !== (new self)->getConnectionName() || $connection->transactionLevel() < 1) {
            throw new LogicException('Product identifiers must be saved within a tenant transaction.');
        }

        $sequences = $connection->table('inventory_sequences');
        $sequence = (clone $sequences)->where('scope', 'product-identifiers')->lockForUpdate()->first();
        if ($sequence === null) {
            $lastProductId = $connection->table('inventory_products')->orderByDesc('id')->lockForUpdate()->value('id') ?? 0;
            $sequences->insertOrIgnore(['scope' => 'product-identifiers', 'last_number' => $lastProductId]);
            $sequence = (clone $sequences)->where('scope', 'product-identifiers')->lockForUpdate()->first();
        }

        return (int) $sequence->last_number;
    }

    public static function identifierInUse(Connection $connection, string $identifier, ?int $productId = null): bool
    {
        // Locking reads see aliases committed while this transaction waited for the shared counter.
        $products = $connection->table('inventory_products')->where(function (QueryBuilder $query) use ($identifier): void {
            $query->where('code', $identifier)->orWhere('barcode', $identifier);
        });
        if ($productId !== null) {
            $products->where('id', '!=', $productId);
        }
        if ($products->lockForUpdate()->first(['id']) !== null) {
            return true;
        }

        $variants = $connection->table('inventory_product_variants')->where('sku', $identifier);
        if ($productId !== null) {
            $variants->where('product_id', '!=', $productId);
        }

        return $variants->lockForUpdate()->first(['id']) !== null;
    }

    public function queryRules(): array
    {
        $rules = parent::queryRules();
        $rules['search'] = ['bail', 'nullable', 'string', 'max:100'];

        return $rules;
    }

    protected function applySearch(Builder $query, string $search, array $fields, array $containsSearch): void
    {
        $query->where(function (Builder $searchQuery) use ($search, $fields, $containsSearch): void {
            parent::applySearch($searchQuery, $search, $fields, $containsSearch);
            if (ctype_digit($search)) {
                $digits = str_pad($search, 6, '0', STR_PAD_LEFT);
                $searchQuery->orWhereKey($search)
                    ->orWhere('inventory_products.source_key', 'drug_medicine:'.$search)
                    ->orWhereHas('variants', fn (Builder $variants) => $variants->whereIn('sku', [$search, 'MED-'.$search, 'OPT-'.$search, 'MED-'.$digits, 'OPT-'.$digits]));
            } else {
                $escaped = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $search);
                $searchQuery->orWhereHas('variants', fn (Builder $variants) => $variants->whereRaw("inventory_product_variants.sku LIKE ? ESCAPE '!'", ['%'.$escaped.'%']));
            }
        });
    }

    private function assignCode(): void
    {
        $connection = $this->getConnection();
        $number = static::lockIdentifiers($connection);
        if (filled($this->barcode) && static::identifierInUse($connection, $this->barcode)) {
            throw ValidationException::withMessages(['barcode' => 'This identifier belongs to another product.']);
        }
        $prefix = match ($this->type) {
            'medicine' => 'MED-',
            'optic' => 'OPT-',
            default => throw ValidationException::withMessages(['type' => 'Choose Medicine or Optic.']),
        };
        do {
            if ($number >= PHP_INT_MAX) {
                throw ValidationException::withMessages(['code' => 'The product code sequence is exhausted.']);
            }
            $number++;
            $code = $prefix.str_pad((string) $number, 6, '0', STR_PAD_LEFT);
        } while (static::identifierInUse($connection, $code));

        $connection->table('inventory_sequences')->where('scope', 'product-identifiers')->update(['last_number' => $number]);
        $this->code = $code;
    }

    private function createDefaultVariantAndUnit(): void
    {
        $piece = Unit::on($this->getConnectionName())->firstOrCreate(['symbol' => 'Pcs'], ['name' => 'Piece']);
        $this->variants()->create(['sku' => $this->code, 'name' => 'Default', 'active' => true]);
        $this->units()->create(['unit_id' => $piece->id, 'factor' => 1]);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function dosage(): BelongsTo
    {
        return $this->belongsTo(Dosage::class);
    }

    public function shelf(): BelongsTo
    {
        return $this->belongsTo(Shelf::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function primaryVariant(): HasOne
    {
        return $this->hasOne(ProductVariant::class)->oldestOfMany();
    }

    public function units(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function hasReferences(): bool
    {
        return $this->variants()->exists() || $this->units()->exists();
    }
}
