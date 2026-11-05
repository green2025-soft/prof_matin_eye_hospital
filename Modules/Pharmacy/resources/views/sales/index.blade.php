@extends('core::layouts.app')

@section('title', ucfirst($filters['product_type'] ?? 'medicine').' Sales')

@section('content')
    <div class="d-flex justify-content-between gap-3 align-items-start mb-3">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type'] ?? 'medicine').' Sales'])
        @if ($canCreate)
            <a class="btn btn-primary text-nowrap" href="{{ route($routePrefix.'.create', ['product_type' => $filters['product_type'] ?? 'medicine']) }}">
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>New sale
            </a>
        @endif
    </div>

    <section class="app-card overflow-hidden">
        @include('pharmacy::entries.partials.filters', ['numberLabel' => 'Invoice no.'])

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Sales and saved amounts</caption>
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">Customer</th>
                        <th scope="col" class="text-end">Total</th>
                        <th scope="col" class="text-end">Paid</th>
                        <th scope="col" class="text-end">Due</th>
                        <th scope="col">Status</th>
                        @if ($canUpdateSales || $canDeleteSales)
                            <th scope="col" class="text-end">Actions</th>
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
                            <td>{{ $customers[$record->customer_id]['name'] ?? '—' }}</td>
                            <td class="text-end">{{ moneyDecimal($record->total) }}</td>
                            <td class="text-end">{{ moneyDecimal($record->paid) }}</td>
                            <td class="text-end">{{ moneyDecimal($record->voided_at ? 0 : $record->outstandingMinor()) }}</td>
                            <td>
                                @include('pharmacy::entries.partials.status')
                            </td>
                            @if ($canUpdateSales || $canDeleteSales)
                                <td class="text-end">
                                    @if ($canUpdateSales && ! $record->voided_at)
                                        <a class="btn btn-outline-primary btn-sm app-action-button" href="{{ route($routePrefix.'.edit', ['sale' => $record, 'product_type' => $record->product_type]) }}" aria-label="Edit {{ $record->number }}" title="Edit">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                    @if ($canDeleteSales)
                                        <form class="d-inline" action="{{ route($routePrefix.'.destroy', ['sale' => $record, 'product_type' => $record->product_type]) }}" method="POST" data-confirm="Delete this sale and reverse its stock and amounts?">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete {{ $record->number }}" title="Delete">
                                                <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 7 + (int) ($canUpdateSales || $canDeleteSales) }}" class="text-center text-body-secondary py-4">No sales found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => $routePrefix])
    </section>
@endsection
