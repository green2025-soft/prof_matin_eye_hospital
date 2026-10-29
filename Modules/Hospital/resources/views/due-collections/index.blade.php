@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Review due payments collected at the selected branch.',
        'create' => ['model' => \Modules\Hospital\Models\DueCollection::class, 'url' => route($routePrefix.'.create'), 'label' => 'New due collection'],
    ])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-money-bill-wave text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Due collections</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter due collections">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label small" for="search">Search due collections</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="Receipt number, date or remarks starts with..." {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No due collections found', 'description' => 'Record a due payment or clear the filters.', 'icon' => 'money-bill-wave'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Due collection receipts with source invoices, patients, and payment types</caption>
                    <thead>
                        <tr>
                            <th scope="col">Receipt</th>
                            <th scope="col">Collection date</th>
                            <th scope="col">Service / Invoice</th>
                            <th scope="col">Patient</th>
                            <th class="text-end" scope="col">Amount</th>
                            <th scope="col">Payment type</th>
                            <th scope="col">Remarks</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $collection)
                            @php($source = $collection->consultation ?? $collection->investigation ?? $collection->operation)
                            <tr>
                                <th class="small fw-normal text-nowrap" scope="row">
                                    <div class="fw-semibold">{{ $collection->invoice_no ?? '-' }}</div>
                                    <div class="text-body-secondary">Recorded {{ $collection->created_at?->format('d M Y, h:i A') }}</div>
                                </th>
                                <td class="small text-nowrap">{{ $collection->collection_date?->format('d M Y') }}</td>
                                <td class="small text-break">
                                    <div class="fw-semibold">{{ filled($source?->invoice_no) ? $source->invoice_no : '—' }}</div>
                                    <div class="text-body-secondary">{{ ucfirst($collection->serviceType()) }}</div>
                                </td>
                                <td class="small text-break">
                                    <div class="fw-semibold">{{ $collection->patient?->name ?? '-' }}</div>
                                    <div class="text-body-secondary">{{ $collection->patient?->mobile_no }}</div>
                                </td>
                                <td class="text-end text-nowrap">{{ $collection->amount }}</td>
                                <td class="small text-break">{{ $collection->paymentType?->name ?? '-' }}</td>
                                <td class="small text-break">{{ $collection->remarks ?? '-' }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2 text-nowrap">
                                        @can('view', $collection)
                                            <a class="btn btn-outline-primary btn-sm app-action-button" href="{{ route($routePrefix.'.show', $collection) }}" aria-label="Print due collection {{ $collection->invoice_no }}" title="Print receipt"><i class="fa-solid fa-print" aria-hidden="true"></i></a>
                                        @endcan
                                        @can('update', $collection)
                                            <a class="btn btn-outline-secondary btn-sm app-action-button" href="{{ route($routePrefix.'.edit', $collection) }}" aria-label="Edit due collection {{ $collection->invoice_no }}" title="Edit due collection"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                        @endcan
                                        @can('delete', $collection)
                                            <form class="d-inline-flex" action="{{ route($routePrefix.'.destroy', $collection) }}" method="POST" data-confirm="Delete this due collection? This cannot be undone.">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete due collection {{ $collection->invoice_no }}" title="Delete due collection"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
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
@endsection
