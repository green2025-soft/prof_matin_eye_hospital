<form class="p-3" action="{{ url()->current() }}" method="GET">
    <div class="row g-3 align-items-end">
        <div class="col-sm-6 col-lg-3">
            <label class="form-label" for="report-month">Report month</label>
            <div class="input-group" data-datepicker data-format="Y-m" data-alt-format="F Y">
                <input class="{{ inputClass('month') }}" id="report-month" name="month" type="text"
                    value="{{ getValue('month', null, $filters['month']) }}" placeholder="YYYY-MM"
                    autocomplete="off" required data-input {{ inputAttrs('month', 'report-month') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose report month">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            @error('month')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-sm-6 col-lg-3">
            <label class="form-label" for="report-depth">Detail level</label>
            <select class="{{ inputClass('depth', 'form-select') }}" id="report-depth" name="depth" required>
                @foreach ([2 => 'Group', 3 => 'Subgroup', 4 => 'Account group', 5 => 'Ledger'] as $value => $label)
                    <option value="{{ $value }}" {{ getSelected($value, 'depth', null, $filters['depth']) }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('depth')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-sm-auto d-flex gap-2">
            <button class="btn btn-primary" type="submit">Filter</button>
            <a class="btn btn-outline-secondary" href="{{ url()->current() }}">Reset</a>
        </div>
    </div>
</form>
