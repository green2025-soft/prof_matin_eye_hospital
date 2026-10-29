@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Manage doctors, departments, and consultation fees at the selected branch.',
        'create' => ['model' => \Modules\Hospital\Models\Doctor::class, 'url' => route($routePrefix.'.create'), 'label' => 'New doctor'],
    ])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-user-doctor text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Doctors</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter doctors">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-xl-7">
                    <label class="form-label small" for="search">Search doctors</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="ID (any part), name, mobile or email starts with..." {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-xl-5">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No doctors found', 'description' => 'Try another doctor name or clear the filters.', 'icon' => 'user-doctor'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Doctors, contact details, departments, and consultation fees</caption>
                    <thead>
                        <tr>
                            <th scope="col">Doctor</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Designation</th>
                            <th scope="col">Department</th>
                            <th class="text-end" scope="col">Consultation fee</th>
                            <th class="text-end" scope="col">Follow-up fee</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $doctor)
                            <tr>
                                <th scope="row">
                                    <div class="app-table-person d-flex align-items-center gap-3">
                                        @if ($doctor->picture_url)
                                            <img class="app-table-avatar rounded-circle object-fit-cover" src="{{ $doctor->picture_url }}" alt="" width="40" height="40">
                                        @else
                                            <span class="app-table-avatar" aria-hidden="true"><i class="fa-solid fa-user-doctor"></i></span>
                                        @endif
                                        <div class="text-break">
                                            <div class="fw-semibold">
                                                @can('view', $doctor)
                                                    <a href="{{ route($routePrefix.'.show', $doctor) }}">{{ $doctor->name }}</a>
                                                @else
                                                    {{ $doctor->name }}
                                                @endcan
                                                @if ($doctor->doctor_id !== null)
                                                    <span class="small fw-normal text-body-secondary ms-2">ID: {{ $doctor->doctor_id }}</span>
                                                @endif
                                            </div>
                                            @if ($doctor->bn_name)
                                                <div class="small fw-normal text-body-secondary" lang="bn">{{ $doctor->bn_name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </th>
                                <td class="small text-break">
                                    <div>{{ $doctor->mobile_no }}</div>
                                    <div class="text-body-secondary">{{ $doctor->email }}</div>
                                </td>
                                <td class="small text-break">{{ $doctor->designation ?? '-' }}</td>
                                <td class="small text-break">{{ $doctor->department?->name }}</td>
                                <td class="text-end text-nowrap">{{ $doctor->consultation_fee }}</td>
                                <td class="text-end text-nowrap">{{ $doctor->follow_up_fee }}</td>
                                <td>@include('core::partials.row-actions', ['model' => $doctor, 'routePrefix' => $routePrefix])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
