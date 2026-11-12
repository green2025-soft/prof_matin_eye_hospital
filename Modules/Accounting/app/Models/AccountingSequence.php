<?php

namespace Modules\Accounting\Models;

class AccountingSequence extends BaseModel
{
    protected $table = 'acc_accounting_sequences';

    protected $primaryKey = 'name';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['name', 'last_number'];

    protected array $exposed = ['name', 'last_number', 'created_at'];

    protected function casts(): array
    {
        return ['last_number' => 'integer'];
    }
}
