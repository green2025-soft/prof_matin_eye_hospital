<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\TenantCore\Models\PaymentType;

class Sale extends StockEntry
{
    use SoftDeletes;

    public const TYPE = 'sale';

    public const REVISABLE = true;

    protected $table = 'inv_sales';

    protected string $crudPermission = 'inventory-sales';

    protected array $exposed = [
        ...parent::COMMON_EXPOSED, 'customer_id', 'payment_type_id', 'stock_method', 'vat_mode', 'vat_percent',
        'subtotal', 'discount', 'vat', 'total', 'paid', 'initial_paid',
        'settlement_discount', 'returned', 'refunded', 'outstanding', 'deleted_at',
    ];

    protected array $filterableIds = ['customer_id' => 'customer_id'];

    protected static function booted(): void
    {
        parent::booted();

        static::saving(function (self $sale): void {
            $sale->outstanding = $sale->outstandingMinor();
        });
    }

    protected function casts(): array
    {
        return parent::casts() + [
            'customer_id' => 'integer', 'payment_type_id' => 'integer', 'vat_percent' => 'decimal:2',
            'subtotal' => 'integer', 'discount' => 'integer', 'vat' => 'integer',
            'total' => 'integer', 'paid' => 'integer', 'initial_paid' => 'integer',
            'settlement_discount' => 'integer', 'returned' => 'integer',
            'refunded' => 'integer', 'outstanding' => 'integer',
        ];
    }

    public function lines(): HasMany
    {
        return $this->hasMany(SaleItem::class)->whereNull('inv_sale_items.superseded_at');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(SaleReturn::class);
    }

    public function paymentAllocations(): HasMany
    {
        return $this->hasMany(SalePaymentAllocation::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function outstandingMinor(): int
    {
        return max(0, $this->total - $this->returned - $this->paid
            + $this->refunded - $this->settlement_discount);
    }

    public function scopeOutstanding(Builder $query): Builder
    {
        return $query->whereNull($this->qualifyColumn('voided_at'))
            ->where($this->qualifyColumn('outstanding'), '>', 0);
    }
}
