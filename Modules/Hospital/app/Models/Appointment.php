<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['doctor_id', 'patient_id', 'appointment_date', 'appointment_time'])]
class Appointment extends BaseModel
{
    protected string $crudPermission = 'appointments';

    protected bool $branchScoped = true;

    protected array $exposed = ['id', 'branch_id', 'doctor_id', 'patient_id', 'appointment_date', 'appointment_time', 'serial_no', 'invoice_no', 'created_at'];

    protected array $searchable = ['id', 'appointment_date', 'serial_no', 'invoice_no'];

    protected array $filterableIds = ['filter_doctor_id' => 'doctor_id', 'filter_patient_id' => 'patient_id'];

    protected array $sortable = ['appointment_date', 'id'];

    protected string $defaultSort = 'id';

    protected string $defaultDirection = 'desc';

    protected array $listWith = ['doctor:id,name', 'patient:id,name,patient_id,mobile_no', 'consultation:id,appointment_id,branch_id'];

    protected array $formWith = [];

    protected array $exposedRelations = ['doctor' => ['id', 'name'], 'patient' => ['id', 'name', 'patient_id', 'mobile_no'], 'consultation' => ['id', 'appointment_id']];

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'doctor_id' => 'integer', 'patient_id' => 'integer', 'appointment_date' => 'date'];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function hasReferences(): bool
    {
        return $this->relationLoaded('consultation') ? $this->consultation !== null : $this->consultation()->exists();
    }

    public function consultation(): HasOne
    {
        return $this->hasOne(Consultation::class);
    }
}
