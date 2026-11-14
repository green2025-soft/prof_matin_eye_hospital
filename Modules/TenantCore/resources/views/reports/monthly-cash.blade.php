@extends('tenantcore::reports.layout')

@section('report-filters')
    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="year">Year</label>
            <select class="{{ inputClass('year', 'form-select') }}" id="year" name="year" {{ inputAttrs('year') }}>
                @for ($year = 2000; $year <= 2100; $year++)
                    <option value="{{ $year }}" @selected((int) $filters['year'] === $year)>{{ $year }}</option>
                @endfor
            </select>
            @error('year')<div class="invalid-feedback d-block" id="year-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="month">Month</label>
            <select class="{{ inputClass('month', 'form-select') }}" id="month" name="month" {{ inputAttrs('month') }}>
                @foreach ([1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $number => $name)
                    <option value="{{ $number }}" @selected((int) $filters['month'] === $number)>{{ $name }}</option>
                @endforeach
            </select>
            @error('month')<div class="invalid-feedback d-block" id="month-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="module">Module</label>
            <select class="form-select" id="module" name="module">
                @foreach (['all' => 'All modules', 'general' => 'General income / expense', 'hospital' => 'Hospital', 'medicine' => 'Medicine', 'optic' => 'Optic'] as $value => $label)
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
    @include('tenantcore::reports.partials.period-totals')
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="app-report-table table table-sm align-middle mb-0">
            <caption class="visually-hidden">Daily income, expense and net balance for the selected month</caption>
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col" class="text-end">Income</th>
                    <th scope="col" class="text-end">Expense</th>
                    <th scope="col" class="text-end">Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach (\Carbon\CarbonPeriodImmutable::create($fromDate, $toDate) as $date)
                    @php
                        $day = $days[$date->toDateString()] ?? [];
                    @endphp
                    <tr>
                        <th scope="row" class="text-nowrap">{{ $date->toDateString() }}</th>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) ($day['received'] ?? 0)) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) ($day['paid'] ?? 0)) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) ($day['net'] ?? 0)) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-semibold">
                    <th scope="row">Total</th>
                    <td class="text-end text-nowrap">{{ $received }}</td>
                    <td class="text-end text-nowrap">{{ $paid }}</td>
                    <td class="text-end text-nowrap">{{ $net }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <p class="small text-body-secondary mt-2">Each date's Balance = that day's Income − Expense.</p>
@endsection
