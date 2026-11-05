import $ from './jquery';
import 'bootstrap';
import { initializeTradeEditor } from './pharmacy-trade-editor';
import { formatAmount, roundRatio } from './money';

const pharmacyQuantityFormatter = new Intl.NumberFormat('en-BD');

function pharmacyMinor(value) {
    const match = String(value ?? '').trim().match(/^(\d{1,10})(?:\.(\d{1,2}))?$/);
    return match ? BigInt(match[1]) * 100n + BigInt((match[2] || '').padEnd(2, '0')) : 0n;
}

function pharmacyInteger(value) {
    return /^\d{1,19}$/.test(String(value ?? '')) ? BigInt(value) : 0n;
}

function tradeAmountError(input) {
    const $input = $(input);
    const value = $input.val();
    if (!$input.length || (value === '' && !$input.prop('required'))) return '';
    return /^\d{1,10}(?:\.\d{1,2})?$/.test(value)
        ? '' : (value === '' ? 'Enter an amount.' : 'Enter a valid amount.');
}

function setTradeValidity(input, message) {
    const $input = $(input);
    input.setCustomValidity(message);
    $input.attr('data-validation-message', message);
    if ($input.attr('data-touched') === '1') {
        $input.toggleClass('is-invalid', Boolean(message));
        if (message) $input.attr('aria-invalid', 'true');
        else $input.removeAttr('aria-invalid');
    }
}

function validateTradeLine($row, options, values) {
    const { quantity, amount, discountMode, rawDiscount, discount } = values;
    const $quantity = $row.find('[name$="[quantity]"]');
    const $price = $row.find('[name$="[unit_price]"]');
    const $discount = $row.find('[name$="[discount]"]');
    const $unit = $row.find('[data-pharmacy-purchase-unit], select[name$="[product_unit_id]"]');
    let quantityError = /^\d+$/.test($quantity.val()) && quantity > 0n ? '' : 'Enter a positive whole quantity.';
    if (!quantityError && options.type === 'sale' && !options.allowWithoutStock) {
        const factor = pharmacyInteger($unit.find('option:selected').attr('data-factor'));
        const available = pharmacyInteger($row.find('[data-pharmacy-product-stock]').val());
        if (factor > 0n && quantity * factor > available) {
            quantityError = 'Quantity exceeds the available stock.';
        }
    }
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
    const $salePrice = $row.find('input[name$="[sale_price_per_piece]"]');
    if ($salePrice.length) setTradeValidity($salePrice[0], tradeAmountError($salePrice[0]));
    const $factor = $row.find('input[name$="[unit_factor]"]');
    if ($factor.length && $unit.length) {
        const factor = pharmacyInteger($factor.val());
        const factorError = /^\d+$/.test($factor.val()) && factor > 0n && factor <= 1000000n
            ? '' : 'Choose a pack size.';
        setTradeValidity($unit[0], factorError);
    }
}

function readEntryLine(row, trade, options, vatMode) {
    const $row = $(row);
    const $quantity = $row.find('[name$="[quantity]"]');
    const $price = $row.find('[name$="[unit_price]"]');
    const $discount = $row.find('[name$="[discount]"]');
    const quantity = pharmacyInteger($quantity.val());
    const $baseQuantity = $row.find('[data-pharmacy-base-quantity]');
    if ($baseQuantity.length) {
        const factor = pharmacyInteger($row.find('input[name$="[unit_factor]"]').val());
        $baseQuantity.text(quantity > 0n && factor > 0n
            ? pharmacyQuantityFormatter.format(quantity * factor) : '—');
    }
    const amount = quantity * pharmacyMinor($price.val());
    const discountMode = $row.find('select[name$="[discount_input]"]').val() || 'amount';
    const rawDiscount = pharmacyMinor($discount.val());
    const discount = discountMode === 'unit' ? rawDiscount * quantity
        : (discountMode === 'percent' ? roundRatio(amount * rawDiscount, 10000n) : rawDiscount);
    const $selectedUnit = $row.find('[data-pharmacy-purchase-unit] option:selected, select[name$="[product_unit_id]"] option:selected');
    const unitName = $selectedUnit.attr('data-unit-name') || $selectedUnit.text() || $row.attr('data-pharmacy-fixed-unit') || '';
    const $salePrice = $row.find('input[name$="[sale_price_per_piece]"]');
    row._pharmacySummary = {
        quantity: `${pharmacyQuantityFormatter.format(quantity)} ${unitName}`,
        rate: formatAmount(pharmacyMinor($price.val())),
        discount: formatAmount(discount),
        'sale-price': $salePrice.val() ? formatAmount(pharmacyMinor($salePrice.val())) : '—',
    };
    if (trade) {
        if (discountMode === 'percent') $discount.attr('max', '100');
        else $discount.removeAttr('max');
        validateTradeLine($row, options, { quantity, amount, discountMode, rawDiscount, discount });
    }
    return {
        row, subtotal: amount, discount: discount > amount ? amount : discount,
        net: amount > discount ? amount - discount : 0n,
        vatMode: trade && options.type === 'purchase'
            ? $row.find('select[name$="[vat_mode]"]').val() || 'none' : vatMode,
    };
}

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

function calculateLineVat($row, vatMode, weight) {
    const $rate = $row.find('input[name$="[vat_percent]"]');
    const $amount = $row.find('input[name$="[vat_amount]"]');
    const $source = $row.find('select[name$="[vat_input]"]');
    const noVat = vatMode === 'none';
    const amountMode = $source.val() === 'amount' || $source.val() === 'unit';
    $source.prop('disabled', noVat);
    $rate.prop({ hidden: amountMode, disabled: noVat || amountMode });
    $amount.prop({ hidden: !amountMode, disabled: noVat || !amountMode, required: !noVat && amountMode });
    let tax = 0n;
    let rateError = '';
    let amountError = '';
    if (!noVat) {
        const rate = pharmacyMinor($rate.val());
        if ($source.val() === 'unit') {
            tax = pharmacyMinor($amount.val()) * pharmacyInteger($row.find('[name$="[quantity]"]').val());
        } else if ($source.val() === 'amount') tax = pharmacyMinor($amount.val());
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
        setTradeValidity($price[0], $price.attr('data-validation-message')
            || 'Saved VAT exceeds this item amount. Adjust Qty or Rate.');
    }
    $row.find('[data-pharmacy-line-vat-total]').text(formatAmount(tax));
    return tax;
}

function applyEntryVat(states, net, lineVat, vatMode, percent) {
    let tax = !lineVat && vatMode !== 'none'
        ? roundRatio(net * percent, vatMode === 'inclusive' ? 10000n + percent : 10000n) : 0n;
    let addedTax = 0n;
    let cumulative = 0n;
    let allocated = 0n;
    for (const state of states) {
        const { row, vatMode: itemVatMode } = state;
        const $row = $(row);
        let rowTax = 0n;
        if (lineVat) {
            rowTax = calculateLineVat($row, itemVatMode, state.weight);
            tax += rowTax;
        } else {
            cumulative += state.weight;
            const target = net > 0n ? tax * cumulative / net : 0n;
            rowTax = target - allocated;
            allocated = target;
        }
        if (itemVatMode === 'exclusive') addedTax += rowTax;
        const itemAmount = state.weight + (itemVatMode === 'exclusive' ? rowTax : 0n);
        row._pharmacySummary.vat = `${formatAmount(rowTax)}${itemVatMode === 'inclusive' ? ' · Included' : (itemVatMode === 'exclusive' ? ' · Added' : '')}`;
        row._pharmacySummary.amount = formatAmount(itemAmount);
        $row.find('[data-pharmacy-row-amount]').text(formatAmount(itemAmount));
    }
    return { tax, addedTax };
}

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

function refreshEntryTotal(form) {
    const $form = $(form);
    if (!$form.find('[data-pharmacy-total]').length) return;
    const trade = $form.is('[data-pharmacy-trade]');
    const options = form._pharmacyOptions;
    const lineVat = trade && options.type === 'purchase';
    const $modeInput = $form.find('[data-pharmacy-vat-mode]');
    const vatMode = $modeInput.length ? $modeInput.val() : options.vatMode;
    const rows = $form.find('[data-pharmacy-line]').filter(function () {
        return trade || !$(this).find('fieldset').prop('disabled');
    }).get();
    const $itemList = $form.find('[data-pharmacy-item-list]');
    if ($itemList.length) {
        const order = new Map($itemList.find('tr[data-index]').get().map((item, index) => [$(item).attr('data-index'), index]));
        rows.sort((left, right) => (order.get($(left).attr('data-index')) ?? -1) - (order.get($(right).attr('data-index')) ?? -1));
    }
    const states = [];
    let subtotal = 0n;
    let lineDiscount = 0n;
    for (const row of rows) {
        const state = readEntryLine(row, trade, options, vatMode);
        subtotal += state.subtotal;
        lineDiscount += state.discount;
        states.push(state);
    }
    const discount = pharmacyMinor($form.find('[name="discount"]').val());
    const beforeDiscount = subtotal - lineDiscount;
    const net = applyInvoiceDiscount(states, beforeDiscount, discount);
    const percent = pharmacyMinor($form.find('[data-pharmacy-invoice-vat-percent]').val() ?? options.vatPercent);
    const { tax, addedTax } = applyEntryVat(states, net, lineVat, vatMode, percent);
    const total = net + addedTax;
    const paid = pharmacyMinor($form.find('[name="paid_amount"]').val());
    const due = total > paid ? total - paid : 0n;
    const amounts = { subtotal, 'line-discount': lineDiscount, 'invoice-discount': discount, vat: tax, total, paid, due };
    for (const [field, value] of Object.entries(amounts)) {
        $form.find(`[data-pharmacy-${field}]`).text(formatAmount(value));
    }
    $form.find('[data-pharmacy-line-count]').text(rows.length);
    if (trade) validateTradeEntry($form, { discount, beforeDiscount, paid, total, vatMode, percent });
}

function refreshReceiptFields(form) {
    const $form = $(form);
    const medicine = $form.find('[name="product_type"]').val() === 'medicine';
    $form.find('[data-pharmacy-line]').each(function () {
        const $row = $(this);
        const decrease = $row.find('[name$="[direction]"]').val() === 'decrease';
        const receipt = form._pharmacyOptions.type === 'opening' || (form._pharmacyOptions.type === 'adjustment' && !decrease);
        $row.find('[name$="[expiry_date]"]').prop('required', medicine && receipt);
        if (form._pharmacyOptions.type === 'adjustment') {
            $row.find('[data-pharmacy-lot]').prop('disabled', !decrease || !$row.find('select[name$="[variant_id]"]').val());
        }
    });
}

function selectVariant(row, data) {
    const $row = $(row);
    row._pharmacyProduct = {
        id: String(data.product_id || ''),
        purchasePrice: data.purchase_price || '0',
        salePrice: data.sale_price || '0',
    };
    $row.find('input[name$="[sale_price_per_piece]"]').attr('placeholder', pharmacyMinor(data.sale_price) > 0n ? data.sale_price : '0');
    const $purchaseUnit = $row.find('[data-pharmacy-purchase-unit]');
    if ($purchaseUnit.length || $row.is('[data-pharmacy-fixed-unit]')) {
        if ($purchaseUnit.length) populatePurchaseUnits(row, Array.isArray(data.units) ? data.units : []);
        const factor = pharmacyInteger($row.find('input[name$="[unit_factor]"]').val());
        const basePrice = pharmacyMinor(row._pharmacyProduct.purchasePrice);
        $row.find('[name$="[unit_price]"]').val(basePrice > 0n && factor > 0n ? formatAmount(basePrice * factor) : '');
        refreshEntryTotal($row.closest('[data-pharmacy-entry]')[0]);
        return;
    }
    const $unit = $row.find('select[name$="[product_unit_id]"]');
    if (!$unit.length) return;
    $unit.empty().append($('<option>', { text: 'Select unit', value: '' }));
    for (const choice of data.units || []) {
        $unit.append($('<option>', {
            text: `${choice.name} × ${choice.factor}`, value: choice.id,
            'data-factor': choice.factor, 'data-unit-name': choice.name, 'data-symbol': choice.symbol,
        }));
    }
    const $base = $unit.find('option[data-factor="1"]');
    const $piece = $base.filter(function () {
        return $(this).attr('data-symbol')?.trim().toLowerCase() === 'pcs';
    });
    $unit.val($piece.first().val() || $base.first().val() || '');
    setDefaultPrice(row);
}

function setDefaultPrice(row) {
    const $row = $(row);
    const form = $row.closest('[data-pharmacy-entry]')[0];
    const $unit = $row.find('select[name$="[product_unit_id]"]');
    const $price = $row.find('[name$="[unit_price]"]');
    if (!$unit.length || !$price.length || ['damage', 'transfer'].includes(form._pharmacyOptions.type)) return;
    const basePrice = form._pharmacyOptions.type === 'sale' ? row._pharmacyProduct.salePrice : row._pharmacyProduct.purchasePrice;
    const factor = pharmacyInteger($unit.find('option:selected').attr('data-factor') || 1);
    const minor = pharmacyMinor(basePrice);
    $price.val(minor > 0n ? formatAmount(minor * factor) : '');
    refreshEntryTotal(form);
}

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

function populatePurchaseUnits(row, profiles) {
    const $row = $(row);
    const $select = $row.find('[data-pharmacy-purchase-unit]');
    const $form = $row.closest('[data-pharmacy-trade]');
    const form = $form[0];
    if (!row._purchaseUnits) {
        row._purchaseUnits = $select.find('option[data-unit-id]').get().map(option => ({
            id: $(option).attr('data-unit-id'), name: $(option).text(), symbol: $(option).attr('data-symbol')?.toLowerCase() || '',
        }));
    }
    const remembered = form._purchasePacks?.get(row._pharmacyProduct.id) || [];
    const unitId = $row.find('input[name$="[unit_id]"]').val();
    const factor = $row.find('input[name$="[unit_factor]"]').val();
    const optic = $form.find('[name="product_type"]').val() === 'optic';
    const allProfiles = [...profiles, ...remembered];
    if (/^\d+$/.test(unitId) && /^[1-9]\d{0,6}$/.test(factor) && Number(factor) <= 1000000) {
        allProfiles.push({ unit_id: unitId, factor });
    }
    const pieces = row._purchaseUnits.find(unit => unit.symbol === 'pcs');
    $select.empty().append($('<option>', { text: 'Unit', value: '' }));
    for (const unit of row._purchaseUnits) {
        const fixed = unit.symbol === 'pcs' ? 1 : (optic && unit.symbol === 'pair' ? 2 : null);
        const factors = fixed === null
            ? allProfiles.filter(profile => String(profile.unit_id) === unit.id).map(profile => Number(profile.factor)) : [fixed];
        for (const size of [...new Set(factors)].filter(size => Number.isInteger(size) && size > 0 && size <= 1000000).sort((a, b) => a - b)) {
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
    row._unitProfiles = allProfiles;
    applyPurchaseUnit(row, $select.find('option:selected')[0]);
}

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

function initializeReturn(form) {
    const $form = $(form);
    const $rows = $form.find('[data-pharmacy-line]');
    const $firstQuantity = $form.find('[data-pharmacy-return-quantity]').first();
    const $paymentType = $form.find('[name="payment_type_id"]');
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
        $form.find('[data-pharmacy-return-total]').text(formatAmount(total));
        $form.find('[data-pharmacy-return-due]').text(formatAmount(dueAdjusted));
        $form.find('[data-pharmacy-return-cash]').text(formatAmount(cash));
        $form.find('[data-pharmacy-return-count]').text(String(selected));
        $paymentType.prop('required', cash > 0n);
        $form.find('[data-pharmacy-return-payment-note]').prop('hidden', cash === 0n);
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

function initializeStockEntry(form) {
    const $form = $(form);
    form._pharmacyOptions = {
        type: $form.attr('data-type'),
        vatMode: $form.attr('data-vat-enabled') === '1' ? $form.attr('data-vat-mode') : 'none',
        vatPercent: $form.attr('data-vat-percent') || '0',
        allowWithoutStock: $form.attr('data-allow-without-stock') === '1',
        revisionStock: {},
        ...JSON.parse($form.find('#pharmacy-sale-options').text() || '{}'),
    };
    $form.find('[data-pharmacy-line]').each(function () {
        const $row = $(this);
        this._pharmacyProduct = {
            id: $row.attr('data-product-id'),
            purchasePrice: $row.attr('data-purchase-price') || '0',
            salePrice: $row.attr('data-sale-price') || '0',
        };
    });
    if ($form.is('[data-pharmacy-trade]')) {
        initializeTradeEditor(form, {
            selectVariant, applyPurchaseUnit, populatePurchaseUnits, setDefaultPrice, refreshEntryTotal,
            parseInteger: pharmacyInteger,
        });
        return;
    }
    if ($form.is('[data-pharmacy-return]')) {
        initializeReturn(form);
        return;
    }
    const $rows = $form.find('[data-pharmacy-lines]');
    $form.find('select[name$="[variant_id]"]').each(function () {
        const row = $(this).closest('[data-pharmacy-line]')[0];
        const $selected = $(this).find('option:selected');
        row._pharmacyProduct.purchasePrice = $selected.attr('data-purchase-price') || '0';
        row._pharmacyProduct.salePrice = $selected.attr('data-sale-price') || '0';
    });
    let nextIndex = Math.max(-1, ...$form.find('[data-pharmacy-line]').get().map(row => Number($(row).attr('data-index')) || 0)) + 1;
    $form.on('click.pharmacy', '[data-pharmacy-add-line]', () => {
        if ($rows.children().length >= 100) return;
        const $row = $($form.find('[data-pharmacy-line-template]').html().replaceAll('__INDEX__', String(nextIndex++)).trim())
            .filter('[data-pharmacy-line]').first();
        const row = $row.get(0);
        row._pharmacyProduct = { id: '', purchasePrice: '0', salePrice: '0' };
        $rows.append($row);
        window.initAjaxSelects(row);
        window.initDateTimePickers(row);
        refreshReceiptFields(form);
        $(row).find('select[name$="[variant_id]"]').select2('open');
    });
    $form.on('select2:select.pharmacy', 'select[name$="[variant_id]"]', event => {
        selectVariant($(event.target).closest('[data-pharmacy-line]')[0], event.params.data);
        refreshReceiptFields(form);
    });
    $form.on('change.pharmacy', 'select[name$="[product_unit_id]"]', function () {
        setDefaultPrice($(this).closest('[data-pharmacy-line]')[0]);
    });
    $form.on('change.pharmacy', () => {
        refreshReceiptFields(form);
        refreshEntryTotal(form);
    });
    $form.on('input.pharmacy', () => refreshEntryTotal(form));
    $form.on('click.pharmacy', '[data-pharmacy-remove-line]', function () {
        const $row = $(this).closest('[data-pharmacy-line]');
        $row.find('.select2-hidden-accessible').select2('destroy');
        $row.find('[data-datepicker], [data-timepicker]').each(function () { this._flatpickr?.destroy(); });
        $row.remove();
        refreshEntryTotal(form);
    });
    refreshReceiptFields(form);
    refreshEntryTotal(form);
}

function initializeProductForm(form) {
    const $form = $(form);
    const $type = $form.find('#type');
    const $fields = $form.find('[data-pharmacy-medicine-field]');
    const refresh = () => {
        const hidden = $type.val() !== 'medicine';
        $fields.prop('hidden', hidden).find('input, select, textarea').prop('disabled', hidden);
    };
    $type.on('change.pharmacy', refresh);
    refresh();
}

function initializeVariantForm(form) {
    const $form = $(form);
    const $product = $form.find('#product_id');
    const $fields = $form.find('[data-pharmacy-optic-field]');
    const refresh = type => {
        const hidden = type !== 'optic';
        $fields.prop('hidden', hidden).find('input, select, textarea').prop('disabled', hidden);
    };
    $product.on('select2:select.pharmacy', event => refresh(event.params.data.type));
    $product.on('select2:clear.pharmacy', () => refresh(null));
    refresh($form.attr('data-product-type'));
}

function initializeCustomerModal(form) {
    const $form = $(form);
    const $button = $form.find('[type="submit"]');
    const $error = $form.find('[data-pharmacy-customer-errors]');
    $form.on('submit.pharmacy', async event => {
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
    });
}

function allocationRow($template, invoice, index) {
    const $row = $template.contents().filter('tr').clone();
    $row.attr({ 'data-invoice-id': invoice.id, 'data-due': invoice.due });
    $row.find('[name]').each(function () {
        const $input = $(this);
        $input.attr('name', $input.attr('name').replace('__INDEX__', String(index)));
    });
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

function initializePayment(form) {
    const $form = $(form);
    const $table = $form.find('[data-pharmacy-allocations]');
    const $template = $form.find('[data-pharmacy-allocation-template]');
    const $more = $form.find('[data-pharmacy-more-invoices]');
    const $error = $form.find('[data-pharmacy-payment-error]');
    const $loading = $form.find('[data-pharmacy-payment-loading]');
    const $direction = $form.find('[name="direction"]');
    const $party = $form.find('[data-pharmacy-payment-party]');
    let page = 1;
    let nextIndex = Math.max(-1, ...$table.find('[name]').get().map(input => Number($(input).attr('name').match(/^allocations\[(\d+)\]/)?.[1]) || 0)) + 1;
    let pending = null;
    function refreshCash() {
        let total = 0n;
        $table.find('[name$="[amount]"]:enabled').each(function () { total += pharmacyMinor($(this).val()); });
        $form.find('[data-pharmacy-cash-total]').text(formatAmount(total));
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
    const $returnSourceForm = $('#pharmacy-return-source-form');
    $('select[form="pharmacy-return-source-form"]').on('change.pharmacy', () => {
        $returnSourceForm[0].requestSubmit();
    });
    $('[data-pharmacy-entry]').each(function () { initializeStockEntry(this); });
    $('[data-pharmacy-product-form]').each(function () { initializeProductForm(this); });
    $('[data-pharmacy-variant-form]').each(function () { initializeVariantForm(this); });
    $('[data-pharmacy-customer-create]').each(function () { initializeCustomerModal(this); });
    $('[data-pharmacy-payment]').each(function () { initializePayment(this); });
});
