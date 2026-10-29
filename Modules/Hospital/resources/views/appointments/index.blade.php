@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Schedule patients with a doctor at the selected branch.'])

    @if ($form !== null)
        <div class="mb-3">
            @include('hospital::appointments.form', $form)
        </div>
    @endif

    @if ($records !== null)
        <section class="app-card overflow-hidden">
            <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
                <i class="fa-solid fa-calendar-check text-body-secondary" aria-hidden="true"></i>
                <h2 class="h6 mb-0">Appointments</h2>
            </div>
            <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter appointments">
                <div class="row g-2 align-items-end">
                    <div class="col-md-6 col-xxl-3">
                        <label class="form-label small" for="search">Search appointments</label>
                        <div class="app-input-wrap app-search-field">
                            <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                            <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="Serial, invoice, ID or date starts with..." {{ inputAttrs('search') }}>
                        </div>
                        @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-xxl-3">
                        <label class="form-label small" for="filter_doctor_id">Doctor</label>
                        <select class="{{ inputClass('filter_doctor_id', 'form-select') }}" id="filter_doctor_id" name="filter_doctor_id" data-ajax-select data-url="{{ route('hospital.doctors.options') }}" data-value-field="id" data-label-field="text" data-placeholder="All doctors" {{ inputAttrs('filter_doctor_id') }}>
                            <option value=""></option>
                            @if (($filterDoctor ?? null) !== null)
                                <option value="{{ $filterDoctor->id }}" selected>{{ $filterDoctor->doctor_id ?: $filterDoctor->id }} — {{ $filterDoctor->name }}</option>
                            @endif
                        </select>
                        @error('filter_doctor_id')<div class="invalid-feedback d-block" id="filter_doctor_id-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8 col-xxl-4">
                        <label class="form-label small" for="filter_patient_id">Patient</label>
                        <select class="{{ inputClass('filter_patient_id', 'form-select') }}" id="filter_patient_id" name="filter_patient_id" data-ajax-select data-url="{{ route('hospital.patients.options') }}" data-value-field="id" data-label-field="text" data-placeholder="All patients" {{ inputAttrs('filter_patient_id') }}>
                            <option value=""></option>
                            @if (($filterPatient ?? null) !== null)
                                <option value="{{ $filterPatient->id }}" selected>{{ $filterPatient->patient_id }} — {{ $filterPatient->name }} — {{ $filterPatient->mobile_no }}</option>
                            @endif
                        </select>
                        @error('filter_patient_id')<div class="invalid-feedback d-block" id="filter_patient_id-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 col-xxl-2">
                        @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                    </div>
                </div>
            </form>

            @if ($records->isEmpty())
                @include('core::partials.empty', ['title' => 'No appointments found', 'description' => 'Create an appointment or clear the filters.', 'icon' => 'calendar-check'])
            @else
                <div class="table-responsive">
                    <table class="table app-table align-middle mb-0">
                        <caption class="visually-hidden">Appointments at the selected branch</caption>
                        <thead>
                            <tr>
                                <th scope="col">Serial</th>
                                <th scope="col">Doctor</th>
                                <th scope="col">Patient</th>
                                <th scope="col">Mobile no.</th>
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th class="text-end" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $appointment)
                                <tr>
                                    <th class="fw-semibold" scope="row">
                                        {{ $appointment->serial_no ?? '-' }}
                                        <div class="small fw-normal text-body-secondary">#{{ $appointment->id }}</div>
                                        @if ($appointment->invoice_no !== null)
                                            <div class="small fw-normal text-body-secondary">Invoice {{ $appointment->invoice_no }}</div>
                                        @endif
                                    </th>
                                    <td class="text-break">{{ $appointment->doctor?->name ?? '-' }}</td>
                                    <td class="text-break">{{ $appointment->patient?->name ?? '-' }}<div class="small text-body-secondary">{{ $appointment->patient?->patient_id }}</div></td>
                                    <td class="text-break">{{ $appointment->patient?->mobile_no ?? '-' }}</td>
                                    <td class="text-nowrap">{{ $appointment->appointment_date->toDateString() }}</td>
                                    <td class="text-nowrap">{{ $appointment->appointment_time === null ? '-' : \Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2 text-nowrap">
                                            @if ($appointment->consultation !== null)
                                                @can('update', $appointment->consultation)
                                                    <a class="btn btn-outline-primary btn-sm app-action-button" href="{{ route('hospital.consultations.edit', $appointment->consultation) }}" aria-label="Edit consultation for appointment {{ $appointment->id }}" title="Edit consultation"><i class="fa-solid fa-notes-medical" aria-hidden="true"></i></a>
                                                @elsecan('viewAny', \Modules\Hospital\Models\Consultation::class)
                                                    <a class="btn btn-outline-primary btn-sm app-action-button" href="{{ route('hospital.consultations.index', ['search' => $appointment->consultation->id]) }}" aria-label="Find consultation for appointment {{ $appointment->id }}" title="Find consultation"><i class="fa-solid fa-notes-medical" aria-hidden="true"></i></a>
                                                @endcan
                                            @else
                                                @can('create', \Modules\Hospital\Models\Consultation::class)
                                                    <a class="btn btn-outline-primary btn-sm app-action-button" href="{{ route('hospital.consultations.create', ['appointment' => $appointment->id]) }}" aria-label="Create consultation for appointment {{ $appointment->id }}" title="Create consultation"><i class="fa-solid fa-notes-medical" aria-hidden="true"></i></a>
                                                @endcan
                                            @endif
                                            @can('update', $appointment)
                                                <a class="btn btn-outline-secondary btn-sm app-action-button" href="{{ route($routePrefix.'.edit', $appointment) }}" aria-label="Edit appointment {{ $appointment->id }}" title="Edit appointment"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                            @endcan
                                            @can('delete', $appointment)
                                                <form class="d-inline-flex" action="{{ route($routePrefix.'.destroy', $appointment) }}" method="POST" data-confirm="Delete this appointment? This cannot be undone.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete appointment {{ $appointment->id }}" title="Delete appointment"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
        </section>
    @endif

    @include('hospital::patients.modal')
@endsection
