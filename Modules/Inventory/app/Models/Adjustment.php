<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Modules\Inventory\Policies\DocumentPolicy;

#[UsePolicy(DocumentPolicy::class)]
class Adjustment extends Document
{
    public const TYPE = 'adjustment';

    protected string $crudPermission = 'inventory-adjustments';
}
