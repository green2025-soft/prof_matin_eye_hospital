@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Draft, issue, and review workspace invoices.',
        'create' => [
            'model' => \Modules\Core\Models\Invoice::class,
            'url' => route('core.invoices.create'),
            'label' => 'New invoice',
        ],
    ])

    @can('create', \Modules\Core\Models\Invoice::class)
        <div class="d-flex justify-content-end mb-3">
            <form action="{{ route('core.invoices.generate') }}" method="POST">
                @csrf
                <button class="btn btn-outline-primary" type="submit"><i class="fa-solid fa-file-invoice me-2" aria-hidden="true"></i>Generate due invoices</button>
            </form>
        </div>
    @endcan

    @include('core::invoices.summary')

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="{{ route('core.invoices.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label" for="search">Invoice number</label>
                    <input
                        class="{{ inputClass('search') }}"
                        id="search"
                        name="search"
                        type="search"
                        value="{{ getValue('search', null, $filters['search'] ?? '') }}"
                        placeholder="Number starts with..."
                        {{ inputAttrs('search') }}
                    >
                    @error('search')
                        <div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="{{ inputClass('status', 'form-select') }}" id="status" name="status" {{ inputAttrs('status') }}>
                        <option value="">All statuses</option>
                        @foreach ($filterOptions['status'] as $value => $label)
                            <option value="{{ $value }}" {{ getSelected($value, 'status', null, $filters['status'] ?? '') }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback d-block" id="status-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    @include('core::partials.filter-actions', ['routePrefix' => 'core.invoices'])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', [
                'title' => 'No invoices found',
                'description' => 'Create a draft invoice or change the filters.',
                'icon' => 'file-invoice',
            ])
        @else
            @include('core::invoices.table', [
                'records' => $records,
                'statusOptions' => $filterOptions['status'],
                'invoiceRoutePrefix' => 'core.invoices',
                'showWorkspace' => true,
            ])
        @endif

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
    @include('core::invoices.manual-submissions')
@endsection
