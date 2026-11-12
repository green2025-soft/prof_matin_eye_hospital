@extends('tenantcore::reports.layout')

@section('report-filters')
    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="from_date">From date</label>
            <div class="input-group" data-datepicker><input class="{{ inputClass('from_date') }}" id="from_date" name="from_date" type="text" value="{{ getValue('from_date', null, $filters['from_date']) }}" placeholder="YYYY-MM-DD" autocomplete="off" required data-input {{ inputAttrs('from_date') }}><button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose from date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button></div>
            @error('from_date')<div class="invalid-feedback d-block" id="from_date-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <label class="form-label" for="to_date">To date</label>
            <div class="input-group" data-datepicker><input class="{{ inputClass('to_date') }}" id="to_date" name="to_date" type="text" value="{{ getValue('to_date', null, $filters['to_date']) }}" placeholder="YYYY-MM-DD" autocomplete="off" required data-input {{ inputAttrs('to_date') }}><button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose to date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button></div>
            @error('to_date')<div class="invalid-feedback d-block" id="to_date-error">{{ $message }}</div>@enderror
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
