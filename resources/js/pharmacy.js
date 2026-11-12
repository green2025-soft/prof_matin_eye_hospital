import $ from './jquery';
import 'bootstrap';
import { initializeTradeEditor } from './pharmacy-trade-editor';
import { formatAmount, parseAmount, roundRatio } from './money';

const pharmacyQuantityFormatter = new Intl.NumberFormat('en-BD');

// Keep invoice arithmetic in integer minor units, including VAT rounding.
function pharmacyMinor(value) {
    return parseAmount(value) ?? 0n;
}

// Quantities and stored minor values are whole numbers with the existing 19-digit limit.
function pharmacyInteger(value) {
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
function setTradeValidity(input, message) {
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

// Allocate VAT without losing minor units, then update each item's displayed amount.
function applyLineAmount(state, tax) {
    const $row = $(state.row);
    const addedTax = state.vatMode === 'exclusive' ? tax : 0n;
    const amount = state.weight + addedTax;
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

function refreshPurchaseTotal(form) {
    const $form = $(form);
    const amounts = calculateEntryTotal($form, orderedTradeRows($form), readPurchaseLine, applyPurchaseVat);
    validateTradeEntry($form, amounts);
}

function refreshSaleTotal(form) {
    const $form = $(form);
    const options = $form.data('pharmacyOptions');
    const readLine = (row, vatMode) => readSaleLine(row, vatMode, options);
    const amounts = calculateEntryTotal($form, orderedTradeRows($form), readLine, applyInvoiceVat);
    validateTradeEntry($form, amounts);
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
function applyProductData($row, data) {
    const product = {
        id: String(data.product_id || ''),
        purchasePrice: data.purchase_price || '0',
        salePrice: data.sale_price || '0',
    };
    $row.data('pharmacyProduct', product);
    return product;
}

// Catalog units share their Pcs-first selection; each owner applies its own price afterwards.
function populateProductUnits($row, units) {
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

function selectPurchaseVariant(row, data) {
    const $row = $(row);
    const product = applyProductData($row, data);
    $row.find('input[name$="[sale_price_per_piece]"]').attr('placeholder', pharmacyMinor(data.sale_price) > 0n ? data.sale_price : '0');
    if ($row.find('[data-pharmacy-purchase-unit]').length) populatePurchaseUnits(row, Array.isArray(data.units) ? data.units : []);
    const factor = pharmacyInteger($row.find('input[name$="[unit_factor]"]').val());
    const basePrice = pharmacyMinor(product.purchasePrice);
    $row.find('[name$="[unit_price]"]').val(basePrice > 0n && factor > 0n ? formatAmount(basePrice * factor) : '');
    refreshPurchaseTotal($row.closest('form')[0]);
}

function selectSaleVariant(row, data) {
    const $row = $(row);
    applyProductData($row, data);
    $row.find('[data-pharmacy-product-stock]').val(data.available_quantity);
    populateProductUnits($row, data.units);
    setSalePrice(row);
}

function selectStockVariant(row, data) {
    const $row = $(row);
    applyProductData($row, data);
    populateProductUnits($row, data.units);
    setStockPrice(row);
}

// Unit conversion is identical; callers provide the actual price and total operation.
function setUnitPrice(row, basePrice, refreshTotal) {
    const $row = $(row);
    const $unit = $row.find('select[name$="[product_unit_id]"]');
    const $price = $row.find('[name$="[unit_price]"]');
    if (!$unit.length || !$price.length) return;
    const factor = pharmacyInteger($unit.find('option:selected').attr('data-factor') || 1);
    const minor = pharmacyMinor(basePrice);
    $price.val(minor > 0n ? formatAmount(minor * factor) : '');
    refreshTotal($row.closest('form')[0]);
}

function setPurchasePrice(row) {
    setUnitPrice(row, $(row).data('pharmacyProduct').purchasePrice, refreshPurchaseTotal);
}

function setSalePrice(row) {
    setUnitPrice(row, $(row).data('pharmacyProduct').salePrice, refreshSaleTotal);
}

function setStockPrice(row) {
    setUnitPrice(row, $(row).data('pharmacyProduct').purchasePrice, refreshStockTotal);
}

// Hidden saved per-unit amounts follow pack edits without compounding earlier rounding.
function rescaleHiddenUnitAmount(row, input, basis, previousFactor, nextFactor) {
    const $input = $(input);
    if (!$input.length || basis !== 'unit' || nextFactor < 1n
        || !$input.closest('[data-pharmacy-hidden-item-field]').length
        || !$(row).find('[name$="[original_line_id]"]').val()) return;

    let originalFactor = pharmacyInteger($input.attr('data-original-unit-factor'));
    if (originalFactor < 1n || !$input.attr('data-original-unit-amount')) {
        if (previousFactor < 1n || previousFactor === nextFactor) return;
        $input.attr({ 'data-original-unit-factor': String(previousFactor), 'data-original-unit-amount': $input.val() || '0' });
        originalFactor = previousFactor;
    }

    const originalAmount = pharmacyMinor($input.attr('data-original-unit-amount'));
    $input.val(formatAmount(roundRatio(originalAmount * nextFactor, originalFactor)));
}

// Apply one saved pack and preserve hidden saved discount/VAT when its size changes.
function applyPurchaseUnit(row, option, clearPrice = false) {
    const $row = $(row);
    const $option = $(option);
    if (!$option.attr('data-factor')) return;
    const $unitId = $row.find('input[name$="[unit_id]"]');
    const $factor = $row.find('input[name$="[unit_factor]"]');
    const unitName = $option.attr('data-unit-name');
    const changed = $unitId.val() !== $option.attr('data-unit-id') || $factor.val() !== $option.attr('data-factor');
    const previousFactor = pharmacyInteger($factor.val());
    const nextFactor = pharmacyInteger($option.attr('data-factor'));
    rescaleHiddenUnitAmount(row,
        $row.find('input[name$="[discount]"]')[0],
        $row.find('select[name$="[discount_input]"]').val(),
        previousFactor, nextFactor);
    rescaleHiddenUnitAmount(row,
        $row.find('input[name$="[vat_amount]"]')[0],
        $row.find('select[name$="[vat_input]"]').val(),
        previousFactor, nextFactor);
    $unitId.val($option.attr('data-unit-id'));
    $factor.val($option.attr('data-factor'));
    $row.attr('data-selected-purchase-unit', $option.val());
    setTradeValidity($row.find('[data-pharmacy-purchase-unit]')[0], '');
    $row.find('[data-pharmacy-edit-unit]').prop('disabled', $option.attr('data-fixed') === '1');
    $row.find('[data-pharmacy-quantity-unit], [data-pharmacy-rate-unit]').text(unitName);
    $row.find('select[name$="[vat_input]"] option[value="unit"], select[name$="[discount_input]"] option[value="unit"]')
        .text(`/ ${unitName}`);
    const name = $row.find('.app-pharmacy-entry-name').text();
    $row.find('[name$="[quantity]"]').attr('aria-label', `Number of ${unitName} for ${name}`);
    const $price = $row.find('[name$="[unit_price]"]').attr('aria-label', `Rate per ${unitName}`);
    if (clearPrice && changed) $price.val('');
}

// Reuse catalog units and distinct pack sizes; repeated edits must not grow profile data.
function populatePurchaseUnits(row, profiles) {
    const $row = $(row);
    const $select = $row.find('[data-pharmacy-purchase-unit]');
    const $form = $row.closest('form');
    let units = $row.data('purchaseUnits');
    if (!units) {
        units = $select.find('option[data-unit-id]').map(function () {
            const $option = $(this);

            return {
                id: $option.attr('data-unit-id'),
                name: $option.text(),
                symbol: $option.attr('data-symbol')?.toLowerCase() || '',
            };
        }).get();
        $row.data('purchaseUnits', units);
    }
    const remembered = $form.data('purchasePacks')?.get($row.data('pharmacyProduct').id) || [];
    const unitId = $row.find('input[name$="[unit_id]"]').val();
    const factor = $row.find('input[name$="[unit_factor]"]').val();
    const allProfiles = [];
    const sizesByUnit = new Map();

    for (const profile of [...profiles, ...remembered, { unit_id: unitId, factor }]) {
        const id = String(profile.unit_id);
        const size = Number(profile.factor);
        if (!/^\d+$/.test(id) || !Number.isInteger(size) || size < 1 || size > 1000000) continue;

        if (!sizesByUnit.has(id)) sizesByUnit.set(id, new Set());
        const sizes = sizesByUnit.get(id);
        if (sizes.has(size)) continue;

        sizes.add(size);
        allProfiles.push({ unit_id: id, factor: String(size) });
    }
    const pieces = units.find(unit => unit.symbol === 'pcs');
    $select.empty().append($('<option>', { text: 'Unit', value: '' }));
    for (const unit of units) {
        const fixed = unit.symbol === 'pcs' ? 1 : null;
        const sizes = fixed === null ? [...(sizesByUnit.get(unit.id) || [])].sort((a, b) => a - b) : [fixed];
        for (const size of sizes) {
            const label = fixed !== null ? unit.name : `${unit.name} · ${size} Pcs`;
            $select.append($('<option>', {
                text: label, value: `pack:${unit.id}:${size}`, 'data-unit-id': unit.id, 'data-factor': String(size),
                'data-unit-name': unit.name, 'data-fixed': fixed !== null ? '1' : '0',
            }));
        }
        if (fixed === null) {
            $select.append($('<option>', {
                text: `${unit.name} (new size)`, value: `unit:${unit.id}`, 'data-unit-id': unit.id, 'data-unit-name': unit.name,
            }));
        }
    }
    const selected = `pack:${unitId}:${factor}`;
    const hasOldSelection = unitId !== '' || factor !== '';
    const hasSelectedPack = $select.find('option').get().some(option => $(option).val() === selected);
    $select.val(hasSelectedPack ? selected : (!hasOldSelection && pieces ? `pack:${pieces.id}:1` : ''));
    $row.data('unitProfiles', allProfiles);
    applyPurchaseUnit(row, $select.find('option:selected')[0]);
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
function initializeEntryData(form) {
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
            salePrice: $row.attr('data-sale-price') || '0',
        });
    });
}

// Purchase packs and sales catalog units have different shortcut lookups.
function purchaseUnitOptions($row, symbol) {
    const choice = $row.data('purchaseUnits')?.find(item => item.symbol === symbol);
    return $row.find('[data-pharmacy-purchase-unit] option').filter(function () {
        return choice && $(this).attr('data-unit-id') === choice.id;
    });
}

function saleUnitOptions($row, symbol) {
    return $row.find('select[name$="[product_unit_id]"] option').filter(function () {
        const $option = $(this);
        return $option.attr('data-symbol')?.toLowerCase() === symbol && (symbol !== 'pcs' || $option.attr('data-factor') === '1');
    });
}

// Purchases allow repeated product rows and editable packs.
function initializePurchaseEntry(form) {
    initializeEntryData(form);
    const $form = $(form);
    const rowSelector = 'fieldset[data-pharmacy-line]';
    const $picker = $form.find('#pharmacy_product_picker');
    const $packModal = $('[data-pharmacy-pack-modal]');
    const $packSize = $packModal.find('[data-pharmacy-pack-size]');
    const purchasePacks = new Map();
    let pendingPack = null;
    let packFocusTarget = null;
    let lastVatInput = null;
    let lastVatMode = null;
    $form.data('purchasePacks', purchasePacks);
    $form.find('fieldset[data-pharmacy-line]:has([data-pharmacy-purchase-unit])').each(function () {
        populatePurchaseUnits(this, $(this).data('unitProfiles') || []);
    });

    function startPurchaseProduct(product, editor) {
        if (!editor.finishCurrentItem() || !editor.canAddItem()) return;
        editor.addProductRow(product);
    }

    $form.on('change.pharmacyTrade', '[data-pharmacy-purchase-unit]', function () {
        const $unit = $(this);
        const $row = $unit.closest(rowSelector);
        const $option = $unit.find('option:selected');
        const factor = $option.attr('data-factor');
        $row.find('[data-pharmacy-edit-unit]').prop('disabled', !factor || $option.attr('data-fixed') === '1');
        if (factor) applyPurchaseUnit($row.get(0), $option.get(0), true);
        else openPackEditor($row, $option.get(0), this);
    });
    $form.on('change.pharmacyPurchase', 'select[name$="[vat_input]"]', function () {
        const $input = $(this);
        lastVatInput = $input.val();
        if (lastVatInput !== 'percent') $input.closest('fieldset[data-pharmacy-line]').find('input[name$="[vat_amount]"]').val('');
    });
    $form.on('change.pharmacyPurchase', 'select[name$="[vat_mode]"]', function () { lastVatMode = $(this).val(); });
    $form.on('pharmacy:itemCreated', 'fieldset[data-pharmacy-line]', function () {
        const $row = $(this);
        for (const [selector, value] of [
            ['select[name$="[vat_input]"]', lastVatInput],
            ['select[name$="[vat_mode]"]', lastVatMode],
        ]) {
            const $input = $row.find(selector);
            if (value !== null && !$input.closest('[data-pharmacy-hidden-item-field]').length) $input.val(value);
        }
    });

    const editor = initializeTradeEditor(form, {
        selectVariant: selectPurchaseVariant,
        setDefaultPrice: setPurchasePrice,
        refreshEntryTotal: refreshPurchaseTotal,
        onProductSelected: startPurchaseProduct,
    });

    function openPackEditor($row, option, trigger) {
        const $option = $(option);
        if (!$packModal.length || !$option.attr('data-unit-id') || $option.attr('data-fixed') === '1') return;
        if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
        editor.closeDatePickers($row);
        pendingPack = { $row, $option };
        packFocusTarget = trigger;
        $packModal.find('[data-pharmacy-pack-label]').text(`Pieces per ${$option.attr('data-unit-name')}`);
        $packSize.val($option.attr('data-factor') || '').get(0).setCustomValidity('');
        $packModal.modal('show');
    }

    function confirmPack() {
        if (!pendingPack || !editor.activeRow || !pendingPack.$row.is(editor.activeRow)) return;
        const factor = pharmacyInteger($packSize.val());
        const packSize = $packSize.get(0);
        packSize.setCustomValidity(factor > 0n && factor <= 1000000n ? '' : 'Enter the pieces per pack.');
        if (!packSize.reportValidity()) return;
        const { $row, $option } = pendingPack;
        const productId = $row.data('pharmacyProduct').id;
        const profile = { unit_id: $option.attr('data-unit-id'), factor: String(factor) };
        const profiles = purchasePacks.get(productId) || [];
        if (!profiles.some(item => String(item.unit_id) === profile.unit_id && String(item.factor) === profile.factor)) profiles.push(profile);
        purchasePacks.set(productId, profiles);
        $form.find(rowSelector).each(function () {
            const $item = $(this);
            if ($item.data('pharmacyProduct').id === productId) {
                populatePurchaseUnits(this, $item.data('unitProfiles') || []);
            }
        });
        const $unit = $row.find('[data-pharmacy-purchase-unit]');
        $unit.val(`pack:${profile.unit_id}:${profile.factor}`);
        applyPurchaseUnit($row.get(0), $unit.find('option:selected').get(0), true);
        pendingPack = null;
        packFocusTarget = $row.find('[name$="[quantity]"]').get(0);
        $packModal.modal('hide');
        editor.refreshRows();
    }

    $packModal.on('shown.bs.modal.pharmacyTrade', () => editor.focusControl($packSize.get(0)));
    $packModal.on('hidden.bs.modal.pharmacyTrade', () => {
        if (pendingPack?.$row.prop('isConnected')) {
            const { $row } = pendingPack;
            const $unit = $row.find('[data-pharmacy-purchase-unit]');
            $unit.val($row.attr('data-selected-purchase-unit') || '');
            applyPurchaseUnit($row.get(0), $unit.find('option:selected').get(0));
        }
        pendingPack = null;
        editor.focusControl($(packFocusTarget).prop('isConnected') ? packFocusTarget : null);
        packFocusTarget = null;
    });
    $packModal.on('click.pharmacyTrade', '[data-pharmacy-pack-confirm]', confirmPack);
    $packSize.on('input.pharmacyTrade', function () { this.setCustomValidity(''); });
    $packSize.on('keydown.pharmacyTrade', event => {
        if (event.key !== 'Enter') return;
        event.preventDefault();
        confirmPack();
    });

    $form.on('click.pharmacyTrade', '[data-pharmacy-edit-unit]', function () {
        const $row = $(this).closest(rowSelector);
        openPackEditor($row, $row.find('[data-pharmacy-purchase-unit] option:selected').get(0), this);
    });
    $form.on('pharmacy:itemRestored', 'fieldset[data-pharmacy-line]', function () {
        const $row = $(this);
        applyPurchaseUnit(this, $row.find('[data-pharmacy-purchase-unit] option:selected')[0]);
    });
    $form.on('focusout.pharmacyPurchase', '[data-pharmacy-purchase-unit]', function () {
        $(this).data('touched', true);
        editor.refreshRows();
    });
    $form.on('pharmacy:unitChooserRequested', () => editor.openUnitChooser(editor.activeRow.find('[data-pharmacy-purchase-unit]')));
    $form.on('pharmacy:unitShortcutRequested', (_, symbol) => {
        const $row = editor.activeRow;
        editor.selectUnitShortcut($row.find('[data-pharmacy-purchase-unit]'), purchaseUnitOptions($row, symbol));
    });
    $form.on('pharmacy:unitSelected', (_, $row) => {
        if (!pendingPack) editor.focusControl($row.find('[name$="[quantity]"]')[0]);
    });

    // Pack editing keeps its own shortcut, including Select2's detached search field.
    $(document).on('keydown.pharmacyPurchase', event => {
        const keyboard = event.originalEvent || event;
        if (keyboard.isComposing || keyboard.repeat || $('.modal.show').length) return;
        if (!$(event.target).closest($form).length && !$picker.data('select2')?.isOpen()) return;
        const $row = editor.activeRow;
        if (!event.altKey || event.key.toLowerCase() !== 'u' || !$row) return;
        const $unit = $row.find('[data-pharmacy-purchase-unit]');
        const $edit = $row.find('[data-pharmacy-edit-unit]');
        if (!$unit.length || !$edit.length || $edit.prop('disabled')) return;
        event.preventDefault();
        event.stopImmediatePropagation();
        openPackEditor($row, $unit.find('option:selected')[0], event.target);
    });
}

// Sales reuse existing product rows and enforce their own stock and base-unit rules.
function initializeSaleEntry(form) {
    initializeEntryData(form);
    const $form = $(form);
    const options = {
        ...$form.data('pharmacyOptions'),
        revisionStock: {},
        allowWithoutStock: false,
        ...JSON.parse($form.find('#pharmacy-sale-options').text()),
    };
    $form.data('pharmacyOptions', options);

    function startSaleProduct(product, editor) {
        const $existing = $form.find('fieldset[data-pharmacy-line]').filter(function () {
            return $(this).find('[name$="[variant_id]"]').val() === String(product.id);
        }).first();
        const available = pharmacyInteger(product.available_quantity) + pharmacyInteger(options.revisionStock[product.id]);
        if ($existing.length && editor.activeRow?.is($existing)) {
            editor.focusControl($existing.find('[name$="[quantity]"]')[0]);
            return;
        }
        if (!editor.finishCurrentItem()) return;
        if ($existing.length) {
            editor.openEntry($existing);
            editor.announce('This product is already added. Update its quantity.');
            return;
        }
        if (!options.allowWithoutStock && available < 1n) {
            editor.announce('This product has no available stock.', true);
            editor.focusPicker();
            return;
        }
        if (!editor.canAddItem()) return;
        if (!Array.isArray(product.units) || product.units.length === 0) {
            editor.announce('Set up a base unit for this product before adding it.', true);
            return;
        }
        editor.addProductRow({ ...product, available_quantity: String(available) });
    }

    const editor = initializeTradeEditor(form, {
        selectVariant: selectSaleVariant,
        setDefaultPrice: setSalePrice,
        refreshEntryTotal: refreshSaleTotal,
        onProductSelected: startSaleProduct,
    });
    $form.on('change.pharmacySale input.pharmacySale', '[name="vat_mode"], [name="vat_percent"]', () => editor.refreshRows());
    $form.on('pharmacy:unitChooserRequested', () => editor.openUnitChooser(editor.activeRow.find('select[name$="[product_unit_id]"]')));
    $form.on('pharmacy:unitShortcutRequested', (_, symbol) => {
        const $row = editor.activeRow;
        editor.selectUnitShortcut($row.find('select[name$="[product_unit_id]"]'), saleUnitOptions($row, symbol));
    });
    $form.on('pharmacy:unitSelected', (_, $row) => editor.focusControl($row.find('[name$="[quantity]"]')[0]));

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

// Build one outstanding invoice allocation using escaped jQuery text and value updates.
function allocationRow($template, invoice, index) {
    const $row = $($template.html().replaceAll('__INDEX__', String(index)).trim()).filter('tr').first();
    $row.attr({ 'data-invoice-id': invoice.id, 'data-due': invoice.due });
    $row.find('[data-pharmacy-allocation-select]').attr('aria-label', `Pay invoice ${invoice.number}`);
    $row.find('[data-pharmacy-allocation-number]').text(invoice.number);
    $row.find('[data-pharmacy-allocation-due]').text(invoice.due);
    $row.find('input[type="hidden"]').val(invoice.id);
    $row.find('[name$="[amount]"]').val(invoice.due).attr({
        max: invoice.due, 'aria-label': `Cash for invoice ${invoice.number}`,
    });
    $row.find('[name$="[discount]"]').attr({
        max: invoice.due, 'aria-label': `Discount for invoice ${invoice.number}`,
    });
    return $row;
}

// A party change cancels the old request before loading its outstanding invoices.
function initializePayment(form) {
    const $form = $(form);
    const $table = $form.find('[data-pharmacy-allocations]');
    const $template = $form.find('[data-pharmacy-allocation-template]');
    const $more = $form.find('[data-pharmacy-more-invoices]');
    const $error = $form.find('[data-pharmacy-payment-error]');
    const $loading = $form.find('[data-pharmacy-payment-loading]');
    const $direction = $form.find('[name="direction"]');
    const $party = $form.find('[data-pharmacy-payment-party]');
    const $cashTotal = $form.find('[data-pharmacy-cash-total]');
    let page = 1;
    let nextIndex = Math.max(-1, ...$table.find('[name$="[amount]"]').map(function () {
        return Number($(this).attr('name').match(/^allocations\[(\d+)\]/)?.[1]) || 0;
    }).get()) + 1;
    let pending = null;
    function refreshCash() {
        let total = 0n;
        $table.find('[name$="[amount]"]:enabled').each(function () { total += pharmacyMinor($(this).val()); });
        $cashTotal.text(formatAmount(total));
    }
    function clearInvoices() {
        const previous = pending;
        pending = null;
        previous?.abort();
        $table.empty();
        page = 1;
        nextIndex = 0;
        $more.addClass('d-none').text('Load more').prop('disabled', false);
        $loading.prop('hidden', true);
        $error.addClass('d-none');
        refreshCash();
    }
    async function loadInvoices(append) {
        if (!$party.val()) {
            window.showToast('Select a customer or supplier first.', 'warning');
            return;
        }
        if (!append) clearInvoices();
        const partyId = $party.val();
        const params = { direction: $direction.val(), product_type: $form.find('[name="product_type"]').val(), page };
        params[$party.attr('name')] = partyId;
        $more.prop('disabled', true);
        $loading.prop('hidden', false);
        $error.addClass('d-none');
        const request = $.ajax({ url: $form.attr('data-invoices-url'), data: params, dataType: 'json', timeout: 15000 });
        pending = request;
        try {
            const data = await request;
            if (pending !== request || $party.val() !== partyId) return;
            const existing = new Set($table.children().get().map(row => $(row).attr('data-invoice-id')));
            for (const invoice of data.results) {
                if (!existing.has(String(invoice.id))) $table.append(allocationRow($template, invoice, nextIndex++));
            }
            refreshCash();
            $more.text('Load more').toggleClass('d-none', !data.pagination.more);
            page += 1;
            if (!$table.children().length) {
                window.showToast('No outstanding invoices found.', 'info');
            }
        } catch (xhr) {
            if (pending !== request) return;
            if (xhr.statusText !== 'abort') {
                const message = xhr.responseJSON?.message || 'Invoices could not be loaded. Please try again.';
                if (xhr.status === 422) {
                    $error.text(message).removeClass('d-none');
                } else {
                    window.showToast(message, 'error');
                }
                $more.text('Retry loading').removeClass('d-none');
            }
        } finally {
            if (pending === request) {
                pending = null;
                $loading.prop('hidden', true);
                $more.prop('disabled', false);
            }
        }
    }
    $more.on('click.pharmacy', () => loadInvoices(true));
    $party.on('change.pharmacy', () => {
        if ($party.val()) loadInvoices(false);
        else clearInvoices();
    });
    $form.on('change.pharmacy', '[data-pharmacy-allocation-select]', function () {
        const checked = $(this).prop('checked');
        const $row = $(this).closest('[data-pharmacy-allocation]');
        $row.find('[name]').prop('disabled', !checked);
        const $amount = $row.find('[name$="[amount]"]');
        if (checked && !Number($amount.val())) $amount.val($row.attr('data-due') || '0.00');
    });
    $form.on('change.pharmacy input.pharmacy', refreshCash);
    refreshCash();
    if ($party.val() && !$table.children().length) loadInvoices(false);
}

$(function () {
    const $entries = $('[data-pharmacy-entry], .app-pharmacy-trade-layout');
    const $returnSourceForm = $('#pharmacy-return-source-form');
    $('select[form="pharmacy-return-source-form"]').on('change.pharmacy', () => {
        $returnSourceForm[0].requestSubmit();
    });
    $entries.filter('[data-type="purchase"]').each(function () { initializePurchaseEntry(this); });
    $('#pharmacy-sale-options').closest('form').each(function () { initializeSaleEntry(this); });
    $('[data-pharmacy-return]').each(function () { initializeReturn(this); });
    $entries.filter('[data-type="opening"]').each(function () { initializeOpeningStock(this); });
    $entries.filter('[data-type="adjustment"]').each(function () { initializeAdjustment(this); });
    $entries.filter('[data-type="damage"], [data-type="transfer"]').each(function () { initializeStockEntry(this); });
    $('[data-pharmacy-product-form]').each(function () { initializeProductForm(this); });
    $('[data-pharmacy-variant-form]').each(function () { initializeVariantForm(this); });
    $('[data-pharmacy-customer-create]').each(function () { initializeCustomerModal(this); });
    $('[data-pharmacy-payment]').each(function () { initializePayment(this); });
});
