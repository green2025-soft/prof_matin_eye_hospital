@extends('tenantcore::reports.layout')

@section('report-filters')
    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label" for="date">Date</label>
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('date') }}" id="date" name="date" type="text" value="{{ getValue('date', null, $filters['date']) }}" placeholder="YYYY-MM-DD" autocomplete="off" required data-input {{ inputAttrs('date') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
            </div>
            @error('date')<div class="invalid-feedback d-block" id="date-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-md-4">
            <label class="form-label" for="module">Module</label>
            <select class="form-select" id="module" name="module">
                @foreach (['all' => 'All modules', 'general' => 'General income / expense', 'hospital' => 'Hospital', 'medicine' => 'Medicine', 'optic' => 'Optic'] as $value => $label)
                    <option value="{{ $value }}" @selected($filters['module'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-4"><button class="btn btn-primary" type="submit">Show report</button></div>
    </form>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table app-table table-sm align-middle">
            <thead><tr><th scope="col">Category</th><th class="text-end" scope="col">Received</th><th class="text-end" scope="col">Paid</th><th class="text-end" scope="col">Receivable change</th><th class="text-end" scope="col">Payable change</th></tr></thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr><th scope="row">{{ $row['label'] }}</th><td class="text-end">{{ $row['received'] }}</td><td class="text-end">{{ $row['paid'] }}</td><td class="text-end">{{ $row['receivable'] }}</td><td class="text-end">{{ $row['payable'] }}</td></tr>
                @empty
                    <tr><td class="text-center text-body-secondary py-4" colspan="5">No activity on this date.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
