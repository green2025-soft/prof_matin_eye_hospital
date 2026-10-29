@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Review consultations and payments at the selected branch.',
        'create' => ['model' => \Modules\Hospital\Models\Consultation::class, 'url' => route($routePrefix.'.create'), 'label' => 'New consultation'],
    ])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-file-invoice text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Consultations</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter consultations">
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-xxl-3">
                    <label class="form-label small" for="search">Search consultations</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="Serial, invoice, ID or date starts with..." {{ inputAttrs('search') }}>
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
            @include('core::partials.empty', ['title' => 'No consultations found', 'description' => 'Try another entry ID or clear the filters.', 'icon' => 'file-invoice'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Consultations with patient, Doctor, and payment details</caption>
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
                        @foreach ($records as $consultation)
                            <tr>
                                <td class="small text-nowrap">
                                    @if ($consultation->invoice_no !== null)
                                        <div class="fw-semibold">Invoice {{ $consultation->invoice_no }}</div>
                                    @endif
                                    @if ($consultation->serial_no !== null)
                                        <div class="small text-body-secondary">Serial {{ $consultation->serial_no }}</div>
                                    @endif
                                    {{ $consultation->entry_date?->format('d M Y') }}
                                    <div class="small text-body-secondary">#{{ $consultation->id }}</div>
                                </td>
                                <td class="small text-break">
                                    <div class="fw-semibold">{{ $consultation->patient?->patient_id }}</div>
                                    <div class="text-body-secondary">{{ $consultation->patient?->name }}</div>
                                </td>
                                <td class="small text-break">{{ $consultation->doctor?->name }}</td>
                                <td class="text-end text-nowrap">{{ $consultation->amount }}</td>
                                <td class="text-end text-nowrap">{{ $consultation->discount_amount }}</td>
                                <td class="text-end text-nowrap">{{ $invoiceBalances[$consultation->id]['remaining_amount'] }}</td>
                                <td class="text-end text-nowrap">
                                    {{ $invoiceBalances[$consultation->id]['remaining_paid'] }}
                                    <div class="small text-body-secondary">Initial: {{ $consultation->paymentType?->name }}</div>
                                </td>
                                <td class="text-end text-nowrap">
                                    {{ $invoiceBalances[$consultation->id]['remaining_due'] }}
                                    @if ($invoiceBalances[$consultation->id]['remaining_due'] !== '0.00')
                                        <div class="small text-body-secondary">{{ $consultation->due_date?->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2 text-nowrap">
                                        @can('view', $consultation)
                                            <a class="btn btn-outline-primary btn-sm app-action-button" href="{{ route($routePrefix.'.show', $consultation) }}" aria-label="Print consultation invoice #{{ $consultation->id }}" title="Print invoice"><i class="fa-solid fa-print" aria-hidden="true"></i></a>
                                        @endcan
                                        @can('update', $consultation)
                                            <a class="btn btn-outline-secondary btn-sm app-action-button" href="{{ route($routePrefix.'.edit', $consultation) }}" aria-label="Edit consultation #{{ $consultation->id }}" title="Edit"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                        @endcan
                                        @can('delete', $consultation)
                                            <form class="d-inline-flex" action="{{ route($routePrefix.'.destroy', $consultation) }}" method="POST" data-confirm="Delete this consultation? This cannot be undone.">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete consultation #{{ $consultation->id }}" title="Delete"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
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
