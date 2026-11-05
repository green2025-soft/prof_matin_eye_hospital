@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])

    <div class="row g-4 align-items-start">
        @if ($form !== null)
            <aside class="{{ $records !== null ? 'col-lg-4' : 'col-12' }}">
                @include('pharmacy::suppliers.form', $form)
            </aside>
        @endif
        @if ($records !== null)
            <div class="{{ $form !== null ? 'col-lg-8' : 'col-12' }}">
                <section class="app-card overflow-hidden">
                    <form class="p-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter suppliers">
                        <input name="type" type="hidden" value="{{ $filters['type'] ?? 'medicine' }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-sm">
                                <label class="form-label small" for="search">Search</label>
                                <input class="form-control" id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" maxlength="80">
                            </div>
                            <div class="col-sm-auto">
                                @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table app-table align-middle mb-0">
                            <caption class="visually-hidden">Suppliers</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Contact no.</th>
                                    <th scope="col">Status</th>
                                    <th class="text-end" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($records as $record)
                                    <tr>
                                        <th scope="row">{{ $record->name }}</th>
                                        <td>{{ $record->contact_no ?? '—' }}</td>
                                        <td>{{ $record->active ? 'Active' : 'Inactive' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-2">
                                                @include('core::partials.row-actions', ['model' => $record, 'routePrefix' => $routePrefix])
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td class="text-center text-body-secondary py-4" colspan="4">No records found.</td></tr>
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
