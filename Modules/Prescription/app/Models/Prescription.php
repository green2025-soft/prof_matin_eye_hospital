<?php

namespace Modules\Prescription\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Models\Patient;
use Modules\Prescription\Policies\PrescriptionPolicy;

#[UsePolicy(PrescriptionPolicy::class)]
#[Fillable([
    'patient_id', 'doctor_id', 'prescription_date', 'chief_complaints', 'section_details', 'history', 'history_details',
    'examination', 'examination_details', 'diagnosis', 'investigations', 'advice', 'follow_up_date', 'follow_up_notes',
    'follow_up_interval', 'follow_up_interval_unit',
])]
class Prescription extends BaseModel
{
    protected string $crudPermission = 'prescriptions';

    protected bool $branchScoped = true;

    protected array $exposed = [
        'id', 'branch_id', 'patient_id', 'doctor_id', 'prescription_date', 'chief_complaints', 'section_details',
        'history', 'history_details', 'examination', 'examination_details', 'diagnosis', 'investigations',
        'advice', 'follow_up_date', 'follow_up_notes', 'follow_up_interval', 'follow_up_interval_unit', 'created_at', 'updated_at',
    ];

    protected array $listColumns = ['id', 'branch_id', 'patient_id', 'doctor_id', 'prescription_date', 'follow_up_date', 'created_at'];

    protected array $searchable = ['id', 'prescription_date'];

    protected array $filterableIds = ['filter_patient_id' => 'patient_id', 'filter_doctor_id' => 'doctor_id'];

    protected array $sortable = ['id', 'prescription_date'];

    protected string $defaultSort = 'id';

    protected string $defaultDirection = 'desc';

    protected array $listWith = ['patient:id,patient_id,name', 'doctor:id,name'];

    protected array $formWith = ['medicines'];

    protected array $exposedRelations = ['patient' => ['id', 'patient_id', 'name'], 'doctor' => ['id', 'name']];

    public function isCrudEnabled(): bool
    {
        return $this->prescriptionSettings()['general_enabled'];
    }

    protected function casts(): array
    {
        return [
            'branch_id' => 'integer',
            'patient_id' => 'integer',
            'doctor_id' => 'integer',
            'prescription_date' => 'date',
            'follow_up_date' => 'date',
            'follow_up_interval' => 'integer',
            'section_details' => 'array',
            'history_details' => 'array',
            'examination_details' => 'array',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function medicines(): HasMany
    {
        return $this->hasMany(PrescriptionMedicine::class)->orderBy('ordering')->orderBy('id');
    }
}
