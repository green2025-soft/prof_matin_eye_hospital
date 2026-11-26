<?php

namespace Modules\Prescription\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name'])]
class PrescriptionDose extends BaseModel
{
    protected string $crudPermission = 'prescription-dose';

    protected array $exposed = ['id', 'name', 'created_at'];

    protected array $searchable = ['name'];

    protected array $containsSearch = ['name'];

    protected array $sortable = ['name', 'id'];

    protected string $defaultSort = 'id';

    protected string $defaultDirection = 'asc';
}
