import $ from './jquery';
import 'bootstrap';

const controlSelector = 'input, select, textarea';
const rowSelector = 'fieldset[data-pharmacy-line]';

function copyDataAttributes($element) {
    const values = {};
    $.each($element.prop('attributes'), (_, attribute) => {
        if (attribute.name.startsWith('data-')) values[attribute.name] = $element.attr(attribute.name);
    });
    return values;
}

function restoreDataAttributes($element, values) {
    $element.removeAttr(Object.keys(copyDataAttributes($element)).join(' ')).attr(values);
}

function snapshotRow($row) {
    const controls = $row.find(controlSelector).map(function () {
        const $input = $(this);
        return {
            input: this,
            value: $input.val(),
            checked: $input.prop('checked'),
            disabled: $input.prop('disabled'),
            hidden: $input.prop('hidden'),
            required: $input.prop('required'),
            className: $input.attr('class') || '',
            ariaInvalid: $input.attr('aria-invalid') ?? null,
            dataAttributes: copyDataAttributes($input),
            customValidity: $input.prop('validity').customError ? $input.prop('validationMessage') : '',
            options: $input.is('select') ? $input.find('option').clone().toArray() : null,
        };
    }).get();
    const pickers = $row.find('[data-datepicker], [data-timepicker]').map(function () {
        const $wrapper = $(this);
        const $input = $wrapper.find('[data-input]').first();
        const picker = this._flatpickr || $input.get(0)?._flatpickr;
        return {
            $wrapper,
            value: $input.val() || '',
            dates: picker ? picker.selectedDates.map(date => new Date(date.getTime())) : null,
        };
    }).get();
    const row = $row.get(0);
    return {
        controls, pickers, dataAttributes: copyDataAttributes($row),
        product: { ...row._pharmacyProduct },
        profiles: row._unitProfiles?.map(profile => ({ ...profile })),
    };
}

function restoreRow($row, snapshot) {
    const row = $row.get(0);
    restoreDataAttributes($row, snapshot.dataAttributes);
    row._pharmacyProduct = snapshot.product;
    row._unitProfiles = snapshot.profiles;
    for (const state of snapshot.controls) {
        const { input } = state;
        const $input = $(input);
        if (state.options) $input.empty().append($(state.options).clone());
        $input.val(state.value).prop({
            disabled: state.disabled,
            hidden: state.hidden,
            required: state.required,
        }).attr('class', state.className);
        if ('checked' in input) $input.prop('checked', state.checked);
        restoreDataAttributes($input, state.dataAttributes);
        input.setCustomValidity(state.customValidity);
        if (state.ariaInvalid === null) $input.removeAttr('aria-invalid');
        else $input.attr('aria-invalid', state.ariaInvalid);
    }
    for (const { $wrapper, dates, value } of snapshot.pickers) {
        const picker = $wrapper.get(0)._flatpickr || $wrapper.find('[data-input]').get(0)?._flatpickr;
        picker?.setDate(dates ?? value, false);
    }
    // Flatpickr restores dates; retain the original typed input as well.
    for (const { input, value } of snapshot.controls) $(input).val(value);
}

function closeDatePickers($root, destroy = false) {
    $root.find('[data-datepicker], [data-timepicker]').each(function () {
        const picker = this._flatpickr || $(this).find('[data-input]').get(0)?._flatpickr;
        if (destroy) picker?.destroy();
        else picker?.close();
    });
}

function writePayload($row, $payload) {
    const inputs = [];
    $row.find(controlSelector).each(function () {
        const $input = $(this);
        // The stored row is disabled. Only own flags and disabled inner
        // fieldsets determine which controls belong in the submitted line.
        if (!this.name || $input.prop('disabled') || $input.closest('[data-pharmacy-hidden-item-field]').length
            || $input.parentsUntil($row, 'fieldset[disabled]').length
            || ['button', 'submit', 'reset', 'file'].includes(this.type)) return;
        if (['checkbox', 'radio'].includes(this.type) && !$input.prop('checked')) return;
        const value = $input.val();
        const values = Array.isArray(value) ? value : [value ?? ''];
        for (const item of values) inputs.push($('<input>', { type: 'hidden', name: this.name, value: item }).get(0));
    });
    $payload.empty().append(inputs);
}

export function initializeTradeEditor(form, {
    selectVariant,
    applyPurchaseUnit,
    populatePurchaseUnits,
    refreshEntryTotal,
    parseInteger,
    setDefaultPrice,
}) {
    const $form = $(form);
    const $editor = $form.find('[data-pharmacy-item-editor]');
    const $storage = $form.find('[data-pharmacy-line-storage]');
    const $list = $form.find('[data-pharmacy-item-list]');
    const $template = $form.find('[data-pharmacy-trade-template]');
    const $summaryTemplate = $form.find('[data-pharmacy-summary-template]');
    const $picker = $form.find('#pharmacy_product_picker');
    if (!$editor.length || !$storage.length || !$list.length || !$template.length || !$summaryTemplate.length || !$picker.length) return;

    const $editorPanel = $form.find('[data-pharmacy-editor-panel]');
    const $empty = $list.find('[data-pharmacy-empty]');
    const $activeProduct = $form.find('[data-pharmacy-active-product]');
    const $editorTitle = $activeProduct.children('h3');
    const $activeProductName = $activeProduct.children('strong');
    const $activeProductCode = $activeProduct.children('small');
    const $commitButton = $form.find('[data-pharmacy-commit-item]');
    const $cancelButton = $form.find('[data-pharmacy-cancel-item]');
    const $feedback = $form.find('#pharmacy-product-feedback');
    const $packModal = $('[data-pharmacy-pack-modal]');
    const $packSize = $packModal.find('[data-pharmacy-pack-size]');
    const options = form._pharmacyOptions;
    const revisionStock = options.revisionStock;
    const entries = new Map();
    const $originalRows = $storage.find(rowSelector);
    const indexes = $originalRows.map((_, row) => Number($(row).attr('data-index'))).get();
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
        $feedback.text(message).toggleClass('text-danger', error).toggleClass('text-body-secondary', !error);
    }

    function focusPicker(open = true) {
        closeDatePickers($editor);
        window.setTimeout(() => {
            if (!$picker.hasClass('select2-hidden-accessible')) {
                $picker.trigger('focus');
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
        $(input).parents('details').prop('open', true);
    }

    function focusControl(input) {
        if (!input) return;
        revealControlDetails(input);
        const $input = $(input).trigger('focus');
        if ($input.is('input, textarea')) $input.trigger('select');
    }

    function updateEditor() {
        const $row = active?.$row || $();
        $editorPanel.add($editor).add($activeProduct).prop('hidden', !active);
        $editorTitle.text(active?.entry ? 'Edit item' : 'Add item');
        $activeProductName.text($row.find('.app-pharmacy-entry-name').text().trim());
        $activeProductCode.text($row.find('.app-pharmacy-entry-code').text().trim());
        $commitButton.prop('disabled', !active).text(active?.entry ? 'Update item' : 'Add item');
        $cancelButton.prop('hidden', !active);
    }

    function setPayloadDisabled(entry, disabled) {
        entry.$payload.find('input').prop('disabled', disabled);
        entry.$summary.toggleClass('table-active', disabled);
    }

    function renderSummary(entry) {
        const { $row, $summary } = entry;
        const name = $row.find('.app-pharmacy-entry-name').text().trim();
        $summary.find('.app-pharmacy-entry-name').text(name);
        $summary.find('.app-pharmacy-entry-code').text($row.find('.app-pharmacy-entry-code').text().trim());
        const values = {
            expiry: $row.find('[name$="[expiry_date]"]').val() || '—',
            ...$row.get(0)._pharmacySummary,
        };
        for (const [key, value] of Object.entries(values)) $summary.find(`[data-pharmacy-summary-${key}]`).text(value);
        $summary.find('[data-pharmacy-edit-line]').attr('aria-label', `Edit ${name}`);
        $summary.find('[data-pharmacy-remove-line]').attr('aria-label', `Remove ${name}`);
    }

    function refreshRows() {
        refreshEntryTotal(form);
        $empty.prop('hidden', entries.size > 0);
        entries.forEach(renderSummary);
    }

    function createEntry($row, prepend = false) {
        const $summary = $($summaryTemplate.html().trim());
        const index = $row.attr('data-index');
        $summary.attr('data-index', index);
        const entry = { $row, $summary, $payload: $summary.find('[data-pharmacy-item-payload]') };
        entries.set(index, entry);
        if (prepend && $empty.length) $empty.after($summary);
        else if (prepend) $list.prepend($summary);
        else $list.append($summary);
        return entry;
    }

    function storeRow($row, $placeholder = null) {
        closeDatePickers($row);
        $row.prop('disabled', true);
        if ($placeholder?.prop('isConnected')) $placeholder.replaceWith($row);
        else $storage.append($row);
    }

    function validateActive() {
        if (!active) return true;
        refreshRows();
        const $controls = active.$row.find(controlSelector).not(':disabled');
        $controls.attr('data-touched', '1');
        refreshRows();
        const invalid = $controls.filter((_, input) => $(input).prop('willValidate') && !input.checkValidity()).get(0);
        if (!invalid) return true;
        focusControl(invalid);
        invalid.reportValidity();
        return false;
    }

    function commitActive() {
        if (!active) return true;
        if (!validateActive()) return false;
        const { $row, $placeholder } = active;
        const entry = active.entry || createEntry($row, true);
        writePayload($row, entry.$payload);
        storeRow($row, $placeholder);
        setPayloadDisabled(entry, false);
        active = null;
        updateEditor();
        refreshRows();
        return true;
    }

    function openEntry(entry, preferredInput = null) {
        const { $row } = entry;
        if (active?.$row.is($row)) {
            focusControl(preferredInput || $row.find('[name$="[quantity]"]').get(0));
            return true;
        }
        if (!commitActive()) return false;
        const $placeholder = $('<template>', { 'data-pharmacy-placeholder': $row.attr('data-index') });
        $row.before($placeholder);
        active = { $row, entry, $placeholder, snapshot: snapshotRow($row) };
        $row.prop('disabled', false);
        $editor.empty().append($row);
        setPayloadDisabled(entry, true);
        window.initDateTimePickers?.($row.get(0));
        updateEditor();
        refreshRows();
        focusControl(preferredInput || $row.find('[name$="[quantity]"]').get(0));
        return true;
    }

    function cancelActive() {
        if (!active) return;
        const { $row, entry, snapshot, $placeholder } = active;
        active = null;
        if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
        if (entry) {
            restoreRow($row, snapshot);
            const $unit = $row.find('[data-pharmacy-purchase-unit]');
            if ($unit.length) applyPurchaseUnit($row.get(0), $unit.find('option:selected').get(0));
            storeRow($row, $placeholder);
            setPayloadDisabled(entry, false);
        } else {
            closeDatePickers($row, true);
            $row.remove();
        }
        updateEditor();
        refreshRows();
        announce('Item changes cancelled.');
        focusPicker(false);
    }

    function removeRow($row) {
        if (!$row?.length) return;
        const index = $row.attr('data-index');
        const entry = entries.get(index);
        const name = $row.find('.app-pharmacy-entry-name').text().trim() || 'Item';
        if (active?.$row.is($row)) {
            active.$placeholder?.remove();
            active = null;
            updateEditor();
        }
        closeDatePickers($row, true);
        $row.remove();
        entry?.$summary.remove();
        entries.delete(index);
        refreshRows();
        announce(`${name} removed.`);
        focusPicker();
    }

    function findVariant(id) {
        return $form.find(rowSelector).filter(function () {
            return $(this).find('[name$="[variant_id]"]').val() === String(id);
        }).first();
    }

    function startProduct(product) {
        const $existing = options.type === 'sale' ? findVariant(product.id) : $();
        const availableQuantity = parseInteger(product.available_quantity) + parseInteger(revisionStock[product.id]);
        if ($existing.length && active?.$row.is($existing)) {
            focusControl($existing.find('[name$="[quantity]"]').get(0));
            return;
        }
        if (!commitActive()) {
            announce('Finish the current item before choosing another product.', true);
            return;
        }
        if ($existing.length) {
            openEntry(entries.get($existing.attr('data-index')));
            announce('This product is already added. Update its quantity.');
            return;
        }
        if (options.type === 'sale' && !options.allowWithoutStock && availableQuantity < 1n) {
            announce('This product has no available stock.', true);
            focusPicker();
            return;
        }
        if (entries.size >= 100) {
            announce('An invoice can contain up to 100 items.', true);
            return;
        }
        if (options.type !== 'purchase' && (!Array.isArray(product.units) || product.units.length === 0)) {
            announce('Set up a base unit for this product before adding it.', true);
            return;
        }

        const $row = $($template.html().replaceAll('__INDEX__', String(nextIndex++)).trim()).filter(rowSelector).first();
        if (!$row.length) return;
        bindItemControls($row);
        $row.prop('disabled', false).find('[name$="[variant_id]"]').val(product.id);
        const name = product.product_name || product.name;
        $row.find('.app-pharmacy-entry-name').text(name);
        $row.find('.app-pharmacy-entry-code').text(product.description);
        if (options.type === 'sale') $row.find('[data-pharmacy-product-stock]').val(String(availableQuantity));
        for (const [selector, value] of [
            ['select[name$="[vat_input]"]', lastVatInput],
            ['select[name$="[vat_mode]"]', lastVatMode],
            ['select[name$="[discount_input]"]', lastDiscountInput],
        ]) {
            const $input = $row.find(selector);
            if (value !== null && !$input.closest('[data-pharmacy-hidden-item-field]').length) $input.val(value);
        }
        active = { $row, entry: null, snapshot: null, $placeholder: null };
        $editor.empty().append($row);
        selectVariant($row.get(0), product);
        window.initDateTimePickers?.($row.get(0));
        updateEditor();
        refreshRows();
        announce(`${name} selected. Add the item when ready.`);
        focusControl($row.find('[name$="[quantity]"]').get(0));
    }

    function openPackEditor($row, option, trigger) {
        const $option = $(option);
        if (!$packModal.length || !$option.attr('data-unit-id') || $option.attr('data-fixed') === '1') return;
        if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
        closeDatePickers($row);
        pendingPack = { $row, $option };
        packFocusTarget = trigger;
        $packModal.find('[data-pharmacy-pack-label]').text(`Pieces per ${$option.attr('data-unit-name')}`);
        $packSize.val($option.attr('data-factor') || '').get(0).setCustomValidity('');
        $packModal.modal('show');
    }

    function openUnitChooser() {
        const $unit = active?.$row.find('[data-pharmacy-purchase-unit], select[name$="[product_unit_id]"]');
        if (!$unit?.length || $unit.prop('disabled')) return;
        closeDatePickers(active.$row);
        if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
        $unit.trigger('focus');
        const unit = $unit.get(0);
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
        const { $row } = active;
        const $unit = $row.find('[data-pharmacy-purchase-unit], select[name$="[product_unit_id]"]');
        if (!$unit.length || $unit.prop('disabled')) return;
        const purchase = $unit.is('[data-pharmacy-purchase-unit]');
        const choice = purchase ? $row.get(0)._purchaseUnits?.find(item => item.symbol === symbol) : null;
        const $options = $unit.find('option').filter(function () {
            const $option = $(this);
            return purchase ? choice && $option.attr('data-unit-id') === choice.id
                : $option.attr('data-symbol')?.toLowerCase() === symbol && (symbol !== 'pcs' || $option.attr('data-factor') === '1');
        });
        const $saved = $options.filter('[data-factor]');
        const $selected = $saved.filter(':selected');
        const $option = $selected.length ? $selected.first()
            : ($saved.length === 1 ? $saved : $options.not('[data-factor]').first());
        if (!$option.length) {
            announce($saved.length > 1 ? 'Choose the Box pack size.' : 'This unit is not set up for this product.', true);
            openUnitChooser();
            return;
        }
        closeDatePickers($row);
        if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
        if ($unit.val() !== $option.val()) $unit.val($option.val()).trigger('change');
        if (!pendingPack) focusControl($row.find('[name$="[quantity]"]').get(0));
    }

    function confirmPack() {
        if (!pendingPack || !active || !pendingPack.$row.is(active.$row)) return;
        const factor = parseInteger($packSize.val());
        const packSize = $packSize.get(0);
        packSize.setCustomValidity(factor > 0n && factor <= 1000000n ? '' : 'Enter the pieces per pack.');
        if (!packSize.reportValidity()) return;
        const { $row, $option } = pendingPack;
        const productId = $row.get(0)._pharmacyProduct.id;
        const profile = { unit_id: $option.attr('data-unit-id'), factor: String(factor) };
        const profiles = form._purchasePacks.get(productId) || [];
        if (!profiles.some(item => String(item.unit_id) === profile.unit_id && String(item.factor) === profile.factor)) profiles.push(profile);
        form._purchasePacks.set(productId, profiles);
        $form.find(rowSelector).each(function () {
            if (this._pharmacyProduct.id === productId) populatePurchaseUnits(this, this._unitProfiles || []);
        });
        const $unit = $row.find('[data-pharmacy-purchase-unit]');
        $unit.val(`pack:${profile.unit_id}:${profile.factor}`);
        applyPurchaseUnit($row.get(0), $unit.find('option:selected').get(0), true);
        pendingPack = null;
        packFocusTarget = $row.find('[name$="[quantity]"]').get(0);
        $packModal.modal('hide');
        refreshRows();
    }

    $packModal.on('shown.bs.modal.pharmacyTrade', () => focusControl($packSize.get(0)));
    $packModal.on('hidden.bs.modal.pharmacyTrade', () => {
        if (pendingPack?.$row.prop('isConnected')) {
            const { $row } = pendingPack;
            const $unit = $row.find('[data-pharmacy-purchase-unit]');
            $unit.val($row.attr('data-selected-purchase-unit') || '');
            applyPurchaseUnit($row.get(0), $unit.find('option:selected').get(0));
        }
        pendingPack = null;
        focusControl($(packFocusTarget).prop('isConnected') ? packFocusTarget : null);
        packFocusTarget = null;
    });
    $packModal.on('click.pharmacyTrade', '[data-pharmacy-pack-confirm]', confirmPack);
    $packSize.on('input.pharmacyTrade', function () { this.setCustomValidity(''); });
    $packSize.on('keydown.pharmacyTrade', event => {
        if (event.key !== 'Enter') return;
        event.preventDefault();
        confirmPack();
    });

    $picker.on('select2:select.pharmacyTrade', event => {
        const product = event.params.data;
        $picker.val(null).trigger('change.select2');
        startProduct(product);
    });
    $form.on('click.pharmacyTrade', '[data-pharmacy-commit-item]', () => {
        if (commitActive()) {
            announce('Item saved.');
            focusPicker();
        }
    });
    $form.on('click.pharmacyTrade', '[data-pharmacy-cancel-item]', cancelActive);
    $form.on('change.pharmacyTrade', 'select[name$="[product_unit_id]"]', function () {
        setDefaultPrice($(this).closest(rowSelector).get(0));
    });
    $form.on('change.pharmacyTrade', '[data-pharmacy-purchase-unit]', function () {
        const $unit = $(this);
        const $row = $unit.closest(rowSelector);
        const $option = $unit.find('option:selected');
        const factor = $option.attr('data-factor');
        $row.find('[data-pharmacy-edit-unit]').prop('disabled', !factor || $option.attr('data-fixed') === '1');
        if (factor) applyPurchaseUnit($row.get(0), $option.get(0), true);
        else openPackEditor($row, $option.get(0), this);
    });
    $form.on('change.pharmacyTrade', 'select[name$="[vat_input]"]', function () {
        const $input = $(this);
        lastVatInput = $input.val();
        if (lastVatInput !== 'percent') $input.closest(rowSelector).find('input[name$="[vat_amount]"]').val('');
    });
    $form.on('change.pharmacyTrade', 'select[name$="[vat_mode]"]', function () { lastVatMode = $(this).val(); });
    $form.on('change.pharmacyTrade', 'select[name$="[discount_input]"]', function () {
        const $input = $(this);
        lastDiscountInput = $input.val();
        $input.closest(rowSelector).find('input[name$="[discount]"]').val('');
    });
    $form.on('change.pharmacyTrade input.pharmacyTrade', refreshRows);
    $form.on('focusin.pharmacyTrade', 'input[type="number"]', function () { $(this).trigger('select'); });
    $form.on('focusout.pharmacyTrade', 'input[type="number"], [data-pharmacy-purchase-unit]', function () {
        $(this).attr('data-touched', '1');
        refreshRows();
    });
    $form.on('click.pharmacyTrade', '[data-pharmacy-edit-unit]', function () {
        const $row = $(this).closest(rowSelector);
        openPackEditor($row, $row.find('[data-pharmacy-purchase-unit] option:selected').get(0), this);
    });
    $form.on('click.pharmacyTrade', '[data-pharmacy-edit-line]', function () {
        const index = $(this).closest('tr[data-index]').attr('data-index');
        const entry = entries.get(index);
        if (entry) openEntry(entry);
    });
    $form.on('click.pharmacyTrade', '[data-pharmacy-remove-line]', function () {
        const $button = $(this);
        const entry = entries.get($button.closest('tr[data-index]').attr('data-index'));
        removeRow(entry?.$row || $button.closest(rowSelector));
    });

    function handleKeyboardShortcut(event) {
        const keyboard = event.originalEvent || event;
        if (keyboard.isComposing || keyboard.repeat || $('.modal.show').length) return;
        if (event.isDefaultPrevented() && !event.ctrlKey && !event.metaKey
            && ['Enter', 'Escape'].includes(event.key)) return;
        if (event.key === 'F2') {
            event.preventDefault();
            event.stopImmediatePropagation();
            focusPicker();
            return;
        }
        const $target = $(event.target);
        if (!$target.closest($form).length && !$picker.data('select2')?.isOpen()) return;
        const $row = $target.closest(rowSelector);
        if (event.key === 'Escape' && !event.ctrlKey && !event.metaKey && !event.altKey && !event.shiftKey && active) {
            if ($('.select2-container--open, .flatpickr-calendar.open').length) return;
            if ($target.is('select') && CSS.supports('selector(select:open)') && $target.is(':open')) return;
            event.preventDefault();
            event.stopImmediatePropagation();
            cancelActive();
            return;
        }
        if (event.altKey && !event.ctrlKey && !event.metaKey && !event.shiftKey && active) {
            const key = event.key.toLowerCase();
            if (key === 'b' || key === 'p' || key === 'a') {
                event.preventDefault();
                event.stopImmediatePropagation();
                if (key === 'a') $commitButton.trigger('click');
                else selectUnitShortcut(key === 'b' ? 'box' : 'pcs');
                return;
            }
        }
        if (event.key === 'F4' && !event.ctrlKey && !event.metaKey && !event.altKey && !event.shiftKey && active) {
            event.preventDefault();
            event.stopImmediatePropagation();
            openUnitChooser();
            return;
        }
        if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
            event.preventDefault();
            event.stopImmediatePropagation();
            if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
            refreshRows();
            form.requestSubmit();
            return;
        }
        if (event.altKey && event.key.toLowerCase() === 'u' && active) {
            const $unit = active.$row.find('[data-pharmacy-purchase-unit]');
            const $edit = active.$row.find('[data-pharmacy-edit-unit]');
            if ($unit.length && $edit.length && !$edit.prop('disabled')) {
                event.preventDefault();
                event.stopImmediatePropagation();
                openPackEditor(active.$row, $unit.find('option:selected').get(0), event.target);
            }
            return;
        }
        if (event.altKey && event.key === 'Delete') {
            const index = $target.closest('tr[data-index]').attr('data-index');
            const $remove = $row.length ? $row : entries.get(index)?.$row || active?.$row;
            if ($remove?.length) {
                event.preventDefault();
                event.stopImmediatePropagation();
                removeRow($remove);
            }
            return;
        }
        if (event.key !== 'Enter' || event.ctrlKey || event.metaKey || event.altKey || !active?.$row.is($row)) return;
        if (!$target.is('input:not([type="hidden"]), select, textarea')) return;
        const datePicker = event.target._flatpickr || $target.closest('[data-datepicker]').get(0)?._flatpickr;
        if (datePicker?.isOpen) return;
        if ($target.is('select') && CSS.supports('selector(select:open)') && $target.is(':open')) return;
        event.preventDefault();
        event.stopImmediatePropagation();
        const fields = $row.find('input:not([type="hidden"]), select, textarea').filter(function () {
            const $input = $(this);
            return !$input.is(':disabled') && !$input.prop('readOnly') && !$input.prop('hidden')
                && !$input.closest('details:not([open])').length && $input.is(':visible');
        }).toArray();
        const next = fields[fields.indexOf(event.target) + (event.shiftKey ? -1 : 1)];
        if (next) focusControl(next);
        else if (event.shiftKey || commitActive()) focusPicker();
    }

    function bindItemControls($row) {
        // Bind before datepicker initialization so item shortcuts run first.
        $row.find(controlSelector)
            .on('keydown.pharmacyTrade', handleKeyboardShortcut)
            .on('invalid.pharmacyTrade', function () { revealControlDetails(this); });
    }

    $(document).on('keydown.pharmacyTrade', event => {
        if ($(event.target).closest(rowSelector).length) return;
        handleKeyboardShortcut(event);
    });
    $form.on('submit.pharmacyTrade', event => {
        if (!commitActive()) {
            event.preventDefault();
            return;
        }
        refreshRows();
        for (const entry of entries.values()) {
            const invalid = entry.$row.find(controlSelector).filter(function () {
                const $input = $(this);
                return !$input.prop('disabled') && !$input.closest('[data-pharmacy-hidden-item-field]').length
                    && $input.attr('data-validation-message');
            }).get(0);
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

    $originalRows.each(function () {
        const $row = $(this).prop('disabled', true);
        bindItemControls($row);
        if ($row.find('[data-pharmacy-purchase-unit]').length) {
            try { this._unitProfiles = JSON.parse($row.attr('data-unit-profiles') || '[]'); }
            catch { this._unitProfiles = []; }
            populatePurchaseUnits(this, this._unitProfiles);
        }
        createEntry($row);
    });
    updateEditor();
    refreshRows();
    entries.forEach(entry => writePayload(entry.$row, entry.$payload));
    for (const entry of entries.values()) {
        const invalid = entry.$row.find('.is-invalid, [aria-invalid="true"]').filter(function () {
            const $input = $(this);
            return !$input.prop('disabled') && !$input.closest('[data-pharmacy-hidden-item-field]').length;
        }).get(0);
        if (!invalid && !entry.$row.find('.invalid-feedback').length) continue;
        openEntry(entry, invalid);
        break;
    }
}
