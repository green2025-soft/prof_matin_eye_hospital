<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

#[Fillable(['name', 'parent_id', 'is_transaction', 'active', 'remarks', 'system_key', 'cash_flow_activity'])]
class AccountHead extends BaseModel
{
    public const REPORT_ROLES = [
        'cash_in_hand' => 'Cash in hand',
        'cash_at_bank' => 'Cash at bank / mobile banking',
    ];

    public const CASH_FLOW_ACTIVITIES = [
        'operating' => 'Operating',
        'investing' => 'Investing',
        'financing' => 'Financing',
        'unclassified' => 'Unclassified',
    ];

    protected $table = 'acc_account_heads';

    protected string $crudPermission = 'accounting-accounts';

    protected $attributes = ['active' => true, 'is_transaction' => false];

    protected array $exposed = ['id', 'code', 'name', 'parent_id', 'type', 'depth', 'is_transaction', 'active', 'remarks', 'system_key', 'cash_flow_activity', 'created_at'];

    protected array $searchable = ['name', 'code'];

    protected array $containsSearch = ['name', 'code'];

    protected array $sortable = ['code', 'name', 'id'];

    protected string $defaultSort = 'code';

    protected array $listWith = ['parent:id,code,name'];

    protected array $formWith = ['parent:id,code,name,depth'];

    protected array $exposedRelations = ['parent' => ['id', 'code', 'name']];

    protected static function booted(): void
    {
        parent::booted();
        static::saving(function (self $account): void {
            if (! $account->exists && $account->parent_id !== null) {
                $account->assignIdentity();
            }
            if ($account->exists && $account->isDirty(['code', 'parent_id', 'type', 'depth'])) {
                throw ValidationException::withMessages(['parent_id' => 'An existing account cannot move to another parent.']);
            }
            if ($account->is_transaction && (int) $account->depth !== 5) {
                throw ValidationException::withMessages(['is_transaction' => 'Only a ledger account can be used in transactions.']);
            }
            if ($account->system_key !== null && $account->type !== 'asset') {
                throw ValidationException::withMessages(['system_key' => 'Cash and bank report roles belong to asset accounts.']);
            }
        });
    }

    protected function casts(): array
    {
        return ['parent_id' => 'integer', 'depth' => 'integer', 'is_transaction' => 'boolean', 'active' => 'boolean'];
    }

    public function scopePostable(Builder $query): void
    {
        $query->where('active', true)->where('is_transaction', true)->where('depth', 5);
    }

    public function listQuery(): Builder
    {
        return parent::listQuery()->withExists(['children', 'journalLines', 'components', 'paymentAccounts']);
    }

    public function isCrudEnabled(): bool
    {
        return ! $this->exists || $this->parent_id !== null;
    }

    public function hasReferences(): bool
    {
        return ($this->children_exists ?? $this->children()->exists())
            || ($this->journal_lines_exists ?? $this->journalLines()->exists())
            || ($this->components_exists ?? $this->components()->exists())
            || ($this->payment_accounts_exists ?? $this->paymentAccounts()->exists());
    }

    private function assignIdentity(): void
    {
        $parent = static::whereKey($this->parent_id)->lockForUpdate()->firstOrFail();
        if (! $parent->active || $parent->depth >= 5 || $parent->is_transaction) {
            throw ValidationException::withMessages(['parent_id' => 'Choose an active group above ledger account level.']);
        }

        $steps = [1 => 10000, 2 => 1000, 3 => 100, 4 => 1];
        $step = $steps[$parent->depth];
        $lastCode = $parent->children()->orderByDesc('code')->lockForUpdate()->value('code');
        $nextCode = (int) ($lastCode ?? $parent->code) + $step;
        $limit = (int) $parent->code + ($parent->depth === 4 ? 100 : $step * 10);
        if ($nextCode >= $limit) {
            throw ValidationException::withMessages(['parent_id' => 'This group has no remaining account codes.']);
        }

        $this->code = (string) $nextCode;
        $this->type = $parent->type;
        $this->depth = $parent->depth + 1;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(JournalEntryDetail::class, 'account_head_id');
    }

    public function components(): HasMany
    {
        return $this->hasMany(ModuleEntryAccount::class, 'account_head_id');
    }

    public function paymentAccounts(): HasMany
    {
        return $this->hasMany(PaymentAccount::class, 'account_head_id');
    }
}
