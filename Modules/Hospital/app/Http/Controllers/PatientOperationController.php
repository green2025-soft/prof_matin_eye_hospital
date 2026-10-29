<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Http\Requests\PatientOperationRequest;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Models\Operation;
use Modules\Hospital\Models\Patient;
use Modules\Hospital\Models\PatientOperation;
use Modules\Hospital\Models\Reference;
use Modules\Hospital\Services\ClinicalEntryService;
use Modules\TenantCore\Models\PaymentType;

class PatientOperationController extends BaseController
{
    protected string $model = PatientOperation::class;

    protected string $title = 'Patient operation';

    protected string $service = ClinicalEntryService::class;

    protected string $views = 'hospital::patient-operations';

    protected string $parameter = 'patient_operation';

    public function index(ListRequest $request): View
    {
        $branchId = max(0, (int) $request->attributes->get('branch_id', 0));
        $view = $this->listData($request);

        return $view->with([
            'invoiceBalances' => app(ClinicalEntryService::class)->listInvoiceBalances($request->user('web'), new PatientOperation, $view->getData()['records']->modelKeys(), $branchId),
            'filterDoctor' => $this->selectedOption(Doctor::query()->where('branch_id', $branchId), $request->validated('filter_doctor_id'), ['id', 'doctor_id', 'name']),
            'filterPatient' => $this->selectedOption(Patient::query(), $request->validated('filter_patient_id'), ['id', 'patient_id', 'name', 'mobile_no']),
        ]);
    }

    public function show(Request $request, ClinicalEntryService $entries): View
    {
        return view('hospital::patient-operations.invoice', $entries->invoiceData($request->user('web'), $this->record($request)));
    }

    public function store(PatientOperationRequest $request, ClinicalEntryService $entries): RedirectResponse
    {
        $operation = $entries->saveOperation($request->user('web'), $this->record($request), $request->payload(), (int) $request->attributes->get('branch_id'));

        return $this->redirectAfterWrite($operation)->with('status', 'Patient operation'.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    protected function formData(Account $actor, Model $record): array
    {
        $branchId = max(0, (int) request()->attributes->get('branch_id', 0));
        $today = now(config('app.timezone'))->toDateString();
        $referenceEnabled = (bool) Setting::values('hospital', $record->getConnectionName())['operation_reference_enabled'];

        return [
            'referenceEnabled' => $referenceEnabled,
            'selectedReference' => $referenceEnabled ? $this->selectedOption(Reference::query(), getValue('reference_id', $record), ['id', 'reference_id', 'name', 'contact_no']) : null,
            'selectedPatient' => $this->selectedOption(Patient::query(), getValue('patient_id', $record), ['id', 'name', 'patient_id', 'mobile_no']),
            'selectedDoctor' => $this->selectedOption(Doctor::query()->where('branch_id', $branchId), getValue('doctor_id', $record), ['id', 'doctor_id', 'name', 'consultation_fee', 'follow_up_fee']),
            'paymentTypes' => PaymentType::query()->orderBy('ordering')->orderBy('id')->get(['id', 'name']),
            'selectedOperation' => $this->selectedOption(Operation::query(), getValue('operation_id', $record), ['id', 'name', 'single_rate', 'both_rate']),
            'selectedConsultant' => $this->selectedOption(Doctor::query()->where('branch_id', $branchId), getValue('consultant_id', $record), ['id', 'doctor_id', 'name']),
            'patientTypes' => patientTypes(),
            'eyeTypes' => eyeTypes(),
            'entryDate' => $record->entry_date?->toDateString() ?? $today,
            'dueDate' => $record->due_date?->toDateString() ?? $today,
            'operationDate' => $record->operation_date?->toDateString() ?? $today,
        ];
    }
}
