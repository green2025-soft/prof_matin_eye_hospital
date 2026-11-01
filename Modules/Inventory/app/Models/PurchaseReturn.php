<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Modules\Inventory\Policies\DocumentPolicy;

#[UsePolicy(DocumentPolicy::class)]
class PurchaseReturn extends Document
{
    public const TYPE = 'purchase_return';

    protected string $crudPermission = 'inventory-purchase-returns';
}
