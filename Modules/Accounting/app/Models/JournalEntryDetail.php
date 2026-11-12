<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntryDetail extends BaseModel
{
    protected $table = 'acc_journal_entry_details';

    protected $fillable = ['account_head_id', 'debit', 'credit', 'remarks', 'ordering'];

    protected array $exposed = ['id', 'journal_entry_id', 'account_head_id', 'debit', 'credit', 'remarks', 'ordering', 'created_at'];

    protected function casts(): array
    {
        return ['debit' => 'integer', 'credit' => 'integer', 'ordering' => 'integer'];
    }

    public function scopePostedInBranch(Builder $query, int $branchId): void
    {
        $query->whereHas('journalEntry', fn (Builder $entries) => $entries
            ->active()
            ->where('branch_id', $branchId));
    }

    public function scopeBeforeDate(Builder $query, string $date): void
    {
        $query->whereHas('journalEntry', fn (Builder $entries) => $entries->where('entry_date', '<', $date));
    }

    public function scopeDuringDates(Builder $query, string $from, string $to): void
    {
        $query->whereHas('journalEntry', fn (Builder $entries) => $entries->whereBetween('entry_date', [$from, $to]));
    }

    public function scopeThroughDate(Builder $query, string $date): void
    {
        $query->whereHas('journalEntry', fn (Builder $entries) => $entries->where('entry_date', '<=', $date));
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function accountHead(): BelongsTo
    {
        return $this->belongsTo(AccountHead::class);
    }
}
