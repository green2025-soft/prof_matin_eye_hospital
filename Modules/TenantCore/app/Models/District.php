<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['division_id', 'name', 'bn_name'])]
class District extends BaseModel
{
    protected string $crudPermission = 'districts';

    protected array $selectorFilters = ['division_id'];

    protected array $exposed = ['id', 'division_id', 'name', 'bn_name', 'created_at'];

    protected array $searchable = ['name', 'bn_name'];

    protected array $sortable = ['name', 'bn_name', 'id'];

    protected string $defaultSort = 'name';

    protected array $listWith = ['division:id,name'];

    protected array $formWith = [];

    protected array $exposedRelations = ['division' => ['id', 'name']];

    protected array $exposedCounts = ['thanas'];

    protected function casts(): array
    {
        return ['division_id' => 'integer'];
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function thanas(): HasMany
    {
        return $this->hasMany(Thana::class);
    }

    public function hasReferences(): bool
    {
        return array_key_exists('thanas_count', $this->getAttributes())
            ? $this->thanas_count > 0 : $this->thanas()->exists();
    }
}
