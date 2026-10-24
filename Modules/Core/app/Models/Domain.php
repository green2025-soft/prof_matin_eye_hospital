<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['domain'])]
class Domain extends Model
{
    protected $connection = 'central';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
