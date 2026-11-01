<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Modules\Inventory\Policies\DocumentPolicy;

#[UsePolicy(DocumentPolicy::class)]
class Sale extends Document
{
    public const TYPE = 'sale';

    protected string $crudPermission = 'inventory-sales';
}
