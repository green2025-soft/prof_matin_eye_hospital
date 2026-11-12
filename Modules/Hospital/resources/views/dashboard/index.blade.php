@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h4 fw-semibold mb-1">{{ $pageTitle }}</h1>
            <p class="small text-body-secondary mb-0">
                @if (filled($branchName)){{ $branchName }} &middot; @endif
                <time datetime="{{ $todayIso }}">{{ $todayLabel }}</time>
            </p>
        </div>
        <span class="badge text-bg-success px-3 py-2"><i class="fa-solid fa-hospital me-2" aria-hidden="true"></i>Hospital</span>
    </header>

    @if ($canCreatePatient || $canCreateAppointment || $canCreateConsultation || $canCreateInvestigation || $canCreateOperation)
        <section class="mb-4" aria-labelledby="hospital-quick-entry-heading">
            <h2 class="h6 fw-semibold mb-3" id="hospital-quick-entry-heading">Quick entry</h2>
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 justify-content-center g-3">
                @if ($canCreatePatient)
                    <div class="col">
                        <a class="btn btn-success bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                            href="{{ route('hospital.patients.create') }}">
                            <i class="fa-solid fa-user-plus fs-3" aria-hidden="true"></i>
                            <span class="small fw-semibold">New patient</span>
                        </a>
                    </div>
                @endif
                @if ($canCreateAppointment)
                    <div class="col">
                        <a class="btn btn-primary bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                            href="{{ route('hospital.appointments.create') }}">
                            <i class="fa-solid fa-calendar-plus fs-3" aria-hidden="true"></i>
                            <span class="small fw-semibold">Appointment</span>
                        </a>
                    </div>
                @endif
                @if ($canCreateConsultation)
                    <div class="col">
                        <a class="btn btn-info bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                            href="{{ route('hospital.consultations.create') }}">
                            <i class="fa-solid fa-notes-medical fs-3" aria-hidden="true"></i>
                            <span class="small fw-semibold">Consultation</span>
                        </a>
                    </div>
                @endif
                @if ($canCreateInvestigation)
                    <div class="col">
                        <a class="btn btn-warning bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                            href="{{ route('hospital.patient-investigations.create') }}">
                            <i class="fa-solid fa-flask fs-3" aria-hidden="true"></i>
                            <span class="small fw-semibold">Investigation</span>
                        </a>
                    </div>
                @endif
                @if ($canCreateOperation)
                    <div class="col">
                        <a class="btn btn-danger bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                            href="{{ route('hospital.patient-operations.create') }}">
                            <i class="fa-solid fa-stethoscope fs-3" aria-hidden="true"></i>
                            <span class="small fw-semibold">Operation</span>
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if ($canViewPatients || $canViewAppointments || $canViewConsultations || $canViewInvestigations || $canViewOperations)
        <section class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xxl-5 g-3 mb-3" aria-label="Today's hospital activity, amounts in BDT">
            @if ($canViewPatients)
                <div class="col flex-grow-1">
                    <article class="bg-success-subtle border border-success-subtle rounded-3 d-flex align-items-center gap-3 h-100 p-3">
                        <span class="d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white text-success p-3">
                            <i class="fa-solid fa-hospital-user fs-4" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="small fw-medium text-success-emphasis mb-1">Today's patients</h2>
                            <p class="h3 fw-bold text-body-emphasis mb-1">{{ number_format($patientCount) }}</p>
                            <p class="small text-body-secondary mb-0">New Registration</p>
                        </div>
                    </article>
                </div>
            @endif
            @if ($canViewAppointments)
                <div class="col flex-grow-1">
                    <article class="bg-primary-subtle border border-primary-subtle rounded-3 d-flex align-items-center gap-3 h-100 p-3">
                        <span class="d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white text-primary p-3">
                            <i class="fa-solid fa-calendar-check fs-4" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="small fw-medium text-primary-emphasis mb-1">Appointment</h2>
                            <p class="h3 fw-bold text-body-emphasis mb-1">{{ number_format($appointmentCount) }}</p>
                            <p class="small text-body-secondary mb-0">Scheduled for today</p>
                        </div>
                    </article>
                </div>
            @endif
            @if ($canViewConsultations)
                <div class="col flex-grow-1">
                    <article class="bg-info-subtle border border-info-subtle rounded-3 d-flex align-items-center gap-3 h-100 p-3">
                        <span class="d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white text-info-emphasis p-3">
                            <i class="fa-solid fa-notes-medical fs-4" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="small fw-medium text-info-emphasis mb-1">Consultation</h2>
                            <p class="h3 fw-bold text-body-emphasis mb-1">{{ number_format($consultationCount) }}</p>
                            <p class="small text-body-secondary text-break mb-0">&#2547; {{ $consultationPayable }} billed today</p>
                        </div>
                    </article>
                </div>
            @endif
            @if ($canViewInvestigations)
                <div class="col flex-grow-1">
                    <article class="bg-warning-subtle border border-warning-subtle rounded-3 d-flex align-items-center gap-3 h-100 p-3">
                        <span class="d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white text-warning-emphasis p-3">
                            <i class="fa-solid fa-flask fs-4" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="small fw-medium text-warning-emphasis mb-1">Investigation</h2>
                            <p class="h3 fw-bold text-body-emphasis mb-1">{{ number_format($investigationCount) }}</p>
                            <p class="small text-body-secondary text-break mb-0">&#2547; {{ $investigationPayable }} billed today</p>
                        </div>
                    </article>
                </div>
            @endif
            @if ($canViewOperations)
                <div class="col flex-grow-1">
                    <article class="bg-danger-subtle border border-danger-subtle rounded-3 d-flex align-items-center gap-3 h-100 p-3">
                        <span class="d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white text-danger p-3">
                            <i class="fa-solid fa-stethoscope fs-4" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="small fw-medium text-danger-emphasis mb-1">Operation</h2>
                            <p class="h3 fw-bold text-body-emphasis mb-1">{{ number_format($operationCount) }}</p>
                            <p class="small text-body-secondary text-break mb-0">&#2547; {{ $operationPayable }} billed today</p>
                        </div>
                    </article>
                </div>
            @endif
        </section>
    @endif

    <div class="row row-cols-1 row-cols-xl-2 g-3">
        @if ($canViewAppointments)
            <div class="col flex-grow-1">
                <section class="app-card h-100 overflow-hidden" aria-labelledby="hospital-recent-appointments-heading">
                    <header class="d-flex align-items-center justify-content-between gap-3 p-3 border-bottom bg-primary-subtle text-primary-emphasis">
                        <div>
                            <h2 class="h6 fw-semibold mb-1" id="hospital-recent-appointments-heading">Recent Appointment</h2>
                            <p class="small text-body-secondary mb-0">Latest 5 Appointments</p>
                        </div>
                        <a class="btn btn-primary btn-sm" href="{{ route('hospital.appointments.index') }}">View all</a>
                    </header>
                    <div class="table-responsive overflow-y-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <caption class="visually-hidden">Recent appointments, patients and consultation status</caption>
                            <thead class="table-light">
                                <tr><th class="px-3 py-2" scope="col">Patient / Doctor</th><th class="px-3 py-2" scope="col">Date</th><th class="text-end px-3 py-2" scope="col">Status</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($recentAppointments as $appointment)
                                    <tr>
                                        <td class="text-break px-3 py-2">
                                            <a class="fw-semibold text-decoration-none" href="{{ route('hospital.appointments.index', ['search' => $appointment->id]) }}">{{ $appointment->patient?->name ?? '-' }}</a>
                                            <div class="small text-body-secondary">{{ $appointment->doctor?->name ?? '-' }}</div>
                                            <div class="small text-body-secondary">Serial: {{ $appointment->serial_no ?? '-' }}</div>
                                        </td>
                                        <td class="small px-3 py-2">
                                            <time datetime="{{ $appointment->appointment_date->toDateString() }}">{{ $appointment->appointment_date->format('d M Y') }}</time>
                                            <div class="text-body-secondary">{{ $appointment->appointment_time === null ? '-' : \Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
                                        </td>
                                        <td class="text-end px-3 py-2">
                                            <span class="badge {{ $appointment->consultation_exists ? 'text-bg-success' : 'text-bg-warning' }}">{{ $appointment->consultation_exists ? 'Consulted' : 'Pending' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td class="text-center text-body-secondary py-5" colspan="3">No appointment yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        @endif
        @if ($canViewPatients)
            <div class="col flex-grow-1">
                <section class="app-card h-100 overflow-hidden" aria-labelledby="hospital-recent-patients-heading">
                    <header class="d-flex align-items-center justify-content-between gap-3 p-3 border-bottom bg-success-subtle text-success-emphasis">
                        <div>
                            <h2 class="h6 fw-semibold mb-1" id="hospital-recent-patients-heading">Recent Patient</h2>
                            <p class="small text-body-secondary mb-0">Latest 5 Registration</p>
                        </div>
                        <a class="btn btn-success btn-sm" href="{{ route('hospital.patients.index') }}">View all</a>
                    </header>
                    <div class="table-responsive overflow-y-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <caption class="visually-hidden">Recently registered patients and contact details</caption>
                            <thead class="table-light">
                                <tr><th class="px-3 py-2" scope="col">Patient</th><th class="px-3 py-2" scope="col">Mobile</th><th class="text-end px-3 py-2" scope="col">Registered</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($recentPatients as $patient)
                                    <tr>
                                        <td class="text-break px-3 py-2">
                                            <a class="fw-semibold text-decoration-none" href="{{ route('hospital.patients.index', ['search' => $patient->patient_id]) }}">{{ $patient->name }}</a>
                                            <div class="small text-body-secondary">{{ $patient->patient_id ?? '-' }}</div>
                                        </td>
                                        <td class="small text-break px-3 py-2">{{ $patient->mobile_no ?? '-' }}</td>
                                        <td class="small text-end px-3 py-2"><time datetime="{{ $patient->created_at?->toDateString() }}">{{ $patient->created_at?->format('d M Y') ?? '-' }}</time></td>
                                    </tr>
                                @empty
                                    <tr><td class="text-center text-body-secondary py-5" colspan="3">No patients registered yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        @endif
    </div>

    @if (! $canViewPatients && ! $canViewAppointments && ! $canViewConsultations && ! $canViewInvestigations && ! $canViewOperations)
        <section class="app-card p-4 text-center">
            <i class="fa-solid fa-lock text-body-secondary fs-4 mb-3" aria-hidden="true"></i>
            <h2 class="h6 fw-semibold">No dashboard figures available</h2>
            <p class="small text-body-secondary mb-0">Your permissions determine which patient and clinical figures appear here.</p>
        </section>
    @endif
@endsection
