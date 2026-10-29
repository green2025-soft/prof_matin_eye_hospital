<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Http\Requests\ConsultationRequest;
use Modules\Hospital\Models\Appointment;
use Modules\Hospital\Models\Consultation;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Models\Patient;
use Modules\Hospital\Models\Reference;
use Modules\Hospital\Services\ClinicalEntryService;
use Modules\TenantCore\Models\PaymentType;

class ConsultationController extends BaseController
{
    protected string $model = Consultation::class;

    protected string $title = 'Consultation';

    protected string $service = ClinicalEntryService::class;

    protected string $views = 'hospital::consultations';

    protected string $parameter = 'consultation';

    public function index(ListRequest $request): View
    {
        $branchId = max(0, (int) $request->attributes->get('branch_id', 0));
        $view = $this->listData($request);

        return $view->with([
            'invoiceBalances' => app(ClinicalEntryService::class)->listInvoiceBalances($request->user('web'), new Consultation, $view->getData()['records']->modelKeys(), $branchId),
            'filterDoctor' => $this->selectedOption(Doctor::query()->where('branch_id', $branchId), $request->validated('filter_doctor_id'), ['id', 'doctor_id', 'name']),
            'filterPatient' => $this->selectedOption(Patient::query(), $request->validated('filter_patient_id'), ['id', 'patient_id', 'name', 'mobile_no']),
        ]);
    }

    public function show(Request $request, ClinicalEntryService $entries): View
    {
        return view('hospital::consultations.invoice', $entries->invoiceData($request->user('web'), $this->record($request)));
    }

    public function store(ConsultationRequest $request, ClinicalEntryService $entries): RedirectResponse
    {
        $consultation = $entries->saveConsultation($request->user('web'), $this->record($request), $request->payload(), (int) $request->attributes->get('branch_id'));

        return $this->redirectAfterWrite($consultation)->with('status', 'Consultation'.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    protected function formData(Account $actor, Model $record): array
    {
        $branchId = max(0, (int) request()->attributes->get('branch_id', 0));
        $today = now(config('app.timezone'))->toDateString();
        $referenceEnabled = (bool) Setting::values('hospital', $record->getConnectionName())['consultation_reference_enabled'];
        $appointmentId = getValue('appointment_id', $record, $record->exists ? null : request()->query('appointment'));
        $appointment = $record->exists ? null : $this->selectedOption(
            Appointment::query(), $appointmentId, ['id', 'patient_id', 'doctor_id', 'appointment_date'],
        );
        if ($appointment !== null) {
            Gate::forUser($actor)->authorize('viewAny', Appointment::class);
        }

        return [
            'referenceEnabled' => $referenceEnabled,
            'selectedReference' => $referenceEnabled ? $this->selectedOption(Reference::query(), getValue('reference_id', $record), ['id', 'reference_id', 'name', 'contact_no']) : null,
            'selectedPatient' => $this->selectedOption(Patient::query(), getValue('patient_id', $record, $appointment?->patient_id), ['id', 'name', 'patient_id', 'mobile_no']),
            'selectedDoctor' => $this->selectedOption(Doctor::query()->where('branch_id', $branchId), getValue('doctor_id', $record, $appointment?->doctor_id), ['id', 'doctor_id', 'name', 'consultation_fee', 'follow_up_fee']),
            'paymentTypes' => PaymentType::query()->orderBy('ordering')->orderBy('id')->get(['id', 'name']),
            'patientTypes' => patientTypes(),
            'entryDate' => $record->entry_date?->toDateString() ?? $appointment?->appointment_date?->toDateString() ?? $today,
            'dueDate' => $record->due_date?->toDateString() ?? $today,
            'appointmentId' => $appointmentId,
        ];
    }
}
