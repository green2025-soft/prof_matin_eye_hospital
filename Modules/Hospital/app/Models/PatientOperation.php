<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\TenantCore\Models\PaymentType;

#[Fillable(['patient_id', 'doctor_id', 'entry_date', 'payment_type_id', 'operation_id', 'consultant_id', 'eye_type', 'patient_type', 'remarks', 'amount', 'discount_amount', 'paid_amount', 'payable_amount', 'due_amount', 'due_date', 'operation_date'])]
class PatientOperation extends BaseModel
{
    protected string $crudPermission = 'patient-operations';

    protected bool $branchScoped = true;

    protected array $exposed = ['id', 'branch_id', 'reference_id', 'reference_commission_percent', 'reference_discount_mode', 'reference_due_mode', 'reference_commission_paid_amount', 'reference_commission_paid_at', 'patient_id', 'doctor_id', 'entry_date', 'payment_type_id', 'operation_id', 'consultant_id', 'eye_type', 'patient_type', 'remarks', 'amount', 'discount_amount', 'paid_amount', 'payable_amount', 'due_amount', 'due_date', 'operation_date', 'invoice_no', 'created_at'];

    protected array $searchable = ['id', 'entry_date', 'invoice_no'];

    protected array $filterableIds = ['filter_doctor_id' => 'doctor_id', 'filter_patient_id' => 'patient_id'];

    protected array $sortable = ['entry_date', 'id'];

    protected string $defaultSort = 'id';

    protected string $defaultDirection = 'desc';

    protected array $exposedCounts = ['refund', 'dueCollections'];

    protected array $listWith = ['patient:id,name,patient_id', 'doctor:id,name', 'paymentType:id,name'];

    protected array $formWith = [];

    protected array $exposedRelations = ['reference' => ['id', 'reference_id', 'name', 'contact_no'], 'patient' => ['id', 'name', 'patient_id'], 'doctor' => ['id', 'name'], 'paymentType' => ['id', 'name']];

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'reference_id' => 'integer', 'reference_commission_percent' => 'decimal:2', 'reference_commission_paid_amount' => 'decimal:2', 'reference_commission_paid_at' => 'datetime', 'patient_id' => 'integer', 'doctor_id' => 'integer', 'payment_type_id' => 'integer', 'operation_id' => 'integer', 'consultant_id' => 'integer', 'entry_date' => 'date', 'due_date' => 'date', 'operation_date' => 'date', 'amount' => 'decimal:2', 'discount_amount' => 'decimal:2', 'paid_amount' => 'decimal:2', 'payable_amount' => 'decimal:2', 'due_amount' => 'decimal:2'];
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

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'consultant_id');
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class, 'operation_id');
    }

    public function dueCollections(): HasMany
    {
        return $this->hasMany(DueCollection::class, 'operation_id');
    }

    public function hasReferences(): bool
    {
        if ($this->reference_commission_paid_at !== null) {
            return true;
        }

        $hasRefund = array_key_exists('refund_count', $this->getAttributes())
            ? $this->refund_count > 0 : $this->refund()->exists();
        if ($hasRefund) {
            return true;
        }

        return array_key_exists('due_collections_count', $this->getAttributes())
            ? $this->due_collections_count > 0 : $this->dueCollections()->exists();
    }
}
