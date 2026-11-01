<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Modules\Inventory\Policies\DocumentPolicy;

#[UsePolicy(DocumentPolicy::class)]
class SaleReturn extends Document
{
    public const TYPE = 'sale_return';

    protected string $crudPermission = 'inventory-sale-returns';
}
