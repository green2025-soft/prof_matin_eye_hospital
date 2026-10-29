<nav class="d-flex flex-wrap gap-2 mb-3" aria-label="Patient entries">
    @can('create', \Modules\Hospital\Models\Consultation::class)
        <a class="btn btn-sm {{ request()->routeIs('hospital.consultations.*') ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('hospital.consultations.create') }}" @if (request()->routeIs('hospital.consultations.*')) aria-current="page" @endif>Consultation</a>
    @endcan
    @can('create', \Modules\Hospital\Models\PatientInvestigation::class)
        <a class="btn btn-sm {{ request()->routeIs('hospital.patient-investigations.*') ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('hospital.patient-investigations.create') }}" @if (request()->routeIs('hospital.patient-investigations.*')) aria-current="page" @endif>Investigation</a>
    @endcan
    @can('create', \Modules\Hospital\Models\PatientOperation::class)
        <a class="btn btn-sm {{ request()->routeIs('hospital.patient-operations.*') ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('hospital.patient-operations.create') }}" @if (request()->routeIs('hospital.patient-operations.*')) aria-current="page" @endif>Operation</a>
    @endcan
</nav>
