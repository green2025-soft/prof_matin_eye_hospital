<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Http\Requests\SelectorRequest;
use Modules\Core\Models\Account;
use Modules\Hospital\Http\Requests\PatientRequest;
use Modules\Hospital\Models\Patient;
use Modules\Hospital\Services\PatientService;
use Modules\TenantCore\Models\District;
use Modules\TenantCore\Models\Division;
use Modules\TenantCore\Models\Thana;

class PatientController extends BaseController
{
    protected string $model = Patient::class;

    protected string $title = 'Patient';

    protected string $views = 'hospital::patients';

    protected string $parameter = 'patient';

    protected array $selectorFields = ['id', 'patient_id', 'name', 'mobile_no', 'age', 'age_months', 'age_days', 'date_of_birth'];

    public function index(ListRequest $request): View
    {
        $data = [
            'genders' => genders(),
            'bloodGroups' => bloodGroups(),
        ];

        return $this->listData($request)->with($data);
    }

    public function options(SelectorRequest $request): JsonResponse
    {
        $data = [
            'records' => (new Patient)->optionRecords($request->validated(), $this->selectorFields),
        ];

        return JsonResponse::fromJsonString(view('hospital::selectors.patients', $data)->render());
    }

    public function store(PatientRequest $request, PatientService $patients): RedirectResponse|JsonResponse
    {
        $patient = $patients->save(
            $request->user('web'),
            $this->record($request),
            $request->payload(),
            (int) $request->attributes->get('branch_id')
        );

        if (! $this->isUpdate($request) && $request->expectsJson()) {
            $data = [
                'patient' => $patient,
                'birthDate' => $patient->date_of_birth,
            ];

            return JsonResponse::fromJsonString(view('hospital::selectors.patient', $data)->render(), 201);
        }

        return $this->redirectAfterWrite($patient)
            ->with('status', 'Patient'.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    public function modal(Request $request): View
    {
        $actor = $request->user('web');
        Gate::forUser($actor)
            ->authorize('create', Patient::class);
        $patient = new Patient;

        $data = [
            ...$this->formData($actor, $patient),
            'patient' => $patient,
            'fieldPrefix' => 'modal_patient_',
        ];

        return view('hospital::patients.fields', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $selectedDivision = $this->selectedOption(Division::query(), getValue('division_id', $record));
        $selectedDistrict = $selectedDivision === null ? null : $this->selectedOption(
            District::where('division_id', $selectedDivision->getKey()),
            getValue('district_id', $record),
        );
        $selectedThana = $selectedDistrict === null ? null : $this->selectedOption(
            Thana::where('district_id', $selectedDistrict->getKey()),
            getValue('thana_id', $record),
        );

        return [
            'genders' => genders(),
            'bloodGroups' => bloodGroups(),
            'ageInput' => getValue('age_input', null, $record->date_of_birth === null ? 'age' : 'date_of_birth'),
            'birthDate' => getValue('date_of_birth', null, $record->date_of_birth?->toDateString()),
            'selectedDivision' => $selectedDivision,
            'selectedDistrict' => $selectedDistrict,
            'selectedThana' => $selectedThana,
        ];
    }
}
