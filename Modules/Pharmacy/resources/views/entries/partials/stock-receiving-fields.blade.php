<div class="col-6 col-lg-3">
    <label class="form-label small" for="line_{{ $lineIndex }}_batch">Batch no.</label>
    <input class="{{ inputClass('lines.'.$lineIndex.'.batch_no', 'form-control') }}" id="line_{{ $lineIndex }}_batch" name="lines[{{ $lineIndex }}][batch_no]" value="{{ $lineValues['batch_no'] ?? '' }}" maxlength="80" {{ inputAttrs('lines.'.$lineIndex.'.batch_no', 'line_'.$lineIndex.'_batch') }}>
    @error('lines.'.$lineIndex.'.batch_no')
        <div class="invalid-feedback d-block" id="line_{{ $lineIndex }}_batch-error">
            {{ $message }}
        </div>
    @enderror
</div>
<div class="col-6 col-lg-3">
    <label class="form-label small" for="line_{{ $lineIndex }}_expiry">Expiry date</label>
    <div class="input-group" data-datepicker>
        <input class="{{ inputClass('lines.'.$lineIndex.'.expiry_date', 'form-control') }}" id="line_{{ $lineIndex }}_expiry" name="lines[{{ $lineIndex }}][expiry_date]" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ $lineValues['expiry_date'] ?? '' }}" data-input {{ inputAttrs('lines.'.$lineIndex.'.expiry_date', 'line_'.$lineIndex.'_expiry') }}>
        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="line_{{ $lineIndex }}_expiry" aria-label="Choose expiry date" title="Choose expiry date">
            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
        </button>
    </div>
    @error('lines.'.$lineIndex.'.expiry_date')
        <div class="invalid-feedback d-block" id="line_{{ $lineIndex }}_expiry-error">
            {{ $message }}
        </div>
    @enderror
</div>
