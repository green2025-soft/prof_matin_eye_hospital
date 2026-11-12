@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Record income by head, date, and payment type.',
        'create' => ['model' => resourceModel($routePrefix.'.create'), 'url' => route($routePrefix.'.create'), 'label' => 'New income'],
    ])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-arrow-trend-up text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Income</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter income">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-6 col-xl-4">
                    <label class="form-label small" for="search">Search income</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search"
                            value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80"
                            placeholder="ID, date, or note starts with..." {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <label class="form-label small" for="filter_head_id">Income head</label>
                    <select class="{{ inputClass('filter_head_id', 'form-select') }}" id="filter_head_id" name="filter_head_id"
                        data-ajax-select data-url="{{ route('tenantcore.income-heads.options') }}"
                        data-placeholder="All income heads"
                        {{ inputAttrs('filter_head_id') }}>
                        <option value=""></option>
                        @if ($selectedHead !== null)
                            <option value="{{ $selectedHead->id }}" selected>{{ $selectedHead->name }}</option>
                        @endif
                    </select>
                    @error('filter_head_id')<div class="invalid-feedback d-block" id="filter_head_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-xl-4">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No income found', 'description' => 'Create an income entry or change the filters.', 'icon' => 'arrow-trend-up'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Income dates, heads, amounts, payment types, and notes</caption>
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Income head</th>
                            <th class="text-end" scope="col">Amount</th>
                            <th scope="col">Payment type</th>
                            <th scope="col">Note</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $income)
                            <tr>
                                <th class="fw-semibold text-nowrap" scope="row">{{ $income->entry_date->toDateString() }}</th>
                                <td class="text-break">{{ $income->head?->name }}</td>
                                <td class="text-end text-nowrap">{{ $income->amount }}</td>
                                <td class="small text-break">{{ $income->paymentType?->name }}</td>
                                <td class="small text-break">{{ $income->note ?? '-' }}</td>
                                <td>@include('core::partials.row-actions', ['model' => $income, 'routePrefix' => $routePrefix, 'actionLabel' => 'income entry'])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
