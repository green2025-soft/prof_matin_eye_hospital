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
use Modules\Hospital\Http\Requests\PatientInvestigationRequest;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Models\Investigation;
use Modules\Hospital\Models\Patient;
use Modules\Hospital\Models\PatientInvestigation;
use Modules\Hospital\Models\Reference;
use Modules\Hospital\Services\ClinicalEntryService;
use Modules\TenantCore\Models\PaymentType;

class PatientInvestigationController extends BaseController
{
    protected string $model = PatientInvestigation::class;

    protected string $title = 'Patient investigation';

    protected string $service = ClinicalEntryService::class;

    protected string $views = 'hospital::patient-investigations';

    protected string $parameter = 'patient_investigation';

    public function index(ListRequest $request): View
    {
        $branchId = max(0, (int) $request->attributes->get('branch_id', 0));
        $view = $this->listData($request);

        return $view->with([
            'invoiceBalances' => app(ClinicalEntryService::class)->listInvoiceBalances($request->user('web'), new PatientInvestigation, $view->getData()['records']->modelKeys(), $branchId),
            'filterDoctor' => $this->selectedOption(Doctor::query()->where('branch_id', $branchId), $request->validated('filter_doctor_id'), ['id', 'doctor_id', 'name']),
            'filterPatient' => $this->selectedOption(Patient::query(), $request->validated('filter_patient_id'), ['id', 'patient_id', 'name', 'mobile_no']),
        ]);
    }

    public function show(Request $request, ClinicalEntryService $entries): View
    {
        return view('hospital::patient-investigations.invoice', $entries->invoiceData($request->user('web'), $this->record($request)));
    }

    public function store(PatientInvestigationRequest $request, ClinicalEntryService $entries): RedirectResponse
    {
        $investigation = $entries->saveInvestigation($request->user('web'), $this->record($request), $request->payload(), (int) $request->attributes->get('branch_id'));

        return $this->redirectAfterWrite($investigation)->with('status', 'Patient investigation'.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    protected function formData(Account $actor, Model $record): array
    {
        $branchId = max(0, (int) request()->attributes->get('branch_id', 0));
        $today = now(config('app.timezone'))->toDateString();
        $referenceEnabled = (bool) Setting::values('hospital', $record->getConnectionName())['investigation_reference_enabled'];
        $selectedIds = array_filter(
            getValue('investigation_ids', null, $record->investigations->modelKeys()),
            fn (mixed $id): bool => (is_int($id) && $id > 0) || (is_string($id) && ctype_digit($id) && (int) $id > 0),
        );
        $selectedIds = array_slice(array_values($selectedIds), 0, 100);
        $positions = array_flip(array_map('intval', $selectedIds));
        $savedIds = $record->investigations->modelKeys();
        if ($selectedIds === []) {
            $selectedInvestigations = (new Investigation)->newCollection();
        } elseif (array_diff($selectedIds, $savedIds) === [] && array_diff($savedIds, $selectedIds) === []) {
            $selectedInvestigations = $record->investigations;
        } else {
            $selectedInvestigations = Investigation::query()->with('category:id,name')->whereIn('id', $selectedIds)
                ->get(['id', 'name', 'category_id', 'single_rate', 'both_rate']);
        }
        $selectedInvestigations = $selectedInvestigations
            ->sortBy(fn (Investigation $investigation): int => $positions[$investigation->getKey()] ?? PHP_INT_MAX)->values();

        return [
            'referenceEnabled' => $referenceEnabled,
            'selectedReference' => $referenceEnabled ? $this->selectedOption(Reference::query(), getValue('reference_id', $record), ['id', 'reference_id', 'name', 'contact_no']) : null,
            'selectedPatient' => $this->selectedOption(Patient::query(), getValue('patient_id', $record), ['id', 'name', 'patient_id', 'mobile_no']),
            'selectedDoctor' => $this->selectedOption(Doctor::query()->where('branch_id', $branchId), getValue('doctor_id', $record), ['id', 'doctor_id', 'name', 'consultation_fee', 'follow_up_fee']),
            'paymentTypes' => PaymentType::query()->orderBy('ordering')->orderBy('id')->get(['id', 'name']),
            'selectedInvestigations' => $selectedInvestigations,
            'investigationRates' => $record->investigations->pluck('pivot.rate', 'id')->all(),
            'investigationEyes' => $record->investigations->pluck('pivot.eye_type', 'id')->all(),
            'eyeTypes' => eyeTypes(),
            'entryDate' => $record->entry_date?->toDateString() ?? $today,
            'dueDate' => $record->due_date?->toDateString() ?? $today,
            'deliveryDate' => $record->delivery_date?->toDateString() ?? $today,
        ];
    }
}
