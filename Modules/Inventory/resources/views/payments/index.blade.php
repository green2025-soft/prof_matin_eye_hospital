@extends('core::layouts.app')
@section('title', ucfirst($filters['product_type'] ?? 'medicine').' '.$pageTitle)
@section('content')
    <div class="d-flex justify-content-between gap-3 mb-3">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type'] ?? 'medicine').' '.$pageTitle])
        @if($canCreate)
            <a class="btn btn-primary text-nowrap align-self-start" href="{{ route('inventory.payments.create', ['product_type' => $filters['product_type'], 'direction' => $filters['direction']]) }}">
                New payment
            </a>
        @endif
    </div>
    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" method="GET" action="{{ route('inventory.payments.index') }}">
            <input name="product_type" type="hidden" value="{{ $filters['product_type'] ?? 'medicine' }}">
            <input name="direction" type="hidden" value="{{ $filters['direction'] }}">
            <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
            <div class="row g-2 align-items-end">
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label small" for="from_date">From date</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('from_date') }}" id="from_date" name="from_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('from_date', null, $filters['from_date'] ?? '') }}" data-input {{ inputAttrs('from_date') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="from_date" aria-label="Choose start date" title="Choose start date">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('from_date')
                        <div class="invalid-feedback d-block" id="from_date-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label small" for="to_date">To date</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('to_date') }}" id="to_date" name="to_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('to_date', null, $filters['to_date'] ?? '') }}" data-input {{ inputAttrs('to_date') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="to_date" aria-label="Choose end date" title="Choose end date">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('to_date')
                        <div class="invalid-feedback d-block" id="to_date-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-4 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        Filter
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('inventory.payments.index', ['product_type' => $filters['product_type'], 'direction' => $filters['direction'], 'per_page' => $filters['per_page'] ?? config('pagination.default')]) }}">
                        Reset
                    </a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">{{ $filters['direction'] === 'customer' ? 'Customer' : 'Supplier' }}</th>
                        <th scope="col" class="text-end">Cash</th>
                        <th scope="col" class="text-end">Discount</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td>
                                <a href="{{ route('inventory.payments.show', ['payment' => $record, 'product_type' => $record->product_type, 'direction' => $record->direction]) }}">
                                    {{ $record->number }}
                                </a>
                            </td>
                            <td>
                                {{ $record->payment_date->format('Y-m-d') }}
                            </td>
                            <td>
                                {{ $record->direction === 'customer' ? ($customers[$record->customer_id]['name'] ?? '—') : ($suppliers[$record->supplier_id] ?? '—') }}
                            </td>
                            <td class="text-end">
                                {{ \Modules\Core\Helpers\Money::decimal($record->amount_minor) }}
                            </td>
                            <td class="text-end">
                                {{ \Modules\Core\Helpers\Money::decimal($record->discount_minor) }}
                            </td>
                            <td>
                                {{ $record->voided_at ? 'Voided' : 'Posted' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-body-secondary py-4" colspan="6">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => $routePrefix])
    </section>
@endsection
