<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferItem extends StockItem
{
    protected $table = 'phr_transfer_items';

    protected array $exposed = [...parent::COMMON_EXPOSED, 'transfer_id'];

    protected function casts(): array
    {
        return parent::casts() + ['transfer_id' => 'integer'];
    }

    public function transfer(): BelongsTo
    {
        return $this->belongsTo(Transfer::class);
    }
}
