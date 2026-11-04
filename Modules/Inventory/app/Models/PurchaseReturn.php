<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\TenantCore\Models\PaymentType;

class PurchaseReturn extends StockEntry
{
    use SoftDeletes;

    public const TYPE = 'purchase_return';

    public const REVISABLE = true;

    protected $table = 'inv_purchase_returns';

    protected string $crudPermission = 'inventory-purchase-returns';

    protected array $exposed = [
        ...parent::COMMON_EXPOSED, 'supplier_id', 'purchase_id', 'payment_type_id',
        'total', 'initial_paid', 'deleted_at',
    ];

    protected array $filterableIds = [
        'supplier_id' => 'supplier_id',
        'purchase_id' => 'purchase_id',
    ];

    protected array $listWith = ['supplier:id,name', 'purchase:id,number'];

    protected array $exposedRelations = [
        'supplier' => ['id', 'name'],
        'purchase' => ['id', 'number'],
    ];

    protected function casts(): array
    {
        return parent::casts() + [
            'supplier_id' => 'integer',
            'purchase_id' => 'integer',
            'payment_type_id' => 'integer',
            'total' => 'integer',
            'initial_paid' => 'integer',
        ];
    }

    public function lines(): HasMany
    {
        return $this->hasMany(PurchaseReturnItem::class)
            ->whereNull('inv_purchase_return_items.superseded_at');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class)->withTrashed();
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
}
