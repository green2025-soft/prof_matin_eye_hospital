<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Inventory\Policies\DocumentPolicy;
use Modules\TenantCore\Models\Branch;
use Modules\TenantCore\Models\PaymentType;

#[UsePolicy(DocumentPolicy::class)]
class Document extends BaseModel
{
    use SoftDeletes;

    public const TYPE = '';

    protected $table = 'inventory_documents';

    protected array $exposed = ['id', 'branch_id', 'destination_branch_id', 'type', 'product_type', 'number', 'document_date', 'customer_id', 'supplier_id', 'supplier_invoice_no', 'source_document_id', 'payment_type_id', 'reason', 'notes', 'stock_method', 'vat_mode', 'vat_percent', 'vat_calculation', 'subtotal_minor', 'discount_minor', 'vat_minor', 'total_minor', 'paid_minor', 'initial_paid_minor', 'settlement_discount_minor', 'returned_minor', 'refunded_minor', 'cost_minor', 'posted_at', 'stock_reversed_at', 'voided_at', 'deleted_at', 'created_at'];

    protected array $searchable = ['number'];

    protected array $filterable = ['product_type' => ['medicine' => 'Medicine', 'optic' => 'Optic']];

    protected array $filterableIds = ['customer_id' => 'customer_id', 'supplier_id' => 'supplier_id'];

    protected array $listWith = ['supplier:id,name', 'sourceDocument:id,number', 'destinationBranch:id,name'];

    protected array $formWith = [];

    protected array $exposedRelations = [
        'supplier' => ['id', 'name'],
        'sourceDocument' => ['id', 'number'],
        'destinationBranch' => ['id', 'name'],
    ];

    protected array $sortable = ['document_date', 'number', 'id'];

    protected string $defaultSort = 'id';

    protected string $defaultDirection = 'desc';

    protected static function booted(): void
    {
        parent::booted();

        $type = static::TYPE;
        if ($type !== '') {
            static::addGlobalScope('documentType', function (Builder $query) use ($type): void {
                $query->where($query->getModel()->qualifyColumn('type'), $type);
            });
        }

        static::addGlobalScope('participatingBranch', function (Builder $query): void {
            $branchId = max(0, (int) request()->attributes->get('branch_id', 0));
            $query->where(function (Builder $query) use ($branchId): void {
                $query->where($query->getModel()->qualifyColumn('branch_id'), $branchId)
                    ->orWhere($query->getModel()->qualifyColumn('destination_branch_id'), $branchId);
            });
        });

        static::creating(function (self $document) use ($type): void {
            if ($type === '') {
                return;
            }
            if (filled($document->type) && $document->type !== $type) {
                throw ValidationException::withMessages(['type' => 'Choose the transaction type belonging to this resource.']);
            }
            $document->type = $type;
        });

        static::saving(function (self $document) use ($type): void {
            if ($document->exists && ($document->isDirty('type') || ($type !== '' && $document->type !== $type))) {
                throw ValidationException::withMessages(['type' => 'An entry must retain its original transaction type.']);
            }
        });
    }

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'destination_branch_id' => 'integer', 'customer_id' => 'integer', 'supplier_id' => 'integer', 'supplier_invoice_no' => 'string', 'source_document_id' => 'integer', 'payment_type_id' => 'integer', 'document_date' => 'date', 'vat_percent' => 'decimal:2', 'vat_calculation' => 'string', 'subtotal_minor' => 'integer', 'discount_minor' => 'integer', 'vat_minor' => 'integer', 'total_minor' => 'integer', 'paid_minor' => 'integer', 'initial_paid_minor' => 'integer', 'settlement_discount_minor' => 'integer', 'returned_minor' => 'integer', 'refunded_minor' => 'integer', 'cost_minor' => 'integer', 'posted_at' => 'datetime', 'stock_reversed_at' => 'datetime', 'voided_at' => 'datetime'];
    }

    public function queryRules(): array
    {
        return parent::queryRules() + [
            'from_date' => ['nullable', 'date_format:Y-m-d'],
            'to_date' => ['nullable', 'date_format:Y-m-d', Rule::when(fn (Fluent $input): bool => filled($input->from_date), ['after_or_equal:from_date'])],
            'source_document_id' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function paginateRecords(array $filters, ?Builder $query = null): LengthAwarePaginator
    {
        $query ??= $this->listQuery();
        $dates = [];
        if (! empty($filters['from_date'])) {
            $query->where($this->qualifyColumn('document_date'), '>=', $filters['from_date']);
            $dates['from_date'] = $filters['from_date'];
        }
        if (! empty($filters['to_date'])) {
            $query->where($this->qualifyColumn('document_date'), '<=', $filters['to_date']);
            $dates['to_date'] = $filters['to_date'];
        }

        return parent::paginateRecords($filters, $query)->appends($dates);
    }

    public static function typePermissions(): array
    {
        return [
            'purchase' => 'inventory-purchases',
            'sale' => 'inventory-sales',
            'purchase_return' => 'inventory-purchase-returns',
            'sale_return' => 'inventory-sale-returns',
            'opening' => 'inventory-opening-stock',
            'adjustment' => 'inventory-adjustments',
            'damage' => 'inventory-damage',
            'transfer' => 'inventory-transfers',
            'customer_opening' => 'inventory-customer-opening-dues',
            'supplier_opening' => 'inventory-supplier-opening-dues',
        ];
    }

    public static function visibleItemFields(array $settings, string $type, string $productType): array
    {
        $prefix = $productType.'_'.$type.'_';

        return [
            'discount' => (bool) ($settings[$prefix.'discount_enabled'] ?? true),
            'vat' => $type === 'purchase' && (bool) ($settings[$prefix.'vat_enabled'] ?? true),
            'sale_price' => $type === 'purchase' && (bool) ($settings[$prefix.'sale_price_enabled'] ?? true),
            'expiry' => $type === 'purchase' && $productType === 'medicine'
                && (bool) ($settings[$prefix.'expiry_enabled'] ?? true),
        ];
    }

    public function lines(): HasMany
    {
        return $this->hasMany(DocumentLine::class, 'document_id')->whereNull('inventory_document_lines.superseded_at');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function sourceDocument(): BelongsTo
    {
        return $this->belongsTo(self::class, 'source_document_id')->withTrashed();
    }

    public function destinationBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'destination_branch_id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function matchesBranchContext(): bool
    {
        $branchId = (int) request()->attributes->get('branch_id', 0);

        return $branchId > 0 && (! $this->exists || $this->branch_id === $branchId || $this->destination_branch_id === $branchId);
    }

    public function outstandingMinor(): int
    {
        return max(0, $this->total_minor - $this->returned_minor - $this->paid_minor + $this->refunded_minor - $this->settlement_discount_minor);
    }

    public static function dueAmountSql(): string
    {
        return 'GREATEST(0, CAST(total_minor AS SIGNED) - CAST(returned_minor AS SIGNED) - CAST(paid_minor AS SIGNED) + CAST(refunded_minor AS SIGNED) - CAST(settlement_discount_minor AS SIGNED))';
    }

    public function scopeOutstanding(Builder $query): Builder
    {
        return $query->whereNull($this->qualifyColumn('voided_at'))->whereRaw(static::dueAmountSql().' > 0');
    }

    public static function customerDues(string $productType): Builder
    {
        return static::query()->outstanding()->where('product_type', $productType)
            ->whereIn('type', ['sale', 'customer_opening'])->whereNotNull('customer_id');
    }

    public static function supplierDues(string $productType): Builder
    {
        return static::query()->outstanding()->where('product_type', $productType)
            ->whereIn('type', ['purchase', 'supplier_opening'])->whereNotNull('supplier_id');
    }

    public function hasReferences(): bool
    {
        return $this->posted_at !== null;
    }
}
