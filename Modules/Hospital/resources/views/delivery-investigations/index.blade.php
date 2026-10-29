@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Review scheduled investigation deliveries and update their delivery status.',
    ])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-file-circle-check text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Delivery investigations</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter delivery investigations">
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-xl-2">
                    <label class="form-label small" for="date_from">Delivery date from</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('date_from') }}" id="date_from" name="date_from" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('date_from', null, $filters['date_from'] ?? '') }}" data-input {{ inputAttrs('date_from') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_from" aria-label="Choose delivery start date" title="Choose delivery start date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    @error('date_from')<div class="invalid-feedback d-block" id="date_from-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 col-xl-2">
                    <label class="form-label small" for="date_to">Delivery date to</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('date_to') }}" id="date_to" name="date_to" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('date_to', null, $filters['date_to'] ?? '') }}" data-input {{ inputAttrs('date_to') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_to" aria-label="Choose delivery end date" title="Choose delivery end date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    @error('date_to')<div class="invalid-feedback d-block" id="date_to-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 col-xl-3">
                    <label class="form-label small" for="filter_patient_id">Patient</label>
                    <select class="{{ inputClass('filter_patient_id', 'form-select') }}" id="filter_patient_id" name="filter_patient_id" data-ajax-select data-url="{{ route('hospital.patients.options') }}" data-value-field="id" data-label-field="text" data-placeholder="All patients" {{ inputAttrs('filter_patient_id') }}>
                        <option value=""></option>
                        @if ($filterPatient !== null)
                            <option value="{{ $filterPatient->id }}" selected>{{ $filterPatient->patient_id }} — {{ $filterPatient->name }} — {{ $filterPatient->mobile_no }}</option>
                        @endif
                    </select>
                    @error('filter_patient_id')<div class="invalid-feedback d-block" id="filter_patient_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 col-xl-3">
                    <label class="form-label small" for="filter_doctor_id">Doctor</label>
                    <select class="{{ inputClass('filter_doctor_id', 'form-select') }}" id="filter_doctor_id" name="filter_doctor_id" data-ajax-select data-url="{{ route('hospital.doctors.options') }}" data-value-field="id" data-label-field="text" data-placeholder="All doctors" {{ inputAttrs('filter_doctor_id') }}>
                        <option value=""></option>
                        @if ($filterDoctor !== null)
                            <option value="{{ $filterDoctor->id }}" selected>{{ $filterDoctor->doctor_id ?: $filterDoctor->id }} — {{ $filterDoctor->name }}</option>
                        @endif
                    </select>
                    @error('filter_doctor_id')<div class="invalid-feedback d-block" id="filter_doctor_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 col-xl-2">
                    <label class="form-label small" for="delivery_status">Status</label>
                    <select class="{{ inputClass('delivery_status', 'form-select') }}" id="delivery_status" name="delivery_status" {{ inputAttrs('delivery_status') }}>
                        <option value="">All statuses</option>
                        @foreach ($deliveryStatuses as $value => $label)
                            <option value="{{ $value }}" {{ getSelected($value, 'delivery_status', null, $filters['delivery_status'] ?? '') }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('delivery_status')<div class="invalid-feedback d-block" id="delivery_status-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 d-flex justify-content-end">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No delivery investigations found', 'description' => 'Choose another delivery date range or clear the filters.', 'icon' => 'file-circle-check'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Investigation invoices with patients, doctors, scheduled delivery dates, and delivery status</caption>
                    <thead>
                        <tr>
                            <th scope="col">Invoice</th>
                            <th scope="col">Patient</th>
                            <th scope="col">Doctor</th>
                            <th scope="col">Entry date</th>
                            <th scope="col">Delivery date</th>
                            <th scope="col">Status</th>
                            @if ($canManageDelivery)
                                <th class="text-end" scope="col">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $investigation)
                            <tr>
                                <th class="small fw-semibold text-break" scope="row">{{ $investigation->invoice_no ?: '#'.$investigation->id }}</th>
                                <td class="small text-break">
                                    <div class="fw-semibold">{{ $investigation->patient?->patient_id }}</div>
                                    <div>{{ $investigation->patient?->name }}</div>
                                    <div class="text-body-secondary">{{ $investigation->patient?->mobile_no }}</div>
                                </td>
                                <td class="small text-break">
                                    <div class="fw-semibold">{{ $investigation->doctor?->doctor_id ?: $investigation->doctor?->id }}</div>
                                    <div>{{ $investigation->doctor?->name }}</div>
                                </td>
                                <td class="small text-nowrap">{{ $investigation->entry_date?->format('d M Y') }}</td>
                                <td class="small text-nowrap">{{ $investigation->delivery_date?->format('d M Y') }}</td>
                                <td><span class="badge {{ $investigation->delivery_status === 'delivered' ? 'text-bg-success' : 'text-bg-warning' }}">{{ $deliveryStatuses[$investigation->delivery_status] }}</span></td>
                                @if ($canManageDelivery)
                                    <td class="text-end">
                                        <form class="d-inline-flex" action="{{ route($routePrefix.'.update', ['patient_investigation' => $investigation] + $filters) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input name="target_status" type="hidden" value="{{ $investigation->delivery_status === 'pending' ? 'delivered' : 'pending' }}">
                                            <button class="btn btn-sm text-nowrap {{ $investigation->delivery_status === 'pending' ? 'btn-success' : 'btn-outline-secondary' }}" type="submit" aria-label="{{ $investigation->delivery_status === 'pending' ? 'Mark delivered' : 'Mark pending' }}: {{ $investigation->invoice_no ?: '#'.$investigation->id }}">
                                                <i class="fa-solid {{ $investigation->delivery_status === 'pending' ? 'fa-check' : 'fa-rotate-left' }} me-1" aria-hidden="true"></i>{{ $investigation->delivery_status === 'pending' ? 'Mark Delivered' : 'Mark Pending' }}
                                            </button>
                                        </form>
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
