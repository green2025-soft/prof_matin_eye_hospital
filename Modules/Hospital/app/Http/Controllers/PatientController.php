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

    protected array $selectorFields = ['id', 'patient_id', 'name', 'mobile_no'];

    public function index(ListRequest $request): View
    {
        return $this->listData($request)->with(['genders' => genders(), 'bloodGroups' => bloodGroups()]);
    }

    public function options(SelectorRequest $request): JsonResponse
    {
        $response = parent::options($request);
        $data = $response->getData(true);
        $data['results'] = array_map(
            fn (array $patient): array => $patient + ['text' => $this->patientOptionText($patient)],
            $data['results'],
        );

        return $response->setData($data);
    }

    public function store(PatientRequest $request, PatientService $patients): RedirectResponse|JsonResponse
    {
        $patient = $patients->save($request->user('web'), $this->record($request), $request->payload(), (int) $request->attributes->get('branch_id'));

        if (! $this->isUpdate($request) && $request->expectsJson()) {
            $option = $patient->only(['id', 'name', 'patient_id', 'mobile_no']);

            return response()->json($option + ['text' => $this->patientOptionText($option)], 201);
        }

        return $this->redirectAfterWrite($patient)->with('status', 'Patient'.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    public function modal(Request $request): View
    {
        $actor = $request->user('web');
        Gate::forUser($actor)->authorize('create', Patient::class);
        $patient = new Patient;

        return view('hospital::patients.fields', [
            ...$this->formData($actor, $patient),
            'patient' => $patient,
            'fieldPrefix' => 'modal_patient_',
        ]);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $selectedDivision = $this->selectedOption(Division::query(), getValue('division_id', $record));
        $selectedDistrict = $selectedDivision === null ? null : $this->selectedOption(
            District::query()->where('division_id', $selectedDivision->getKey()),
            getValue('district_id', $record),
        );
        $selectedThana = $selectedDistrict === null ? null : $this->selectedOption(
            Thana::query()->where('district_id', $selectedDistrict->getKey()),
            getValue('thana_id', $record),
        );

        return [
            'genders' => genders(),
            'bloodGroups' => bloodGroups(),
            'selectedDivision' => $selectedDivision,
            'selectedDistrict' => $selectedDistrict,
            'selectedThana' => $selectedThana,
        ];
    }

    private function patientOptionText(array $patient): string
    {
        return $patient['patient_id'].' — '.$patient['name'].' — '.$patient['mobile_no'];
    }
}
