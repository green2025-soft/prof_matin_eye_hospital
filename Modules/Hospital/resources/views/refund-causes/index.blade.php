@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage refund cause names and display order.'])

    <div class="row g-4 align-items-start">
        @if ($form !== null)
            <aside class="{{ $records !== null ? 'col-xl-4 col-lg-5' : 'col-12' }}">
                @include('hospital::refund-causes.form', $form)
            </aside>
        @endif

        @if ($records !== null)
            <div class="{{ $form !== null ? 'col-xl-8 col-lg-7' : 'col-12' }}">
                <section class="app-card overflow-hidden">
                    <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
                        <i class="fa-solid fa-rotate-left text-body-secondary" aria-hidden="true"></i>
                        <h2 class="h6 mb-0">Refund causes</h2>
                    </div>
                    <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter refund causes">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-xl-7">
                                <label class="form-label small" for="search">Search refund causes</label>
                                <div class="app-input-wrap app-search-field">
                                    <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                                    <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="Refund cause name starts with..." {{ inputAttrs('search') }}>
                                </div>
                                @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-xl-5">
                                @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                            </div>
                        </div>
                    </form>

                    @if ($records->isEmpty())
                        @include('core::partials.empty', ['title' => 'No refund causes found', 'description' => 'Try another refund cause name or clear the filters.', 'icon' => 'rotate-left'])
                    @else
                        <div class="table-responsive">
                            <table class="table app-table align-middle mb-0">
                                <caption class="visually-hidden">Refund causes and their display order</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Ordering</th>
                                        <th class="text-end" scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $refundCause)
                                        <tr>
                                            <th class="fw-semibold text-break" scope="row">{{ $refundCause->name }}</th>
                                            <td class="small text-body-secondary">{{ $refundCause->ordering }}</td>
                                            <td>@include('core::partials.row-actions', ['model' => $refundCause, 'routePrefix' => $routePrefix])</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
                </section>
            </div>
        @endif
    </div>
@endsection
