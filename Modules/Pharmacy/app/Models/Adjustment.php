<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Adjustment extends StockEntry
{
    public const TYPE = 'adjustment';

    protected $table = 'phr_adjustments';

    protected string $crudPermission = 'pharmacy-adjustments';

    protected array $exposed = [...parent::COMMON_EXPOSED, 'reason', 'stock_method'];

    public function lines(): HasMany
    {
        return $this->hasMany(AdjustmentItem::class);
    }
}
