<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['scope', 'last_number'])]
class ClinicalSequence extends BaseModel
{
    protected $primaryKey = 'scope';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected function casts(): array
    {
        return ['last_number' => 'integer'];
    }
}
