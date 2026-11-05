@extends('core::layouts.app')

@section('title', ucfirst($filters['product_type'] ?? 'medicine').' Branch Transfers')

@section('content')
    <div class="d-flex justify-content-between gap-3 align-items-start mb-3">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type'] ?? 'medicine').' Branch Transfers'])
        @if ($canCreate)
            <a class="btn btn-primary text-nowrap" href="{{ route($routePrefix.'.create', ['product_type' => $filters['product_type'] ?? 'medicine']) }}">
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>New transfer
            </a>
        @endif
    </div>

    <section class="app-card overflow-hidden">
        @include('pharmacy::entries.partials.filters', ['numberLabel' => 'Entry no.'])

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Branch Transfers and saved amounts</caption>
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">Destination branch</th>
                        <th scope="col" class="text-end">Stock value</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                <a href="{{ route($routePrefix.'.show', [$resourceParameter => $record, 'product_type' => $record->product_type]) }}">{{ $record->number }}</a>
                            </td>
                            <td class="text-nowrap">{{ $record->entry_date->format('Y-m-d') }}</td>
                            <td>{{ $record->destinationBranch?->name ?? '—' }}</td>
                            <td class="text-end">{{ moneyDecimal($record->cost) }}</td>
                            <td>
                                @include('pharmacy::entries.partials.status')
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-body-secondary py-4">No branch transfers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => $routePrefix])
    </section>
@endsection
