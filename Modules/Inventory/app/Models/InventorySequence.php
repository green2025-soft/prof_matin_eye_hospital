<?php

namespace Modules\Inventory\Models;

class InventorySequence extends BaseModel
{
    protected $table = 'inv_sequences';

    protected $primaryKey = 'scope';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected array $exposed = ['scope', 'last_number'];

    protected function casts(): array
    {
        return ['last_number' => 'integer'];
    }
}
