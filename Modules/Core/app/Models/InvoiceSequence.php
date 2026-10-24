<?php

namespace Modules\Core\Models;

class InvoiceSequence extends CoreModel
{
    protected $fillable = ['year', 'counter'];

    protected array $exposed = ['id', 'year', 'counter'];

    protected function casts(): array
    {
        return ['year' => 'integer', 'counter' => 'integer'];
    }
}
