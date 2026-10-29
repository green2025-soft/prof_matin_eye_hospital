<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TenantCore\Models\District;
use Modules\TenantCore\Models\Division;
use Modules\TenantCore\Models\Thana;

#[Fillable(['name', 'father_husband_name', 'age', 'gender', 'blood_group', 'mobile_no', 'division_id', 'district_id', 'thana_id', 'address'])]
class Patient extends BaseModel
{
    protected string $crudPermission = 'patients';

    protected bool $branchScoped = true;

    protected array $exposed = ['id', 'branch_id', 'patient_id', 'name', 'father_husband_name', 'age', 'gender', 'blood_group', 'mobile_no', 'division_id', 'district_id', 'thana_id', 'address', 'created_at'];

    protected array $searchable = ['patient_id', 'name', 'mobile_no'];

    protected array $containsSearch = ['patient_id'];

    protected array $sortable = ['name', 'patient_id', 'id'];

    protected string $defaultSort = 'id';

    protected array $listWith = ['division:id,name', 'district:id,name', 'thana:id,name'];

    protected array $formWith = [];

    protected array $exposedRelations = ['division' => ['id', 'name'], 'district' => ['id', 'name'], 'thana' => ['id', 'name']];

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'age' => 'integer', 'division_id' => 'integer', 'district_id' => 'integer', 'thana_id' => 'integer'];
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function thana(): BelongsTo
    {
        return $this->belongsTo(Thana::class);
    }
}
