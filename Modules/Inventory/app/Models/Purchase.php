<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Modules\Inventory\Policies\DocumentPolicy;

#[UsePolicy(DocumentPolicy::class)]
class Purchase extends Document
{
    public const TYPE = 'purchase';

    protected string $crudPermission = 'inventory-purchases';
}
