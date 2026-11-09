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
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Models\Patient;
use Modules\Prescription\Http\Requests\GlassPrescriptionRequest;
use Modules\Prescription\Models\GlassPrescription;

class GlassPrescriptionController extends BaseController
{
    protected string $model = GlassPrescription::class;

    protected string $title = 'Glass prescription';

    protected string $views = 'prescription::glass-prescriptions';

    protected string $parameter = 'glass_prescription';

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(GlassPrescriptionRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }

    public function show(Request $request): View
    {
        $actor = $request->user('web');
        $record = $this->record($request);

        Gate::forUser($actor)
            ->authorize('view', $record);

        $record->load([
            'patient:id,patient_id,name,mobile_no,age,gender',
            'doctor:id,name,bn_name,designation,specialist,bn_specialist,'
                .'education_degree,bn_education_degree,current_employment,bn_current_employment,'
                .'bmdc_reg,bn_bmdc_reg,pad_footer_text',
        ]);
        $settings = Setting::values('prescription', $record->getConnectionName());

        $data = [
            'record' => $record,
            'patient' => $record->patient,
            'patientAge' => $record->patient?->age ?? '--',
            'doctor' => $record->doctor,
            'entryDate' => $record->prescription_date->format('d-m-Y'),
            'referenceNumber' => $record->rx_number ?? '#'.$record->id,
            'documentTitle' => 'Glass Prescription',
            'backUrl' => route('prescription.glass-prescriptions.index'),
            'canEdit' => Gate::forUser($actor)
                ->allows('update', $record),
            'editUrl' => route('prescription.glass-prescriptions.edit', $record),
            'paperSize' => $settings['glass_paper'],
            'customPaperSize' => [
                'width' => $settings['glass_custom_width'],
                'height' => $settings['glass_custom_height'],
            ],
            'showHeader' => $settings['glass_header_enabled'],
            'printMargins' => [
                'top' => $settings['glass_margin_top'],
                'right' => $settings['glass_margin_right'],
                'bottom' => $settings['glass_margin_bottom'],
                'left' => $settings['glass_margin_left'],
            ],
            'settings' => $settings,
            'lensOptions' => GlassPrescription::lensOptions(),
        ];

        return view('prescription::glass-prescriptions.show', $data);
    }

    protected function formData(Account $actor, Model $record): array
    {
        $branchId = max(0, (int) request()->attributes->get('branch_id', 0));
        $patientQuery = Patient::query()
            ->where('branch_id', $branchId);
        $doctorQuery = Doctor::query()
            ->where('branch_id', $branchId);
        $doctorLocked = $actor instanceof Doctor;
        $doctorId = $doctorLocked ? $actor->getKey() : getValue('doctor_id', $record);

        $selectedPatient = $this->selectedOption(
            $patientQuery,
            getValue('patient_id', $record),
            ['id', 'patient_id', 'name', 'mobile_no', 'age', 'gender', 'address'],
        );
        $selectedDoctor = $this->selectedOption(
            $doctorQuery,
            $doctorId,
            ['id', 'doctor_id', 'name'],
        );

        return [
            'record' => $record,
            'selectedPatient' => $selectedPatient,
            'selectedDoctor' => $selectedDoctor,
            'doctorLocked' => $doctorLocked,
            'entryDate' => getValue('prescription_date', null, $record->prescription_date?->toDateString() ?? now()->toDateString()),
            'settings' => Setting::values('prescription', $record->getConnectionName()),
            'lensOptions' => GlassPrescription::lensOptions(),
        ];
    }
}
