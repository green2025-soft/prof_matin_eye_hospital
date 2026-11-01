import $ from 'jquery';
import { Modal } from 'bootstrap';
import { initializeTradeEditor } from './inventory-trade-editor';

const inventoryQuantityFormatter = new Intl.NumberFormat('en-BD');

function inventoryMinor(value) {
    const match = String(value ?? '').trim().match(/^(\d{1,10})(?:\.(\d{1,2}))?$/);
    return match ? BigInt(match[1]) * 100n + BigInt((match[2] || '').padEnd(2, '0')) : 0n;
}

function inventoryInteger(value) {
    return /^\d{1,19}$/.test(String(value ?? '')) ? BigInt(value) : 0n;
}

function inventoryDecimal(value) {
    return `${value / 100n}.${String(value % 100n).padStart(2, '0')}`;
}

function inventoryRoundRatio(numerator, denominator) {
    return denominator > 0n ? (numerator * 2n + denominator) / (denominator * 2n) : 0n;
}

function tradeAmountError(input) {
    if (!input || (input.value === '' && !input.required)) return '';
    return /^\d{1,10}(?:\.\d{1,2})?$/.test(input.value)
        ? '' : (input.value === '' ? 'Enter an amount.' : 'Enter a valid amount.');
}

function setTradeValidity(input, message) {
    input.setCustomValidity(message);
    input.dataset.validationMessage = message;
    if (input.dataset.touched === '1') {
        input.classList.toggle('is-invalid', Boolean(message));
        if (message) input.setAttribute('aria-invalid', 'true');
        else input.removeAttribute('aria-invalid');
    }
}

function refreshDocumentTotal(form) {
    if (!form.querySelector('[data-inventory-total]')) return;
    const trade = form.hasAttribute('data-inventory-trade');
    const lineVat = trade && form.dataset.type === 'purchase';
    const modeInput = form.querySelector('[data-inventory-vat-mode]');
    const vatMode = modeInput ? modeInput.value : (form.dataset.vatEnabled === '1' ? form.dataset.vatMode : 'none');
    const rows = Array.from(form.querySelectorAll('[data-inventory-line]')).filter(row => trade || !row.querySelector('fieldset')?.disabled);
    const itemList = form.querySelector('[data-inventory-item-list]');
    if (itemList) {
        const order = new Map(Array.from(itemList.querySelectorAll('tr[data-index]'), (item, index) => [item.dataset.index, index]));
        rows.sort((left, right) => (order.get(left.dataset.index) ?? -1) - (order.get(right.dataset.index) ?? -1));
    }
    let subtotal = 0n;
    let lineDiscount = 0n;
    const states = rows.map(row => {
        const quantityInput = row.querySelector('[name$="[quantity]"]');
        const priceInput = row.querySelector('[name$="[unit_price]"]');
        const discountInput = row.querySelector('[name$="[discount]"]');
        const quantity = inventoryInteger(quantityInput?.value);
        const baseQuantity = row.querySelector('[data-inventory-base-quantity]');
        if (baseQuantity) {
            const factor = inventoryInteger(row.querySelector('[data-inventory-unit-factor]')?.value);
            baseQuantity.textContent = quantity > 0n && factor > 0n
                ? inventoryQuantityFormatter.format(quantity * factor) : '—';
        }
        const amount = quantity * inventoryMinor(priceInput?.value);
        const discountMode = row.querySelector('[data-inventory-line-discount-input]')?.value || 'amount';
        const rawDiscount = inventoryMinor(discountInput?.value);
        const discount = discountMode === 'unit' ? rawDiscount * quantity
            : (discountMode === 'percent' ? inventoryRoundRatio(amount * rawDiscount, 10000n) : rawDiscount);
        const unit = row.querySelector('[data-inventory-purchase-unit], [data-inventory-unit]');
        const unitName = unit?.selectedOptions[0]?.dataset.unitName || unit?.selectedOptions[0]?.textContent || row.dataset.fixedUnit || '';
        row.dataset.summaryQuantity = `${inventoryQuantityFormatter.format(quantity)} ${unitName}`;
        row.dataset.summaryRate = inventoryDecimal(inventoryMinor(priceInput?.value));
        row.dataset.summaryDiscount = inventoryDecimal(discount);
        const salePrice = row.querySelector('[data-inventory-sale-price-per-piece]');
        row.dataset.summarySalePrice = salePrice?.value ? inventoryDecimal(inventoryMinor(salePrice.value)) : '—';
        if (discountInput && trade) {
            if (discountMode === 'percent') discountInput.max = '100';
            else discountInput.removeAttribute('max');
        }
        subtotal += amount;
        lineDiscount += discount > amount ? amount : discount;
        if (trade) {
            let quantityError = /^\d+$/.test(quantityInput.value) && quantity > 0n ? '' : 'Enter a positive whole quantity.';
            if (!quantityError && form.dataset.type === 'sale' && form.dataset.allowWithoutStock !== '1') {
                const factor = inventoryInteger(unit?.selectedOptions[0]?.dataset.factor);
                const available = inventoryInteger(row.dataset.availableQuantity);
                if (factor > 0n && quantity * factor > available) {
                    quantityError = 'Quantity exceeds the available stock.';
                }
            }
            let priceError = tradeAmountError(priceInput);
            const discountError = tradeAmountError(discountInput)
                || (discountMode === 'percent' && rawDiscount > 10000n ? 'Discount cannot exceed 100%.' : '')
                || (discount > amount ? 'Discount exceeds the item amount.' : '');
            const hiddenDiscount = discountInput?.closest('[data-inventory-hidden-item-field]');
            if (hiddenDiscount && discountError && !priceError) {
                priceError = 'Saved discount exceeds this item amount. Adjust Qty or Rate.';
            }
            setTradeValidity(quantityInput, quantityError);
            setTradeValidity(priceInput, priceError);
            setTradeValidity(discountInput, hiddenDiscount ? '' : discountError);
            const salePriceInput = row.querySelector('[data-inventory-sale-price-per-piece]');
            if (salePriceInput) setTradeValidity(salePriceInput, tradeAmountError(salePriceInput));
            const factorInput = row.querySelector('[data-inventory-unit-factor]');
            if (factorInput && unit) {
                const factor = inventoryInteger(factorInput.value);
                const factorError = /^\d+$/.test(factorInput.value) && factor > 0n && factor <= 1000000n
                    ? '' : 'Choose a pack size.';
                setTradeValidity(unit, factorError);
            }
        }
        return { row, net: amount > discount ? amount - discount : 0n,
            vatMode: lineVat ? row.querySelector('[data-inventory-line-vat-mode]')?.value || 'none' : vatMode };
    });
    const discountInput = form.querySelector('[name="discount"]');
    const discount = inventoryMinor(discountInput?.value);
    const beforeDiscount = subtotal - lineDiscount;
    const allowedDiscount = discount > beforeDiscount ? beforeDiscount : discount;
    const net = beforeDiscount - allowedDiscount;
    let cumulative = 0n;
    let allocated = 0n;
    for (const state of states) {
        cumulative += state.net;
        const target = beforeDiscount > 0n ? allowedDiscount * cumulative / beforeDiscount : 0n;
        state.weight = state.net - (target - allocated);
        allocated = target;
    }
    const headerPercent = form.querySelector('[data-inventory-invoice-vat-percent]');
    const percent = inventoryMinor(headerPercent?.value ?? form.dataset.vatPercent);
    let tax = !lineVat && vatMode !== 'none'
        ? inventoryRoundRatio(net * percent, vatMode === 'inclusive' ? 10000n + percent : 10000n) : 0n;
    let addedTax = 0n;
    cumulative = 0n;
    allocated = 0n;
    for (const state of states) {
        const { row, vatMode: itemVatMode } = state;
        let rowTax = 0n;
        if (lineVat) {
            const rateInput = row.querySelector('[data-inventory-line-vat-percent]');
            const amountInput = row.querySelector('[data-inventory-line-vat-amount]');
            const sourceInput = row.querySelector('[data-inventory-line-vat-input]');
            const noVat = itemVatMode === 'none';
            const amountMode = sourceInput.value === 'amount' || sourceInput.value === 'unit';
            sourceInput.disabled = noVat;
            rateInput.hidden = amountMode;
            rateInput.disabled = noVat || amountMode;
            amountInput.hidden = !amountMode;
            amountInput.disabled = noVat || !amountMode;
            amountInput.required = !noVat && amountMode;
            let rateError = '';
            let amountError = '';
            if (!noVat) {
                const rate = inventoryMinor(rateInput.value);
                if (sourceInput.value === 'unit') {
                    rowTax = inventoryMinor(amountInput.value) * inventoryInteger(row.querySelector('[name$="[quantity]"]').value);
                } else if (sourceInput.value === 'amount') rowTax = inventoryMinor(amountInput.value);
                else rowTax = inventoryRoundRatio(state.weight * rate, itemVatMode === 'inclusive' ? 10000n + rate : 10000n);
                const includedError = itemVatMode === 'inclusive' && rowTax > state.weight ? 'VAT exceeds the item amount.' : '';
                if (amountMode) amountError = tradeAmountError(amountInput) || includedError;
                else rateError = tradeAmountError(rateInput) || (rate > 10000n ? 'VAT percentage cannot exceed 100.' : '') || includedError;
            }
            const hiddenVat = sourceInput.closest('[data-inventory-hidden-item-field]');
            setTradeValidity(rateInput, hiddenVat ? '' : rateError);
            setTradeValidity(amountInput, hiddenVat ? '' : amountError);
            if (hiddenVat && (rateError || amountError)) {
                const priceInput = row.querySelector('[name$="[unit_price]"]');
                setTradeValidity(priceInput, priceInput.dataset.validationMessage
                    || 'Saved VAT exceeds this item amount. Adjust Qty or Rate.');
            }
            row.querySelector('[data-inventory-line-vat-total]').textContent = inventoryDecimal(rowTax);
            tax += rowTax;
        } else {
            cumulative += state.weight;
            const target = net > 0n ? tax * cumulative / net : 0n;
            rowTax = target - allocated;
            allocated = target;
        }
        if (itemVatMode === 'exclusive') addedTax += rowTax;
        const itemAmount = state.weight + (itemVatMode === 'exclusive' ? rowTax : 0n);
        row.dataset.summaryVat = `${inventoryDecimal(rowTax)}${itemVatMode === 'inclusive' ? ' · Included' : (itemVatMode === 'exclusive' ? ' · Added' : '')}`;
        row.dataset.summaryAmount = inventoryDecimal(itemAmount);
        const lineOutput = row.querySelector('[data-inventory-row-amount]');
        if (lineOutput) lineOutput.textContent = inventoryDecimal(itemAmount);
    }
    const total = net + addedTax;
    const paidInput = form.querySelector('[name="paid_amount"]');
    const paid = inventoryMinor(paidInput?.value);
    const due = total > paid ? total - paid : 0n;
    const amounts = { subtotal, 'line-discount': lineDiscount, 'invoice-discount': discount, vat: tax, total, paid, due };
    for (const [field, value] of Object.entries(amounts)) {
        form.querySelectorAll(`[data-inventory-${field}]`).forEach(element => { element.textContent = inventoryDecimal(value); });
    }
    form.querySelectorAll('[data-inventory-line-count]').forEach(element => { element.textContent = rows.length; });
    if (trade) {
        const discountError = tradeAmountError(discountInput) || (discount > beforeDiscount ? 'Invoice discount exceeds the remaining amount.' : '');
        const paidError = tradeAmountError(paidInput) || (paid > total ? 'Paid amount cannot exceed the payable amount.' : '');
        let taxError = '';
        if (headerPercent) {
            headerPercent.disabled = vatMode === 'none';
            taxError = vatMode === 'none' ? '' : tradeAmountError(headerPercent) || (percent > 10000n ? 'VAT percentage cannot exceed 100.' : '');
            setTradeValidity(headerPercent, taxError);
        }
        setTradeValidity(discountInput, discountError);
        setTradeValidity(paidInput, paidError);
        const feedback = form.querySelector('[data-inventory-payment-error]');
        feedback.textContent = '';
        feedback.classList.add('d-none');
        form.querySelector('[name="payment_type_id"]').required = paid > 0n;
    }
}

function refreshReceiptFields(form) {
    const medicine = form.querySelector('[name="product_type"]')?.value === 'medicine';
    form.querySelectorAll('[data-inventory-line]').forEach(row => {
        const decrease = row.querySelector('[name$="[direction]"]')?.value === 'decrease';
        const receipt = form.dataset.type === 'opening' || (form.dataset.type === 'adjustment' && !decrease);
        for (const field of ['expiry_date']) {
            const input = row.querySelector(`[name$="[${field}]"]`);
            if (input) input.required = medicine && receipt;
        }
        const lot = row.querySelector('[data-inventory-lot]');
        if (lot && form.dataset.type === 'adjustment') {
            lot.disabled = !decrease || !row.querySelector('[data-inventory-variant]')?.value;
        }
    });
}

function selectVariant(row, data) {
    row.dataset.productId = data.product_id || '';
    row.dataset.purchasePrice = data.purchase_price || '0';
    row.dataset.salePrice = data.sale_price || '0';
    const salePrice = row.querySelector('[data-inventory-sale-price-per-piece]');
    if (salePrice) salePrice.placeholder = inventoryMinor(data.sale_price) > 0n ? data.sale_price : '0';
    const purchaseUnit = row.querySelector('[data-inventory-purchase-unit]');
    if (purchaseUnit || row.hasAttribute('data-inventory-fixed-unit')) {
        if (purchaseUnit) populatePurchaseUnits(row, Array.isArray(data.units) ? data.units : []);
        const factor = inventoryInteger(row.querySelector('[data-inventory-unit-factor]').value);
        const basePrice = inventoryMinor(row.dataset.purchasePrice);
        row.querySelector('[name$="[unit_price]"]').value = basePrice > 0n && factor > 0n ? inventoryDecimal(basePrice * factor) : '';
        refreshDocumentTotal(row.closest('[data-inventory-document]'));
        return;
    }
    const unit = row.querySelector('[data-inventory-unit]');
    if (!unit) return;
    unit.replaceChildren(new Option('Select unit', ''));
    for (const choice of data.units || []) {
        const option = new Option(`${choice.name} × ${choice.factor}`, choice.id);
        option.dataset.factor = choice.factor;
        option.dataset.unitName = choice.name;
        option.dataset.symbol = choice.symbol;
        unit.append(option);
    }
    const piece = Array.from(unit.options).find(option => option.dataset.factor === '1'
        && option.dataset.symbol?.trim().toLowerCase() === 'pcs');
    const base = Array.from(unit.options).find(option => option.dataset.factor === '1');
    unit.value = piece?.value || base?.value || '';
    setDefaultPrice(row);
}

function setDefaultPrice(row) {
    const form = row.closest('[data-inventory-document]');
    const unit = row.querySelector('[data-inventory-unit]');
    const price = row.querySelector('[name$="[unit_price]"]');
    if (!unit || !price || ['damage', 'transfer'].includes(form.dataset.type)) return;
    const basePrice = form.dataset.type === 'sale' ? row.dataset.salePrice : row.dataset.purchasePrice;
    const factor = inventoryInteger(unit.selectedOptions[0]?.dataset.factor || 1);
    const minor = inventoryMinor(basePrice);
    price.value = minor > 0n ? inventoryDecimal(minor * factor) : '';
    refreshDocumentTotal(form);
}

function rescaleHiddenUnitAmount(row, input, basis, previousFactor, nextFactor) {
    if (!input || basis !== 'unit' || nextFactor < 1n
        || !input.closest('[data-inventory-hidden-item-field]')
        || !row.querySelector('[name$="[original_line_id]"]')?.value) return;

    let originalFactor = inventoryInteger(input.dataset.originalUnitFactor);
    if (originalFactor < 1n || input.dataset.originalUnitAmount === undefined || input.dataset.originalUnitAmount === '') {
        if (previousFactor < 1n || previousFactor === nextFactor) return;
        input.dataset.originalUnitFactor = String(previousFactor);
        input.dataset.originalUnitAmount = input.value || '0';
        originalFactor = previousFactor;
    }

    const originalAmount = inventoryMinor(input.dataset.originalUnitAmount);
    input.value = inventoryDecimal(inventoryRoundRatio(originalAmount * nextFactor, originalFactor));
}

function applyPurchaseUnit(row, option, clearPrice = false) {
    if (!option?.dataset.factor) return;
    const unitId = row.querySelector('[data-inventory-unit-id]');
    const factor = row.querySelector('[data-inventory-unit-factor]');
    const changed = unitId.value !== option.dataset.unitId || factor.value !== option.dataset.factor;
    const previousFactor = inventoryInteger(factor.value);
    const nextFactor = inventoryInteger(option.dataset.factor);
    rescaleHiddenUnitAmount(row,
        row.querySelector('[data-inventory-line-discount-value]'),
        row.querySelector('[data-inventory-line-discount-input]')?.value,
        previousFactor, nextFactor);
    rescaleHiddenUnitAmount(row,
        row.querySelector('[data-inventory-line-vat-amount]'),
        row.querySelector('[data-inventory-line-vat-input]')?.value,
        previousFactor, nextFactor);
    unitId.value = option.dataset.unitId;
    factor.value = option.dataset.factor;
    row.dataset.selectedPurchaseUnit = option.value;
    const select = row.querySelector('[data-inventory-purchase-unit]');
    setTradeValidity(select, '');
    const edit = row.querySelector('[data-inventory-edit-unit]');
    if (edit) edit.disabled = option.dataset.fixed === '1';
    row.querySelectorAll('[data-inventory-quantity-unit], [data-inventory-rate-unit]').forEach(label => {
        label.textContent = option.dataset.unitName;
    });
    row.querySelectorAll('[data-inventory-line-vat-input] option[value="unit"], [data-inventory-line-discount-input] option[value="unit"]')
        .forEach(unitOption => { unitOption.textContent = `/ ${option.dataset.unitName}`; });
    const quantity = row.querySelector('[name$="[quantity]"]');
    const name = row.querySelector('[data-inventory-product-name]').textContent;
    quantity.setAttribute('aria-label', `Number of ${option.dataset.unitName} for ${name}`);
    const price = row.querySelector('[name$="[unit_price]"]');
    price.setAttribute('aria-label', `Rate per ${option.dataset.unitName}`);
    if (clearPrice && changed) price.value = '';
}

function populatePurchaseUnits(row, profiles) {
    const select = row.querySelector('[data-inventory-purchase-unit]');
    const form = row.closest('[data-inventory-trade]');
    if (!row._purchaseUnits) {
        row._purchaseUnits = Array.from(select.options).filter(option => option.dataset.unitId).map(option => ({
            id: option.dataset.unitId, name: option.textContent, symbol: option.dataset.symbol?.toLowerCase() || '',
        }));
    }
    const remembered = form._purchasePacks?.get(row.dataset.productId) || [];
    const unitId = row.querySelector('[data-inventory-unit-id]').value;
    const factor = row.querySelector('[data-inventory-unit-factor]').value;
    const optic = form.querySelector('[name="product_type"]').value === 'optic';
    const allProfiles = [...profiles, ...remembered];
    if (/^\d+$/.test(unitId) && /^[1-9]\d{0,6}$/.test(factor) && Number(factor) <= 1000000) {
        allProfiles.push({ unit_id: unitId, factor });
    }
    const pieces = row._purchaseUnits.find(unit => unit.symbol === 'pcs');
    select.replaceChildren(new Option('Unit', ''));
    for (const unit of row._purchaseUnits) {
        const fixed = unit.symbol === 'pcs' ? 1 : (optic && unit.symbol === 'pair' ? 2 : null);
        const factors = fixed === null
            ? allProfiles.filter(profile => String(profile.unit_id) === unit.id).map(profile => Number(profile.factor)) : [fixed];
        for (const size of [...new Set(factors)].filter(size => Number.isInteger(size) && size > 0 && size <= 1000000).sort((a, b) => a - b)) {
            const label = fixed !== null ? unit.name : `${unit.name} · ${size} Pcs`;
            const option = new Option(label, `pack:${unit.id}:${size}`);
            Object.assign(option.dataset, { unitId: unit.id, factor: String(size), unitName: unit.name,
                fixed: fixed !== null ? '1' : '0' });
            select.append(option);
        }
        if (fixed === null) {
            const option = new Option(`${unit.name} (new size)`, `unit:${unit.id}`);
            Object.assign(option.dataset, { unitId: unit.id, unitName: unit.name });
            select.append(option);
        }
    }
    const selected = `pack:${unitId}:${factor}`;
    const hasOldSelection = unitId !== '' || factor !== '';
    select.value = Array.from(select.options).some(option => option.value === selected)
        ? selected : (!hasOldSelection && pieces ? `pack:${pieces.id}:1` : '');
    row._unitProfiles = allProfiles;
    applyPurchaseUnit(row, select.selectedOptions[0]);
}

function refreshReturnUnit(row) {
    const unit = row.querySelector('[data-inventory-return-unit]');
    if (!unit) return;
    const factor = inventoryInteger(unit.selectedOptions[0]?.dataset.factor || 1);
    const quantity = row.querySelector('[name$="[quantity]"]');
    const remaining = inventoryInteger(row.dataset.returnRemaining);
    quantity.max = String(factor > 0n ? remaining / factor : 0n);
    row.querySelector('[data-inventory-return-remaining]').textContent = inventoryQuantityFormatter.format(remaining);
    const price = inventoryDecimal(inventoryRoundRatio(inventoryMinor(row.dataset.originalPrice) * factor, inventoryInteger(row.dataset.originalFactor)));
    row.querySelector('[data-inventory-return-rate-input]').value = price;
    row.querySelector('[data-inventory-return-rate]').textContent = price;
}

function initializeReturn(form) {
    const rows = Array.from(form.querySelectorAll('[data-inventory-line]'));
    const firstQuantity = form.querySelector('[data-inventory-return-quantity]');
    const paymentType = form.querySelector('[name="payment_type_id"]');
    const remainingTotal = inventoryInteger(form.dataset.returnSourceTotal)
        - inventoryInteger(form.dataset.returnSourceReturned) + inventoryInteger(form.dataset.returnEditTotal);
    const settlementDiscount = inventoryInteger(form.dataset.returnSourceDiscount);
    const dueBeforeReturn = remainingTotal - inventoryInteger(form.dataset.returnSourcePaid)
        + inventoryInteger(form.dataset.returnSourceRefunded) - inventoryInteger(form.dataset.returnEditCash) - settlementDiscount;

    function refreshSelection() {
        let selected = 0;
        let total = 0n;
        for (const row of rows) {
            const quantity = row.querySelector('[data-inventory-return-quantity]');
            const value = inventoryInteger(quantity.value);
            const positive = value > 0n;
            row.querySelectorAll('[data-inventory-return-field]').forEach(input => { input.disabled = !positive; });
            if (positive) selected++;
            const factor = inventoryInteger(row.querySelector('[data-inventory-return-unit]').selectedOptions[0]?.dataset.factor || 1);
            const remaining = inventoryInteger(row.dataset.returnRemaining);
            const amount = remaining > 0n ? inventoryInteger(row.dataset.returnAmountMinor) * value * factor / remaining : 0n;
            row.querySelector('[data-inventory-return-amount]').textContent = inventoryDecimal(amount);
            total += amount;
        }
        let selectionError = selected > 0 ? '' : 'Enter a return quantity for at least one item.';
        if (selected > 0 && total > remainingTotal - settlementDiscount) {
            selectionError = 'Void the settlement discount payment before returning this amount.';
        }
        firstQuantity?.setCustomValidity(selectionError);

        const cash = selected > 0 && total > dueBeforeReturn ? total - dueBeforeReturn : 0n;
        const dueAdjusted = total > cash ? total - cash : 0n;
        const totalOutput = form.querySelector('[data-inventory-return-total]');
        const dueOutput = form.querySelector('[data-inventory-return-due]');
        const cashOutput = form.querySelector('[data-inventory-return-cash]');
        const countOutput = form.querySelector('[data-inventory-return-count]');
        if (totalOutput) totalOutput.textContent = inventoryDecimal(total);
        if (dueOutput) dueOutput.textContent = inventoryDecimal(dueAdjusted);
        if (cashOutput) cashOutput.textContent = inventoryDecimal(cash);
        if (countOutput) countOutput.textContent = String(selected);
        if (paymentType) paymentType.required = cash > 0n;
        const paymentNote = form.querySelector('[data-inventory-return-payment-note]');
        if (paymentNote) paymentNote.hidden = cash === 0n;
    }
    for (const row of rows) refreshReturnUnit(row);
    form.addEventListener('input', event => {
        if (event.target.matches('[data-inventory-return-quantity]')) refreshSelection();
    });
    form.addEventListener('change', event => {
        if (event.target.matches('[data-inventory-return-unit]')) refreshReturnUnit(event.target.closest('[data-inventory-line]'));
        refreshSelection();
    });
    for (const eventName of ['focusin', 'click']) {
        form.addEventListener(eventName, event => {
            if (event.target.matches('input[type="number"]')) event.target.select();
        });
    }
    const submit = form.querySelector('[type="submit"]');
    if (submit && rows.length === 0) submit.disabled = true;
    refreshSelection();
}

function initializeDocument(form) {
    if (form.hasAttribute('data-inventory-trade')) {
        initializeTradeEditor(form, {
            selectVariant, applyPurchaseUnit, populatePurchaseUnits, setDefaultPrice, refreshDocumentTotal,
            parseInteger: inventoryInteger,
        });
        return;
    }
    if (form.hasAttribute('data-inventory-return')) {
        initializeReturn(form);
        return;
    }
    const rows = form.querySelector('[data-inventory-lines]');
    form.querySelectorAll('[data-inventory-variant]').forEach(select => {
        const row = select.closest('[data-inventory-line]');
        row.dataset.purchasePrice = select.selectedOptions[0]?.dataset.purchasePrice || '0';
        row.dataset.salePrice = select.selectedOptions[0]?.dataset.salePrice || '0';
    });
    let nextIndex = Math.max(-1, ...Array.from(form.querySelectorAll('[data-inventory-line]'), row => Number(row.dataset.index) || 0)) + 1;
    form.querySelector('[data-inventory-add-line]')?.addEventListener('click', () => {
        if (rows.children.length >= 100) return;
        const template = form.querySelector('[data-inventory-line-template]');
        const fragment = document.createElement('template');
        fragment.innerHTML = template.innerHTML.replaceAll('__INDEX__', String(nextIndex++));
        const row = fragment.content.firstElementChild;
        rows.append(row);
        window.initAjaxSelects(row);
        window.initDateTimePickers(row);
        refreshReceiptFields(form);
        $(row).find('[data-inventory-variant]').select2('open');
    });
    $(form).on('select2:select', '[data-inventory-variant]', event => {
        selectVariant(event.target.closest('[data-inventory-line]'), event.params.data);
        refreshReceiptFields(form);
    });
    form.addEventListener('change', event => {
        const row = event.target.closest('[data-inventory-line]');
        if (event.target.matches('[data-inventory-unit]')) setDefaultPrice(row);
        refreshReceiptFields(form);
        refreshDocumentTotal(form);
    });
    form.addEventListener('input', () => refreshDocumentTotal(form));
    form.addEventListener('click', event => {
        const remove = event.target.closest('[data-inventory-remove-line]');
        if (!remove) return;
        const row = remove.closest('[data-inventory-line]');
        $(row).find('.select2-hidden-accessible').select2('destroy');
        row.querySelectorAll('[data-datepicker], [data-timepicker]').forEach(picker => picker._flatpickr?.destroy());
        row.remove();
        refreshDocumentTotal(form);
    });
    refreshReceiptFields(form);
    refreshDocumentTotal(form);
}

function initializeProductForm(form) {
    const type = form.querySelector('#type');
    const refresh = () => form.querySelectorAll('[data-inventory-medicine-field]').forEach(field => {
        field.hidden = type.value !== 'medicine';
        field.querySelectorAll('input, select, textarea').forEach(input => { input.disabled = field.hidden; });
    });
    type.addEventListener('change', refresh);
    refresh();
}

function initializeVariantForm(form) {
    const product = form.querySelector('#product_id');
    const refresh = type => form.querySelectorAll('[data-inventory-optic-field]').forEach(field => {
        field.hidden = type !== 'optic';
        field.querySelectorAll('input, select, textarea').forEach(input => { input.disabled = field.hidden; });
    });
    $(product).on('select2:select', event => refresh(event.params.data.type));
    $(product).on('select2:clear', () => refresh(null));
    refresh(form.dataset.productType);
}

function initializeCustomerModal(form) {
    form.addEventListener('submit', async event => {
        event.preventDefault();
        const button = form.querySelector('[type="submit"]');
        const error = form.querySelector('[data-inventory-customer-errors]');
        button.disabled = true;
        error.classList.add('d-none');
        try {
            const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await response.json();
            if (!response.ok) {
                if (response.status === 422) {
                    error.textContent = Object.values(data.errors || {}).flat().join(' ') || data.message || 'Check the customer details.';
                    error.classList.remove('d-none');
                } else {
                    window.showToast(data.message || 'Customer could not be saved.', 'error');
                }
                return;
            }
            const customer = data.customer;
            const select = document.querySelector('#inventory_customer');
            select.append(new Option(customer.text || customer.name, customer.id, true, true));
            $(select).trigger('change');
            Modal.getInstance(form.closest('.modal'))?.hide();
            form.reset();
            window.showToast('Customer created.', 'success');
        } catch {
            window.showToast('Customer could not be saved. Please try again.', 'error');
        } finally {
            button.disabled = false;
        }
    });
}

function allocationRow(invoice, index, canDiscount) {
    const row = document.createElement('tr');
    row.dataset.inventoryAllocation = '';
    row.dataset.documentId = invoice.id;
    row.dataset.due = invoice.due;
    const selectionCell = row.insertCell();
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.className = 'form-check-input';
    checkbox.dataset.inventoryAllocationSelect = '';
    checkbox.checked = true;
    checkbox.setAttribute('aria-label', `Pay invoice ${invoice.number}`);
    selectionCell.append(checkbox);
    const invoiceCell = row.insertCell();
    invoiceCell.textContent = invoice.number;
    const id = document.createElement('input');
    id.type = 'hidden';
    id.name = `allocations[${index}][document_id]`;
    id.value = invoice.id;
    invoiceCell.append(id);
    const dueCell = row.insertCell();
    dueCell.className = 'text-end';
    dueCell.textContent = invoice.due;
    for (const field of canDiscount ? ['amount', 'discount'] : ['amount']) {
        const input = document.createElement('input');
        input.className = 'form-control';
        input.type = 'number';
        input.name = `allocations[${index}][${field}]`;
        input.min = '0';
        input.step = '0.01';
        input.max = invoice.due;
        input.value = field === 'amount' ? invoice.due : '0.00';
        input.setAttribute('aria-label', `${field === 'amount' ? 'Cash' : 'Discount'} for invoice ${invoice.number}`);
        row.insertCell().append(input);
    }
    return row;
}

function initializePayment(form) {
    const table = form.querySelector('[data-inventory-allocations]');
    const more = form.querySelector('[data-inventory-more-invoices]');
    const error = form.querySelector('[data-inventory-payment-error]');
    const loading = form.querySelector('[data-inventory-payment-loading]');
    const direction = form.querySelector('[name="direction"]');
    const party = form.querySelector('[data-inventory-payment-party]');
    let page = 1;
    let nextIndex = Math.max(-1, ...Array.from(table.querySelectorAll('[name]'), input => Number(input.name.match(/^allocations\[(\d+)\]/)?.[1]) || 0)) + 1;
    let pending = null;
    function refreshCash() {
        let total = 0n;
        table.querySelectorAll('[name$="[amount]"]:enabled').forEach(input => { total += inventoryMinor(input.value); });
        form.querySelector('[data-inventory-cash-total]').textContent = inventoryDecimal(total);
    }
    function clearInvoices() {
        const previous = pending;
        pending = null;
        previous?.abort();
        table.replaceChildren();
        page = 1;
        nextIndex = 0;
        more.classList.add('d-none');
        more.textContent = 'Load more';
        more.disabled = false;
        loading.hidden = true;
        error.classList.add('d-none');
        refreshCash();
    }
    async function loadInvoices(append) {
        if (!party.value) {
            window.showToast('Select a customer or supplier first.', 'warning');
            return;
        }
        if (!append) clearInvoices();
        const partyId = party.value;
        const params = { direction: direction.value, product_type: form.querySelector('[name="product_type"]').value, page };
        params[party.name] = partyId;
        more.disabled = true;
        loading.hidden = false;
        error.classList.add('d-none');
        const request = $.ajax({ url: form.dataset.invoicesUrl, data: params, dataType: 'json', timeout: 15000 });
        pending = request;
        try {
            const data = await request;
            if (pending !== request || party.value !== partyId) return;
            const existing = new Set(Array.from(table.children, row => row.dataset.documentId));
            for (const invoice of data.results) {
                if (!existing.has(String(invoice.id))) table.append(allocationRow(invoice, nextIndex++, form.dataset.canDiscount === '1'));
            }
            refreshCash();
            more.textContent = 'Load more';
            more.classList.toggle('d-none', !data.pagination.more);
            page += 1;
            if (table.children.length === 0) {
                window.showToast('No outstanding invoices found.', 'info');
            }
        } catch (xhr) {
            if (pending !== request) return;
            if (xhr.statusText !== 'abort') {
                const message = xhr.responseJSON?.message || 'Invoices could not be loaded. Please try again.';
                if (xhr.status === 422) {
                    error.textContent = message;
                    error.classList.remove('d-none');
                } else {
                    window.showToast(message, 'error');
                }
                more.textContent = 'Retry loading';
                more.classList.remove('d-none');
            }
        } finally {
            if (pending === request) {
                pending = null;
                loading.hidden = true;
                more.disabled = false;
            }
        }
    }
    more.addEventListener('click', () => loadInvoices(true));
    $(party).on('change', () => {
        if (party.value) loadInvoices(false);
        else clearInvoices();
    });
    form.addEventListener('change', event => {
        if (event.target.matches('[data-inventory-allocation-select]')) {
            const row = event.target.closest('[data-inventory-allocation]');
            row.querySelectorAll('[name]').forEach(input => { input.disabled = !event.target.checked; });
            const amount = row.querySelector('[name$="[amount]"]');
            if (event.target.checked && !Number(amount.value)) amount.value = row.dataset.due || '0.00';
        }
        refreshCash();
    });
    form.addEventListener('input', refreshCash);
    refreshCash();
    if (party.value && table.children.length === 0) loadInvoices(false);
}

$(function () {
    document.querySelectorAll('form[data-inventory-return-source]').forEach(form => {
        const source = form.elements.namedItem('source_document_id');
        if (source instanceof HTMLSelectElement) $(source).on('change', () => form.requestSubmit());
    });
    document.querySelectorAll('[data-inventory-document]').forEach(initializeDocument);
    document.querySelectorAll('[data-inventory-product-form]').forEach(initializeProductForm);
    document.querySelectorAll('[data-inventory-variant-form]').forEach(initializeVariantForm);
    document.querySelectorAll('[data-inventory-customer-create]').forEach(initializeCustomerModal);
    document.querySelectorAll('[data-inventory-payment]').forEach(initializePayment);
    document.querySelectorAll('[data-inventory-print]').forEach(button => button.addEventListener('click', () => window.print()));
    document.querySelectorAll('[data-inventory-confirm]').forEach(form => form.addEventListener('submit', event => {
        if (!window.confirm(form.dataset.inventoryConfirm)) event.preventDefault();
    }));
});
