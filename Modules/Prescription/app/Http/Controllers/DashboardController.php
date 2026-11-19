<?php

namespace Modules\Prescription\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Hospital\Models\Doctor;
use Modules\Prescription\Models\GlassPrescription;
use Modules\Prescription\Models\Prescription;

class DashboardController extends BaseController
{
    public function index(Request $request): View
    {
        $actor = $request->user('web');
        $permissions = $request->attributes->get('navigation_permissions', []);
        $gate = Gate::forUser($actor);
        $today = now(config('app.timezone'));
        $doctorId = $actor instanceof Doctor ? $actor->getKey() : null;

        $canViewGeneral = isset($permissions['prescriptions.view']) && $gate->allows('viewAny', Prescription::class);
        $canViewGlass = isset($permissions['glass-prescriptions.view']) && $gate->allows('viewAny', GlassPrescription::class);
        $generalToday = null;
        $glassToday = null;
        $recentGeneral = null;
        $recentGlass = null;

        if ($canViewGeneral) {
            $generalPrescriptions = Prescription::query();
            if ($doctorId !== null) {
                $generalPrescriptions->where('doctor_id', $doctorId);
            }

            $generalToday = (clone $generalPrescriptions)
                ->where('prescription_date', $today->toDateString())
                ->count();
            $recentGeneral = $this->recentPrescriptions($generalPrescriptions);
        }

        if ($canViewGlass) {
            $glassPrescriptions = GlassPrescription::query();
            if ($doctorId !== null) {
                $glassPrescriptions->where('doctor_id', $doctorId);
            }

            $glassToday = (clone $glassPrescriptions)
                ->where('prescription_date', $today->toDateString())
                ->count();
            $recentGlass = $this->recentPrescriptions($glassPrescriptions);
        }

        $data = [
            'pageTitle' => 'Prescription dashboard',
            'branchName' => (string) $request->attributes->get('branch_name'),
            'todayLabel' => $today->format('d M Y'),
            'todayIso' => $today->toDateString(),
            'canViewGeneral' => $canViewGeneral,
            'canViewGlass' => $canViewGlass,
            'canCreateGeneral' => isset($permissions['prescriptions.create']) && $gate->allows('create', Prescription::class),
            'canCreateGlass' => isset($permissions['glass-prescriptions.create']) && $gate->allows('create', GlassPrescription::class),
            'generalToday' => $generalToday,
            'glassToday' => $glassToday,
            'totalToday' => ($generalToday ?? 0) + ($glassToday ?? 0),
            'recentGeneral' => $recentGeneral,
            'recentGlass' => $recentGlass,
        ];

        return view('prescription::dashboard.index', $data);
    }

    private function recentPrescriptions(Builder $query): Collection
    {
        return $query->select(['id', 'rx_number', 'patient_id', 'doctor_id', 'prescription_date'])
            ->with(['patient:id,patient_id,name', 'doctor:id,name'])
            ->orderByDesc('prescription_date')
            ->orderByDesc('id')
            ->limit(6)
            ->get();
    }
}
