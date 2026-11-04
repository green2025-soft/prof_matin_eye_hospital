<?php

namespace Modules\Prescription\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['section', 'name', 'ordering'])]
class PrescriptionOption extends BaseModel
{
    private const SECTIONS = [
        'chief_complaints' => 'Chief complaints',
        'history' => 'History',
        'examination' => 'On examination',
        'diagnosis' => 'Diagnosis',
        'advice' => 'Advice',
        'follow_up_notes' => 'Follow-up',
        'medicine_unit' => 'Medicine unit',
        'medicine_instruction' => 'Medicine instruction',
    ];

    protected string $crudPermission = 'prescription-options';

    protected $attributes = ['ordering' => 0];

    protected array $exposed = ['id', 'section', 'name', 'ordering', 'created_at'];

    protected array $searchable = ['name'];

    protected array $containsSearch = ['name'];

    protected array $filterable = ['section' => self::SECTIONS];

    protected array $selectorFilters = ['section'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected function casts(): array
    {
        return ['ordering' => 'integer'];
    }

    public static function sections(): array
    {
        return self::SECTIONS;
    }
}
