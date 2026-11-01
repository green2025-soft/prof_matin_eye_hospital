<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Modules\Inventory\Policies\DocumentPolicy;

#[UsePolicy(DocumentPolicy::class)]
class Transfer extends Document
{
    public const TYPE = 'transfer';

    protected string $crudPermission = 'inventory-transfers';
}
