<label class="form-label" for="notes">Notes</label>
<textarea class="{{ inputClass('notes') }}" id="notes" name="notes" rows="2" maxlength="2000" {{ inputAttrs('notes') }}>{{ getValue('notes', $document) }}</textarea>
@error('notes')
    <div class="invalid-feedback d-block" id="notes-error">{{ $message }}</div>
@enderror
