<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\Account;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

#[Fillable(['name', 'email', 'password', 'branch_id', 'redirect_url'])]
class User extends Account
{
    use UsesTenantConnection;

    protected function casts(): array
    {
        return [...parent::casts(), 'branch_id' => 'integer'];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function queryContract(): array
    {
        $contract = parent::queryContract();
        $contract['exposed'] = [...$contract['exposed'], 'branch_id', 'redirect_url'];
        $contract['listColumns'] = [...$contract['listColumns'], 'branch_id', 'redirect_url'];
        $contract['listWith'][] = 'branch:id,name';
        $contract['formWith'][] = 'branch:id,name';
        $contract['allowedIncludes'][] = 'branch';
        $contract['exposedRelations']['branch'] = ['id', 'name'];

        return $contract;
    }
}
