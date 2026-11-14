@extends('tenantcore::reports.layout')

@section('report-filters')
    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="from_date">From date</label>
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('from_date') }}" id="from_date" name="from_date" type="text" value="{{ getValue('from_date', null, $filters['from_date']) }}" placeholder="YYYY-MM-DD" autocomplete="off" required data-input {{ inputAttrs('from_date') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose from date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
            </div>
            @error('from_date')<div class="invalid-feedback d-block" id="from_date-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="to_date">To date</label>
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('to_date') }}" id="to_date" name="to_date" type="text" value="{{ getValue('to_date', null, $filters['to_date']) }}" placeholder="YYYY-MM-DD" autocomplete="off" required data-input {{ inputAttrs('to_date') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose to date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
            </div>
            @error('to_date')<div class="invalid-feedback d-block" id="to_date-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="module">Module</label>
            <select class="form-select" id="module" name="module">
                @foreach (['all' => 'All modules', 'general' => 'General income / expense', 'hospital' => 'Hospital', 'medicine' => 'Medicine', 'optic' => 'Optics'] as $value => $label)
                    <option value="{{ $value }}" @selected($filters['module'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <button class="btn btn-primary" type="submit">Show report</button>
        </div>
    </form>
@endsection

@section('report-summary')
    <div class="app-income-expense-columns row g-3 py-3">
        <section class="col-12 col-md-6" aria-labelledby="income-heading">
            <div class="border rounded h-100">
                <h4 class="h6 text-success bg-success-subtle p-3 mb-0" id="income-heading">Income</h4>
                <table class="app-report-table table table-sm align-middle mb-0">
                    <thead>
                        <tr><th scope="col" class="ps-3">Income Particular</th><th scope="col" class="text-end pe-3">Amount</th></tr>
                    </thead>
                    <tbody>
                        @include('tenantcore::reports.partials.head-rows', ['side' => 'received'])
                    </tbody>
                    <tfoot>
                        <tr class="fw-semibold"><th scope="row" class="ps-3">Total Income</th><td class="text-end text-nowrap pe-3">{{ $received }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </section>
        <section class="col-12 col-md-6" aria-labelledby="expense-heading">
            <div class="border rounded h-100">
                <h4 class="h6 text-danger bg-danger-subtle p-3 mb-0" id="expense-heading">Expense</h4>
                <table class="app-report-table table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col" class="ps-3">Expense Particular</th>
                            <th scope="col" class="text-end pe-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('tenantcore::reports.partials.head-rows', ['side' => 'paid'])
                    </tbody>
                    <tfoot>
                        <tr class="fw-semibold">
                            <th scope="row" class="ps-3">Total Expense</th>
                            <td class="text-end text-nowrap pe-3">{{ $paid }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </div>
    <div class="app-report-balance d-flex flex-wrap justify-content-between gap-2 bg-primary-subtle rounded p-3">
        <span class="fw-semibold">Balance <span class="small fw-normal">(Income − Expense)</span></span>
        <strong class="fs-5">{{ $net }}</strong>
    </div>
    <p class="small text-body-secondary mt-2 mb-0">Income and expense show received and paid amounts. Unpaid invoice amounts remain due.</p>
@endsection
