<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\TenantCore\Models\PaymentType;

class Purchase extends StockEntry
{
    use SoftDeletes;

    public const TYPE = 'purchase';

    public const REVISABLE = true;

    protected $table = 'phr_purchases';

    protected string $crudPermission = 'pharmacy-purchases';

    protected array $exposed = [
        ...parent::COMMON_EXPOSED, 'supplier_id', 'supplier_invoice_no', 'payment_type_id',
        'subtotal', 'discount', 'vat', 'total', 'paid', 'initial_paid',
        'settlement_discount', 'returned', 'refunded', 'outstanding', 'deleted_at',
    ];

    protected array $filterableIds = ['supplier_id' => 'supplier_id'];

    protected array $listWith = ['supplier:id,name'];

    protected array $exposedRelations = ['supplier' => ['id', 'name']];

    protected static function booted(): void
    {
        parent::booted();

        static::saving(function (self $purchase): void {
            $purchase->outstanding = $purchase->outstandingMinor();
        });
    }

    protected function casts(): array
    {
        return parent::casts() + [
            'supplier_id' => 'integer', 'payment_type_id' => 'integer',
            'subtotal' => 'integer', 'discount' => 'integer', 'vat' => 'integer',
            'total' => 'integer', 'paid' => 'integer', 'initial_paid' => 'integer',
            'settlement_discount' => 'integer', 'returned' => 'integer',
            'refunded' => 'integer', 'outstanding' => 'integer',
        ];
    }

    public function lines(): HasMany
    {
        return $this->hasMany(PurchaseItem::class)
            ->whereNull('phr_purchase_items.superseded_at');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(PurchaseReturn::class);
    }

    public function paymentAllocations(): HasMany
    {
        return $this->hasMany(PurchasePaymentAllocation::class);
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
