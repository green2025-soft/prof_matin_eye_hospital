@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Immutable receipts for manual and verified online payments.',
        'create' => [
            'model' => \Modules\Core\Models\Payment::class,
            'url' => route('core.payments.create'),
            'label' => 'Record payment',
        ],
    ])

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="{{ route('core.payments.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label" for="search">Provider reference</label>
                    <input
                        class="{{ inputClass('search') }}"
                        id="search"
                        name="search"
                        type="search"
                        value="{{ getValue('search', null, $filters['search'] ?? '') }}"
                        placeholder="Reference starts with..."
                        {{ inputAttrs('search') }}
                    >
                    @error('search')
                        <div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    @include('core::partials.filter-actions', ['routePrefix' => 'core.payments'])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', [
                'title' => 'No payments found',
                'description' => 'Recorded payments will appear here.',
                'icon' => 'money-bill-transfer',
            ])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Receipt</th>
                            <th scope="col">Invoice</th>
                            <th scope="col">Workspace</th>
                            <th scope="col">Provider</th>
                            <th scope="col">Reference</th>
                            <th class="text-end" scope="col">Invoice amount</th>
                            <th class="text-end" scope="col">Extra charge</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $payment)
                            <tr>
                                <th scope="row">
                                    <a href="{{ route('core.payments.show', $payment) }}">#{{ $payment->id }}</a>
                                    <div class="small fw-normal text-body-secondary">
                                        {{ $payment->paid_at->format('d M Y') }}
                                    </div>
                                </th>
                                <td>{{ $payment->invoice->number }}</td>
                                <td>{{ $payment->tenant->name }}</td>
                                <td>{{ $payment->provider }}</td>
                                <td class="text-break">{{ $payment->provider_reference ?? '-' }}</td>
                                <td class="text-end text-nowrap">{{ $payment->currency }} {{ $payment->amount }}</td>
                                <td class="text-end text-nowrap">{{ $payment->currency }} {{ $payment->extra_charge }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
