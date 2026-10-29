<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Http\Requests\SelectorRequest;
use Modules\Core\Models\Account;
use Modules\Hospital\Http\Requests\DoctorRequest;
use Modules\Hospital\Models\Department;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Services\DoctorService;

class DoctorController extends BaseController
{
    protected string $model = Doctor::class;

    protected string $title = 'Doctor';

    protected string $service = DoctorService::class;

    protected string $views = 'hospital::doctors';

    protected string $parameter = 'doctor';

    protected array $selectorFields = ['id', 'doctor_id', 'name', 'consultation_fee', 'follow_up_fee'];

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function options(SelectorRequest $request): JsonResponse
    {
        $response = parent::options($request);
        $data = $response->getData(true);
        $data['results'] = array_map(
            fn (array $doctor): array => $doctor + ['text' => ($doctor['doctor_id'] ?: $doctor['id']).' — '.$doctor['name']],
            $data['results'],
        );

        return $response->setData($data);
    }

    public function store(DoctorRequest $request, DoctorService $doctors): RedirectResponse
    {
        $actor = $request->user('web');
        $doctor = $doctors->save($actor, $this->record($request), $request->payload(), (int) $request->attributes->get('branch_id'));
        if ($actor->is($doctor)) {
            Auth::guard('web')->setUser($doctor);
            $request->session()->regenerate();
        }

        return $this->redirectAfterWrite($doctor)->with('status', 'Doctor'.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    public function show(Request $request): View
    {
        $doctor = $this->record($request);
        Gate::forUser($request->user('web'))->authorize('view', $doctor);
        $doctor->loadMissing('department:id,name');
        $doctor->setRawAttributes($doctor->only($doctor->queryContract()['exposed']), true);

        return view('hospital::doctors.show', [
            'pageTitle' => "$this->title details",
            'doctor' => $doctor,
            'genderLabel' => genders()[$doctor->gender] ?? $doctor->gender,
            'bloodGroupLabel' => bloodGroups()[$doctor->blood_group] ?? '-',
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function permissions(DoctorRequest $request, DoctorService $doctors): View
    {
        return view('hospital::doctors.permissions', $doctors->permissionPageData($request->user('web')));
    }

    public function savePermissions(DoctorRequest $request, DoctorService $doctors): RedirectResponse
    {
        $doctors->savePermissions($request->user('web'), $request->validated('permissions'));

        return redirect()->route('hospital.doctors.permissions')->with('status', 'Doctor permissions updated.');
    }

    protected function formData(Account $actor, Model $record): array
    {
        return [
            'selectedDepartment'    => $this->selectedOption(Department::query(), getValue('department_id', $record)),
            'genders'               => genders(),
            'bloodGroups'           => bloodGroups(),
            'passwordMinLength'     => config('auth.password_min_length', 5),
        ];
    }
}
