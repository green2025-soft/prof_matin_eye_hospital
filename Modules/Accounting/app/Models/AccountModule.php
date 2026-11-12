<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'active'])]
class AccountModule extends BaseModel
{
    protected $table = 'acc_account_modules';

    protected string $crudPermission = 'accounting-mappings';

    protected array $exposed = ['id', 'key', 'name', 'module', 'voucher_type_id', 'active', 'created_at'];

    protected array $searchable = ['name', 'key'];

    protected array $containsSearch = ['name', 'key'];

    protected array $listWith = ['voucherType:id,name'];

    protected array $formWith = ['components'];

    protected array $exposedRelations = ['voucherType' => ['id', 'name']];

    public function queryContract(): array
    {
        $contract = parent::queryContract();
        $contract['filterable']['module'] = array_values(array_unique(array_column(
            config('accounting.integrations', []), 'module'
        )));

        return $contract;
    }

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function voucherType(): BelongsTo
    {
        return $this->belongsTo(VoucherType::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(ModuleEntryAccount::class)->orderBy('ordering')->orderBy('id');
    }
}
