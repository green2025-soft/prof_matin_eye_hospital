<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleEntryAccount extends BaseModel
{
    protected $table = 'acc_module_entry_accounts';

    protected $fillable = ['account_head_id'];

    protected array $exposed = ['id', 'account_module_id', 'component', 'name', 'side', 'account_head_id', 'use_payment_type', 'ordering', 'created_at'];

    protected function casts(): array
    {
        return ['use_payment_type' => 'boolean', 'ordering' => 'integer'];
    }

    public function accountModule(): BelongsTo
    {
        return $this->belongsTo(AccountModule::class);
    }

    public function accountHead(): BelongsTo
    {
        return $this->belongsTo(AccountHead::class);
    }
}
