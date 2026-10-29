<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\TenantCore\Models\PaymentType;

#[Fillable(['patient_id', 'doctor_id', 'entry_date', 'payment_type_id', 'eye_type', 'amount', 'discount_mode', 'discount_percent', 'discount_amount', 'extra_discount_percent', 'extra_discount_amount', 'paid_amount', 'payable_amount', 'due_amount', 'due_date', 'delivery_date'])]
class PatientInvestigation extends BaseModel
{
    public const DELIVERY_STATUSES = ['pending' => 'Pending', 'delivered' => 'Delivered'];

    protected string $crudPermission = 'patient-investigations';

    protected bool $branchScoped = true;

    protected $attributes = ['delivery_status' => 'pending'];

    protected array $exposed = ['id', 'branch_id', 'reference_id', 'reference_commission_percent', 'reference_discount_mode', 'reference_due_mode', 'reference_commission_paid_amount', 'reference_commission_paid_at', 'patient_id', 'doctor_id', 'entry_date', 'payment_type_id', 'eye_type', 'amount', 'discount_mode', 'discount_percent', 'discount_amount', 'extra_discount_percent', 'extra_discount_amount', 'paid_amount', 'payable_amount', 'due_amount', 'due_date', 'delivery_date', 'delivery_status', 'invoice_no', 'created_at'];

    protected array $searchable = ['id', 'entry_date', 'invoice_no'];

    protected array $filterable = ['delivery_status' => self::DELIVERY_STATUSES];

    protected array $filterableIds = ['filter_doctor_id' => 'doctor_id', 'filter_patient_id' => 'patient_id'];

    protected array $sortable = ['entry_date', 'delivery_date', 'id'];

    protected string $defaultSort = 'id';

    protected string $defaultDirection = 'desc';

    protected array $exposedCounts = ['refunds', 'dueCollections'];

    protected array $listWith = ['patient:id,name,patient_id,mobile_no', 'doctor:id,doctor_id,name', 'paymentType:id,name'];

    protected array $formWith = ['investigations:id,name,category_id,single_rate,both_rate', 'investigations.category:id,name'];

    protected array $exposedRelations = ['reference' => ['id', 'reference_id', 'name', 'contact_no'], 'patient' => ['id', 'name', 'patient_id', 'mobile_no'], 'doctor' => ['id', 'doctor_id', 'name'], 'paymentType' => ['id', 'name'], 'investigations' => ['id', 'name']];

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'reference_id' => 'integer', 'reference_commission_percent' => 'decimal:2', 'reference_commission_paid_amount' => 'decimal:2', 'reference_commission_paid_at' => 'datetime', 'patient_id' => 'integer', 'doctor_id' => 'integer', 'payment_type_id' => 'integer', 'entry_date' => 'date', 'due_date' => 'date', 'delivery_date' => 'date', 'amount' => 'decimal:2', 'discount_percent' => 'decimal:2', 'discount_amount' => 'decimal:2', 'extra_discount_percent' => 'decimal:2', 'extra_discount_amount' => 'decimal:2', 'paid_amount' => 'decimal:2', 'payable_amount' => 'decimal:2', 'due_amount' => 'decimal:2'];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function reference(): BelongsTo
    {
        return $this->belongsTo(Reference::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function investigations(): BelongsToMany
    {
        return $this->belongsToMany(Investigation::class, 'patient_investigation_items')
            ->withPivot(['rate', 'eye_type', 'position'])->orderByPivot('position');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'investigation_id');
    }

    public function dueCollections(): HasMany
    {
        return $this->hasMany(DueCollection::class, 'investigation_id');
    }

    public function hasReferences(): bool
    {
        if ($this->reference_commission_paid_at !== null) {
            return true;
        }

        $hasRefund = array_key_exists('refunds_count', $this->getAttributes())
            ? $this->refunds_count > 0 : $this->refunds()->exists();
        if ($hasRefund) {
            return true;
        }

        return array_key_exists('due_collections_count', $this->getAttributes())
            ? $this->due_collections_count > 0 : $this->dueCollections()->exists();
    }
}
