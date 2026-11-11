<section class="app-form-section" aria-labelledby="pharmacy-payment-heading">
    <h2 class="app-form-section-title" id="pharmacy-payment-heading">
        <span class="app-form-section-icon"><i class="fa-solid fa-wallet" aria-hidden="true"></i></span>
        Payment details
    </h2>
    <div class="row g-2">
        <div class="col-6">
            <label class="form-label" for="discount">Invoice discount</label>
            <input class="{{ inputClass('discount') }}" id="discount" name="discount" type="number"
                inputmode="decimal" min="0" step="0.01" placeholder="0"
                value="{{ getValue('discount', null, $entry->exists ? moneyDecimal($entry->discount - $entry->lines->sum('discount')) : '') }}"
                {{ inputAttrs('discount') }}>
            @error('discount')
                <div class="invalid-feedback d-block" id="discount-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-6">
            <label class="form-label" for="paid_amount">Paid amount</label>
            <input class="{{ inputClass('paid_amount') }}" id="paid_amount" name="paid_amount" type="number"
                inputmode="decimal" min="0" step="0.01" placeholder="0"
                value="{{ getValue('paid_amount', null, $entry->exists ? moneyDecimal($entry->initial_paid) : '') }}"
                {{ inputAttrs('paid_amount') }}>
            @error('paid_amount')
                <div class="invalid-feedback d-block" id="paid_amount-error">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="border rounded bg-body-tertiary p-2 mt-3">
        <dl class="mb-0">
            <div class="d-flex justify-content-between gap-2 py-1">
                <dt class="small text-body-secondary fw-normal">Subtotal</dt>
                <dd class="mb-0 text-end" data-pharmacy-subtotal>0.00</dd>
            </div>
            <div class="d-flex justify-content-between gap-2 py-1">
                <dt class="small text-body-secondary fw-normal">Item discounts</dt>
                <dd class="mb-0 text-end" data-pharmacy-line-discount>0.00</dd>
            </div>
            <div class="d-flex justify-content-between gap-2 py-1">
                <dt class="small text-body-secondary fw-normal">Invoice discount</dt>
                <dd class="mb-0 text-end" data-pharmacy-invoice-discount>0.00</dd>
            </div>
            <div class="d-flex justify-content-between gap-2 py-1">
                <dt class="small text-body-secondary fw-normal">Item VAT total</dt>
                <dd class="mb-0 text-end" data-pharmacy-vat>0.00</dd>
            </div>
            <div class="d-flex justify-content-between gap-2 border-top pt-2 mt-1 fw-bold">
                <dt class="small fw-semibold">Payable</dt>
                <dd class="mb-0 text-end" data-pharmacy-total>0.00</dd>
            </div>
            <div class="d-flex justify-content-between gap-2 py-1">
                <dt class="small text-body-secondary fw-normal">Paid</dt>
                <dd class="mb-0 text-end" data-pharmacy-paid>0.00</dd>
            </div>
            <div class="d-flex justify-content-between gap-2 bg-primary-subtle text-primary rounded px-2 py-1 mt-1 fw-semibold">
                <dt class="small fw-semibold">Due</dt>
                <dd class="mb-0 text-end" data-pharmacy-due>0.00</dd>
            </div>
        </dl>
    </div>
    <div class="mt-3">
        <label class="form-label" for="notes">Notes</label>
        <textarea class="{{ inputClass('notes') }}" id="notes" name="notes" rows="2" maxlength="2000"
            {{ inputAttrs('notes') }}>{{ getValue('notes', $entry) }}</textarea>
        @error('notes')
            <div class="invalid-feedback d-block" id="notes-error">{{ $message }}</div>
        @enderror
    </div>
</section>
