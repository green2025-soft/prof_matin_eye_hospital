@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Manage investigations, categories, and rates.',
        'create' => ['model' => \Modules\Hospital\Models\Investigation::class, 'url' => route($routePrefix.'.create'), 'label' => 'New investigation'],
    ])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-flask text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Investigations</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter investigations">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-xl-7">
                    <label class="form-label small" for="search">Search investigations</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="Investigation name starts with..." {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-xl-5">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No investigations found', 'description' => 'Try another investigation name or clear the filters.', 'icon' => 'flask'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Investigations, categories, rates, and display order</caption>
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th class="text-end" scope="col">Single rate</th>
                            <th class="text-end" scope="col">Both rate</th>
                            <th scope="col">Room no.</th>
                            <th scope="col">Ordering</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $investigation)
                            <tr>
                                <th class="fw-semibold text-break" scope="row">{{ $investigation->name }}</th>
                                <td class="small text-break">{{ $investigation->category?->name }}</td>
                                <td class="text-end text-nowrap">{{ $investigation->single_rate }}</td>
                                <td class="text-end text-nowrap">{{ $investigation->both_rate }}</td>
                                <td class="small">{{ $investigation->room_no ?? '-' }}</td>
                                <td class="small text-body-secondary">{{ $investigation->ordering }}</td>
                                <td>@include('core::partials.row-actions', ['model' => $investigation, 'routePrefix' => $routePrefix])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
