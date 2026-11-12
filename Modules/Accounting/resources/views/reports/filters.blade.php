<form class="p-3" action="{{ url()->current() }}" method="GET">
    <div class="row g-3 align-items-end">
        @yield('report-account-filter')
        <div class="col-sm-6 col-lg-3">
            <label class="form-label" for="from_date">From date</label>
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('from_date') }}" id="from_date" name="from_date" type="text"
                    value="{{ getValue('from_date', null, $filters['from_date']) }}" placeholder="YYYY-MM-DD"
                    autocomplete="off" required data-input {{ inputAttrs('from_date') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose from date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            @error('from_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-sm-6 col-lg-3">
            <label class="form-label" for="to_date">To date</label>
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('to_date') }}" id="to_date" name="to_date" type="text"
                    value="{{ getValue('to_date', null, $filters['to_date']) }}" placeholder="YYYY-MM-DD"
                    autocomplete="off" required data-input {{ inputAttrs('to_date') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose to date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            @error('to_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        @yield('report-extra-filters')
        <div class="col-sm-auto d-flex gap-2">
            <button class="btn btn-primary" type="submit">Filter</button>
            <a class="btn btn-outline-secondary" href="{{ url()->current() }}">Reset</a>
        </div>
    </div>
</form>
