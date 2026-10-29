<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'designation', 'contact_no', 'address', 'consultation_percent', 'investigation_percent', 'operation_percent'])]
class Reference extends BaseModel
{
    protected string $crudPermission = 'references';

    protected bool $branchScoped = true;

    protected array $exposed = ['id', 'branch_id', 'reference_id', 'name', 'designation', 'contact_no', 'address', 'consultation_percent', 'investigation_percent', 'operation_percent', 'created_at'];

    protected array $searchable = ['reference_id', 'name', 'contact_no'];

    protected array $containsSearch = ['reference_id'];

    protected array $sortable = ['name', 'reference_id', 'id'];

    protected string $defaultSort = 'id';

    protected array $exposedCounts = ['consultations', 'patientInvestigations', 'patientOperations'];

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'consultation_percent' => 'decimal:2', 'investigation_percent' => 'decimal:2', 'operation_percent' => 'decimal:2'];
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function patientInvestigations(): HasMany
    {
        return $this->hasMany(PatientInvestigation::class);
    }

    public function patientOperations(): HasMany
    {
        return $this->hasMany(PatientOperation::class);
    }

    public function hasReferences(): bool
    {
        $hasConsultations = array_key_exists('consultations_count', $this->getAttributes())
            ? $this->consultations_count > 0 : $this->consultations()->exists();
        $hasInvestigations = array_key_exists('patient_investigations_count', $this->getAttributes())
            ? $this->patient_investigations_count > 0 : $this->patientInvestigations()->exists();
        $hasOperations = array_key_exists('patient_operations_count', $this->getAttributes())
            ? $this->patient_operations_count > 0 : $this->patientOperations()->exists();

        return $hasConsultations || $hasInvestigations || $hasOperations;
    }
}
