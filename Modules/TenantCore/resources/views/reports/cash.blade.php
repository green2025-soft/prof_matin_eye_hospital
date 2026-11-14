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
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('to_date') }}" id="to_date" name="to_date" type="text" value="{{ getValue('to_date', null, $filters['to_date']) }}" placeholder="YYYY-MM-DD" autocomplete="off" required data-input {{ inputAttrs('to_date') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose to date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
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

@section('report-summary')
    @include('tenantcore::reports.partials.period-totals')
@endsection

@section('report-table')
    @php
        $entryGroups = collect($entries)
            ->sortBy('date')
            ->groupBy(fn ($entry) => $entry['module'].'|'.$entry['label']);
    @endphp

    @forelse ($entryGroups as $key => $headEntries)
        @php
            $head = $headEntries->first();
            $side = $headCatalog[$head['module']][$head['label']];
            $subtotal = $headTotals[$key][$side] ?? 0;
        @endphp

        <div class="table-responsive mb-4">
            <table class="app-report-table table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="colgroup" colspan="4" class="bg-body-tertiary">
                            {{ $head['label'] }} <span class="small fw-normal text-body-secondary">{{ ucfirst($head['module']) }}</span>
                        </th>
                    </tr>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Particular</th>
                        <th scope="col">Details</th>
                        <th scope="col" class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($headEntries as $entry)
                        <tr>
                            <td class="text-nowrap">{{ $entry['date'] }}</td>
                            <td>{{ $entry['reference'] }}</td>
                            <td>{{ $entry['description'] }}</td>
                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $entry[$side]) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-semibold">
                        <th scope="row" colspan="3">Total {{ $head['label'] }}</th>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $subtotal) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @empty
        <p class="text-center text-body-secondary py-4">No transactions in this period.</p>
    @endforelse
@endsection
