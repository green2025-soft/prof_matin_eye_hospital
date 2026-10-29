@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Review patient investigations and payments at the selected branch.',
        'create' => ['model' => \Modules\Hospital\Models\PatientInvestigation::class, 'url' => route($routePrefix.'.create'), 'label' => 'New investigation'],
    ])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-file-invoice text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Patient investigations</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter patient investigations">
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-xxl-3">
                    <label class="form-label small" for="search">Search patient investigations</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="Invoice, ID or entry date starts with..." {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 col-xxl-3">
                    <label class="form-label small" for="filter_doctor_id">Doctor</label>
                    <select class="{{ inputClass('filter_doctor_id', 'form-select') }}" id="filter_doctor_id" name="filter_doctor_id" data-ajax-select data-url="{{ route('hospital.doctors.options') }}" data-value-field="id" data-label-field="text" data-placeholder="All doctors" {{ inputAttrs('filter_doctor_id') }}>
                        <option value=""></option>
                        @if (($filterDoctor ?? null) !== null)
                            <option value="{{ $filterDoctor->id }}" selected>{{ $filterDoctor->doctor_id ?: $filterDoctor->id }} — {{ $filterDoctor->name }}</option>
                        @endif
                    </select>
                    @error('filter_doctor_id')<div class="invalid-feedback d-block" id="filter_doctor_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8 col-xxl-4">
                    <label class="form-label small" for="filter_patient_id">Patient</label>
                    <select class="{{ inputClass('filter_patient_id', 'form-select') }}" id="filter_patient_id" name="filter_patient_id" data-ajax-select data-url="{{ route('hospital.patients.options') }}" data-value-field="id" data-label-field="text" data-placeholder="All patients" {{ inputAttrs('filter_patient_id') }}>
                        <option value=""></option>
                        @if (($filterPatient ?? null) !== null)
                            <option value="{{ $filterPatient->id }}" selected>{{ $filterPatient->patient_id }} — {{ $filterPatient->name }} — {{ $filterPatient->mobile_no }}</option>
                        @endif
                    </select>
                    @error('filter_patient_id')<div class="invalid-feedback d-block" id="filter_patient_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 col-xxl-2">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No patient investigations found', 'description' => 'Try another entry ID or clear the filters.', 'icon' => 'file-invoice'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Patient investigations with patient, Doctor, and payment details</caption>
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Patient</th>
                            <th scope="col">Doctor</th>
                            <th class="text-end" scope="col">Amount</th>
                            <th class="text-end" scope="col">Discount</th>
                            <th class="text-end" scope="col">Current payable</th>
                            <th class="text-end" scope="col">Current paid</th>
                            <th class="text-end" scope="col">Current due</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $patient_investigation)
                            <tr>
                                <td class="small text-nowrap">
                                    @if ($patient_investigation->invoice_no !== null)
                                        <div class="fw-semibold">Invoice {{ $patient_investigation->invoice_no }}</div>
                                    @endif
                                    {{ $patient_investigation->entry_date?->format('d M Y') }}
                                    <div class="small text-body-secondary">Delivery {{ $patient_investigation->delivery_date?->format('d M Y') }}</div>
                                    <div class="small text-body-secondary">#{{ $patient_investigation->id }}</div>
                                </td>
                                <td class="small text-break">
                                    <div class="fw-semibold">{{ $patient_investigation->patient?->patient_id }}</div>
                                    <div class="text-body-secondary">{{ $patient_investigation->patient?->name }}</div>
                                </td>
                                <td class="small text-break">{{ $patient_investigation->doctor?->name }}</td>
                                <td class="text-end text-nowrap">{{ $patient_investigation->amount }}</td>
                                <td class="text-end text-nowrap">
                                    {{ $patient_investigation->discount_amount }}
                                    <div class="small text-body-secondary">Extra {{ $patient_investigation->extra_discount_amount }}</div>
                                </td>
                                <td class="text-end text-nowrap">{{ $invoiceBalances[$patient_investigation->id]['remaining_amount'] }}</td>
                                <td class="text-end text-nowrap">
                                    {{ $invoiceBalances[$patient_investigation->id]['remaining_paid'] }}
                                    <div class="small text-body-secondary">Initial: {{ $patient_investigation->paymentType?->name }}</div>
                                </td>
                                <td class="text-end text-nowrap">
                                    {{ $invoiceBalances[$patient_investigation->id]['remaining_due'] }}
                                    @if ($invoiceBalances[$patient_investigation->id]['remaining_due'] !== '0.00')
                                        <div class="small text-body-secondary">{{ $patient_investigation->due_date?->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2 text-nowrap">
                                        @can('view', $patient_investigation)
                                            <a class="btn btn-outline-primary btn-sm app-action-button" href="{{ route($routePrefix.'.show', $patient_investigation) }}" aria-label="Print investigation invoice #{{ $patient_investigation->id }}" title="Print invoice"><i class="fa-solid fa-print" aria-hidden="true"></i></a>
                                        @endcan
                                        @can('update', $patient_investigation)
                                            <a class="btn btn-outline-secondary btn-sm app-action-button" href="{{ route($routePrefix.'.edit', $patient_investigation) }}" aria-label="Edit investigation #{{ $patient_investigation->id }}" title="Edit"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                        @endcan
                                        @can('delete', $patient_investigation)
                                            <form class="d-inline-flex" action="{{ route($routePrefix.'.destroy', $patient_investigation) }}" method="POST" data-confirm="Delete this investigation? This cannot be undone.">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete investigation #{{ $patient_investigation->id }}" title="Delete"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
