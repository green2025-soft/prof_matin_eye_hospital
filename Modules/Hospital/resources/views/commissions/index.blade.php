@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter commissions">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-lg-2">
                    <label class="form-label small" for="date_from">From Date</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('date_from') }}" id="date_from" name="date_from" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('date_from', null, $filters['date_from'] ?? '') }}" data-input {{ inputAttrs('date_from') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_from" aria-label="Choose start date" title="Choose start date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    @error('date_from')<div class="invalid-feedback d-block" id="date_from-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-6 col-lg-2">
                    <label class="form-label small" for="date_to">To Date</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('date_to') }}" id="date_to" name="date_to" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('date_to', null, $filters['date_to'] ?? '') }}" data-input {{ inputAttrs('date_to') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_to" aria-label="Choose end date" title="Choose end date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    @error('date_to')<div class="invalid-feedback d-block" id="date_to-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-lg-5">
                    <label class="form-label small" for="filter_reference_id">Reference By</label>
                    <select class="{{ inputClass('filter_reference_id', 'form-select') }}" id="filter_reference_id" name="filter_reference_id" data-ajax-select data-url="{{ route('hospital.references.options') }}" data-value-field="id" data-label-field="text" data-placeholder="All references" {{ inputAttrs('filter_reference_id') }}>
                        <option value=""></option>
                        @if ($selectedReference !== null)
                            <option value="{{ $selectedReference->id }}" selected>{{ $selectedReference->reference_id }} — {{ $selectedReference->name }} ({{ $selectedReference->contact_no }})</option>
                        @endif
                    </select>
                    @error('filter_reference_id')<div class="invalid-feedback d-block" id="filter_reference_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-lg-3">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No commissions found', 'description' => '', 'icon' => 'hand-holding-dollar'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Reference commissions and payment status</caption>
                    <thead>
                        <tr>
                            <th scope="col">Invoice</th>
                            <th scope="col">Reference</th>
                            <th class="text-end" scope="col">%</th>
                            <th class="text-end" scope="col">Commission</th>
                            <th class="text-end" scope="col">Eligible</th>
                            <th class="text-end" scope="col">Paid amount</th>
                            <th scope="col">Status / Paid date</th>
                            @if ($canManageCommissions)
                                <th class="text-end" scope="col">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $commission)
                            <tr>
                                <th class="small fw-normal text-nowrap" scope="row">
                                    <div class="fw-semibold">{{ $commission['invoice_no'] ?: '#'.$commission['source_id'] }}</div>
                                    <div class="text-body-secondary">{{ $commission['entry_date'] }} · {{ $commission['service_label'] }}</div>
                                </th>
                                <td class="small text-break">
                                    <div class="fw-semibold">{{ $commission['reference_code'] }}</div>
                                    <div>{{ $commission['reference_name'] }}</div>
                                </td>
                                <td class="text-end text-nowrap">{{ $commission['percent'] }}%</td>
                                <td class="text-end text-nowrap">{{ $commission['commission'] }}</td>
                                <td class="text-end text-nowrap">{{ $commission['eligible'] }}</td>
                                <td class="text-end text-nowrap">
                                    {{ $commission['paid_amount'] }}
                                    @if (isset($commission['overpaid']) && $commission['overpaid'] !== '0.00')
                                        <div class="small text-danger">Excess: {{ $commission['overpaid'] }}</div>
                                    @endif
                                </td>
                                <td class="small text-nowrap">
                                    <span class="badge {{ $commission['status'] === 'paid' ? 'text-bg-success' : 'text-bg-warning' }}">{{ $commission['status'] === 'paid' ? 'Paid' : 'Unpaid' }}</span>
                                    @if ($commission['paid_at'] !== null)
                                        <div class="text-body-secondary mt-1">{{ $commission['paid_at'] }}</div>
                                    @endif
                                </td>
                                @if ($canManageCommissions)
                                    <td class="text-end">
                                        @if ($commission['can_pay'] || $commission['can_unpay'])
                                            <form class="d-inline-flex" action="{{ route($routePrefix.'.update', ['service' => $commission['service'], 'invoice' => $commission['source_id']] + $filters) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input name="target_status" type="hidden" value="{{ $commission['can_unpay'] ? 'unpaid' : 'paid' }}">
                                                <button class="btn btn-sm text-nowrap {{ $commission['can_unpay'] ? 'btn-outline-secondary' : 'btn-success' }}" type="submit" aria-label="{{ $commission['can_unpay'] ? 'Mark unpaid' : 'Mark paid' }}: {{ $commission['invoice_no'] ?: '#'.$commission['source_id'] }}">
                                                    <i class="fa-solid {{ $commission['can_unpay'] ? 'fa-rotate-left' : 'fa-check' }} me-1" aria-hidden="true"></i>{{ $commission['can_unpay'] ? 'Unpaid' : 'Paid' }}
                                                </button>
                                            </form>
                                        @elseif ($commission['status'] === 'unpaid' && $commission['pending'] !== '0.00')
                                            <span class="small text-body-secondary">Pending</span>
                                        @else
                                            <span class="text-body-secondary">—</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
