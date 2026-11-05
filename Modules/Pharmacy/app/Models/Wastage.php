<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Wastage extends StockEntry
{
    public const TYPE = 'damage';

    protected $table = 'phr_wastages';

    protected string $crudPermission = 'pharmacy-damage';

    protected array $exposed = [...parent::COMMON_EXPOSED, 'reason', 'stock_method'];

    public function lines(): HasMany
    {
        return $this->hasMany(WastageItem::class);
    }
}
