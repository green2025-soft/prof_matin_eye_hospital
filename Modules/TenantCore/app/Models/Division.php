<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'bn_name'])]
class Division extends BaseModel
{
    protected string $crudPermission = 'divisions';

    protected array $exposed = ['id', 'name', 'bn_name', 'created_at'];

    protected array $searchable = ['name', 'bn_name'];

    protected array $sortable = ['name', 'bn_name', 'id'];

    protected string $defaultSort = 'name';

    protected array $exposedCounts = ['districts'];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    public function hasReferences(): bool
    {
        return array_key_exists('districts_count', $this->getAttributes())
            ? $this->districts_count > 0 : $this->districts()->exists();
    }
}
