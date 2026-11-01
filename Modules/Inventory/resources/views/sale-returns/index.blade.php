@extends('core::layouts.app')

@section('title', ucfirst($filters['product_type'] ?? 'medicine').' Sales Returns')

@section('content')
    <div class="d-flex justify-content-between gap-3 align-items-start mb-3">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type'] ?? 'medicine').' Sales Returns'])
        @if ($canCreate)
            <a class="btn btn-primary text-nowrap" href="{{ route($routePrefix.'.create', ['product_type' => $filters['product_type'] ?? 'medicine']) }}">
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>New sales return
            </a>
        @endif
    </div>

    <section class="app-card overflow-hidden">
        @include('inventory::entries.partials.filters', ['numberLabel' => 'Entry no.'])

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Sales Returns and saved amounts</caption>
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">Original invoice</th>
                        <th scope="col">Customer</th>
                        <th scope="col" class="text-end">Invoice credit</th>
                        <th scope="col" class="text-end">Cash refunded</th>
                        <th scope="col">Status</th>
                        @if ($canUpdateReturns || $canDeleteReturns)
                            <th class="text-end" scope="col">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                <a href="{{ route($routePrefix.'.show', [$resourceParameter => $record, 'product_type' => $record->product_type]) }}">{{ $record->number }}</a>
                            </td>
                            <td class="text-nowrap">{{ $record->document_date->format('Y-m-d') }}</td>
                            <td>{{ $record->sourceDocument?->number ?? '—' }}</td>
                            <td>{{ $customers[$record->customer_id]['name'] ?? '—' }}</td>
                            <td class="text-end">{{ \Modules\Core\Helpers\Money::decimal($record->total_minor) }}</td>
                            <td class="text-end">{{ \Modules\Core\Helpers\Money::decimal($record->initial_paid_minor) }}</td>
                            <td>
                                @include('inventory::entries.partials.status')
                            </td>
                            @if ($canUpdateReturns || $canDeleteReturns)
                                <td>
                                    @include('core::partials.row-actions', [
                                        'model' => $record,
                                        'actionLabel' => $record->number,
                                        'routeParameters' => ['product_type' => $record->product_type],
                                        'deleteConfirmation' => 'Delete this sales return and reverse its stock and amounts?',
                                    ])
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 7 + (int) ($canUpdateReturns || $canDeleteReturns) }}" class="text-center text-body-secondary py-4">No sales returns found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => $routePrefix])
    </section>
@endsection
