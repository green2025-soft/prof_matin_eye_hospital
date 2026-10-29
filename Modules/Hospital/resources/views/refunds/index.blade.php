@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Review refunds at the selected branch.',
        'create' => ['model' => \Modules\Hospital\Models\Refund::class, 'url' => route($routePrefix.'.create'), 'label' => 'New refund'],
    ])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-rotate-left text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Refunds</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter refunds">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label small" for="search">Search refunds</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="Refund ID or remarks starts with..." {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No refunds found', 'description' => 'Create a refund or clear the filters.', 'icon' => 'rotate-left'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Refunds with source invoices, patients, and refund causes</caption>
                    <thead>
                        <tr>
                            <th scope="col">Refund</th>
                            <th scope="col">Service / Invoice</th>
                            <th scope="col">Patient</th>
                            <th class="text-end" scope="col">Cash refund</th>
                            <th class="text-end" scope="col">Due adjustment</th>
                            <th scope="col">Refund cause</th>
                            <th scope="col">Remarks</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $refund)
                            @php($source = $refund->consultation ?? $refund->investigation ?? $refund->operation)
                            <tr>
                                <th class="small fw-normal text-nowrap" scope="row">
                                    <div class="fw-semibold">{{ $refund->invoice_no ?: '#'.$refund->id }}</div>
                                    <div class="text-body-secondary">{{ $refund->created_at?->format('d M Y, h:i A') }}</div>
                                </th>
                                <td class="small text-break">
                                    <div class="fw-semibold">{{ filled($source?->invoice_no) ? $source->invoice_no : '#'.$source?->getKey() }}</div>
                                    <div class="text-body-secondary">{{ ucfirst($refund->serviceType()) }}</div>
                                </td>
                                <td class="small text-break">
                                    <div class="fw-semibold">{{ $refund->patient?->patient_id ?? '-' }}</div>
                                    <div>{{ $refund->patient?->name ?? '-' }}</div>
                                    <div class="text-body-secondary">{{ $refund->patient?->mobile_no }}</div>
                                </td>
                                <td class="text-end text-nowrap">{{ $refund->amount }}</td>
                                <td class="text-end text-nowrap">{{ $refund->due_adjustment }}</td>
                                <td class="small text-break">{{ $refund->cause?->name ?? '-' }}</td>
                                <td class="small text-break">{{ $refund->remarks ?? '-' }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2 text-nowrap">
                                        @can('view', $refund)
                                            <a class="btn btn-outline-primary btn-sm app-action-button" href="{{ route($routePrefix.'.show', $refund) }}" aria-label="Print refund #{{ $refund->id }}" title="Print refund"><i class="fa-solid fa-print" aria-hidden="true"></i></a>
                                        @endcan
                                        @can('update', $refund)
                                            <a class="btn btn-outline-secondary btn-sm app-action-button" href="{{ route($routePrefix.'.edit', $refund) }}" aria-label="Edit refund #{{ $refund->id }}" title="Edit refund"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                        @endcan
                                        @can('delete', $refund)
                                            <form class="d-inline-flex" action="{{ route($routePrefix.'.destroy', $refund) }}" method="POST" data-confirm="Delete this refund record? This cannot be undone.">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete refund #{{ $refund->id }}" title="Delete refund"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
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
