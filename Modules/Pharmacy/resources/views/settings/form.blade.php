<form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
    @csrf
    @method('PUT')
    <fieldset class="app-form-section border-0 p-0 mb-0" aria-labelledby="settings-pharmacy-heading" @disabled(! $canUpdate)>
        <h2 class="app-form-section-title" id="settings-pharmacy-heading"><span class="app-form-section-icon"><i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i></span>Pharmacy</h2>
        @error('data')<div class="invalid-feedback d-block mb-3">{{ $message }}</div>@enderror
        @error('section')<div class="invalid-feedback d-block mb-3">{{ $message }}</div>@enderror

        <input name="section" type="hidden" value="{{ $activePharmacySection }}">
        @if ($activePharmacySection === 'general')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="pharmacy-stock-method">Stock method</label>
                    <select class="{{ inputClass('data.stock_method', 'form-select') }}" id="pharmacy-stock-method" name="data[stock_method]" required>
                        <option value="fifo" @selected(getValue('data.stock_method', null, $values['data']['stock_method']) === 'fifo')>FIFO — oldest stock first</option>
                        <option value="lifo" @selected(getValue('data.stock_method', null, $values['data']['stock_method']) === 'lifo')>LIFO — newest stock first</option>
                    </select>
                    @error('data.stock_method')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="pharmacy-customer-scope">Sales customers</label>
                    <select class="{{ inputClass('data.customer_scope', 'form-select') }}" id="pharmacy-customer-scope" name="data[customer_scope]" required>
                        <option value="all" @selected(getValue('data.customer_scope', null, $values['data']['customer_scope']) === 'all')>All patients and customers</option>
                        <option value="customer" @selected(getValue('data.customer_scope', null, $values['data']['customer_scope']) === 'customer')>Customers only</option>
                    </select>
                    @error('data.customer_scope')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input type="hidden" name="data[medicine_sale_without_stock_enabled]" value="0">
                        <input class="{{ inputClass('data.medicine_sale_without_stock_enabled', 'form-check-input') }}" id="pharmacy-medicine-sale-without-stock" name="data[medicine_sale_without_stock_enabled]" type="checkbox" value="1" role="switch" {{ getChecked(1, 'data.medicine_sale_without_stock_enabled', null, $values['data']['medicine_sale_without_stock_enabled']) }} {{ inputAttrs('data.medicine_sale_without_stock_enabled', 'pharmacy-medicine-sale-without-stock') }}>
                        <label class="form-check-label" for="pharmacy-medicine-sale-without-stock">Medicine: allow sales without stock</label>
                    </div>
                    @error('data.medicine_sale_without_stock_enabled')<div class="invalid-feedback d-block" id="pharmacy-medicine-sale-without-stock-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input type="hidden" name="data[optic_sale_without_stock_enabled]" value="0">
                        <input class="{{ inputClass('data.optic_sale_without_stock_enabled', 'form-check-input') }}" id="pharmacy-optic-sale-without-stock" name="data[optic_sale_without_stock_enabled]" type="checkbox" value="1" role="switch" {{ getChecked(1, 'data.optic_sale_without_stock_enabled', null, $values['data']['optic_sale_without_stock_enabled']) }} {{ inputAttrs('data.optic_sale_without_stock_enabled', 'pharmacy-optic-sale-without-stock') }}>
                        <label class="form-check-label" for="pharmacy-optic-sale-without-stock">Optic: allow sales without stock</label>
                    </div>
                    @error('data.optic_sale_without_stock_enabled')<div class="invalid-feedback d-block" id="pharmacy-optic-sale-without-stock-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="pharmacy-sale-invoice-paper">Sales invoice paper</label>
                    <select class="{{ inputClass('data.sale_invoice_paper', 'form-select') }}" id="pharmacy-sale-invoice-paper" name="data[sale_invoice_paper]" required {{ inputAttrs('data.sale_invoice_paper', 'pharmacy-sale-invoice-paper') }}>
                        <option value="a4" @selected(getValue('data.sale_invoice_paper', null, $values['data']['sale_invoice_paper']) === 'a4')>A4</option>
                        <option value="a5" @selected(getValue('data.sale_invoice_paper', null, $values['data']['sale_invoice_paper']) === 'a5')>A5</option>
                        <option value="pos" @selected(getValue('data.sale_invoice_paper', null, $values['data']['sale_invoice_paper']) === 'pos')>POS</option>
                    </select>
                    @error('data.sale_invoice_paper')<div class="invalid-feedback d-block" id="pharmacy-sale-invoice-paper-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6" data-pharmacy-pos-width @if (getValue('data.sale_invoice_paper', null, $values['data']['sale_invoice_paper']) !== 'pos' && ! $errors->has('data.sale_pos_width_mm')) hidden @endif>
                    <label class="form-label" for="pharmacy-sale-pos-width">POS width (mm)</label>
                    <input class="{{ inputClass('data.sale_pos_width_mm') }}" id="pharmacy-sale-pos-width" name="data[sale_pos_width_mm]" type="number" inputmode="decimal" min="20" max="300" step="0.01" value="{{ getValue('data.sale_pos_width_mm', null, $values['data']['sale_pos_width_mm']) }}" {{ inputAttrs('data.sale_pos_width_mm', 'pharmacy-sale-pos-width') }}>
                    @error('data.sale_pos_width_mm')<div class="invalid-feedback d-block" id="pharmacy-sale-pos-width-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="hidden" name="data[vat_enabled]" value="0">
                        <input class="{{ inputClass('data.vat_enabled', 'form-check-input') }}" id="pharmacy-vat-enabled" name="data[vat_enabled]" type="checkbox" value="1" role="switch" {{ getChecked(1, 'data.vat_enabled', null, $values['data']['vat_enabled']) }}>
                        <label class="form-check-label" for="pharmacy-vat-enabled">Enable VAT</label>
                    </div>
                    @error('data.vat_enabled')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="pharmacy-vat-mode">VAT calculation</label>
                    <select class="{{ inputClass('data.vat_mode', 'form-select') }}" id="pharmacy-vat-mode" name="data[vat_mode]" required>
                        <option value="exclusive" @selected(getValue('data.vat_mode', null, $values['data']['vat_mode']) === 'exclusive')>Add to price</option>
                        <option value="inclusive" @selected(getValue('data.vat_mode', null, $values['data']['vat_mode']) === 'inclusive')>Included in price</option>
                    </select>
                    @error('data.vat_mode')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="pharmacy-vat-percent">VAT %</label>
                    <input class="{{ inputClass('data.vat_percent') }}" id="pharmacy-vat-percent" name="data[vat_percent]" type="number" min="0" max="100" step="0.01" value="{{ getValue('data.vat_percent', null, $values['data']['vat_percent']) }}" required>
                    @error('data.vat_percent')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>
        @elseif ($activePharmacySection === 'numbering')
            @php
                $patternLabels = [
                    'purchase_invoice_pattern' => 'Purchase',
                    'sale_invoice_pattern' => 'Sale',
                    'purchase_return_invoice_pattern' => 'Purchase return',
                    'sale_return_invoice_pattern' => 'Sale return',
                    'payment_invoice_pattern' => 'Payment',
                    'adjustment_invoice_pattern' => 'Adjustment',
                    'damage_invoice_pattern' => 'Wastage',
                    'transfer_invoice_pattern' => 'Branch transfer',
                ];
            @endphp
            <p class="small text-body-secondary mb-2">Changes apply to new invoices.</p>
            <details class="small text-body-secondary mb-3">
                <summary>Pattern guide</summary>
                <p class="mt-2 mb-0" id="pharmacy-pattern-help">Use {YYYY}, {MM}, {DD}, {BRANCH}, {PRODUCT} (MED/OPT), and one {SEQ} or {SEQ:1} to {SEQ:12}. {BRANCH} is required. {SEQ} uses at least six digits. Counters continue when a pattern changes.</p>
            </details>
            <div class="row g-3">
                @foreach ($patternLabels as $field => $label)
                    <div class="col-md-6">
                        <label class="form-label" for="pharmacy-{{ $field }}">{{ $label }}</label>
                        <input class="{{ inputClass('data.'.$field) }}" id="pharmacy-{{ $field }}" name="data[{{ $field }}]" type="text" value="{{ getValue('data.'.$field, null, $values['data'][$field]) }}" maxlength="{{ $field === 'payment_invoice_pattern' ? 40 : 80 }}" required {{ inputAttrs('data.'.$field, 'pharmacy-'.$field, 'pharmacy-pattern-help') }}>
                        @error('data.'.$field)
                            <div class="invalid-feedback d-block" id="pharmacy-{{ $field }}-error">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>
        @elseif ($activePharmacySection === 'items')
            @php
                $itemColumns = ['Discount', 'VAT', 'Sale price', 'Expiry'];
                $itemRows = [
                    'Medicine Purchase' => ['medicine_purchase_discount_enabled', 'medicine_purchase_vat_enabled', 'medicine_purchase_sale_price_enabled', 'medicine_purchase_expiry_enabled'],
                    'Optic Purchase' => ['optic_purchase_discount_enabled', 'optic_purchase_vat_enabled', 'optic_purchase_sale_price_enabled', null],
                    'Medicine Sales' => ['medicine_sale_discount_enabled', null, null, null],
                    'Optic Sales' => ['optic_sale_discount_enabled', null, null, null],
                ];
            @endphp
            <p class="small text-body-secondary mb-2">Choose which item fields appear.</p>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Item field visibility</caption>
                    <thead>
                        <tr>
                            <th scope="col">Form</th>
                            @foreach ($itemColumns as $label)
                                <th class="text-center" scope="col">{{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemRows as $label => $fields)
                            <tr>
                                <th scope="row">{{ $label }}</th>
                                @foreach ($fields as $column => $field)
                                    <td class="text-center">
                                        @if ($field !== null)
                                            <div class="form-check form-switch d-inline-block mb-0">
                                                <input name="data[{{ $field }}]" type="hidden" value="0">
                                                <input class="{{ inputClass('data.'.$field, 'form-check-input') }}" id="pharmacy-{{ $field }}" name="data[{{ $field }}]" type="checkbox" value="1" role="switch" {{ getChecked(1, 'data.'.$field, null, $values['data'][$field]) }} {{ inputAttrs('data.'.$field, 'pharmacy-'.$field) }}>
                                                <label class="visually-hidden" for="pharmacy-{{ $field }}">{{ $label }} {{ $itemColumns[$column] }}</label>
                                            </div>
                                            @error('data.'.$field)
                                                <div class="invalid-feedback d-block" id="pharmacy-{{ $field }}-error">{{ $message }}</div>
                                            @enderror
                                        @else
                                            <span class="text-body-secondary" aria-label="Not available">&mdash;</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($activePharmacySection === 'sms')
            <p class="small text-body-secondary mb-2">Send SMS for new records only. Edits and reversals do not send another message. Platform access and SMS balance apply.</p>
            <details class="small text-body-secondary mb-3">
                <summary>Message placeholders</summary>
                <p class="mt-2 mb-1">Organization: {organization}, {organization_phone}, {organization_address}.</p>
                <p class="mb-1">{name}, {invoice}, {date}, {total}, {paid}, {due}, {amount}</p>
                <p class="mb-1">Customer SMS can also use {patient_name}, {patient_mobile}, {patient_address}, {patient_id}, {patient_age}, {father_husband_name}. Choose the patient details needed in your message.</p>
                <p class="mb-1">Sales returns also support {source_invoice}, {refund}, {due_adjustment}. Payments support {direction}.</p>
                <p class="mb-0">Return total is invoice credit; refund is cash returned. For payments, total includes cash and discount; amount and paid are cash. Payment due is the party's remaining balance in this branch.</p>
            </details>
            <div class="row g-3">
                @foreach (collect($smsNotifications)->groupBy('type') as $typeLabel => $notifications)
                    <section class="col-lg-6" aria-labelledby="pharmacy-sms-{{ strtolower($typeLabel) }}-heading">
                        <h3 class="h6 fw-semibold mb-3" id="pharmacy-sms-{{ strtolower($typeLabel) }}-heading">{{ $typeLabel }}</h3>
                        <div class="vstack gap-3">
                            @foreach ($notifications as $notification)
                                @php
                                    $enabledField = $notification['enabled'];
                                    $templateField = $notification['template'];
                                @endphp
                                <div class="border rounded p-3">
                                    <div class="form-check form-switch mb-2">
                                        <input name="data[{{ $enabledField }}]" type="hidden" value="0">
                                        <input class="{{ inputClass('data.'.$enabledField, 'form-check-input') }}" id="pharmacy-{{ $enabledField }}"
                                            name="data[{{ $enabledField }}]" type="checkbox" value="1" role="switch"
                                            {{ getChecked(1, 'data.'.$enabledField, null, $values['data'][$enabledField]) }}
                                            {{ inputAttrs('data.'.$enabledField, 'pharmacy-'.$enabledField) }}>
                                        <label class="form-check-label fw-medium" for="pharmacy-{{ $enabledField }}">{{ $notification['label'] }} SMS</label>
                                    </div>
                                    @error('data.'.$enabledField)
                                        <div class="invalid-feedback d-block mb-2" id="pharmacy-{{ $enabledField }}-error">{{ $message }}</div>
                                    @enderror
                                    <label class="form-label small mb-1" for="pharmacy-{{ $templateField }}">Message</label>
                                    <textarea class="{{ inputClass('data.'.$templateField) }}" id="pharmacy-{{ $templateField }}" name="data[{{ $templateField }}]" rows="2"
                                        {{ inputAttrs('data.'.$templateField, 'pharmacy-'.$templateField) }}>{{ getValue('data.'.$templateField, null, $values['data'][$templateField]) }}</textarea>
                                    @error('data.'.$templateField)
                                        <div class="invalid-feedback d-block" id="pharmacy-{{ $templateField }}-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        @endif
    </fieldset>
    @if ($canUpdate)
        <div class="d-flex justify-content-end border-top pt-2 mt-3">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check me-2" aria-hidden="true"></i>Save</button>
        </div>
    @else
        <p class="small text-body-secondary mb-0 mt-3">You have read-only access to these settings.</p>
    @endif
</form>
