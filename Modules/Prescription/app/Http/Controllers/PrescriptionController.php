<?php

namespace Modules\Prescription\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Services\AccessManager;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Models\Patient;
use Modules\Prescription\Http\Requests\PrescriptionRequest;
use Modules\Prescription\Models\Prescription;
use Modules\Prescription\Models\PrescriptionOption;
use Modules\Prescription\Services\PrescriptionService;

class PrescriptionController extends BaseController
{
    protected string $model = Prescription::class;

    protected string $title = 'Prescription';

    protected string $service = PrescriptionService::class;

    protected string $views = 'prescription::prescriptions';

    protected string $parameter = 'prescription';

    public function index(ListRequest $request): View
    {
        $branchId = (int) $request->attributes->get('branch_id', 0);

        $data = [
            'selectedPatient' => $this->selectedOption(
                Patient::query()
                    ->where('branch_id', $branchId),
                $request->validated('filter_patient_id'),
                ['id', 'patient_id', 'name', 'mobile_no'],
            ),
        ];

        return $this->listData($request)
            ->with($data);
    }

    public function store(PrescriptionRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request, Prescription $prescription): View
    {
        $actor = $request->user('web');
        Gate::forUser($actor)
            ->authorize('view', $prescription);

        $prescription->load([
            'patient:id,patient_id,name,mobile_no,age,gender,address',
            'doctor:id,branch_id,doctor_id,name,bn_name,designation,specialist,bn_specialist,education_degree,bn_education_degree,current_employment,bn_current_employment,bmdc_reg,bn_bmdc_reg,pad_footer_text,mobile_no',
            'medicines',
        ]);
        $settings = Setting::values('prescription');
        $routePrefix = $this->routePrefix();

        $data = [
            'record' => $prescription,
            'patient' => $prescription->patient,
            'doctor' => $prescription->doctor,
            'medicines' => $prescription->medicines,
            'entryDate' => $prescription->prescription_date->format('d-m-Y'),
            'referenceNumber' => '#'.$prescription->id,
            'documentTitle' => 'Prescription',
            'pageTitle' => 'Prescription #'.$prescription->id,
            'backUrl' => route($routePrefix.'.index'),
            'canEdit' => Gate::forUser($actor)
                ->allows('update', $prescription),
            'editUrl' => route($routePrefix.'.edit', $prescription),
            'settings' => $settings,
            'paperSize' => $settings['general_paper'],
            'showHeader' => $settings['general_header_enabled'],
        ];

        return view('prescription::prescriptions.show', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $branchId = (int) request()->attributes->get('branch_id', 0);
        $copiedFrom = null;

        if (! $record->exists && request()->query->has('copy')) {
            $values = request()->validate([
                'copy' => ['required', 'integer', 'min:1'],
            ]);
            $source = Prescription::query()
                ->where('branch_id', $branchId)
                ->with('medicines')
                ->findOrFail($values['copy']);
            Gate::forUser($actor)
                ->authorize('view', $source);

            $record->fill($source->only([
                'patient_id', 'doctor_id', 'chief_complaints', 'history',
                'examination', 'diagnosis', 'investigations', 'advice',
                'history_details', 'examination_details',
                'section_details',
            ]));
            $record->setRelation('medicines', $source->medicines);
            $copiedFrom = $source->id;
        }

        $patientQuery = Patient::query()
            ->where('branch_id', $branchId);
        $doctorQuery = Doctor::query()
            ->where('branch_id', $branchId);
        $doctorLocked = $actor instanceof Doctor;
        $doctorId = $doctorLocked
            ? $actor->getKey()
            : getValue('doctor_id', $record, request()->query('doctor_id'));

        $selectedPatient = $this->selectedOption(
            $patientQuery,
            getValue('patient_id', $record, request()->query('patient_id')),
            ['id', 'patient_id', 'name', 'mobile_no', 'age', 'gender', 'address'],
        );
        $selectedDoctor = $this->selectedOption(
            $doctorQuery,
            $doctorId,
            ['id', 'doctor_id', 'name'],
        );
        $subscription = request()->attributes->get('application_subscription');
        $plan = $subscription?->isUsable() ? $subscription->plan : null;
        $permissions = app(AccessManager::class)->navigationPermissions($actor, $plan);
        $sectionDetails = $record->section_details ?? [];

        return [
            'record' => $record,
            'copiedFrom' => $copiedFrom,
            'selectedPatient' => $selectedPatient,
            'selectedDoctor' => $selectedDoctor,
            'doctorLocked' => $doctorLocked,
            'canCreatePatient' => isset($permissions['hospital.view'], $permissions['patients.create'])
                && Gate::forUser($actor)
                    ->allows('create', Patient::class),
            'canCreateOptions' => isset($permissions['prescription-options.create'])
                && Gate::forUser($actor)
                    ->allows('create', PrescriptionOption::class),
            'entryDate' => $record->prescription_date?->toDateString() ?? now()
                ->toDateString(),
            'settings' => Setting::values('prescription'),
            'medicineInputs' => $this->oldRows('medicines', [], 100),
            'sectionInputs' => [
                'chief_complaints' => $this->oldRows(
                    'section_details.chief_complaints', $sectionDetails['chief_complaints'] ?? [],
                ),
                'history' => $this->oldRows('section_details.history', $sectionDetails['history'] ?? []),
                'examination' => $this->oldRows('section_details.examination', $sectionDetails['examination'] ?? []),
                'diagnosis' => $this->oldRows('section_details.diagnosis', $sectionDetails['diagnosis'] ?? []),
                'investigations' => $this->oldRows(
                    'section_details.investigations', $sectionDetails['investigations'] ?? [],
                ),
                'advice' => $this->oldRows('section_details.advice', $sectionDetails['advice'] ?? []),
            ],
            'hasOldMedicines' => request()
                ->session()
                ->hasOldInput('medicines'),
        ];
    }
}
