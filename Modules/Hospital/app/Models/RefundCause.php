<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'ordering'])]
class RefundCause extends BaseModel
{
    protected string $crudPermission = 'refund-causes';

    protected array $exposed = ['id', 'name', 'ordering', 'created_at'];

    protected array $searchable = ['name'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected array $exposedCounts = ['refunds'];

    protected function casts(): array
    {
        return ['ordering' => 'integer'];
    }

    public function refunds(): HasMany
    {
        // A tenant-wide cause must retain references from every branch.
        return $this->hasMany(Refund::class, 'cause_id')->withoutGlobalScope('branch');
    }

    public function hasReferences(): bool
    {
        return array_key_exists('refunds_count', $this->getAttributes())
            ? $this->refunds_count > 0 : $this->refunds()->exists();
    }
}
