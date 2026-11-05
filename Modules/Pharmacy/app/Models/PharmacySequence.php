<?php

namespace Modules\Pharmacy\Models;

class PharmacySequence extends BaseModel
{
    protected $table = 'phr_sequences';

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
