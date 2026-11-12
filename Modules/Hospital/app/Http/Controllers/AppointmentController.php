<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Hospital\Http\Requests\AppointmentRequest;
use Modules\Hospital\Models\Appointment;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Models\Patient;
use Modules\Hospital\Services\AppointmentService;

class AppointmentController extends BaseController
{
    protected string $model = Appointment::class;

    protected string $title = 'Appointment';

    protected string $views = 'hospital::appointments';

    protected string $parameter = 'appointment';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        $branchId = max(0, (int) $request->attributes->get('branch_id', 0));

        $data = [
            'filterDoctor' => $this->selectedOption(
                Doctor::where('branch_id', $branchId),
                $request->validated('filter_doctor_id'),
                ['id', 'doctor_id', 'name']
            ),
            'filterPatient' => $this->selectedOption(
                Patient::query(),
                $request->validated('filter_patient_id'),
                ['id', 'patient_id', 'name', 'mobile_no']
            ),
        ];

        return $this->listData($request)->with($data);
    }

    public function store(AppointmentRequest $request, AppointmentService $appointments): RedirectResponse
    {
        $appointment = $appointments->save(
            $request->user('web'),
            $this->record($request),
            $request->payload(),
            (int) $request->attributes->get('branch_id')
        );

        return $this->redirectAfterWrite($appointment)
            ->with('status', 'Appointment'.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    protected function formData(Account $actor, Model $record): array
    {
        $branchId = max(0, (int) request()->attributes->get('branch_id', 0));

        return [
            'canCreatePatient' => Gate::forUser($actor)->allows('create', Patient::class),
            'selectedDoctor' => $this->selectedOption(
                Doctor::where('branch_id', $branchId),
                getValue('doctor_id', $record),
                ['id', 'doctor_id', 'name']
            ),
            'selectedPatient' => $this->selectedOption(Patient::query(), getValue('patient_id', $record), ['id', 'name', 'patient_id', 'mobile_no']),
            'appointmentDate' => $record->appointment_date?->toDateString() ?? now(config('app.timezone'))
                ->toDateString(),
            'appointmentTime' => $record->appointment_time === null ? null : substr($record->appointment_time, 0, 5),
        ];
    }
}
