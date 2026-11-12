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

// Keep an edit snapshot so Cancel restores values, validation and datepicker state.
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
            touched: $input.data('touched'),
            validationMessage: $input.data('validationMessage'),
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
            altValue: picker?.altInput ? $(picker.altInput).val() : null,
            dates: picker ? picker.selectedDates.map(date => new Date(date.getTime())) : null,
        };
    }).get();
    return {
        controls, pickers, dataAttributes: copyDataAttributes($row),
        product: { ...$row.data('pharmacyProduct') },
        profiles: $row.data('unitProfiles')?.map(profile => ({ ...profile })),
    };
}

function restoreRow($row, snapshot) {
    restoreDataAttributes($row, snapshot.dataAttributes);
    $row.data({ pharmacyProduct: snapshot.product, unitProfiles: snapshot.profiles });
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
        $input.data({ touched: state.touched, validationMessage: state.validationMessage });
        input.setCustomValidity(state.customValidity);
        if (state.ariaInvalid === null) $input.removeAttr('aria-invalid');
        else $input.attr('aria-invalid', state.ariaInvalid);
    }
    for (const { $wrapper, dates, value, altValue } of snapshot.pickers) {
        const $input = $wrapper.find('[data-input]').first();
        const picker = $wrapper.get(0)._flatpickr || $input.get(0)?._flatpickr;
        picker?.setDate(dates ?? value, false);
        // Flatpickr formats values; Cancel must preserve the original typed text too.
        $input.val(value);
        if (picker?.altInput && altValue !== null) $(picker.altInput).val(altValue);
    }
    $row.trigger('pharmacy:itemRestored');
}

function closeDatePickers($root, destroy = false) {
    $root.find('[data-datepicker], [data-timepicker]').each(function () {
        const picker = this._flatpickr || $(this).find('[data-input]').get(0)?._flatpickr;
        if (destroy) picker?.destroy();
        else picker?.close();
    });
}

// Stored editor fieldsets are disabled; submit their current values as hidden inputs.
function writePayload($row, $payload) {
    const inputs = [];
    $row.find(controlSelector).each(function () {
        const $input = $(this);
        const name = $input.attr('name');
        const type = $input.attr('type');
        // The stored row is disabled. Only own flags and disabled inner
        // fieldsets determine which controls belong in the submitted line.
        if (!name || $input.prop('disabled') || $input.closest('[data-pharmacy-hidden-item-field]').length
            || $input.parentsUntil($row, 'fieldset[disabled]').length
            || ['button', 'submit', 'reset', 'file'].includes(type)) return;
        if (['checkbox', 'radio'].includes(type) && !$input.prop('checked')) return;
        const value = $input.val();
        const values = Array.isArray(value) ? value : [value ?? ''];
        for (const item of values) inputs.push($('<input>', { type: 'hidden', name, value: item }).get(0));
    });
    $payload.empty().append(inputs);
}

export function initializeTradeEditor(form, {
    selectVariant,
    refreshEntryTotal,
    setDefaultPrice,
    onProductSelected,
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
    const entries = new Map();
    const $originalRows = $storage.find(rowSelector);
    let nextIndex = 0;
    let active = null;
    let lastDiscountInput = null;

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
        const values = $row.data('pharmacySummary');
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
        const index = $row.data('pharmacyLineIndex');
        $summary.data('pharmacyLineIndex', index);
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
        const $controls = active.$row.find(controlSelector);
        $controls.data('touched', true);
        refreshRows();
        const invalid = $controls.not(':disabled')
            .filter((_, input) => $(input).prop('willValidate') && !input.checkValidity()).get(0);
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

    function openEntry($row, preferredInput = null) {
        const entry = entries.get($row.data('pharmacyLineIndex'));
        if (active?.$row.is($row)) {
            focusControl(preferredInput || $row.find('[name$="[quantity]"]').get(0));
            return true;
        }
        if (!commitActive()) return false;
        const $placeholder = $('<template>');
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
        const index = $row.data('pharmacyLineIndex');
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

    // Choosing a new product must first finish the current item.
    function finishCurrentItem() {
        if (commitActive()) return true;
        announce('Finish the current item before choosing another product.', true);
        return false;
    }

    function canAddItem() {
        if (entries.size < 100) return true;
        announce('An invoice can contain up to 100 items.', true);
        return false;
    }

    // Both invoice owners use the same row creation, remembered modes and editor focus.
    function addProductRow(product) {
        const index = String(nextIndex++);
        const $row = $($template.html().replaceAll('__INDEX__', index).trim()).filter(rowSelector).first();
        if (!$row.length) return;
        $row.data('pharmacyLineIndex', index);
        bindItemControls($row);
        $row.prop('disabled', false).find('[name$="[variant_id]"]').val(product.id);
        const name = product.product_name || product.name;
        $row.find('.app-pharmacy-entry-name').text(name);
        $row.find('.app-pharmacy-entry-code').text(product.description);
        const $discountInput = $row.find('select[name$="[discount_input]"]');
        if (lastDiscountInput !== null && !$discountInput.closest('[data-pharmacy-hidden-item-field]').length) {
            $discountInput.val(lastDiscountInput);
        }
        active = { $row, entry: null, snapshot: null, $placeholder: null };
        $editor.empty().append($row);
        $row.trigger('pharmacy:itemCreated');
        selectVariant($row.get(0), product);
        window.initDateTimePickers?.($row.get(0));
        updateEditor();
        refreshRows();
        announce(`${name} selected. Add the item when ready.`);
        focusControl($row.find('[name$="[quantity]"]').get(0));
    }

    function openUnitChooser($unit) {
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

    function selectUnitShortcut($unit, $options) {
        const { $row } = active;
        if (!$unit.length || $unit.prop('disabled')) return;
        const $saved = $options.filter('[data-factor]');
        const $selected = $saved.filter(':selected');
        const $option = $selected.length ? $selected.first()
            : ($saved.length === 1 ? $saved : $options.not('[data-factor]').first());
        if (!$option.length) {
            announce($saved.length > 1 ? 'Choose the Box pack size.' : 'This unit is not set up for this product.', true);
            openUnitChooser($unit);
            return;
        }
        closeDatePickers($row);
        if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
        if ($unit.val() !== $option.val()) $unit.val($option.val()).trigger('change');
        $form.trigger('pharmacy:unitSelected', [$row]);
    }

    const editor = {
        get activeRow() { return active?.$row; },
        finishCurrentItem,
        canAddItem,
        addProductRow,
        openEntry,
        focusControl,
        focusPicker,
        announce,
        refreshRows,
        closeDatePickers,
        openUnitChooser,
        selectUnitShortcut,
    };

    $picker.on('select2:select.pharmacyTrade', event => {
        const product = event.params.data;
        $picker.val(null).trigger('change.select2');
        onProductSelected(product, editor);
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
    $form.on('change.pharmacyTrade', 'select[name$="[discount_input]"]', function () {
        const $input = $(this);
        lastDiscountInput = $input.val();
        $input.closest(rowSelector).find('input[name$="[discount]"]').val('');
    });
    $form.on('change.pharmacyTrade input.pharmacyTrade',
        `${rowSelector} input, ${rowSelector} select, ${rowSelector} textarea,
        [name="discount"], [name="paid_amount"]`, refreshRows);
    $form.on('focusin.pharmacyTrade', 'input[type="number"]', function () { $(this).trigger('select'); });
    $form.on('focusout.pharmacyTrade', 'input[type="number"]', function () {
        $(this).data('touched', true);
        refreshRows();
    });
    $form.on('click.pharmacyTrade', '[data-pharmacy-edit-line]', function () {
        const index = $(this).closest('tr').data('pharmacyLineIndex');
        const entry = entries.get(index);
        if (entry) openEntry(entry.$row);
    });
    $form.on('click.pharmacyTrade', '[data-pharmacy-remove-line]', function () {
        const $button = $(this);
        const entry = entries.get($button.closest('tr').data('pharmacyLineIndex'));
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
                else $form.trigger('pharmacy:unitShortcutRequested', [key === 'b' ? 'box' : 'pcs']);
                return;
            }
        }
        if (event.key === 'F4' && !event.ctrlKey && !event.metaKey && !event.altKey && !event.shiftKey && active) {
            event.preventDefault();
            event.stopImmediatePropagation();
            $form.trigger('pharmacy:unitChooserRequested');
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
        if (event.altKey && event.key === 'Delete') {
            const index = $target.closest('tr').data('pharmacyLineIndex');
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
                    && $input.data('validationMessage');
            }).get(0);
            if (!invalid) continue;
            event.preventDefault();
            openEntry(entry.$row, invalid);
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
        const index = $row.find('input[name$="[variant_id]"]').attr('name').match(/^lines\[([^\]]+)\]/)[1];
        const numericIndex = Number(index);
        $row.data('pharmacyLineIndex', index);
        if (Number.isSafeInteger(numericIndex) && numericIndex >= nextIndex) nextIndex = numericIndex + 1;
        bindItemControls($row);
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
        openEntry(entry.$row, invalid);
        break;
    }
    return editor;
}
