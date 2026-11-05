<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\TenantCore\Models\PaymentType;

class SaleReturn extends StockEntry
{
    use SoftDeletes;

    public const TYPE = 'sale_return';

    public const REVISABLE = true;

    protected $table = 'phr_sale_returns';

    protected string $crudPermission = 'pharmacy-sale-returns';

    protected array $exposed = [
        ...parent::COMMON_EXPOSED, 'customer_id', 'sale_id', 'payment_type_id',
        'total', 'initial_paid', 'deleted_at',
    ];

    protected array $filterableIds = [
        'customer_id' => 'customer_id',
        'sale_id' => 'sale_id',
    ];

    protected array $listWith = ['sale:id,number'];

    protected array $exposedRelations = ['sale' => ['id', 'number']];

    protected function casts(): array
    {
        return parent::casts() + [
            'customer_id' => 'integer',
            'sale_id' => 'integer',
            'payment_type_id' => 'integer',
            'total' => 'integer',
            'initial_paid' => 'integer',
        ];
    }

    public function lines(): HasMany
    {
        return $this->hasMany(SaleReturnItem::class)
            ->whereNull('phr_sale_return_items.superseded_at');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class)->withTrashed();
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
}
