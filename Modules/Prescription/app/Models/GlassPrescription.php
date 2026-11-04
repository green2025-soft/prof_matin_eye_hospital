<?php

namespace Modules\Prescription\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Models\Patient;
use Modules\Prescription\Policies\PrescriptionPolicy;

#[UsePolicy(PrescriptionPolicy::class)]
#[Fillable([
    'patient_id', 'doctor_id', 'prescription_date', 'pd', 'disclaimer', 'lens_options',
    'r_sphere_dv', 'r_cylinder_dv', 'r_axis_dv', 'r_acuity_dv',
    'r_sphere_nv', 'r_cylinder_nv', 'r_axis_nv', 'r_acuity_nv',
    'l_sphere_dv', 'l_cylinder_dv', 'l_axis_dv', 'l_acuity_dv',
    'l_sphere_nv', 'l_cylinder_nv', 'l_axis_nv', 'l_acuity_nv',
])]
class GlassPrescription extends BaseModel
{
    protected string $crudPermission = 'glass-prescriptions';

    protected bool $branchScoped = true;

    protected array $exposed = [
        'id', 'branch_id', 'patient_id', 'doctor_id', 'prescription_date', 'pd', 'disclaimer', 'lens_options',
        'r_sphere_dv', 'r_cylinder_dv', 'r_axis_dv', 'r_acuity_dv',
        'r_sphere_nv', 'r_cylinder_nv', 'r_axis_nv', 'r_acuity_nv',
        'l_sphere_dv', 'l_cylinder_dv', 'l_axis_dv', 'l_acuity_dv',
        'l_sphere_nv', 'l_cylinder_nv', 'l_axis_nv', 'l_acuity_nv', 'created_at', 'updated_at',
    ];

    protected array $listColumns = ['id', 'branch_id', 'patient_id', 'doctor_id', 'prescription_date', 'pd', 'created_at'];

    protected array $searchable = ['id', 'prescription_date'];

    protected array $filterableIds = ['filter_patient_id' => 'patient_id', 'filter_doctor_id' => 'doctor_id'];

    protected array $sortable = ['prescription_date', 'id'];

    protected string $defaultSort = 'prescription_date';

    protected string $defaultDirection = 'desc';

    protected array $listWith = ['patient:id,patient_id,name', 'doctor:id,name'];

    protected array $formWith = [];

    protected array $exposedRelations = ['patient' => ['id', 'patient_id', 'name'], 'doctor' => ['id', 'name']];

    protected function casts(): array
    {
        return [
            'branch_id' => 'integer',
            'patient_id' => 'integer',
            'doctor_id' => 'integer',
            'prescription_date' => 'date',
            'lens_options' => 'array',
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

    public function isCrudEnabled(): bool
    {
        return $this->prescriptionSettings()['glass_enabled'];
    }

    public static function lensOptions(): array
    {
        return [
            'bifocal' => 'Bifocal',
            'varifocal' => 'Varifocal',
            'unifocal' => 'Unifocal',
            'constant_use' => 'Constant use',
            'reading' => 'Reading',
        ];
    }
}
