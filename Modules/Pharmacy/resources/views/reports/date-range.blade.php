<div class="col-sm-6 col-lg-2">
    <label class="form-label small" for="report_from">From date</label>
    <div class="input-group" data-datepicker>
        <input class="{{ inputClass('from_date') }}" id="report_from" name="from_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
            value="{{ getValue('from_date', null, $filters['from_date']) }}" required data-input
            {{ inputAttrs('from_date', 'report_from') }}>
        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="report_from" aria-label="Choose start date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
    </div>
    @error('from_date')<div class="invalid-feedback d-block" id="report_from-error">{{ $message }}</div>@enderror
</div>
<div class="col-sm-6 col-lg-2">
    <label class="form-label small" for="report_to">To date</label>
    <div class="input-group" data-datepicker>
        <input class="{{ inputClass('to_date') }}" id="report_to" name="to_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
            value="{{ getValue('to_date', null, $filters['to_date']) }}" required data-input
            {{ inputAttrs('to_date', 'report_to') }}>
        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="report_to" aria-label="Choose end date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
    </div>
    @error('to_date')<div class="invalid-feedback d-block" id="report_to-error">{{ $message }}</div>@enderror
</div>
