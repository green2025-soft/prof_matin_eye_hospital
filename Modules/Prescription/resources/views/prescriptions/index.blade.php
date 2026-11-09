@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Prescriptions at the selected branch.',
        'create' => ['model' => \Modules\Prescription\Models\Prescription::class, 'url' => route($routePrefix.'.create'), 'label' => 'New prescription'],
    ])

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Search prescriptions">
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-xl-4">
                    <label class="form-label small" for="prescription_search">Search</label>
                    <input class="{{ inputClass('search') }}" id="prescription_search" name="search" type="search" maxlength="80" value="{{ getValue('search', null, $filters['search'] ?? '') }}" placeholder="Rx number, ID or date" {{ inputAttrs('search', 'prescription_search') }}>
                    @error('search')<div class="invalid-feedback d-block" id="prescription_search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 col-xl-5">
                    <label class="form-label small" for="filter_patient_id">Patient history</label>
                    <select class="{{ inputClass('filter_patient_id', 'form-select') }}" id="filter_patient_id" name="filter_patient_id" data-ajax-select data-url="{{ route('hospital.patients.options') }}" data-value-field="id" data-label-field="text" data-placeholder="All patients" {{ inputAttrs('filter_patient_id') }}>
                        <option value=""></option>
                        @if ($selectedPatient !== null)
                            <option value="{{ $selectedPatient->id }}" selected>{{ $selectedPatient->patient_id }} / {{ $selectedPatient->name }} / {{ $selectedPatient->mobile_no }}</option>
                        @endif
                    </select>
                    @error('filter_patient_id')<div class="invalid-feedback d-block" id="filter_patient_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-xl-3">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Saved prescriptions with patient and doctor details</caption>
                <thead>
                    <tr>
                        <th scope="col">Prescription</th>
                        <th scope="col">Date</th>
                        <th scope="col">Patient</th>
                        <th scope="col">Doctor</th>
                        <th scope="col">Follow-up</th>
                        <th class="text-end" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <th scope="row">{{ $record->rx_number ?? '#'.$record->id }}</th>
                            <td class="text-nowrap">{{ $record->prescription_date?->format('d M Y') }}</td>
                            <td><a class="d-block fw-semibold" href="{{ route($routePrefix.'.index', ['filter_patient_id' => $record->patient_id]) }}" title="View patient prescription history">{{ $record->patient?->name }}</a><small class="text-body-secondary">{{ $record->patient?->patient_id }}</small></td>
                            <td>{{ $record->doctor?->name }}</td>
                            <td>{{ $record->follow_up_date?->format('d M Y') ?? '--' }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    @can('view', $record)
                                        <a class="btn btn-outline-primary btn-sm app-action-button" href="{{ route($routePrefix.'.show', $record) }}" aria-label="View prescription #{{ $record->id }}" title="View / Print"><i class="fa-solid fa-print" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('update', $record)
                                        <a class="btn btn-outline-secondary btn-sm app-action-button" href="{{ route($routePrefix.'.edit', $record) }}" aria-label="Edit prescription #{{ $record->id }}" title="Edit"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('delete', $record)
                                        <form action="{{ route($routePrefix.'.destroy', $record) }}" method="POST" data-confirm="Delete this prescription? This cannot be undone.">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete prescription #{{ $record->id }}" title="Delete"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-body-secondary py-4" colspan="6">No prescriptions match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
