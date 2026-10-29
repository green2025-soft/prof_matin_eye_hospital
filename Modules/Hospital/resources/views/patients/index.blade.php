@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Manage patients registered at the selected branch.',
        'create' => ['model' => \Modules\Hospital\Models\Patient::class, 'url' => route($routePrefix.'.create'), 'label' => 'New patient'],
    ])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-hospital-user text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Patients</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter patients">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-xl-7">
                    <label class="form-label small" for="search">Search patients</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="Patient ID (any part), name or mobile..." {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-xl-5">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No patients found', 'description' => 'Try another patient ID, name, or mobile number, or clear the filters.', 'icon' => 'hospital-user'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Patients registered at the selected branch</caption>
                    <thead>
                        <tr>
                            <th scope="col">Patient ID</th>
                            <th scope="col">Patient name</th>
                            <th scope="col">Age</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Blood group</th>
                            <th scope="col">Mobile no.</th>
                            <th scope="col">Address</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $patient)
                            <tr>
                                <td class="text-nowrap">{{ $patient->patient_id }}</td>
                                <th class="fw-semibold text-break" scope="row">
                                    {{ $patient->name }}
                                    @if ($patient->father_husband_name)
                                        <div class="small fw-normal text-body-secondary">{{ $patient->father_husband_name }}</div>
                                    @endif
                                </th>
                                <td class="text-nowrap">{{ $patient->age }}</td>
                                <td>{{ $genders[$patient->gender] ?? '-' }}</td>
                                <td>{{ $bloodGroups[$patient->blood_group] ?? '-' }}</td>
                                <td class="text-nowrap">{{ $patient->mobile_no }}</td>
                                <td class="small text-break">
                                    @if ($patient->address)
                                        <div>{{ $patient->address }}</div>
                                    @endif
                                    <div class="text-body-secondary">{{ $patient->thana?->name }}@if ($patient->thana && $patient->district), @endif{{ $patient->district?->name }}@if ($patient->district && $patient->division), @endif{{ $patient->division?->name }}</div>
                                </td>
                                <td>@include('core::partials.row-actions', ['model' => $patient, 'routePrefix' => $routePrefix])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
