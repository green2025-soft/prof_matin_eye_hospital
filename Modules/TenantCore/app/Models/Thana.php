<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['district_id', 'name', 'bn_name'])]
class Thana extends BaseModel
{
    protected string $crudPermission = 'thanas';

    protected array $selectorFilters = ['district_id'];

    protected array $exposed = ['id', 'district_id', 'name', 'bn_name', 'created_at'];

    protected array $searchable = ['name', 'bn_name'];

    protected array $sortable = ['name', 'bn_name', 'id'];

    protected string $defaultSort = 'name';

    protected array $listWith = ['district:id,name'];

    protected array $formWith = [];

    protected array $exposedRelations = ['district' => ['id', 'name']];

    protected function casts(): array
    {
        return ['district_id' => 'integer'];
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
