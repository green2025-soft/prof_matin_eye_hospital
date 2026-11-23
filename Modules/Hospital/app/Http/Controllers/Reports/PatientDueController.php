<?php

namespace Modules\Hospital\Http\Controllers\Reports;

use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Hospital\Http\Requests\PatientDueReportRequest;
use Modules\Hospital\Models\Patient;
use Modules\Hospital\Services\ClinicalInvoiceService;

class PatientDueController extends BaseController
{
    public function index(PatientDueReportRequest $request, ClinicalInvoiceService $invoices): View
    {
        $filters = $request->validated();
        $from = $filters['from_date'] ?? now(config('app.timezone'))->toDateString();
        $to = $filters['to_date'] ?? $from;
        $patientId = $filters['patient_id'] ?? null;
        $branchId = (int) $request->attributes->get('branch_id');
        $records = $invoices->patientDueReport($branchId,$from,$to,$patientId,);

        $data = [
            'pageTitle' => 'Patient Due List',
            'fromDate' => $from,
            'toDate' => $to,
            'filters' => [
                'from_date' => $from,
                'to_date' => $to,
                'patient_id' => $patientId ?? '',
            ],
            'selectedPatient' => $this->selectedOption(
                Patient::query(),
                $patientId,
                ['id', 'patient_id', 'name', 'mobile_no'],
            ),
            'records' => $records,
        ];

        return view('hospital::reports.patient-due', $data);
    }
}
