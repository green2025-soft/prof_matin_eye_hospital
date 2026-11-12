<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class VoucherType extends BaseModel
{
    protected $table = 'acc_voucher_types';

    protected $fillable = ['code', 'prefix', 'name', 'is_manual', 'active'];

    protected array $exposed = ['id', 'code', 'prefix', 'name', 'is_manual', 'active', 'created_at'];

    protected function casts(): array
    {
        return ['is_manual' => 'boolean', 'active' => 'boolean'];
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }
}
