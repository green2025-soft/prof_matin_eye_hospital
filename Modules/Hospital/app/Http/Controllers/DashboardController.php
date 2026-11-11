<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Hospital\Models\Appointment;
use Modules\Hospital\Models\Consultation;
use Modules\Hospital\Models\Patient;
use Modules\Hospital\Models\PatientInvestigation;
use Modules\Hospital\Models\PatientOperation;

class DashboardController extends BaseController
{
    public function index(Request $request): View
    {
        $permissions = $request->attributes->get('navigation_permissions', []);
        $branchId = (int) $request->attributes->get('branch_id');
        $today = now(config('app.timezone'))->startOfDay();
        $todayIso = $today->toDateString();

        $canViewPatients = isset($permissions['patients.view']);
        $canViewAppointments = isset($permissions['appointments.view']);
        $canViewConsultations = isset($permissions['consultations.view']);
        $canViewInvestigations = isset($permissions['patient-investigations.view']);
        $canViewOperations = isset($permissions['patient-operations.view']);

        $patientCount = null;
        $appointmentCount = null;
        $consultationCount = null;
        $investigationCount = null;
        $operationCount = null;
        $consultationPayable = null;
        $investigationPayable = null;
        $operationPayable = null;
        $recentAppointments = collect();
        $recentPatients = collect();

        if ($canViewPatients) {
            $patientCount = Patient::where('created_at', '>=', $today)
                ->where('created_at', '<', $today->copy()->addDay())
                ->count();
            $recentPatients = Patient::orderByDesc('id')
                ->limit(5)
                ->get(['id', 'patient_id', 'name', 'mobile_no', 'created_at']);
        }

        if ($canViewAppointments) {
            $appointmentCount = Appointment::where('appointment_date', $todayIso)->count();
            $recentAppointments = Appointment::with([
                'patient:id,patient_id,name',
                'doctor' => fn ($query) => $query->where('branch_id', $branchId)
                    ->select(['id', 'name']),
            ])
                ->select(['id', 'doctor_id', 'patient_id', 'serial_no', 'appointment_date', 'appointment_time'])
                ->withExists('consultation')
                ->orderByDesc('id')
                ->limit(5)
                ->get();
        }

        if ($canViewConsultations) {
            $consultations = Consultation::where('entry_date', $todayIso);
            $consultationCount = (clone $consultations)->count();
            $consultationPayable = $consultations->sum('payable_amount') ?: '0.00';
        }

        if ($canViewInvestigations) {
            $investigations = PatientInvestigation::where('entry_date', $todayIso);
            $investigationCount = (clone $investigations)->count();
            $investigationPayable = $investigations->sum('payable_amount') ?: '0.00';
        }

        if ($canViewOperations) {
            $operations = PatientOperation::where('entry_date', $todayIso);
            $operationCount = (clone $operations)->count();
            $operationPayable = $operations->sum('payable_amount') ?: '0.00';
        }

        $data = [
            'pageTitle' => 'Hospital Dashboard',
            'branchName' => $request->attributes->get('branch_name'),
            'todayIso' => $todayIso,
            'todayLabel' => $today->format('d M Y'),
            'canViewPatients' => $canViewPatients,
            'canViewAppointments' => $canViewAppointments,
            'canViewConsultations' => $canViewConsultations,
            'canViewInvestigations' => $canViewInvestigations,
            'canViewOperations' => $canViewOperations,
            'canCreatePatient' => isset($permissions['patients.create']),
            'canCreateAppointment' => isset($permissions['appointments.create']),
            'canCreateConsultation' => isset($permissions['consultations.create']),
            'canCreateInvestigation' => isset($permissions['patient-investigations.create']),
            'canCreateOperation' => isset($permissions['patient-operations.create']),
            'patientCount' => $patientCount,
            'appointmentCount' => $appointmentCount,
            'consultationCount' => $consultationCount,
            'investigationCount' => $investigationCount,
            'operationCount' => $operationCount,
            'consultationPayable' => $consultationPayable,
            'investigationPayable' => $investigationPayable,
            'operationPayable' => $operationPayable,
            'recentAppointments' => $recentAppointments,
            'recentPatients' => $recentPatients,
        ];

        return view('hospital::dashboard.index', $data);
    }
}
