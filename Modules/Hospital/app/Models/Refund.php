<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use LogicException;

#[Fillable(['cause_id', 'remarks'])]
class Refund extends BaseModel
{
    protected string $crudPermission = 'refunds';

    protected bool $branchScoped = true;

    protected array $exposed = ['id', 'branch_id', 'consultation_id', 'investigation_id', 'operation_id', 'cause_id', 'invoice_no', 'amount', 'due_adjustment', 'remarks', 'created_at', 'updated_at'];

    protected array $searchable = ['id', 'invoice_no', 'remarks'];

    protected array $sortable = ['id', 'created_at'];

    protected string $defaultSort = 'id';

    protected string $defaultDirection = 'desc';

    // Load source keys first so the final patient relation can batch every service together.
    protected array $listWith = ['cause:id,name', 'consultation:id,patient_id,invoice_no', 'investigation:id,patient_id,invoice_no', 'operation:id,patient_id,invoice_no', 'patient:id,patient_id,name,mobile_no'];

    protected array $formWith = [];

    protected array $allowedIncludes = [];

    protected array $exposedRelations = ['cause' => ['id', 'name'], 'consultation' => ['id', 'patient_id', 'invoice_no'], 'investigation' => ['id', 'patient_id', 'invoice_no'], 'operation' => ['id', 'patient_id', 'invoice_no'], 'patient' => ['id', 'patient_id', 'name', 'mobile_no'], 'items' => ['id', 'name']];

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'consultation_id' => 'integer', 'investigation_id' => 'integer', 'operation_id' => 'integer', 'cause_id' => 'integer', 'amount' => 'decimal:2', 'due_adjustment' => 'decimal:2'];
    }

    public function serviceType(): string
    {
        return match (true) {
            $this->consultation_id !== null => 'consultation',
            $this->investigation_id !== null => 'investigation',
            $this->operation_id !== null => 'operation',
            default => throw new LogicException('A refund must reference one clinical invoice.'),
        };
    }

    public function cause(): BelongsTo
    {
        return $this->belongsTo(RefundCause::class, 'cause_id');
    }

    protected function sourcePatientId(): Attribute
    {
        return Attribute::get(function (): ?int {
            $source = $this->serviceType();
            if (! $this->relationLoaded($source)) {
                throw new LogicException('Eager-load the source invoice before its patient.');
            }

            return $this->getRelation($source)?->patient_id;
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'source_patient_id');
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function investigation(): BelongsTo
    {
        return $this->belongsTo(PatientInvestigation::class, 'investigation_id');
    }

    public function operation(): BelongsTo
    {
        return $this->belongsTo(PatientOperation::class, 'operation_id');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Investigation::class, 'refund_items', 'refund_id', 'item_id')
            ->withPivot(['invoice_id', 'rate', 'amount', 'eye_type', 'position'])->orderByPivot('position');
    }
}
