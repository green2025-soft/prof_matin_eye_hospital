import $ from 'jquery';
import { Modal } from 'bootstrap';

const controlSelector = 'input, select, textarea';

function copyDataset(element) {
    return { ...element.dataset };
}

function restoreDataset(element, values) {
    for (const key of Object.keys(element.dataset)) delete element.dataset[key];
    Object.assign(element.dataset, values);
}

function snapshotRow(row) {
    const controls = Array.from(row.querySelectorAll(controlSelector), input => ({
        input,
        value: input.value,
        checked: input.checked,
        disabled: input.disabled,
        hidden: input.hidden,
        required: input.required,
        className: input.className,
        ariaInvalid: input.getAttribute('aria-invalid'),
        dataset: copyDataset(input),
        customValidity: input.validity.customError ? input.validationMessage : '',
        options: input instanceof HTMLSelectElement
            ? Array.from(input.options, option => option.cloneNode(true)) : null,
    }));
    const pickers = Array.from(row.querySelectorAll('[data-datepicker], [data-timepicker]'), wrapper => {
        const picker = wrapper._flatpickr || wrapper.querySelector('[data-input]')?._flatpickr;
        return {
            wrapper,
            value: wrapper.querySelector('[data-input]')?.value || '',
            dates: picker ? picker.selectedDates.map(date => new Date(date.getTime())) : null,
        };
    });
    return { controls, pickers, dataset: copyDataset(row), profiles: row._unitProfiles?.map(profile => ({ ...profile })) };
}

function restoreRow(row, snapshot) {
    restoreDataset(row, snapshot.dataset);
    row._unitProfiles = snapshot.profiles;
    for (const state of snapshot.controls) {
        const { input } = state;
        if (state.options) input.replaceChildren(...state.options.map(option => option.cloneNode(true)));
        input.value = state.value;
        if ('checked' in input) input.checked = state.checked;
        input.disabled = state.disabled;
        input.hidden = state.hidden;
        input.required = state.required;
        input.className = state.className;
        restoreDataset(input, state.dataset);
        input.setCustomValidity(state.customValidity);
        if (state.ariaInvalid === null) input.removeAttribute('aria-invalid');
        else input.setAttribute('aria-invalid', state.ariaInvalid);
    }
    for (const { wrapper, dates, value } of snapshot.pickers) {
        const picker = wrapper._flatpickr || wrapper.querySelector('[data-input]')?._flatpickr;
        picker?.setDate(dates ?? value, false);
    }
    // Flatpickr restores its selected dates; retain any original typed value as well.
    for (const { input, value } of snapshot.controls) input.value = value;
}

function closeDatePickers(row, destroy = false) {
    row.querySelectorAll('[data-datepicker], [data-timepicker]').forEach(wrapper => {
        const picker = wrapper._flatpickr || wrapper.querySelector('[data-input]')?._flatpickr;
        if (destroy) picker?.destroy();
        else picker?.close();
    });
}

function writePayload(row, payload) {
    const fragment = document.createDocumentFragment();
    for (const input of row.querySelectorAll(controlSelector)) {
        // The stored FIELDSET is disabled. Only the control's own flag describes
        // whether its current basis/value belongs in the submitted line.
        if (!input.name || input.disabled || input.closest('[data-inventory-hidden-item-field]')
            || ['button', 'submit', 'reset', 'file'].includes(input.type)) continue;
        if (['checkbox', 'radio'].includes(input.type) && !input.checked) continue;
        const values = input instanceof HTMLSelectElement && input.multiple
            ? Array.from(input.selectedOptions, option => option.value) : [input.value];
        for (const value of values) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = input.name;
            hidden.value = value;
            fragment.append(hidden);
        }
    }
    payload.replaceChildren(fragment);
}

export function initializeTradeEditor(form, {
    selectVariant,
    applyPurchaseUnit,
    populatePurchaseUnits,
    refreshDocumentTotal,
    parseInteger,
    setDefaultPrice,
}) {
    const editor = form.querySelector('[data-inventory-item-editor]');
    const storage = form.querySelector('[data-inventory-line-storage]');
    const list = form.querySelector('[data-inventory-item-list]');
    const template = form.querySelector('[data-inventory-trade-template]');
    const summaryTemplate = form.querySelector('[data-inventory-summary-template]');
    const picker = form.querySelector('[data-inventory-product-picker]');
    if (!editor || !storage || !list || !template || !summaryTemplate || !picker) return;

    const editorPanel = form.querySelector('[data-inventory-editor-panel]');
    const $picker = $(picker);
    const empty = list.querySelector('[data-inventory-empty]');
    const editorTitle = form.querySelector('[data-inventory-editor-title]');
    const editorEmpty = form.querySelector('[data-inventory-editor-empty]');
    const activeProduct = form.querySelector('[data-inventory-active-product]');
    const activeProductName = form.querySelector('[data-inventory-active-product-name]');
    const activeProductCode = form.querySelector('[data-inventory-active-product-code]');
    const activeProductStock = form.querySelector('[data-inventory-active-product-stock]');
    const commitButton = form.querySelector('[data-inventory-commit-item]');
    const cancelButton = form.querySelector('[data-inventory-cancel-item]');
    const feedback = form.querySelector('[data-inventory-product-feedback]');
    const packModalElement = document.querySelector('[data-inventory-pack-modal]');
    const packSize = packModalElement?.querySelector('[data-inventory-pack-size]');
    const revisionStock = JSON.parse(form.dataset.inventoryRevisionStock || '{}');
    const entries = new Map();
    const originalRows = Array.from(storage.querySelectorAll('fieldset[data-inventory-line]'));
    const indexes = originalRows.map(row => Number(row.dataset.index));
    let nextIndex = Math.max(-1, ...indexes.filter(index => Number.isSafeInteger(index) && index >= 0)) + 1;
    let active = null;
    let pendingPack = null;
    let packFocusTarget = null;
    let lastVatInput = null;
    let lastVatMode = null;
    let lastDiscountInput = null;
    form._purchasePacks = new Map();

    function announce(message, error = false) {
        if (error) window.showToast?.(message, 'error');
        if (!feedback) return;
        feedback.textContent = message;
        feedback.classList.toggle('text-danger', error);
        feedback.classList.toggle('text-body-secondary', !error);
    }

    function focusPicker(open = true) {
        closeDatePickers(editor);
        window.setTimeout(() => {
            if (!$picker.hasClass('select2-hidden-accessible')) {
                picker.focus();
                return;
            }
            if (open) {
                $picker.select2('open');
                return;
            }
            $picker.select2('close');
            $picker.next('.select2-container').find('.select2-selection').trigger('focus');
        }, 0);
    }

    function revealControlDetails(input) {
        let details = input.closest('details');
        while (details) {
            details.open = true;
            details = details.parentElement?.closest('details');
        }
    }

    function focusControl(input) {
        if (!input) return;
        revealControlDetails(input);
        input.focus();
        input.select?.();
    }

    function updateEditor() {
        if (editorPanel) editorPanel.hidden = !active;
        editor.hidden = !active;
        if (editorEmpty) editorEmpty.hidden = Boolean(active);
        if (editorTitle) editorTitle.textContent = active?.entry ? 'Edit item' : 'Add item';
        if (activeProduct) activeProduct.hidden = !active;
        if (activeProductName) activeProductName.textContent = active?.row.querySelector('[data-inventory-product-name]')?.textContent.trim() || '';
        if (activeProductCode) activeProductCode.textContent = active?.row.querySelector('[data-inventory-product-code]')?.textContent.trim() || '';
        if (activeProductStock) {
            const stock = active?.row.querySelector('[data-inventory-product-stock]');
            activeProductStock.textContent = stock?.textContent.trim() || '';
            activeProductStock.hidden = !stock || stock.classList.contains('d-none') || !activeProductStock.textContent;
        }
        if (commitButton) {
            commitButton.disabled = !active;
            commitButton.textContent = active?.entry ? 'Update item' : 'Add item';
        }
        if (cancelButton) cancelButton.hidden = !active;
    }

    function setPayloadDisabled(entry, disabled) {
        entry.payload.querySelectorAll('input').forEach(input => { input.disabled = disabled; });
        entry.summary.classList.toggle('table-active', disabled);
    }

    function renderSummary(entry) {
        const { row, summary } = entry;
        const values = {
            name: row.querySelector('[data-inventory-product-name]')?.textContent.trim() || '',
            code: row.querySelector('[data-inventory-product-code]')?.textContent.trim() || '',
            quantity: row.dataset.summaryQuantity || '',
            rate: row.dataset.summaryRate || '0.00',
            discount: row.dataset.summaryDiscount || '0.00',
            vat: row.dataset.summaryVat || '0.00',
            expiry: row.querySelector('[name$="[expiry_date]"]')?.value || '—',
            amount: row.dataset.summaryAmount || '0.00',
            'sale-price': row.dataset.summarySalePrice || '—',
        };
        for (const [key, value] of Object.entries(values)) {
            const output = summary.querySelector(`[data-inventory-summary-${key}]`);
            if (output) output.textContent = value;
        }
        summary.querySelector('[data-inventory-edit-line]')?.setAttribute('aria-label', `Edit ${values.name}`);
        summary.querySelector('[data-inventory-remove-line]')?.setAttribute('aria-label', `Remove ${values.name}`);
    }

    function refreshRows() {
        refreshDocumentTotal(form);
        if (empty) empty.hidden = entries.size > 0;
        entries.forEach(renderSummary);
    }

    function createEntry(row, prepend = false) {
        const summary = summaryTemplate.content.firstElementChild.cloneNode(true);
        summary.dataset.index = row.dataset.index;
        const entry = { row, summary, payload: summary.querySelector('[data-inventory-item-payload]') };
        entries.set(row.dataset.index, entry);
        if (prepend && empty) empty.after(summary);
        else if (prepend) list.prepend(summary);
        else list.append(summary);
        return entry;
    }

    function storeRow(row, placeholder = null) {
        closeDatePickers(row);
        row.disabled = true;
        if (placeholder?.isConnected) placeholder.replaceWith(row);
        else storage.append(row);
    }

    function validateActive() {
        if (!active) return true;
        refreshRows();
        const controls = Array.from(active.row.querySelectorAll(controlSelector)).filter(input => !input.matches(':disabled'));
        for (const input of controls) input.dataset.touched = '1';
        refreshRows();
        const invalid = controls.find(input => input.willValidate && !input.checkValidity());
        if (!invalid) return true;
        focusControl(invalid);
        invalid.reportValidity();
        return false;
    }

    function commitActive() {
        if (!active) return true;
        if (!validateActive()) return false;
        const { row, placeholder } = active;
        const entry = active.entry || createEntry(row, true);
        writePayload(row, entry.payload);
        row.dataset.committed = '1';
        storeRow(row, placeholder);
        setPayloadDisabled(entry, false);
        active = null;
        updateEditor();
        refreshRows();
        return true;
    }

    function openEntry(entry, preferredInput = null) {
        if (active?.row === entry.row) {
            focusControl(preferredInput || entry.row.querySelector('[name$="[quantity]"]'));
            return true;
        }
        if (!commitActive()) return false;
        const placeholder = document.createComment(`Inventory item ${entry.row.dataset.index}`);
        entry.row.before(placeholder);
        active = { row: entry.row, entry, placeholder, snapshot: snapshotRow(entry.row) };
        entry.row.disabled = false;
        editor.replaceChildren(entry.row);
        setPayloadDisabled(entry, true);
        window.initDateTimePickers?.(entry.row);
        updateEditor();
        refreshRows();
        focusControl(preferredInput || entry.row.querySelector('[name$="[quantity]"]'));
        return true;
    }

    function cancelActive() {
        if (!active) return;
        const { row, entry, snapshot, placeholder } = active;
        active = null;
        if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
        if (entry) {
            restoreRow(row, snapshot);
            const unit = row.querySelector('[data-inventory-purchase-unit]');
            if (unit) applyPurchaseUnit(row, unit.selectedOptions[0]);
            storeRow(row, placeholder);
            setPayloadDisabled(entry, false);
        } else {
            closeDatePickers(row, true);
            row.remove();
        }
        updateEditor();
        refreshRows();
        announce('Item changes cancelled.');
        focusPicker(false);
    }

    function removeRow(row) {
        if (!row) return;
        const entry = entries.get(row.dataset.index);
        const name = row.querySelector('[data-inventory-product-name]')?.textContent.trim() || 'Item';
        if (active?.row === row) {
            active.placeholder?.remove();
            active = null;
            updateEditor();
        }
        closeDatePickers(row, true);
        row.remove();
        entry?.summary.remove();
        entries.delete(row.dataset.index);
        refreshRows();
        announce(`${name} removed.`);
        focusPicker();
    }

    function findVariant(id) {
        return Array.from(form.querySelectorAll('fieldset[data-inventory-line]')).find(row =>
            row.querySelector('[name$="[variant_id]"]')?.value === String(id));
    }

    function startProduct(product) {
        const existing = form.dataset.type === 'sale' ? findVariant(product.id) : null;
        const availableQuantity = parseInteger(product.available_quantity) + parseInteger(revisionStock[product.id]);
        if (existing && active?.row === existing) {
            focusControl(existing.querySelector('[name$="[quantity]"]'));
            return;
        }
        if (!commitActive()) {
            announce('Finish the current item before choosing another product.', true);
            return;
        }
        if (existing) {
            openEntry(entries.get(existing.dataset.index));
            announce('This product is already added. Update its quantity.');
            return;
        }
        if (form.dataset.type === 'sale' && form.dataset.allowWithoutStock !== '1'
            && availableQuantity < 1n) {
            announce('This product has no available stock.', true);
            focusPicker();
            return;
        }
        if (entries.size >= 100) {
            announce('An invoice can contain up to 100 items.', true);
            return;
        }
        if (form.dataset.type !== 'purchase' && (!Array.isArray(product.units) || product.units.length === 0)) {
            announce('Set up a base unit for this product before adding it.', true);
            return;
        }

        const fragment = document.createElement('template');
        fragment.innerHTML = template.innerHTML.replaceAll('__INDEX__', String(nextIndex++));
        const row = fragment.content.querySelector('fieldset[data-inventory-line]');
        if (!row) return;
        row.disabled = false;
        row.querySelector('[name$="[variant_id]"]').value = product.id;
        const name = product.product_name || product.name;
        row.querySelector('[data-inventory-product-name]').textContent = name;
        const description = [product.sku, product.variant_name === 'Default' ? '' : product.variant_name, product.color, product.size];
        if (product.sph !== null && product.sph !== undefined) description.push(`SPH ${product.sph}`);
        if (product.cyl !== null && product.cyl !== undefined) description.push(`CYL ${product.cyl}`);
        row.querySelector('[data-inventory-product-code]').textContent = description.filter(Boolean).join(' / ');
        if (form.dataset.type === 'sale' && product.available_quantity !== undefined) {
            row.dataset.availableQuantity = String(availableQuantity);
            const stock = row.querySelector('[data-inventory-product-stock]');
            if (stock) {
                stock.textContent = new Intl.NumberFormat('en-BD').format(availableQuantity);
            }
        }
        for (const [hook, value] of [
            ['data-inventory-line-vat-input', lastVatInput],
            ['data-inventory-line-vat-mode', lastVatMode],
            ['data-inventory-line-discount-input', lastDiscountInput],
        ]) {
            const input = row.querySelector(`[${hook}]`);
            if (input && value !== null && !input.closest('[data-inventory-hidden-item-field]')) input.value = value;
        }
        active = { row, entry: null, snapshot: null, placeholder: null };
        editor.replaceChildren(row);
        selectVariant(row, product);
        window.initDateTimePickers?.(row);
        updateEditor();
        refreshRows();
        announce(`${name} selected. Add the item when ready.`);
        focusControl(row.querySelector('[name$="[quantity]"]'));
    }

    function openPackEditor(row, option, trigger) {
        if (!packModalElement || !option?.dataset.unitId || option.dataset.fixed === '1') return;
        if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
        closeDatePickers(row);
        pendingPack = { row, option };
        packFocusTarget = trigger;
        packModalElement.querySelector('[data-inventory-pack-label]').textContent = `Pieces per ${option.dataset.unitName}`;
        packSize.value = option.dataset.factor || '';
        packSize.setCustomValidity('');
        Modal.getOrCreateInstance(packModalElement).show();
    }

    function openUnitChooser() {
        const unit = active?.row.querySelector('[data-inventory-purchase-unit], [data-inventory-unit]');
        if (!unit || unit.disabled) return;
        closeDatePickers(active.row);
        if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
        unit.focus();
        if (typeof unit.showPicker === 'function') {
            try {
                unit.showPicker();
            } catch (error) {
                // Keep native keyboard selection available when opening is blocked.
                if (!(error instanceof DOMException)) throw error;
            }
        }
    }

    function selectUnitShortcut(symbol) {
        const row = active.row;
        const unit = row.querySelector('[data-inventory-purchase-unit], [data-inventory-unit]');
        if (!unit || unit.disabled) return;
        const purchase = unit.hasAttribute('data-inventory-purchase-unit');
        const choice = purchase ? row._purchaseUnits?.find(item => item.symbol === symbol) : null;
        const options = Array.from(unit.options).filter(option => purchase
            ? choice && option.dataset.unitId === choice.id
            : option.dataset.symbol?.toLowerCase() === symbol && (symbol !== 'pcs' || option.dataset.factor === '1'));
        const saved = options.filter(option => option.dataset.factor);
        const selected = saved.find(option => option.selected);
        const option = selected || (saved.length === 1 ? saved[0] : options.find(item => !item.dataset.factor));
        if (!option) {
            announce(saved.length > 1 ? 'Choose the Box pack size.' : 'This unit is not set up for this product.', true);
            openUnitChooser();
            return;
        }
        closeDatePickers(row);
        if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
        if (unit.value !== option.value) {
            unit.value = option.value;
            unit.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (!pendingPack) focusControl(row.querySelector('[name$="[quantity]"]'));
    }

    function confirmPack() {
        if (!pendingPack || pendingPack.row !== active?.row) return;
        const factor = parseInteger(packSize.value);
        packSize.setCustomValidity(factor > 0n && factor <= 1000000n ? '' : 'Enter the pieces per pack.');
        if (!packSize.reportValidity()) return;
        const { row, option } = pendingPack;
        const profile = { unit_id: option.dataset.unitId, factor: String(factor) };
        const profiles = form._purchasePacks.get(row.dataset.productId) || [];
        if (!profiles.some(item => String(item.unit_id) === profile.unit_id && String(item.factor) === profile.factor)) profiles.push(profile);
        form._purchasePacks.set(row.dataset.productId, profiles);
        form.querySelectorAll('fieldset[data-inventory-line]').forEach(other => {
            if (other.dataset.productId === row.dataset.productId) populatePurchaseUnits(other, other._unitProfiles || []);
        });
        const unit = row.querySelector('[data-inventory-purchase-unit]');
        unit.value = `pack:${profile.unit_id}:${profile.factor}`;
        applyPurchaseUnit(row, unit.selectedOptions[0], true);
        pendingPack = null;
        packFocusTarget = row.querySelector('[name$="[quantity]"]');
        Modal.getInstance(packModalElement).hide();
        refreshRows();
    }

    if (packModalElement) {
        packModalElement.addEventListener('shown.bs.modal', () => focusControl(packSize));
        packModalElement.addEventListener('hidden.bs.modal', () => {
            if (pendingPack?.row.isConnected) {
                const unit = pendingPack.row.querySelector('[data-inventory-purchase-unit]');
                unit.value = pendingPack.row.dataset.selectedPurchaseUnit || '';
                applyPurchaseUnit(pendingPack.row, unit.selectedOptions[0]);
            }
            pendingPack = null;
            focusControl(packFocusTarget?.isConnected ? packFocusTarget : null);
            packFocusTarget = null;
        });
        packModalElement.querySelector('[data-inventory-pack-confirm]').addEventListener('click', confirmPack);
        packSize.addEventListener('input', () => packSize.setCustomValidity(''));
        packSize.addEventListener('keydown', event => {
            if (event.key !== 'Enter') return;
            event.preventDefault();
            confirmPack();
        });
    }

    $picker.on('select2:select', event => {
        const product = event.params.data;
        $picker.val(null).trigger('change.select2');
        startProduct(product);
    });
    commitButton?.addEventListener('click', () => {
        if (commitActive()) {
            announce('Item saved.');
            focusPicker();
        }
    });
    cancelButton?.addEventListener('click', cancelActive);
    form.addEventListener('change', event => {
        const row = event.target.closest('fieldset[data-inventory-line]');
        if (row && event.target.matches('[data-inventory-unit]')) setDefaultPrice(row);
        if (row && event.target.matches('[data-inventory-purchase-unit]')) {
            const option = event.target.selectedOptions[0];
            const edit = row.querySelector('[data-inventory-edit-unit]');
            if (edit) edit.disabled = !option?.dataset.factor || option.dataset.fixed === '1';
            if (option?.dataset.factor) applyPurchaseUnit(row, option, true);
            else openPackEditor(row, option, event.target);
        }
        if (row && event.target.matches('[data-inventory-line-vat-input]')) {
            lastVatInput = event.target.value;
            if (lastVatInput !== 'percent') row.querySelector('[data-inventory-line-vat-amount]').value = '';
        }
        if (row && event.target.matches('[data-inventory-line-vat-mode]')) lastVatMode = event.target.value;
        if (row && event.target.matches('[data-inventory-line-discount-input]')) {
            lastDiscountInput = event.target.value;
            row.querySelector('[data-inventory-line-discount-value]').value = '';
        }
        refreshRows();
    });
    form.addEventListener('input', refreshRows);
    form.addEventListener('focusin', event => {
        if (event.target.matches('input[type="number"]')) event.target.select();
    });
    form.addEventListener('focusout', event => {
        if (!event.target.matches('input[type="number"], [data-inventory-purchase-unit]')) return;
        event.target.dataset.touched = '1';
        refreshRows();
    });
    form.addEventListener('click', event => {
        const packEdit = event.target.closest('[data-inventory-edit-unit]');
        if (packEdit) {
            const row = packEdit.closest('fieldset[data-inventory-line]');
            openPackEditor(row, row.querySelector('[data-inventory-purchase-unit]').selectedOptions[0], packEdit);
            return;
        }
        const edit = event.target.closest('[data-inventory-edit-line]');
        const remove = event.target.closest('[data-inventory-remove-line]');
        const summary = (edit || remove)?.closest('tr[data-index]');
        const entry = summary ? entries.get(summary.dataset.index) : null;
        if (edit && entry) openEntry(entry);
        else if (remove) removeRow(entry?.row || remove.closest('fieldset[data-inventory-line]'));
    });
    document.addEventListener('keydown', event => {
        if (event.isComposing || event.repeat || document.querySelector('.modal.show')) return;
        if (event.key === 'F2') {
            event.preventDefault();
            event.stopPropagation();
            focusPicker();
            return;
        }
        if (!form.contains(event.target) && !$picker.data('select2')?.isOpen()) return;
        const row = event.target.closest('fieldset[data-inventory-line]');
        if (event.key === 'Escape' && !event.ctrlKey && !event.metaKey && !event.altKey && !event.shiftKey && active) {
            if (document.querySelector('.select2-container--open, .flatpickr-calendar.open')) return;
            if (event.target instanceof HTMLSelectElement && CSS.supports('selector(select:open)') && event.target.matches(':open')) return;
            event.preventDefault();
            event.stopPropagation();
            cancelActive();
            return;
        }
        if (event.altKey && !event.ctrlKey && !event.metaKey && !event.shiftKey && active) {
            const key = event.key.toLowerCase();
            if (key === 'b' || key === 'p' || key === 'a') {
                event.preventDefault();
                event.stopPropagation();
                if (key === 'a') commitButton?.click();
                else selectUnitShortcut(key === 'b' ? 'box' : 'pcs');
                return;
            }
        }
        if (event.key === 'F4' && !event.ctrlKey && !event.metaKey && !event.altKey && !event.shiftKey && active) {
            event.preventDefault();
            event.stopPropagation();
            openUnitChooser();
            return;
        }
        if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
            event.preventDefault();
            event.stopPropagation();
            if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
            refreshRows();
            form.requestSubmit();
            return;
        }
        if (event.altKey && event.key.toLowerCase() === 'u' && active) {
            const unit = active.row.querySelector('[data-inventory-purchase-unit]');
            const edit = active.row.querySelector('[data-inventory-edit-unit]');
            if (unit && edit && !edit.disabled) {
                event.preventDefault();
                event.stopPropagation();
                openPackEditor(active.row, unit.selectedOptions[0], event.target);
            }
            return;
        }
        if (event.altKey && event.key === 'Delete') {
            const summary = event.target.closest('tr[data-index]');
            const target = row || entries.get(summary?.dataset.index)?.row || active?.row;
            if (target) {
                event.preventDefault();
                event.stopPropagation();
                removeRow(target);
            }
            return;
        }
        if (event.key !== 'Enter' || event.ctrlKey || event.metaKey || event.altKey || row !== active?.row) return;
        if (!event.target.matches('input:not([type="hidden"]), select, textarea')) return;
        const datePicker = event.target._flatpickr || event.target.closest('[data-datepicker]')?._flatpickr;
        if (datePicker?.isOpen) return;
        if (event.target instanceof HTMLSelectElement && CSS.supports('selector(select:open)') && event.target.matches(':open')) return;
        event.preventDefault();
        event.stopPropagation();
        const fields = Array.from(row.querySelectorAll('input:not([type="hidden"]), select, textarea'))
            .filter(input => !input.matches(':disabled') && !input.readOnly && !input.hidden
                && !input.closest('details:not([open])') && input.getClientRects().length > 0);
        const next = fields[fields.indexOf(event.target) + (event.shiftKey ? -1 : 1)];
        if (next) focusControl(next);
        else if (event.shiftKey || commitActive()) focusPicker();
    }, true);
    form.addEventListener('invalid', event => {
        if (active?.row.contains(event.target)) revealControlDetails(event.target);
    }, true);
    form.addEventListener('submit', event => {
        if (!commitActive()) {
            event.preventDefault();
            return;
        }
        refreshRows();
        for (const entry of entries.values()) {
            const invalid = Array.from(entry.row.querySelectorAll(controlSelector))
                .find(input => !input.disabled && !input.closest('[data-inventory-hidden-item-field]') && input.dataset.validationMessage);
            if (!invalid) continue;
            event.preventDefault();
            openEntry(entry, invalid);
            invalid.reportValidity();
            return;
        }
        if (entries.size === 0) {
            event.preventDefault();
            announce('Add at least one product before saving.', true);
            focusPicker();
        }
    });

    for (const row of originalRows) {
        row.disabled = true;
        row.dataset.committed = '1';
        if (row.querySelector('[data-inventory-purchase-unit]')) {
            try { row._unitProfiles = JSON.parse(row.dataset.unitProfiles || '[]'); }
            catch { row._unitProfiles = []; }
            populatePurchaseUnits(row, row._unitProfiles);
        }
        createEntry(row);
    }
    updateEditor();
    refreshRows();
    entries.forEach(entry => writePayload(entry.row, entry.payload));
    const invalidEntry = Array.from(entries.values()).find(entry =>
        entry.row.querySelector('.invalid-feedback') ||
        Array.from(entry.row.querySelectorAll('.is-invalid, [aria-invalid="true"]'))
            .some(input => !input.disabled && !input.closest('[data-inventory-hidden-item-field]')));
    if (invalidEntry) {
        const invalid = Array.from(invalidEntry.row.querySelectorAll('.is-invalid, [aria-invalid="true"]'))
            .find(input => !input.disabled && !input.closest('[data-inventory-hidden-item-field]'));
        openEntry(invalidEntry, invalid);
    }
}
