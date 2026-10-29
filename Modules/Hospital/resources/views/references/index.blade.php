@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Manage references registered at the selected branch.',
        'create' => ['model' => \Modules\Hospital\Models\Reference::class, 'url' => route($routePrefix.'.create'), 'label' => 'New reference'],
    ])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-address-card text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">References</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter references">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-xl-7">
                    <label class="form-label small" for="search">Search references</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="Reference ID (any part), name or contact..." {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-xl-5">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No references found', 'description' => 'Try another reference ID, name, or contact number, or clear the filters.', 'icon' => 'address-card'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">References registered at the selected branch</caption>
                    <thead>
                        <tr>
                            <th scope="col">Reference ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Designation</th>
                            <th scope="col">Contact no.</th>
                            <th scope="col">Address</th>
                            <th class="text-end" scope="col">Consultation %</th>
                            <th class="text-end" scope="col">Investigation %</th>
                            <th class="text-end" scope="col">Operation %</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $reference)
                            <tr>
                                <td class="text-nowrap">{{ $reference->reference_id }}</td>
                                <th class="fw-semibold text-break" scope="row">{{ $reference->name }}</th>
                                <td class="small text-break">{{ $reference->designation ?: '-' }}</td>
                                <td class="text-nowrap">{{ $reference->contact_no }}</td>
                                <td class="small text-break">{{ $reference->address ?: '-' }}</td>
                                <td class="text-end text-nowrap">{{ $reference->consultation_percent }}%</td>
                                <td class="text-end text-nowrap">{{ $reference->investigation_percent }}%</td>
                                <td class="text-end text-nowrap">{{ $reference->operation_percent }}%</td>
                                <td>@include('core::partials.row-actions', ['model' => $reference, 'routePrefix' => $routePrefix])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
