<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'ordering'])]
class InvestigationCategory extends BaseModel
{
    protected string $crudPermission = 'investigation-categories';

    protected array $exposed = ['id', 'name', 'ordering', 'created_at'];

    protected array $searchable = ['name'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected array $exposedCounts = ['investigations'];

    protected function casts(): array
    {
        return ['ordering' => 'integer'];
    }

    public function investigations(): HasMany
    {
        return $this->hasMany(Investigation::class, 'category_id');
    }

    public function hasReferences(): bool
    {
        return array_key_exists('investigations_count', $this->getAttributes())
            ? $this->investigations_count > 0 : $this->investigations()->exists();
    }
}
