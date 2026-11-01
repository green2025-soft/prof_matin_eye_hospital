<label class="form-label" for="payment_type_id">Payment type</label>
<select class="{{ inputClass('payment_type_id', 'form-select') }}" id="payment_type_id" name="payment_type_id" {{ inputAttrs('payment_type_id') }}>
    <option value="">Select payment type</option>
    @foreach ($paymentTypes as $paymentType)
        <option value="{{ $paymentType->id }}" @selected((string) getValue('payment_type_id', $document) === (string) $paymentType->id)>{{ $paymentType->name }}</option>
    @endforeach
</select>
@error('payment_type_id')
    <div class="invalid-feedback d-block" id="payment_type_id-error">{{ $message }}</div>
@enderror
