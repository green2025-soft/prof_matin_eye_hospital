<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Modules\Inventory\Policies\DocumentPolicy;

#[UsePolicy(DocumentPolicy::class)]
class Wastage extends Document
{
    public const TYPE = 'damage';

    protected string $crudPermission = 'inventory-damage';
}
