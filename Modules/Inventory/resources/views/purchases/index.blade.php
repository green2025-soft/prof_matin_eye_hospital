@extends('core::layouts.app')

@section('title', ucfirst($filters['product_type'] ?? 'medicine').' Purchases')

@section('content')
    <div class="d-flex justify-content-between gap-3 align-items-start mb-3">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type'] ?? 'medicine').' Purchases'])
        @if ($canCreate)
            <a class="btn btn-primary text-nowrap" href="{{ route($routePrefix.'.create', ['product_type' => $filters['product_type'] ?? 'medicine']) }}">
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>New purchase
            </a>
        @endif
    </div>

    <section class="app-card overflow-hidden">
        @include('inventory::entries.partials.filters', ['numberLabel' => 'Invoice no.'])

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Purchases and saved amounts</caption>
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">Supplier</th>
                        <th scope="col">Supplier invoice no.</th>
                        <th scope="col" class="text-end">Total</th>
                        <th scope="col" class="text-end">Paid</th>
                        <th scope="col" class="text-end">Due</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                <a href="{{ route($routePrefix.'.show', [$resourceParameter => $record, 'product_type' => $record->product_type]) }}">{{ $record->number }}</a>
                            </td>
                            <td class="text-nowrap">{{ $record->document_date->format('Y-m-d') }}</td>
                            <td>{{ $record->supplier?->name ?? '—' }}</td>
                            <td>{{ $record->supplier_invoice_no ?: '—' }}</td>
                            <td class="text-end">{{ \Modules\Core\Helpers\Money::decimal($record->total_minor) }}</td>
                            <td class="text-end">{{ \Modules\Core\Helpers\Money::decimal($record->paid_minor) }}</td>
                            <td class="text-end">{{ \Modules\Core\Helpers\Money::decimal($record->voided_at ? 0 : $record->outstandingMinor()) }}</td>
                            <td>
                                @include('inventory::entries.partials.status')
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    @if ($canUpdatePurchases && ! $record->voided_at)
                                        <a class="btn btn-outline-primary btn-sm app-action-button" href="{{ route($routePrefix.'.edit', [$resourceParameter => $record, 'product_type' => $record->product_type]) }}" aria-label="Edit {{ $record->number }}" title="Edit">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                    @if ($canDeletePurchases)
                                        <form action="{{ route($routePrefix.'.destroy', [$resourceParameter => $record, 'product_type' => $record->product_type]) }}" method="POST" data-inventory-confirm="Delete this purchase and reverse its stock and amounts?">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete {{ $record->number }}" title="Delete">
                                                <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-body-secondary py-4">No purchases found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => $routePrefix])
    </section>
@endsection
