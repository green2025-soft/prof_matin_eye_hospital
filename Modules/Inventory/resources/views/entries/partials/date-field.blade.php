<label class="form-label" for="document_date">Date <span class="text-danger" aria-hidden="true">*</span></label>
<div class="input-group" data-datepicker>
    <input class="{{ inputClass('document_date') }}" id="document_date" name="document_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('document_date', null, $document->exists ? $document->document_date->toDateString() : now()->toDateString()) }}" required data-input {{ inputAttrs('document_date') }}>
    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="document_date" aria-label="Choose entry date" title="Choose entry date">
        <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
    </button>
</div>
@error('document_date')
    <div class="invalid-feedback d-block" id="document_date-error">{{ $message }}</div>
@enderror
