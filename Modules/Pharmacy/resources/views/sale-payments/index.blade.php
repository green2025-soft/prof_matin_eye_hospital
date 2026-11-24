@extends('core::layouts.app')
@section('title', ucfirst($filters['product_type'] ?? 'medicine').' '.$pageTitle)
@section('content')
    <div class="d-flex justify-content-between gap-3 mb-3">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type'] ?? 'medicine').' '.$pageTitle])
        @if ($canCreate)
            <a class="btn btn-primary text-nowrap align-self-start"
                href="{{ route('pharmacy.sale-payments.create', ['product_type' => $filters['product_type']]) }}">
                New Collection
            </a>
        @endif
    </div>
    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" method="GET" action="{{ route('pharmacy.sale-payments.index') }}">
            <input name="product_type" type="hidden" value="{{ $filters['product_type'] ?? 'medicine' }}">
            <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
            <div class="row g-2 align-items-end">
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label small" for="from_date">From Date</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('from_date') }}" id="from_date" name="from_date" type="text"
                            inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                            value="{{ getValue('from_date', null, $filters['from_date'] ?? '') }}" data-input
                            {{ inputAttrs('from_date') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="from_date"
                            aria-label="Choose start date" title="Choose start date">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('from_date')
                        <div class="invalid-feedback d-block" id="from_date-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label small" for="to_date">To Date</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('to_date') }}" id="to_date" name="to_date" type="text"
                            inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                            value="{{ getValue('to_date', null, $filters['to_date'] ?? '') }}" data-input
                            {{ inputAttrs('to_date') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="to_date"
                            aria-label="Choose end date" title="Choose end date">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('to_date')
                        <div class="invalid-feedback d-block" id="to_date-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-4 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary"
                        href="{{ route('pharmacy.sale-payments.index', ['product_type' => $filters['product_type'], 'per_page' => $filters['per_page'] ?? config('pagination.default')]) }}">
                        Reset
                    </a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Sales and saved amounts</caption>
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">Customer</th>
                        <th scope="col" class="text-end">Collection</th>
                        <th scope="col" class="text-end">Discount</th>
                        {{-- <th scope="col">Status</th> --}}
                        @if ($canDelete)
                            <th scope="col" class="text-end">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                <a href="{{ route('pharmacy.sale-payments.show', ['sale_payment' => $record, 'product_type' => $record->product_type]) }}">
                                    {{ $record->number }}
                                </a>
                            </td>
                            <td>{{ $record->payment_date->format('Y-m-d') }}</td>
                            <td>{{ $customers[$record->customer_id]['name'] ?? '—' }}</td>
                            <td class="text-end">{{ moneyDecimal($record->amount) }}</td>
                            <td class="text-end">{{ moneyDecimal($record->discount) }}</td>
                            {{-- <td>{{ $record->voided_at ? 'Voided' : 'Posted' }}</td> --}}
                            @if ($canDelete)
                                <td class="text-end">
                                    <form action="{{ route('pharmacy.sale-payments.destroy', [
                                        'sale_payment' => $record,
                                        'product_type' => $record->product_type,
                                    ]) }}" method="POST"
                                        data-confirm="Permanently delete this payment? Active invoice and journal effects will be reversed.">
                                        @csrf
                                        @method('DELETE')
                                       <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete {{ $record->number }}" title="Delete">
                                            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-body-secondary py-4" colspan="{{ 5 + (int) $canDelete }}">
                                No customer collections found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => $routePrefix])
    </section>
@endsection
