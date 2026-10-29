<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Models\BaseModel as CoreBaseModel;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

abstract class BaseModel extends CoreBaseModel
{
    use UsesTenantConnection;

    protected bool $branchScoped = false;

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('branch', function (Builder $query): void {
            $model = $query->getModel();

            if ($model->branchScoped) {
                $query->where($model->qualifyColumn('branch_id'), $model->currentBranchId());
            }
        });

        static::creating(function (self $model): void {
            if ($model->branchScoped) {
                abort_unless($model->matchesBranchContext(), 403, 'Select a branch before saving this record.');
                $model->setAttribute('branch_id', $model->currentBranchId());
            }
        });

        static::saving(function (self $model): void {
            if ($model->branchScoped && $model->exists) {
                abort_unless($model->matchesBranchContext() && ! $model->isDirty('branch_id'), 403, 'This record does not belong to the selected branch.');
            }
        });
    }

    public function matchesBranchContext(): bool
    {
        if (! $this->branchScoped) {
            return true;
        }

        $branchId = $this->currentBranchId();

        return $branchId > 0 && ((! $this->exists && $this->getAttribute('branch_id') === null)
            || (int) $this->getAttribute('branch_id') === $branchId);
    }

    private function currentBranchId(): int
    {
        return max(0, (int) request()->attributes->get('branch_id', 0));
    }
}
