<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Accounting\Policies\JournalEntryPolicy;

#[UsePolicy(JournalEntryPolicy::class)]
class JournalEntry extends BaseModel
{
    protected $table = 'acc_journal_entries';

    protected string $crudPermission = 'accounting-journals';

    protected $fillable = ['voucher_type_id', 'entry_date', 'reference', 'narration'];

    protected array $exposed = ['id', 'branch_id', 'voucher_type_id', 'number', 'entry_date', 'reference', 'narration', 'source_type', 'source_id', 'feature', 'total', 'created_by_name', 'voided_at', 'created_at'];

    protected array $searchable = ['number', 'reference'];

    protected array $containsSearch = ['number', 'reference'];

    protected array $sortable = ['id', 'entry_date', 'number'];

    protected string $defaultSort = 'id';

    protected string $defaultDirection = 'desc';

    protected array $listWith = ['voucherType:id,name'];

    protected array $formWith = ['lines'];

    protected array $exposedRelations = ['voucherType' => ['id', 'name']];

    protected function casts(): array
    {
        return ['entry_date' => 'immutable_date', 'total' => 'integer', 'voided_at' => 'immutable_datetime'];
    }

    public function scopeActive(Builder $query): void
    {
        $query->whereNull('voided_at');
    }

    public function listQuery(): Builder
    {
        return parent::listQuery()->where('branch_id', $this->currentBranchId());
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)
            ->where($this->qualifyColumn('branch_id'), $this->currentBranchId());
    }

    public function isAutomatic(): bool
    {
        return $this->source_id !== null;
    }

    public function voucherType(): BelongsTo
    {
        return $this->belongsTo(VoucherType::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryDetail::class)->orderBy('ordering')->orderBy('id');
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
