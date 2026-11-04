<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class StockBalance extends BaseModel
{
    protected $table = 'inv_stock_balances';

    protected bool $branchScoped = true;

    protected function casts(): array
    {
        return [
            'branch_id' => 'integer',
            'variant_id' => 'integer',
            'quantity' => 'integer',
            'cost' => 'integer',
        ];
    }

    public function lots(): HasMany
    {
        return $this->hasMany(StockLot::class, 'variant_id', 'variant_id');
    }
}
