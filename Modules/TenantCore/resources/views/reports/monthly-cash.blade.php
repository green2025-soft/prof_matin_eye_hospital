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
        <div class="col-12 col-md-6 col-xl-3"><button class="btn btn-primary" type="submit">Show report</button></div>
    </form>
@endsection

@section('report-table')
    @include('tenantcore::reports.partials.day-table')
@endsection
