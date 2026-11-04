<section class="app-form-section mt-4" aria-labelledby="inventory-payment-heading">
    <h2 class="app-form-section-title" id="inventory-payment-heading">
        <span class="app-form-section-icon"><i class="fa-solid fa-wallet" aria-hidden="true"></i></span>
        Payment details
    </h2>
    <div class="row g-3 g-xl-4">
        <div class="col-lg-7">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label" for="discount">Invoice discount</label>
                    <input class="{{ inputClass('discount') }}" id="discount" name="discount" type="number" inputmode="decimal" min="0" step="0.01" placeholder="0" value="{{ getValue('discount', null, $entry->exists ? moneyDecimal($entry->discount - $entry->lines->sum('discount')) : '') }}" {{ inputAttrs('discount') }}>
                    @error('discount')
                        <div class="invalid-feedback d-block" id="discount-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="paid_amount">Paid amount</label>
                    <input class="{{ inputClass('paid_amount') }}" id="paid_amount" name="paid_amount" type="number" inputmode="decimal" min="0" step="0.01" placeholder="0" value="{{ getValue('paid_amount', null, $entry->exists ? moneyDecimal($entry->initial_paid) : '') }}" {{ inputAttrs('paid_amount') }}>
                    @error('paid_amount')
                        <div class="invalid-feedback d-block" id="paid_amount-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="notes">Notes</label>
                    <textarea class="{{ inputClass('notes') }}" id="notes" name="notes" rows="2" maxlength="2000" {{ inputAttrs('notes') }}>{{ getValue('notes', $entry) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback d-block" id="notes-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="app-inventory-entry-summary">
                <dl class="mb-0">
                    <div>
                        <dt>Subtotal</dt>
                        <dd data-inventory-subtotal>0.00</dd>
                    </div>
                    <div>
                        <dt>Item discounts</dt>
                        <dd data-inventory-line-discount>0.00</dd>
                    </div>
                    <div>
                        <dt>Invoice discount</dt>
                        <dd data-inventory-invoice-discount>0.00</dd>
                    </div>
                    <div>
                        <dt>Item VAT total</dt>
                        <dd data-inventory-vat>0.00</dd>
                    </div>
                    <div class="app-inventory-entry-payable">
                        <dt>Payable</dt>
                        <dd data-inventory-total>0.00</dd>
                    </div>
                    <div>
                        <dt>Paid</dt>
                        <dd data-inventory-paid>0.00</dd>
                    </div>
                    <div class="app-inventory-entry-due">
                        <dt>Due</dt>
                        <dd data-inventory-due>0.00</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</section>
