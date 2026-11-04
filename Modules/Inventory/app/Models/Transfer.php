<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\TenantCore\Models\Branch;

class Transfer extends StockEntry
{
    public const TYPE = 'transfer';

    protected $table = 'inv_transfers';

    protected string $crudPermission = 'inventory-transfers';

    protected bool $branchScoped = false;

    protected array $exposed = [...parent::COMMON_EXPOSED, 'destination_branch_id', 'reason', 'stock_method'];

    protected array $listWith = ['destinationBranch:id,name'];

    protected array $exposedRelations = ['destinationBranch' => ['id', 'name']];

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('participatingBranch', function (Builder $query): void {
            $branchId = max(0, (int) request()->attributes->get('branch_id', 0));
            $query->where(function (Builder $query) use ($branchId): void {
                $query->where($query->getModel()->qualifyColumn('branch_id'), $branchId)
                    ->orWhere($query->getModel()->qualifyColumn('destination_branch_id'), $branchId);
            });
        });
    }

    protected function casts(): array
    {
        return parent::casts() + ['destination_branch_id' => 'integer'];
    }

    public function lines(): HasMany
    {
        return $this->hasMany(TransferItem::class);
    }

    public function destinationBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'destination_branch_id');
    }

    public function matchesBranchContext(): bool
    {
        $branchId = (int) request()->attributes->get('branch_id', 0);

        return $branchId > 0 && (! $this->exists || $this->branch_id === $branchId || $this->destination_branch_id === $branchId);
    }

    public function stockBranchIds(): array
    {
        return [(int) $this->branch_id, (int) $this->destination_branch_id];
    }
}
