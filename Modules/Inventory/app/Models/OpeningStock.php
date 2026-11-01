<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Modules\Inventory\Policies\DocumentPolicy;

#[UsePolicy(DocumentPolicy::class)]
class OpeningStock extends Document
{
    public const TYPE = 'opening';

    protected string $crudPermission = 'inventory-opening-stock';
}
