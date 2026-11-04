@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])

    <div class="row g-4 align-items-start">
        @if ($form !== null)
            <aside class="{{ $records !== null ? 'col-xl-4 col-lg-5' : 'col-12' }}">
                @include('prescription::options.form', $form)
            </aside>
        @endif

        @if ($records !== null)
            <div class="{{ $form !== null ? 'col-xl-8 col-lg-7' : 'col-12' }}">
                <section class="app-card overflow-hidden">
                    <form class="p-3 p-md-4 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET"
                        aria-label="Filter prescription options">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-lg-4">
                                <label class="form-label small" for="filter-section">Section</label>
                                <select class="{{ inputClass('section', 'form-select') }}" id="filter-section"
                                    name="section" {{ inputAttrs('section', 'filter-section') }}>
                                    <option value="">All sections</option>
                                    @foreach ($sections as $value => $label)
                                        <option value="{{ $value }}"
                                            @selected(($filters['section'] ?? '') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('section')
                                    <div class="invalid-feedback d-block" id="filter-section-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-lg-4">
                                <label class="form-label small" for="search">Search</label>
                                <input class="{{ inputClass('search') }}" id="search" name="search" type="search"
                                    value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80"
                                    {{ inputAttrs('search') }}>
                                @error('search')
                                    <div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-lg-4">
                                @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table app-table align-middle mb-0">
                            <caption class="visually-hidden">Prescription options by section and display order</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Ordering</th>
                                    <th class="text-end" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($records as $option)
                                    <tr>
                                        <th class="fw-semibold text-break" scope="row">{{ $option->name }}</th>
                                        <td>{{ $sections[$option->section] }}</td>
                                        <td class="small text-body-secondary">{{ $option->ordering }}</td>
                                        <td>
                                            @include('core::partials.row-actions', [
                                                'model' => $option,
                                                'routePrefix' => $routePrefix,
                                            ])
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-body-secondary py-4" colspan="4">
                                            No prescription options match these filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
                </section>
            </div>
        @endif
    </div>
@endsection
