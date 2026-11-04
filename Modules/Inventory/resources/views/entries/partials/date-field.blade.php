<label class="form-label" for="entry_date">Date <span class="text-danger" aria-hidden="true">*</span></label>
<div class="input-group" data-datepicker>
    <input class="{{ inputClass('entry_date') }}" id="entry_date" name="entry_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('entry_date', null, $entry->exists ? $entry->entry_date->toDateString() : now()->toDateString()) }}" required data-input {{ inputAttrs('entry_date') }}>
    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="entry_date" aria-label="Choose entry date" title="Choose entry date">
        <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
    </button>
</div>
@error('entry_date')
    <div class="invalid-feedback d-block" id="entry_date-error">{{ $message }}</div>
@enderror
