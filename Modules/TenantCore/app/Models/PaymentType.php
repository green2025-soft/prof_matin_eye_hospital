<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'ordering'])]
class PaymentType extends BaseModel
{
    protected string $crudPermission = 'payment-types';

    protected array $exposed = ['id', 'name', 'ordering', 'created_at'];

    protected array $searchable = ['name'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected function casts(): array
    {
        return ['ordering' => 'integer'];
    }
}
