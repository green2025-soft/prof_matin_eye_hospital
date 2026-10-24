<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Concerns\HasCrudContract;

abstract class CoreModel extends Model
{
    use HasCrudContract;

    protected $connection = 'central';
}
