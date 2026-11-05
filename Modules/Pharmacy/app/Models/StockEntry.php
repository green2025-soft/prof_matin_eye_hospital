<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;
use Modules\Pharmacy\Policies\StockEntryPolicy;

#[UsePolicy(StockEntryPolicy::class)]
abstract class StockEntry extends BaseModel
{
    public const TYPE = '';

    public const REVISABLE = false;

    protected const COMMON_EXPOSED = [
        'id', 'branch_id', 'product_type', 'number', 'entry_date',
        'notes', 'cost', 'posted_at', 'stock_reversed_at', 'voided_at', 'created_at', 'updated_at',
    ];

    protected bool $branchScoped = true;

    protected array $exposed = self::COMMON_EXPOSED;

    protected array $searchable = ['number'];

    protected array $filterable = ['product_type' => ['medicine' => 'Medicine', 'optic' => 'Optic']];

    protected array $formWith = [];

    protected array $sortable = ['entry_date', 'number', 'id'];

    protected string $defaultSort = 'id';

    protected string $defaultDirection = 'desc';

    protected function casts(): array
    {
        return [
            'branch_id' => 'integer',
            'entry_date' => 'date',
            'cost' => 'integer',
            'posted_at' => 'datetime',
            'stock_reversed_at' => 'datetime',
            'voided_at' => 'datetime',
        ];
    }

    public function queryRules(): array
    {
        return parent::queryRules() + [
            'from_date' => ['nullable', 'date_format:Y-m-d'],
            'to_date' => [
                'nullable',
                'date_format:Y-m-d',
                Rule::when(fn (Fluent $input): bool => filled($input->from_date), ['after_or_equal:from_date']),
            ],
        ];
    }

    public function paginateRecords(array $filters, ?Builder $query = null): LengthAwarePaginator
    {
        $query ??= $this->listQuery();
        $dates = [];
        if (! empty($filters['from_date'])) {
            $query->where($this->qualifyColumn('entry_date'), '>=', $filters['from_date']);
            $dates['from_date'] = $filters['from_date'];
        }
        if (! empty($filters['to_date'])) {
            $query->where($this->qualifyColumn('entry_date'), '<=', $filters['to_date']);
            $dates['to_date'] = $filters['to_date'];
        }

        return parent::paginateRecords($filters, $query)->appends($dates);
    }

    public function stockBranchIds(): array
    {
        return [(int) $this->branch_id];
    }

    abstract public function lines(): HasMany;

    public function hasReferences(): bool
    {
        return $this->posted_at !== null;
    }
}
