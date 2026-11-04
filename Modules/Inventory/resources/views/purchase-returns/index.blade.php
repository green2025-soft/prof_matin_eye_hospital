@extends('core::layouts.app')

@section('title', ucfirst($filters['product_type'] ?? 'medicine').' Purchase Returns')

@section('content')
    <div class="d-flex justify-content-between gap-3 align-items-start mb-3">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type'] ?? 'medicine').' Purchase Returns'])
        @if ($canCreate)
            <a class="btn btn-primary text-nowrap" href="{{ route($routePrefix.'.create', ['product_type' => $filters['product_type'] ?? 'medicine']) }}">
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>New purchase return
            </a>
        @endif
    </div>

    <section class="app-card overflow-hidden">
        @include('inventory::entries.partials.filters', ['numberLabel' => 'Entry no.'])

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Purchase Returns and saved amounts</caption>
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">Original invoice</th>
                        <th scope="col">Supplier</th>
                        <th scope="col" class="text-end">Invoice credit</th>
                        <th scope="col" class="text-end">Cash received</th>
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
                            <td class="text-nowrap">{{ $record->entry_date->format('Y-m-d') }}</td>
                            <td>{{ $record->purchase?->number ?? '—' }}</td>
                            <td>{{ $record->supplier?->name ?? '—' }}</td>
                            <td class="text-end">{{ moneyDecimal($record->total) }}</td>
                            <td class="text-end">{{ moneyDecimal($record->initial_paid) }}</td>
                            <td>
                                @include('inventory::entries.partials.status')
                            </td>
                            @if ($canUpdateReturns || $canDeleteReturns)
                                <td>
                                    @include('core::partials.row-actions', [
                                        'model' => $record,
                                        'actionLabel' => $record->number,
                                        'routeParameters' => ['product_type' => $record->product_type],
                                        'deleteConfirmation' => 'Delete this purchase return and reverse its stock and amounts?',
                                    ])
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 7 + (int) ($canUpdateReturns || $canDeleteReturns) }}" class="text-center text-body-secondary py-4">No purchase returns found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => $routePrefix])
    </section>
@endsection
