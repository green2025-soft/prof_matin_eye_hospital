import $ from './jquery';
import { formatAmount, parseAmount, roundRatio } from './money';

export const pharmacyQuantityFormatter = new Intl.NumberFormat('en-BD');

// Keep invoice arithmetic in integer minor units, including VAT rounding.
export function pharmacyMinor(value) {
    return parseAmount(value) ?? 0n;
}

// Quantities and stored minor values are whole numbers with the existing 19-digit limit.
export function pharmacyInteger(value) {
    return /^\d{1,19}$/.test(String(value ?? '')) ? BigInt(value) : 0n;
}

// Blank optional amounts are valid; visible input validation keeps its untrimmed grammar.
function tradeAmountError(input) {
    const $input = $(input);
    const value = $input.val();
    if (!$input.length || (value === '' && !$input.prop('required'))) return '';
    return /^\d{1,10}(?:\.\d{1,2})?$/.test(value)
        ? '' : (value === '' ? 'Enter an amount.' : 'Enter a valid amount.');
}

// Keep browser validity and inline feedback in sync after the field is touched.
export function setTradeValidity(input, message) {
    const $input = $(input);
    input.setCustomValidity(message);
    $input.data('validationMessage', message);
    if ($input.data('touched')) {
        $input.toggleClass('is-invalid', Boolean(message));
        if (message) $input.attr('aria-invalid', 'true');
        else $input.removeAttr('aria-invalid');
    }
}

// Validate the entered quantity, rate, discount and selected pack without changing their values.
function validateTradeLine($row, values, quantityError) {
    const { subtotal: amount, discountMode, rawDiscount, enteredDiscount: discount } = values;
    const $quantity = $row.find('[name$="[quantity]"]');
    const $price = $row.find('[name$="[unit_price]"]');
    const $discount = $row.find('[name$="[discount]"]');
    if (discountMode === 'percent') $discount.attr('max', '100');
    else $discount.removeAttr('max');
    let priceError = tradeAmountError($price[0]);
    const discountError = tradeAmountError($discount[0])
        || (discountMode === 'percent' && rawDiscount > 10000n ? 'Discount cannot exceed 100%.' : '')
        || (discount > amount ? 'Discount exceeds the item amount.' : '');
    const hiddenDiscount = $discount.closest('[data-pharmacy-hidden-item-field]').length > 0;
    if (hiddenDiscount && discountError && !priceError) {
        priceError = 'Saved discount exceeds this item amount. Adjust Qty or Rate.';
    }
    setTradeValidity($quantity[0], quantityError);
    setTradeValidity($price[0], priceError);
    setTradeValidity($discount[0], hiddenDiscount ? '' : discountError);
}

function positiveQuantityError($input, quantity) {
    return /^\d+$/.test($input.val()) && quantity > 0n ? '' : 'Enter a positive whole quantity.';
}

// A purchase owns its optional sale price and editable pack-size validation.
function validatePurchaseLine($row, values) {
    const $quantity = $row.find('[name$="[quantity]"]');
    const quantityError = positiveQuantityError($quantity, values.quantity);
    validateTradeLine($row, values, quantityError);
    const $salePrice = $row.find('input[name$="[sale_price_per_piece]"]');
    if ($salePrice.length) setTradeValidity($salePrice[0], tradeAmountError($salePrice[0]));
    const $factor = $row.find('input[name$="[unit_factor]"]');
    const $unit = $row.find('[data-pharmacy-purchase-unit]');
    if ($factor.length && $unit.length) {
        const factor = pharmacyInteger($factor.val());
        const factorError = /^\d+$/.test($factor.val()) && factor > 0n && factor <= 1000000n
            ? '' : 'Choose a pack size.';
        setTradeValidity($unit[0], factorError);
    }
}

// A sale checks the chosen quantity against stock unless its setting permits a shortage.
function validateSaleLine($row, values, options) {
    const $quantity = $row.find('[name$="[quantity]"]');
    let quantityError = positiveQuantityError($quantity, values.quantity);
    if (!quantityError && !options.allowWithoutStock) {
        const factor = pharmacyInteger($row.find('select[name$="[product_unit_id]"] option:selected').attr('data-factor'));
        const available = pharmacyInteger($row.find('[data-pharmacy-product-stock]').val());
        if (factor > 0n && values.quantity * factor > available) quantityError = 'Quantity exceeds the available stock.';
    }
    validateTradeLine($row, values, quantityError);
}

// Read one item's amounts once, then prepare its summary and discount-allocation state.
function readEntryLine(row, vatMode) {
    const $row = $(row);
    const $quantity = $row.find('[name$="[quantity]"]');
    const $price = $row.find('[name$="[unit_price]"]');
    const $discount = $row.find('[name$="[discount]"]');
    const quantity = pharmacyInteger($quantity.val());
    const unitPrice = pharmacyMinor($price.val());
    const amount = quantity * unitPrice;
    const discountMode = $row.find('select[name$="[discount_input]"]').val() || 'amount';
    const rawDiscount = pharmacyMinor($discount.val());
    const discount = discountMode === 'unit' ? rawDiscount * quantity
        : (discountMode === 'percent' ? roundRatio(amount * rawDiscount, 10000n) : rawDiscount);
    const $selectedUnit = $row.find('[data-pharmacy-purchase-unit] option:selected, select[name$="[product_unit_id]"] option:selected');
    const unitName = $selectedUnit.attr('data-unit-name') || $selectedUnit.text() || $row.attr('data-pharmacy-fixed-unit') || '';
    $row.data('pharmacySummary', {
        quantity: `${pharmacyQuantityFormatter.format(quantity)} ${unitName}`,
        rate: formatAmount(unitPrice),
        discount: formatAmount(discount),
    });
    return {
        row, quantity, subtotal: amount, discountMode, rawDiscount, enteredDiscount: discount,
        discount: discount > amount ? amount : discount,
        net: amount > discount ? amount - discount : 0n,
        vatMode,
    };
}

function readPurchaseLine(row) {
    const $row = $(row);
    const state = readEntryLine(row, $row.find('select[name$="[vat_mode]"]').val() || 'none');
    const $baseQuantity = $row.find('[data-pharmacy-base-quantity]');
    if ($baseQuantity.length) {
        const factor = pharmacyInteger($row.find('input[name$="[unit_factor]"]').val());
        $baseQuantity.text(state.quantity > 0n && factor > 0n
            ? pharmacyQuantityFormatter.format(state.quantity * factor) : '—');
    }
    const summary = $row.data('pharmacySummary');
    const salePrice = $row.find('input[name$="[sale_price_per_piece]"]').val();
    summary['sale-price'] = salePrice ? formatAmount(pharmacyMinor(salePrice)) : '\u2014';
    summary.expiry = $row.find('[name$="[expiry_date]"]').val() || '\u2014';
    validatePurchaseLine($row, state);
    return state;
}

function readSaleLine(row, vatMode, options) {
    const state = readEntryLine(row, vatMode);
    validateSaleLine($(row), state, options);
    return state;
}

// Allocate invoice discounts cumulatively so item rounding preserves the exact total.
function applyInvoiceDiscount(states, beforeDiscount, discount) {
    const allowedDiscount = discount > beforeDiscount ? beforeDiscount : discount;
    let cumulative = 0n;
    let allocated = 0n;
    for (const state of states) {
        cumulative += state.net;
        const target = beforeDiscount > 0n ? allowedDiscount * cumulative / beforeDiscount : 0n;
        state.weight = state.net - (target - allocated);
        allocated = target;
    }
    return beforeDiscount - allowedDiscount;
}

// Purchase VAT can be a percentage, a whole-line amount or an amount per selected unit.
function calculateLineVat($row, vatMode, weight) {
    const $rate = $row.find('input[name$="[vat_percent]"]');
    const $amount = $row.find('input[name$="[vat_amount]"]');
    const $source = $row.find('select[name$="[vat_input]"]');
    const vatInput = $source.val();
    const noVat = vatMode === 'none';
    const amountMode = vatInput === 'amount' || vatInput === 'unit';
    $source.prop('disabled', noVat);
    $rate.prop({ hidden: amountMode, disabled: noVat || amountMode });
    $amount.prop({ hidden: !amountMode, disabled: noVat || !amountMode, required: !noVat && amountMode });
    let tax = 0n;
    let rateError = '';
    let amountError = '';
    if (!noVat) {
        const rate = pharmacyMinor($rate.val());
        if (vatInput === 'unit') {
            tax = pharmacyMinor($amount.val()) * pharmacyInteger($row.find('[name$="[quantity]"]').val());
        } else if (vatInput === 'amount') tax = pharmacyMinor($amount.val());
        else tax = roundRatio(weight * rate, vatMode === 'inclusive' ? 10000n + rate : 10000n);
        const includedError = vatMode === 'inclusive' && tax > weight ? 'VAT exceeds the item amount.' : '';
        if (amountMode) amountError = tradeAmountError($amount[0]) || includedError;
        else rateError = tradeAmountError($rate[0]) || (rate > 10000n ? 'VAT percentage cannot exceed 100.' : '') || includedError;
    }
    const hiddenVat = $source.closest('[data-pharmacy-hidden-item-field]').length > 0;
    setTradeValidity($rate[0], hiddenVat ? '' : rateError);
    setTradeValidity($amount[0], hiddenVat ? '' : amountError);
    if (hiddenVat && (rateError || amountError)) {
        const $price = $row.find('[name$="[unit_price]"]');
        setTradeValidity($price[0], $price.data('validationMessage')
            || 'Saved VAT exceeds this item amount. Adjust Qty or Rate.');
    }
    $row.find('[data-pharmacy-line-vat-total]').text(formatAmount(tax));
    return tax;
}

// Keep allocated invoice discounts in the invoice total, not in displayed item amounts.
function applyLineAmount(state, tax) {
    const $row = $(state.row);
    const addedTax = state.vatMode === 'exclusive' ? tax : 0n;
    const amount = state.net + addedTax;
    const summary = $row.data('pharmacySummary');
    summary.vat = `${formatAmount(tax)}${state.vatMode === 'inclusive' ? ' \u00b7 Included' : (state.vatMode === 'exclusive' ? ' \u00b7 Added' : '')}`;
    summary.amount = formatAmount(amount);
    $row.find('[data-pharmacy-row-amount]').text(formatAmount(amount));
    return addedTax;
}

// Purchase items each keep their own VAT mode and entered VAT amount.
function applyPurchaseVat(states) {
    let tax = 0n;
    let addedTax = 0n;
    for (const state of states) {
        const rowTax = calculateLineVat($(state.row), state.vatMode, state.weight);
        tax += rowTax;
        addedTax += applyLineAmount(state, rowTax);
    }
    return { tax, addedTax };
}

// Header VAT uses cumulative allocation so the last item retains any rounding remainder.
function applyInvoiceVat(states, net, vatMode, percent) {
    const tax = vatMode !== 'none'
        ? roundRatio(net * percent, vatMode === 'inclusive' ? 10000n + percent : 10000n) : 0n;
    let addedTax = 0n;
    let cumulative = 0n;
    let allocated = 0n;
    for (const state of states) {
        cumulative += state.weight;
        const target = net > 0n ? tax * cumulative / net : 0n;
        addedTax += applyLineAmount(state, target - allocated);
        allocated = target;
    }
    return { tax, addedTax };
}

// Header discount and payment must stay within the calculated invoice amount.
function validateTradeEntry($form, amounts) {
    const { discount, beforeDiscount, paid, total, vatMode, percent } = amounts;
    const $discount = $form.find('[name="discount"]');
    const $paid = $form.find('[name="paid_amount"]');
    const $headerPercent = $form.find('[data-pharmacy-invoice-vat-percent]');
    const discountError = tradeAmountError($discount[0]) || (discount > beforeDiscount ? 'Invoice discount exceeds the remaining amount.' : '');
    const paidError = tradeAmountError($paid[0]) || (paid > total ? 'Paid amount cannot exceed the payable amount.' : '');
    if ($headerPercent.length) {
        $headerPercent.prop('disabled', vatMode === 'none');
        const taxError = vatMode === 'none' ? '' : tradeAmountError($headerPercent[0]) || (percent > 10000n ? 'VAT percentage cannot exceed 100.' : '');
        setTradeValidity($headerPercent[0], taxError);
    }
    setTradeValidity($discount[0], discountError);
    setTradeValidity($paid[0], paidError);
    $form.find('[name="payment_type_id"]').prop('required', paid > 0n);
}

// Recalculate the displayed invoice totals from the current item order.
function orderedTradeRows($form) {
    const rows = $form.find('[data-pharmacy-line]').get();
    const order = new Map($form.find('[data-pharmacy-item-list]').children('tr').map(function (index) {
        return [[ $(this).data('pharmacyLineIndex'), index ]];
    }).get());
    return rows.sort((left, right) => (order.get($(left).data('pharmacyLineIndex')) ?? -1) - (order.get($(right).data('pharmacyLineIndex')) ?? -1));
}

// The owners provide their row and VAT operations; the invoice arithmetic is identical.
function calculateEntryTotal($form, rows, readLine, applyVat) {
    const options = $form.data('pharmacyOptions');
    const $modeInput = $form.find('[data-pharmacy-vat-mode]');
    const vatMode = $modeInput.length ? $modeInput.val() : options.vatMode;
    const states = [];
    let subtotal = 0n;
    let lineDiscount = 0n;
    for (const row of rows) {
        const state = readLine(row, vatMode);
        subtotal += state.subtotal;
        lineDiscount += state.discount;
        states.push(state);
    }
    const discount = pharmacyMinor($form.find('[name="discount"]').val());
    const beforeDiscount = subtotal - lineDiscount;
    const net = applyInvoiceDiscount(states, beforeDiscount, discount);
    const percent = pharmacyMinor($form.find('[data-pharmacy-invoice-vat-percent]').val() ?? options.vatPercent);
    const { tax, addedTax } = applyVat(states, net, vatMode, percent);
    const total = net + addedTax;
    const paid = pharmacyMinor($form.find('[name="paid_amount"]').val());
    const due = total > paid ? total - paid : 0n;
    const amounts = { subtotal, 'line-discount': lineDiscount, 'invoice-discount': discount, vat: tax, total, paid, due };
    for (const [field, value] of Object.entries(amounts)) {
        $form.find(`[data-pharmacy-${field}]`).text(formatAmount(value));
    }
    $form.find('[data-pharmacy-line-count]').text(rows.length);
    return { discount, beforeDiscount, paid, total, vatMode, percent };
}

export function refreshPurchaseTotal(form) {
    const $form = $(form);
    const amounts = calculateEntryTotal($form, orderedTradeRows($form), readPurchaseLine, applyPurchaseVat);
    validateTradeEntry($form, amounts);
    return amounts.total;
}

export function refreshSaleTotal(form) {
    const $form = $(form);
    const options = $form.data('pharmacyOptions');
    const readLine = (row, vatMode) => readSaleLine(row, vatMode, options);
    const amounts = calculateEntryTotal($form, orderedTradeRows($form), readLine, applyInvoiceVat);
    validateTradeEntry($form, amounts);
    return amounts.total;
}

export function initializePaidAmountAutoFill(form) {
    const $form = $(form);
    const $paid = $form.find('[name="paid_amount"]');
    $form.data('pharmacyPaidAutoFill', String($paid.val() ?? '').trim() === '');
    $form.on('input.pharmacyPaid change.pharmacyPaid', '[name="paid_amount"]', () => {
        $form.data('pharmacyPaidAutoFill', false);
    });
}

export function updatePaidAmountFromCommittedTotal(form, total) {
    const $form = $(form);
    if (!$form.data('pharmacyPaidAutoFill')) return;

    const $paid = $form.find('[name="paid_amount"]');
    $paid.val(total > 0n ? formatAmount(total) : '');
    setTradeValidity($paid[0], '');
    $form.find('[name="payment_type_id"]').prop('required', total > 0n);
}

function refreshStockTotal(form) {
    const $form = $(form);
    if (!$form.find('[data-pharmacy-total]').length) return;
    const rows = $form.find('[data-pharmacy-line]').filter(function () {
        return !$(this).find('fieldset').prop('disabled');
    }).get();
    calculateEntryTotal($form, rows, readEntryLine, applyInvoiceVat);
}

// Receipt dates and source-lot controls follow the stock entry's existing direction.
function refreshOpeningFields(form) {
    const $form = $(form);
    $form.find('[name$="[expiry_date]"]').prop('required', $form.find('[name="product_type"]').val() === 'medicine');
}

function refreshAdjustmentFields(form) {
    const $form = $(form);
    const medicine = $form.find('[name="product_type"]').val() === 'medicine';
    $form.find('[data-pharmacy-line]').each(function () {
        const $row = $(this);
        const decrease = $row.find('[name$="[direction]"]').val() === 'decrease';
        $row.find('[name$="[expiry_date]"]').prop('required', medicine && !decrease);
        $row.find('[data-pharmacy-lot]').prop('disabled', !decrease || !$row.find('select[name$="[variant_id]"]').val());
    });
}

// Apply the selected product's catalog prices and units to its existing editor fields.
export function applyProductData($row, data) {
    const product = {
        id: String(data.product_id || ''),
        purchasePrice: data.purchase_price || '0',
        lastPurchaseRate: data.last_purchase_rate ?? null,
        lastPurchaseFactor: data.last_purchase_factor ?? null,
        salePrice: data.sale_price || '0',
    };
    $row.data('pharmacyProduct', product);
    return product;
}

// Catalog units share their Pcs-first selection; each owner applies its own price afterwards.
export function populateProductUnits($row, units) {
    const $unit = $row.find('select[name$="[product_unit_id]"]');
    if (!$unit.length) return;
    $unit.empty().append($('<option>', { text: 'Select unit', value: '' }));
    for (const choice of units || []) {
        $unit.append($('<option>', {
            text: `${choice.name} \u00d7 ${choice.factor}`, value: choice.id,
            'data-factor': choice.factor, 'data-unit-name': choice.name, 'data-symbol': choice.symbol,
        }));
    }
    const $base = $unit.find('option[data-factor="1"]');
    const $piece = $base.filter(function () {
        return $(this).attr('data-symbol')?.trim().toLowerCase() === 'pcs';
    });
    $unit.val($piece.first().val() || $base.first().val() || '');
}

function selectStockVariant(row, data) {
    const $row = $(row);
    applyProductData($row, data);
    populateProductUnits($row, data.units);
    setStockPrice(row);
}

// Unit conversion is identical; callers provide the actual price and total operation.
export function setUnitPrice(row, basePrice, refreshTotal) {
    const $row = $(row);
    const $unit = $row.find('select[name$="[product_unit_id]"]');
    const $price = $row.find('[name$="[unit_price]"]');

    if (!$unit.length || !$price.length) return;
    const factor = pharmacyInteger($unit.find('option:selected').attr('data-factor') || 1);
    const minor = pharmacyMinor(basePrice);
    $price.val(minor > 0n ? formatAmount(minor * factor) : '');
    refreshTotal($row.closest('form')[0]);
}

function setStockPrice(row) {
    setUnitPrice(row, $(row).data('pharmacyProduct').purchasePrice, refreshStockTotal);
}

// Show the remaining pieces and original price for the selected return unit.
function refreshReturnUnit(row) {
    const $row = $(row);
    const $unit = $row.find('[data-pharmacy-return-unit]');
    if (!$unit.length) return;
    const factor = pharmacyInteger($unit.find('option:selected').attr('data-factor') || 1);
    const remaining = pharmacyInteger($row.attr('data-return-remaining'));
    $row.find('[name$="[quantity]"]').attr('max', String(factor > 0n ? remaining / factor : 0n));
    $row.find('[data-pharmacy-return-remaining]').text(pharmacyQuantityFormatter.format(remaining));
    const price = formatAmount(roundRatio(pharmacyMinor($row.attr('data-original-price')) * factor, pharmacyInteger($row.attr('data-original-factor'))));
    $row.find('[data-pharmacy-return-rate-input]').val(price);
    $row.find('[data-pharmacy-return-rate]').text(price);
}

// Return quantities determine both the due reduction and any refundable cash.
function initializeReturn(form) {
    const $form = $(form);
    const $rows = $form.find('[data-pharmacy-line]');
    const $firstQuantity = $form.find('[data-pharmacy-return-quantity]').first();
    const $paymentType = $form.find('[name="payment_type_id"]');
    const $total = $form.find('[data-pharmacy-return-total]');
    const $due = $form.find('[data-pharmacy-return-due]');
    const $cash = $form.find('[data-pharmacy-return-cash]');
    const $count = $form.find('[data-pharmacy-return-count]');
    const $paymentNote = $form.find('[data-pharmacy-return-payment-note]');
    const remainingTotal = pharmacyInteger($form.attr('data-return-source-total'))
        - pharmacyInteger($form.attr('data-return-source-returned')) + pharmacyInteger($form.attr('data-return-edit-total'));
    const settlementDiscount = pharmacyInteger($form.attr('data-return-source-discount'));
    const dueBeforeReturn = remainingTotal - pharmacyInteger($form.attr('data-return-source-paid'))
        + pharmacyInteger($form.attr('data-return-source-refunded')) - pharmacyInteger($form.attr('data-return-edit-cash')) - settlementDiscount;

    function refreshSelection() {
        let selected = 0;
        let total = 0n;
        $rows.each(function () {
            const $row = $(this);
            const value = pharmacyInteger($row.find('[data-pharmacy-return-quantity]').val());
            const positive = value > 0n;
            $row.find('[data-pharmacy-return-field]').prop('disabled', !positive);
            if (positive) selected++;
            const factor = pharmacyInteger($row.find('[data-pharmacy-return-unit] option:selected').attr('data-factor') || 1);
            const remaining = pharmacyInteger($row.attr('data-return-remaining'));
            const amount = remaining > 0n ? pharmacyInteger($row.attr('data-return-amount-minor')) * value * factor / remaining : 0n;
            $row.find('[data-pharmacy-return-amount]').text(formatAmount(amount));
            total += amount;
        });
        let selectionError = selected > 0 ? '' : 'Enter a return quantity for at least one item.';
        if (selected > 0 && total > remainingTotal - settlementDiscount) {
            selectionError = 'Void the settlement discount payment before returning this amount.';
        }
        $firstQuantity[0]?.setCustomValidity(selectionError);

        const cash = selected > 0 && total > dueBeforeReturn ? total - dueBeforeReturn : 0n;
        const dueAdjusted = total > cash ? total - cash : 0n;
        $total.text(formatAmount(total));
        $due.text(formatAmount(dueAdjusted));
        $cash.text(formatAmount(cash));
        $count.text(selected);
        $paymentType.prop('required', cash > 0n);
        $paymentNote.prop('hidden', cash === 0n);
    }
    $rows.each(function () { refreshReturnUnit(this); });
    $form.on('input.pharmacy', '[data-pharmacy-return-quantity]', refreshSelection);
    $form.on('change.pharmacy', '[data-pharmacy-return-unit]', function () {
        refreshReturnUnit($(this).closest('[data-pharmacy-line]')[0]);
    });
    $form.on('change.pharmacy', refreshSelection);
    $form.on('focusin.pharmacy click.pharmacy', 'input[type="number"]', function () { $(this).trigger('select'); });
    if (!$rows.length) $form.find('[type="submit"]').prop('disabled', true);
    refreshSelection();
}

// Read server defaults once before initializing either item editor.
export function initializeEntryData(form) {
    const $form = $(form);
    $form.data('pharmacyOptions', {
        vatMode: $form.attr('data-vat-enabled') === '1' ? $form.attr('data-vat-mode') : 'none',
        vatPercent: $form.attr('data-vat-percent') || '0',
    });
    $form.find('[data-pharmacy-line]').each(function () {
        const $row = $(this);
        $row.data('pharmacyProduct', {
            id: $row.attr('data-product-id'),
            purchasePrice: $row.attr('data-purchase-price') || '0',
            lastPurchaseRate: $row.attr('data-last-purchase-rate') || null,
            lastPurchaseFactor: $row.attr('data-last-purchase-factor') || null,
            salePrice: $row.attr('data-sale-price') || '0',
        });
    });
}

// Stock entries keep their own row-add, unit-change and row-remove interactions together.
function initializeStockEntry(form) {
    initializeEntryData(form);

    const $form = $(form);
    const $rows = $form.find('[data-pharmacy-lines]');
    const $template = $form.find('[data-pharmacy-line-template]');
    $form.find('select[name$="[variant_id]"]').each(function () {
        const product = $(this).closest('[data-pharmacy-line]').data('pharmacyProduct');
        const $selected = $(this).find('option:selected');
        product.purchasePrice = $selected.attr('data-purchase-price') || '0';
        product.salePrice = $selected.attr('data-sale-price') || '0';
    });
    let nextIndex = Math.max(-1, ...$form.find('[data-pharmacy-line]').map(function () {
        return Number($(this).attr('data-index')) || 0;
    }).get()) + 1;

    function addStockLine() {
        if ($rows.children().length >= 100) return;
        const $row = $($template.html().replaceAll('__INDEX__', String(nextIndex++)).trim())
            .filter('[data-pharmacy-line]').first();
        const row = $row.get(0);
        $row.data('pharmacyProduct', { id: '', purchasePrice: '0', salePrice: '0' });
        $rows.append($row);
        window.initAjaxSelects(row);
        window.initDateTimePickers(row);
        $row.find('select[name$="[variant_id]"]').select2('open');
    }

    function selectStockProduct(event) {
        selectStockVariant($(event.target).closest('[data-pharmacy-line]')[0], event.params.data);
    }

    function changeStockUnit() {
        setStockPrice($(this).closest('[data-pharmacy-line]')[0]);
    }

    function removeStockLine() {
        const $row = $(this).closest('[data-pharmacy-line]');
        $row.find('.select2-hidden-accessible').select2('destroy');
        $row.find('[data-datepicker], [data-timepicker]').each(function () { this._flatpickr?.destroy(); });
        $row.remove();
        refreshStockTotal(form);
    }

    $form.on('click.pharmacy', '[data-pharmacy-add-line]', addStockLine);
    $form.on('select2:select.pharmacy', 'select[name$="[variant_id]"]', selectStockProduct);
    $form.on('change.pharmacy', 'select[name$="[product_unit_id]"]', changeStockUnit);
    $form.on('change.pharmacy input.pharmacy', () => refreshStockTotal(form));
    $form.on('click.pharmacy', '[data-pharmacy-remove-line]', removeStockLine);
    refreshStockTotal(form);
}

function initializeOpeningStock(form) {
    initializeStockEntry(form);
    $(form).on('change.pharmacyOpening select2:select.pharmacyOpening', () => refreshOpeningFields(form));
    $(form).on('click.pharmacyOpening', '[data-pharmacy-add-line]', () => refreshOpeningFields(form));
    refreshOpeningFields(form);
}

function initializeAdjustment(form) {
    initializeStockEntry(form);
    $(form).on('change.pharmacyAdjustment select2:select.pharmacyAdjustment', () => refreshAdjustmentFields(form));
    $(form).on('click.pharmacyAdjustment', '[data-pharmacy-add-line]', () => refreshAdjustmentFields(form));
    refreshAdjustmentFields(form);
}

// Medicine-only catalog fields stay disabled while another product type is selected.
function initializeProductForm(form) {
    const $form = $(form);
    const $type = $form.find('#type');
    const $fields = $form.find('[data-pharmacy-medicine-field]');
    function refreshMedicineFields() {
        const hidden = $type.val() !== 'medicine';
        $fields.prop('hidden', hidden).find('input, select, textarea').prop('disabled', hidden);
    }

    $type.on('change.pharmacy', refreshMedicineFields);
    refreshMedicineFields();
}

// Optical variant fields follow the selected product without submitting hidden controls.
function initializeVariantForm(form) {
    const $form = $(form);
    const $product = $form.find('#product_id');
    const $fields = $form.find('[data-pharmacy-optic-field]');
    function refreshOpticFields(type) {
        const hidden = type !== 'optic';
        $fields.prop('hidden', hidden).find('input, select, textarea').prop('disabled', hidden);
    }

    $product.on('select2:select.pharmacy', event => refreshOpticFields(event.params.data.type));
    $product.on('select2:clear.pharmacy', () => refreshOpticFields(null));
    refreshOpticFields($form.attr('data-product-type'));
}

// Create a customer through the existing modal, then select the returned patient record.
function initializeCustomerModal(form) {
    const $form = $(form);
    const $button = $form.find('[type="submit"]');
    const $error = $form.find('[data-pharmacy-customer-errors]');
    async function saveCustomer(event) {
        event.preventDefault();
        $button.prop('disabled', true);
        $error.addClass('d-none');
        try {
            const data = await $.ajax({
                url: $form.attr('action'), method: 'POST', data: $form.serialize(), dataType: 'json',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const customer = data.customer;
            $('#pharmacy_customer').append($('<option>', {
                text: customer.text || customer.name, value: customer.id,
            }).prop({ defaultSelected: true, selected: true })).trigger('change');
            $form.closest('.modal').modal('hide');
            $form.trigger('reset');
            window.showToast('Customer created.', 'success');
        } catch (xhr) {
            const data = xhr.responseJSON || {};
            if (xhr.status === 422) {
                $error.text(Object.values(data.errors || {}).flat().join(' ') || data.message || 'Check the customer details.')
                    .removeClass('d-none');
            } else {
                window.showToast(data.message || 'Customer could not be saved. Please try again.', 'error');
            }
        } finally {
            $button.prop('disabled', false);
        }
    }

    $form.on('submit.pharmacy', saveCustomer);
}

$(function () {
    const $entries = $('[data-pharmacy-entry], .app-pharmacy-trade-layout');
    const $returnSourceForm = $('#pharmacy-return-source-form');
    $('select[form="pharmacy-return-source-form"]').on('change.pharmacy', () => {
        $returnSourceForm[0].requestSubmit();
    });
    $('[data-pharmacy-return]').each(function () { initializeReturn(this); });
    $entries.filter('[data-type="opening"]').each(function () { initializeOpeningStock(this); });
    $entries.filter('[data-type="adjustment"]').each(function () { initializeAdjustment(this); });
    $entries.filter('[data-type="damage"], [data-type="transfer"]').each(function () { initializeStockEntry(this); });
    $('[data-pharmacy-product-form]').each(function () { initializeProductForm(this); });
    $('[data-pharmacy-variant-form]').each(function () { initializeVariantForm(this); });
    $('[data-pharmacy-customer-create]').each(function () { initializeCustomerModal(this); });
});
