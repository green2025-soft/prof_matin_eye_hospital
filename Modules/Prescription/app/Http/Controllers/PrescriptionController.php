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
use Modules\Pharmacy\Models\Product;
use Modules\Prescription\Http\Requests\PrescriptionRequest;
use Modules\Prescription\Models\Prescription;
use Modules\Prescription\Models\PrescriptionMedicine;
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
                Patient::where('branch_id', $branchId),
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

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        if ($record !== null && ! request()->isMethod('DELETE') && Gate::allows('view', $record)) {
            return redirect()
                ->route($this->routePrefix().'.show', [
                    $this->parameter => $record,
                ] + $this->routeParameters($record));
        }

        return parent::redirectAfterWrite($record);
    }

    public function show(Request $request, Prescription $prescription): View
    {
        $actor = $request->user('web');
        Gate::forUser($actor)
            ->authorize('view', $prescription);

        // Read stored clinical JSON through object properties in the print templates.
        $prescription->mergeCasts([
            'section_details' => 'object',
            'history_details' => 'object',
            'examination_details' => 'object',
        ]);
        $prescription->load([
            'patient:id,patient_id,name,mobile_no,age,age_months,age_days,date_of_birth,gender',
            'doctor:id,name,bn_name,designation,specialist,bn_specialist,'
                .'education_degree,bn_education_degree,current_employment,bn_current_employment,'
                .'bmdc_reg,bn_bmdc_reg,pad_footer_text',
            'medicines:id,prescription_id,product_id,medicine_name,dose,frequency,duration,instructions,ordering',
            'medicines.generic:phr_product_categories.id,phr_product_categories.name',
        ]);

        $settings       = Setting::values('prescription');
        $routePrefix    = $this->routePrefix();
        $sectionDetails = $prescription->section_details;

        $settingsKey    = fn($key) => $settings["general_$key"]?? null;
        $customPaperSize = [
            'width'     => $settingsKey('custom_width'),
            'height'    => $settingsKey('custom_height'),
        ];
        $printMargins = [
            'top'       => $settingsKey('margin_top'),
            'right'     => $settingsKey('margin_right'),
            'bottom'    => $settingsKey('margin_bottom'),
            'left'      => $settingsKey('margin_left'),
        ];

        $enableNote     = fn($key) => $settingsKey($key."_enabled") ? collect($sectionDetails?->$key ?? null) : null;

        $data = [
            'prescription'      => $prescription,
            'patient'           => $prescription->patient,
            'patientAge'        => $prescription->patient?->ageLabel() ?? '--',
            'doctor'            => $prescription->doctor,
            'medicines'         => $prescription->medicines,
            'chiefComplaints'   => $enableNote('chief_complaints'),
            'historyNotes'      => $enableNote('history'),
            'examinationNotes'  => $enableNote('examination'),
            'diagnosisNotes'    => $enableNote('diagnosis'),
            'investigationNotes' => $enableNote('investigations'),
            'adviceNotes'       => $enableNote('advice'),
            'showFollowUp'      => $settings['general_follow_up_enabled'],
            'entryDate'         => $prescription->prescription_date->format('d-m-Y'),
            'referenceNumber'   => $prescription->rx_number ?? '#'.$prescription->id,
            'documentTitle'     => 'Prescription',
            'backUrl'           => route($routePrefix.'.index'),
            'canEdit'           => Gate::forUser($actor)->allows('update', $prescription),
            'editUrl'           => route($routePrefix.'.edit', $prescription),
            'canCreate'         => Gate::forUser($actor)->allows('create', Prescription::class),
            'copyUrl'           => route($routePrefix.'.create', ['copy' => $prescription->id]),
            'paperSize'         => $settings['general_paper'],
            'customPaperSize'   => $customPaperSize,
            'showHeader'        => $settings['general_header_enabled'],
            'printMargins'      => $printMargins,
        ];

        return view('prescription::prescriptions.show', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $request  = request();
        $branchId = (int) $request->attributes->get('branch_id', 0);

        // A copied prescription starts with its doctor only.
        if (! $record->exists && $request->query->has('copy')) {
            $values = $request->validate([
                'copy' => ['required', 'integer', 'min:1'],
            ]);
            $source = Prescription::where('branch_id', $branchId)
                ->findOrFail($values['copy'], ['id', 'branch_id', 'doctor_id']);
            Gate::forUser($actor)
                ->authorize('view', $source);

            $record->doctor_id = $source->doctor_id;
        }

        $patientQuery = Patient::where('branch_id', $branchId);
        $doctorQuery = Doctor::where('branch_id', $branchId);

        $doctorLocked = $actor instanceof Doctor;
        $doctorId     = $doctorLocked
            ? $actor->getKey()
            : getValue('doctor_id', $record, $request->query('doctor_id'));

        $selectedPatient = $this->selectedOption(
            $patientQuery,
            getValue('patient_id', $record, $request->query('patient_id')),
            ['id', 'patient_id', 'name', 'mobile_no', 'age', 'gender', 'address'],
        );
        $selectedDoctor = $this->selectedOption(
            $doctorQuery,
            $doctorId,
            ['id', 'doctor_id', 'name'],
        );

        $subscription = $request->attributes->get('application_subscription');
        $plan         = $subscription?->isUsable() ? $subscription->plan : null;
        $permissions  = app(AccessManager::class)->navigationPermissions($actor, $plan);

        // Flashed empty medicine rows must override the saved prescription rows.
        $medicineInputs  = $this->oldRows('medicines', [], 100);
        $hasOldMedicines = $request->session()->hasOldInput('medicines');
        $medicineIds     = $hasOldMedicines
            ? array_column($medicineInputs, 'product_id')
            : $record->medicines
                ->pluck('product_id')
                ->all();

        $medicineProducts = Product::where('type', 'medicine')
            ->with(['category:id,name', 'dosage:id,name'])
            ->findMany($medicineIds, ['id', 'name', 'category_id', 'dosage_id'])
            ->keyBy('id');

        $canCreatePatient = isset($permissions['hospital.view'], $permissions['patients.create'])
            && Gate::forUser($actor)->allows('create', Patient::class);
        $canCreateOptions = isset($permissions['prescription-options.create'])
            && Gate::forUser($actor)->allows('create', PrescriptionOption::class);
        $entryDate        = $record->prescription_date?->toDateString() ?? now()->toDateString();

        $data = [
            'record'           => $record,
            'selectedPatient'  => $selectedPatient,
            'selectedDoctor'   => $selectedDoctor,
            'doctorLocked'     => $doctorLocked,
            'canCreatePatient' => $canCreatePatient,
            'canCreateOptions' => $canCreateOptions,
            'entryDate'        => $entryDate,
            'settings'         => Setting::values('prescription'),
            'medicineInputs'   => $medicineInputs,
            'medicineProducts' => $medicineProducts,
            'dosageTypes'      => PrescriptionMedicine::dosageTypes(),
            'hasOldMedicines'  => $hasOldMedicines,
        ];

        return $data;
    }
}
