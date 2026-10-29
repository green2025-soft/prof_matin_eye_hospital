<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Hospital\Http\Requests\DeliveryInvestigationRequest;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Models\Patient;
use Modules\Hospital\Models\PatientInvestigation;
use Modules\Hospital\Services\ClinicalEntryService;

class DeliveryInvestigationController extends BaseController
{
    protected string $model = PatientInvestigation::class;

    protected string $title = 'Delivery investigation';

    protected string $views = 'hospital::delivery-investigations';

    protected string $parameter = 'patient_investigation';

    public function index(DeliveryInvestigationRequest $request, ClinicalEntryService $entries): View
    {
        $filters = $request->filters();
        $branchId = (int) $request->attributes->get('branch_id');
        $model = new PatientInvestigation;
        $query = $model->listQuery()->without('paymentType')->select($model->qualifyColumns([
            'id', 'branch_id', 'patient_id', 'doctor_id', 'entry_date', 'delivery_date', 'delivery_status', 'invoice_no',
        ]));

        if (filled($filters['date_from'] ?? null)) {
            $query->where('delivery_date', '>=', $filters['date_from']);
        }
        if (filled($filters['date_to'] ?? null)) {
            $query->where('delivery_date', '<=', $filters['date_to']);
        }

        $records = $model->paginateRecords($filters, $query)->appends(Arr::only($filters, ['date_from', 'date_to']));

        return $this->listData($request, [
            'records' => $records,
            'filters' => $filters,
            'deliveryStatuses' => PatientInvestigation::DELIVERY_STATUSES,
            'filterDoctor' => $this->selectedOption(Doctor::query()->where('branch_id', $branchId), $filters['filter_doctor_id'] ?? null, ['id', 'doctor_id', 'name']),
            'filterPatient' => $this->selectedOption(Patient::query(), $filters['filter_patient_id'] ?? null, ['id', 'patient_id', 'name', 'mobile_no']),
            'canManageDelivery' => $entries->allowsInvestigationDelivery($request->user('web'), 'update', $branchId),
        ]);
    }

    public function update(DeliveryInvestigationRequest $request, ClinicalEntryService $entries): RedirectResponse
    {
        $record = $entries->setInvestigationDeliveryStatus(
            $request->user('web'), $this->record($request), $request->validated('target_status'),
            (int) $request->attributes->get('branch_id'),
        );

        return redirect()->route('hospital.delivery-investigations.index', Arr::except($request->filters(), 'page'))
            ->with('status', 'Investigation marked '.strtolower(PatientInvestigation::DELIVERY_STATUSES[$record->delivery_status]).'.');
    }
}
