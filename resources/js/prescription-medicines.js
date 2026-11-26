import $ from './jquery';
import { prescriptionHeaderDropdown } from './prescription-header-dropdown';
import { initializePrescriptionKeyboard } from './prescription-keyboard';
import { initializePrescriptionInstructions } from './prescription-instructions';
import { createPrescriptionPalette } from './prescription-palette';
import { createEmptySearchGuard } from './select2-empty-search';
import {
    firstPrescriptionDoseControl,
    initializePrescriptionDosage,
    updatePrescriptionDoseType,
} from './prescription-application-dose';

// Show the saved unit on the row selector, using select when it is blank.
function refreshUnitToggle($row) {
    const value = String($row.find('[name$="[dose]"]').val() || '');
    $row.find('.prescription-unit-toggle').val(value);
    $row.find('.prescription-unit-label').text(value || 'select');
}

// Show structured duration labels in the pad without changing saved text.
function previewDuration($row, duration) {
    const unit = $row.find('.prescription-duration-unit').val();
    if (!duration || unit === 'custom') return duration;

    const unitLabel = $row.find('.prescription-duration-unit option:selected').text();
    if (unit === 'continue') return unitLabel;

    const count = $row.find('.prescription-duration-count').val();
    return count ? count + ' ' + unitLabel : duration;
}

// Refresh one medicine summary from its current row fields.
function updatePreview($row) {
    const $item = $row.data('preview');
    const name = $row.find('[name$="[medicine_name]"]').val();
    const dose = $row.find('[name$="[dose]"]').val();
    const frequency = String($row.find('[name$="[frequency]"]').val() || '').replace(/\s*\+\s*/g, ' + ');
    const duration = $row.find('[name$="[duration]"]').val();
    const instructions = $row.find('[name$="[instructions]"]').val();
    const directions = [frequency, dose, instructions, previewDuration($row, duration)].filter(Boolean).join(' ');

    $item.find('.app-prescription-rx-preview-title').text(name).attr('aria-label', 'Edit medicine: ' + name);
    $item.find('.prescription-medicine-preview-generic')
        .text($row.find('.prescription-medicine-generic').text());
    $item.find('.prescription-medicine-directions').text(directions);
}

// Show the count or custom text required by the selected duration mode.
function displayDurationMode($row, unit) {
    const custom = unit === 'custom';
    const hideCount = custom || unit === 'continue';
    $row.find('.prescription-duration-count').prop('hidden', hideCount).prop('disabled', hideCount);
    $row.find('.prescription-duration-value').prop('hidden', !custom);
}

// Restore known saved duration formats and leave unmatched legacy text in Custom.
function initializeDuration($row) {
    const duration = String($row.find('.prescription-duration-value').val() || '');
    const interval = duration.match(/^(\d+)\s+(.+)$/);
    const units = {
        day: 'days', days: 'days', 'দিন': 'days',
        week: 'weeks', weeks: 'weeks', 'সপ্তাহ': 'weeks',
        month: 'months', months: 'months', 'মাস': 'months',
        year: 'years', years: 'years', 'বছর': 'years',
    };
    const intervalUnit = interval && units[interval[2].toLowerCase()];
    const count = interval ? Number(interval[1]) : null;
    const structured = intervalUnit && Number.isSafeInteger(count) && count > 0;
    const continuous = duration === 'চলবে';
    const custom = Boolean(duration) && !structured && !continuous;
    let unit = 'days';
    if (custom) unit = 'custom';
    if (continuous) unit = 'continue';
    if (structured) unit = intervalUnit;

    $row.find('.prescription-duration-unit').val(unit);
    displayDurationMode($row, unit);
    if (structured) $row.find('.prescription-duration-count').val(interval[1]);
}

// Write the selected duration into the existing field used by save and preview.
function updateDuration($row) {
    const unit = $row.find('.prescription-duration-unit').val();
    const custom = unit === 'custom';
    const continuous = unit === 'continue';
    const count = $row.find('.prescription-duration-count').val();
    displayDurationMode($row, unit);
    if (custom) return;

    const label = $row.find('.prescription-duration-unit option:selected').text();
    const duration = continuous ? 'চলবে' : (count ? count + ' ' + label : '');
    $row.find('.prescription-duration-value').val(duration).trigger('input');
}

// Connect medicine search, Rx rows, pad previews and the dose/unit controls.
export function initializePrescriptionMedicines() {
    const $modal = $('#prescription-rx-modal');
    const $search = $('#prescription-medicine-search');
    const $rows = $('#prescription-medicines');
    const $preview = $('#prescription-medicine-preview');
    const $suggestions = $('#prescription-rx-options');
    const $template = $('#prescription-medicine-template');
    const $unitPalette = $('#prescription-unit-palette');
    const unitPalette = createPrescriptionPalette($modal, $unitPalette, 'bottom');
    const instructions = initializePrescriptionInstructions($modal, $rows);
    const medicineSearch = createEmptySearchGuard($search);
    const unitNames = new Set($unitPalette.find('.prescription-unit-choice').map(function () {
        return $(this).val();
    }).get().filter(Boolean));
    const unitButtons = new Map();
    const rowsByChoice = new Map();
    const optionsByChoice = new Map();
    const suggestionButtons = new Map();
    const savedOrderings = $rows.children().map(function () {
        const ordering = $(this).attr('data-ordering');
        return ordering === undefined || ordering === '' ? null : Number(ordering);
    }).get().filter(Number.isFinite);
    const maxSavedOrdering = savedOrderings.length ? Math.max(...savedOrderings) : -1;
    let nextIndex = 0;
    let nextPreviewOrder = maxSavedOrdering + 1;
    let $focusAfterOpen = $();
    let $activeUnit = $();
    let unitOptionsRequest = null;
    let unitOptionsLoaded = false;

    // Update the pad count and empty-state message after a row changes.
    function updateCount() {
        const count = $rows.children('.prescription-medicine-row').length;
        $('#prescription-medicine-count').text(count + (count === 1 ? ' Medicine' : ' Medicine'));
        $('#prescription-medicine-empty').prop('hidden', count > 0);
    }

    // Keep a suggestion chip aligned with its selected medicine rows.
    function updateSuggestion(choiceId) {
        const $button = suggestionButtons.get(choiceId);
        if (!$button) return;

        const selected = rowsByChoice.has(choiceId);
        $button.toggleClass('active', selected).attr('aria-pressed', String(selected));
    }

    // Mark a medicine selected in the search list and suggestion chips.
    function selectChoice(choiceId) {
        const selected = $search.val() || [];
        if (!selected.includes(choiceId)) {
            $search.val([...selected, choiceId]).trigger('change.select2');
        }
        syncSearchChecks();
        updateSuggestion(choiceId);
    }

    // Remove a medicine selection from the search list and suggestion chips.
    function deselectChoice(choiceId) {
        const selected = ($search.val() || []).filter(value => value !== choiceId);
        $search.val(selected).trigger('change.select2');
        syncSearchChecks();
        updateSuggestion(choiceId);
    }

    // Update the open search results without rebuilding the medicine rows.
    function syncSearchChecks() {
        const picker = $search.data('select2');
        if (!picker) return;

        const selected = new Set($search.val() || []);
        picker.$dropdown.find('.app-prescription-rx-result').each(function () {
            const $result = $(this);
            const checked = selected.has($result.data('choiceId'));
            $result.closest('.select2-results__option')
                .toggleClass('select2-results__option--selected', checked).attr('aria-selected', String(checked));
        });
    }

    // Link a saved or new row to its catalog/manual search choice.
    function registerChoice($row, medicine) {
        const choiceId = String(medicine.id);
        const $existingRows = rowsByChoice.get(choiceId) || $();
        rowsByChoice.set(choiceId, $existingRows.add($row));
        $row.data('choiceId', choiceId);

        if (!optionsByChoice.has(choiceId)) {
            const $option = medicine.element ? $(medicine.element) : $('<option>')
                .val(choiceId).text(medicine.name || medicine.text).appendTo($search);
            optionsByChoice.set(choiceId, $option);
        }
        selectChoice(choiceId);
    }

    // Unlink a removed or renamed row while retaining any other matching rows.
    function detachChoice($row) {
        const choiceId = $row.data('choiceId');
        if (!choiceId) return;

        const $remaining = rowsByChoice.get(choiceId).not($row);
        $row.removeData('choiceId');
        if ($remaining.length) {
            rowsByChoice.set(choiceId, $remaining);
        } else {
            rowsByChoice.delete(choiceId);
            deselectChoice(choiceId);
        }
    }

    // Focus an Rx field immediately or after its modal opens.
    function focusMedicineInput($input) {
        if (!$input.length) return;

        $focusAfterOpen = $input;
        if ($modal.hasClass('show')) {
            $focusAfterOpen.trigger('focus');
            $focusAfterOpen = $();
        } else {
            $modal.modal('show');
        }
    }

    // Create the pad summary and connect its edit/remove buttons to this row.
    function attachPreview($row) {
        const $item = $('<li class="d-flex align-items-end justify-content-between">');
        const $content = $('<div>').appendTo($item);
        $('<button type="button" class="app-prescription-rx-preview-title">')
            .on('click.prescription', () => focusMedicineInput(firstPrescriptionDoseControl($row))).appendTo($content);
        $('<small class="d-block text-body-secondary prescription-medicine-preview-generic">').appendTo($content);
        $('<p class="prescription-medicine-directions">').appendTo($content);
        $('<button type="button" class="app-prescription-rx-preview-remove" aria-label="Remove medicine">')
            .addClass('flex-shrink-0 p-0 border-0 bg-transparent')
            .append('<i class="fa-solid fa-xmark" aria-hidden="true"></i>')
            .on('click.prescription', () => removeRow($row)).appendTo($item);

        $row.data('preview', $item);
        const previewOrder = Number($row.data('previewOrder'));
        $item.data('previewOrder', previewOrder);
        const $nextPreview = $preview.children().filter(function () {
            return Number($(this).data('previewOrder')) > previewOrder;
        }).first();
        if ($nextPreview.length) $item.insertBefore($nextPreview);
        else $preview.append($item);
        updatePreview($row);
    }

    // Close the unit choices and clear their active row control.
    function closeUnitPalette() {
        unitPalette.close();
        $activeUnit = $();
    }

    // Add a unit once and mark the currently selected choice.
    function appendUnitChoice(name, selected) {
        if (unitButtons.has(name)) return;

        const active = name === selected;
        const $button = $('<button type="button" class="dropdown-item prescription-unit-choice">')
            .attr({ role: 'menuitemradio', 'aria-checked': String(active) })
            .val(name).text(name || '—').toggleClass('active', active)
            .prop('disabled', name.length > 100 && !active).appendTo($unitPalette);
        unitButtons.set(name, $button);
    }

    // Rebuild unit choices, including any saved label outside the preset list.
    function renderUnitPalette() {
        const selected = String($activeUnit.closest('.prescription-medicine-row').find('[name$="[dose]"]').val() || '');
        $unitPalette.empty();
        unitButtons.clear();
        appendUnitChoice('', selected);
        unitNames.forEach(name => appendUnitChoice(name, selected));
        if (selected) appendUnitChoice(selected, selected);
    }

    // Load additional catalog units once; leave failed requests available for retry.
    function loadUnitOptions(page = 1) {
        if (unitOptionsLoaded || unitOptionsRequest) return;

        $unitPalette.attr('aria-busy', 'true');
        unitOptionsRequest = $.ajax({
            url: $unitPalette.attr('data-url'),
            data: { search: '', page },
            dataType: 'json',
            headers: { Accept: 'application/json' },
            timeout: 15000,
        }).done(data => {
            unitOptionsRequest = null;
            const selected = String($activeUnit.val() || '');
            const open = $unitPalette.hasClass('show');
            data.results.forEach(option => {
                unitNames.add(option.name);
                if (open) appendUnitChoice(option.name, selected);
            });
            if (open) unitPalette.update();
            const more = Boolean(data.pagination?.more) && page < 10;
            unitOptionsLoaded = !more;
            if (more) loadUnitOptions(page + 1);
            else $unitPalette.removeAttr('aria-busy');
        }).fail((_, status) => {
            unitOptionsRequest = null;
            $unitPalette.removeAttr('aria-busy');
            if (status !== 'abort') window.showToast('Extra units could not be loaded. Reopen Unit to retry.', 'error');
        });
    }

    // Open units for the active row.
    function openUnitPalette($button) {
        const alreadyOpen = $activeUnit.is($button) && $unitPalette.hasClass('show');
        closeUnitPalette();
        if (alreadyOpen) return;

        $activeUnit = $button;
        renderUnitPalette();
        unitPalette.open($button);
        loadUnitOptions();
    }

    // Keep the dose menu's label in sync with the text stored for this medicine.
    function initializeFrequency($row) {
        const frequency = String($row.find('.prescription-frequency-value').val() || '');
        const $preset = $row.find('.prescription-dose-preset').filter(function () {
            return String($(this).val()) === frequency;
        }).first();
        const $toggle = $row.find('.prescription-frequency-mode');

        if ($preset.length) {
            $toggle.text($preset.text()).val($preset.val());
        } else if (frequency) {
            $toggle.text('Custom dose').val('custom');
        } else {
            $toggle.text('Dose').val('');
        }
    }

    function syncFrequencyPicker($row) {
        initializeFrequency($row);
    }

    // Open the Rx modal at the field rejected by native form validation.
    function showInvalidMedicine(event) {
        event.preventDefault();
        focusMedicineInput($(event.currentTarget));
    }

    // Connect a row to its editors, catalog selection and pad preview.
    function attachRow($row, medicine) {
        const generic = medicine.category?.name || '';

        initializeFrequency($row);
        initializeDuration($row);
        refreshUnitToggle($row);
        instructions.initializeRow($row);
        initializePrescriptionDosage($row, medicine);
        registerChoice($row, medicine);
        $row.find('.prescription-medicine-generic').text(generic).prop('hidden', !generic);
        attachPreview($row);
        $row.find(':input').on('invalid.prescription', showInvalidMedicine);
    }

    // Reuse a selected medicine row or prepend a new row from the Blade template.
    function addMedicine(medicine) {
        const choiceId = String(medicine.id);
        const $existing = rowsByChoice.get(choiceId);
        if ($existing?.length) return $existing.first();

        if ($rows.children('.prescription-medicine-row').length >= 100) {
            window.showToast('A prescription can contain up to 100 medicines.', 'warning');
            deselectChoice(choiceId);
            return $();
        }

        const $row = $($template.html().replaceAll('__INDEX__', String(nextIndex++)));
        $row.data('previewOrder', nextPreviewOrder++);
        $row.find('[name$="[product_id]"]').val(medicine.manual ? '' : medicine.id);
        $row.find('[name$="[medicine_name]"]').val(medicine.name || medicine.text);
        $rows.prepend($row);
        attachRow($row, medicine);
        updateCount();
        return $row;
    }

    // Remove a row, its widgets and summary after pending instruction saves finish.
    function removeRow($row) {
        if (!instructions.canRemoveRow($row)) return;
        closeUnitPalette();
        $row.find('.select2-hidden-accessible').each(function () { $(this).select2('destroy'); });
        detachChoice($row);
        $row.data('preview').remove();
        $row.remove();
        updateCount();
    }

    // Remove rows for an unselected search choice, keeping any rows still being saved.
    function removeChoice(choiceId) {
        const $selectedRows = rowsByChoice.get(choiceId);
        $selectedRows?.each(function () { removeRow($(this)); });
        if (rowsByChoice.has(choiceId)) selectChoice(choiceId);
    }

    // Show matching medicines as chips that add, focus or remove their rows.
    function renderSuggestions(medicines) {
        $suggestions.empty();
        suggestionButtons.clear();
        medicines.forEach(medicine => {
            const choiceId = String(medicine.id);
            const $button = $('<button type="button" class="app-prescription-rx-chip">')
                .text(medicine.name).on('click.prescription', () => {
                    if (rowsByChoice.has(choiceId)) {
                        removeChoice(choiceId);
                    } else {
                        const $row = addMedicine(medicine);
                        focusMedicineInput(firstPrescriptionDoseControl($row));
                    }
                }).appendTo($suggestions);
            suggestionButtons.set(choiceId, $button);
            updateSuggestion(choiceId);
        });
        if (!medicines.length) {
            $('<p class="small text-body-secondary mb-0">')
                .text('No medicines found. Type a name to add.').appendTo($suggestions);
        }
    }

    // Build an escaped medicine search result with its generic and catalog details.
    function medicineResult(medicine) {
        if (!medicine.id) return medicine.text;

        const $result = $('<div class="app-prescription-rx-result">').data('choiceId', String(medicine.id));
        $('<span class="prescription-medicine-result-check" aria-hidden="true">').appendTo($result);
        const $description = $('<span>').appendTo($result);
        $('<strong>').text(medicine.name || medicine.text).appendTo($description);
        const details = [
            medicine.category?.name,
            medicine.dosage?.name,
            medicine.supplier?.name,
            medicine.code,
        ].filter(Boolean).join(' / ');
        if (details) $('<small>').text(details).appendTo($description);
        return $result;
    }

    // Resume an explicit edit, then prefer server errors before the medicine search.
    function focusOpenedModal() {
        if ($focusAfterOpen.length) {
            $focusAfterOpen.trigger('focus');
            $focusAfterOpen = $();
            return;
        }

        const $invalidRow = $rows.children('.prescription-medicine-row').has('.is-invalid, .invalid-feedback').first();
        const $invalidSelect = $invalidRow.find('select.is-invalid').first();
        if ($invalidSelect.length) {
            $invalidSelect.select2('open');
            return;
        }
        const $invalidInput = $invalidRow.find('.is-invalid:visible').first();
        if ($invalidInput.length) {
            $invalidInput.trigger('focus');
            return;
        }
        const $error = $modal.find('.invalid-feedback').first();
        if ($error.length) {
            $error.attr('tabindex', '-1').trigger('focus');
            return;
        }
        $search.select2('open');
    }

    // Close floating controls together before search opens or the editor closes.
    function closeMedicineControls($selects) {
        $selects.select2('close');
        closeUnitPalette();
    }

    // Keep the editor open until an inline instruction save finishes.
    function closeEditor(event) {
        instructions.preventWhileSaving(event);
        if (!event.isDefaultPrevented()) closeMedicineControls($modal.find('select.select2-hidden-accessible'));
    }

    // Copy a saved dose into the field shown in the prescription preview.
    function selectDosePreset(event) {
        const $button = $(event.currentTarget);
        const $row = $button.closest('.prescription-medicine-row');

        $row.find('.prescription-frequency-value').val($button.val()).trigger('input');
    }

    // Keep the dose field editable when no saved preset matches the intended direction.
    function selectCustomDose(event) {
        const $row = $(event.currentTarget).closest('.prescription-medicine-row');

        $row.find('.prescription-frequency-mode').text('Custom dose').val('custom');
        $row.find('.prescription-frequency-value').trigger('focus');
    }

    // Unit selection leaves other directions unchanged and moves to Duration.
    function selectUnit(event) {
        const $row = $activeUnit.closest('.prescription-medicine-row');
        const $duration = $row.find('.prescription-duration-count:visible, .prescription-duration-value:visible').first();
        const $focus = $duration.length ? $duration : $row.find('.prescription-duration-unit');
        $row.find('[name$="[dose]"]').val($(event.currentTarget).val()).trigger('input');
        closeUnitPalette();
        $focus.trigger('focus');
    }

    // A manually edited name no longer represents the selected catalog product.
    function detachRenamedMedicine(event) {
        const $row = $(event.currentTarget).closest('.prescription-medicine-row');
        detachChoice($row);
        $row.find('[name$="[product_id]"]').val('');
        $row.find('.prescription-medicine-generic').empty().prop('hidden', true);
    }

    // Keep the Dose label aligned with the row value after a Type change.
    function changeDosageType(event) {
        const $row = $(event.currentTarget).closest('.prescription-medicine-row');
        closeUnitPalette();
        initializeFrequency($row);
        refreshUnitToggle($row);
        updatePrescriptionDoseType($row);
    }

    // Restore saved rows before connecting the shared medicine search.
    $rows.children('.prescription-medicine-row').each(function () {
        const $row = $(this);
        const ordering = $row.attr('data-ordering');
        const medicineIndex = Number($row.attr('data-medicine-index'));
        const previewOrder = ordering !== undefined && ordering !== ''
            ? maxSavedOrdering - Number(ordering)
            : medicineIndex;
        $row.data('previewOrder', Number.isFinite(previewOrder) ? previewOrder : nextPreviewOrder);
        nextPreviewOrder = Math.max(nextPreviewOrder, Number($row.data('previewOrder')) + 1);
        const name = $row.find('[name$="[medicine_name]"]').attr('name');
        const index = name.match(/^medicines\[(\d+)\]/);
        if (index) nextIndex = Math.max(nextIndex, Number(index[1]) + 1);

        const productId = $row.find('[name$="[product_id]"]').val();
        const medicineName = $row.find('[name$="[medicine_name]"]').val();
        attachRow($row, {
            id: productId || 'manual:' + medicineName,
            name: medicineName,
            manual: !productId,
            category: { name: $row.find('.prescription-medicine-generic').text().trim() },
        });
    });
    updateCount();

    $search.select2({
        width: '100%',
        dropdownParent: $search.closest('.app-prescription-rx-search'),
        dropdownAdapter: prescriptionHeaderDropdown(),
        placeholder: 'Search / Add Item...',
        closeOnSelect: false,
        tags: true,
        minimumInputLength: 0,
        maximumInputLength: 255,
        templateResult: medicineResult,
        templateSelection: medicine => medicine.name || medicine.text,
        // Offer a typed medicine only after the current search finishes with no matches.
        createTag(params) {
            const name = String(params.term || '').trim();
            if (!medicineSearch.canCreate(name)) return null;
            return { id: 'manual:' + name, text: name, name, manual: true, newTag: true };
        },
        ajax: {
            url: $search.attr('data-url'),
            dataType: 'json',
            delay: 250,
            timeout: 15000,
            // Track this search so an older response cannot enable a new manual item.
            data(params) {
                medicineSearch.begin(params);
                return { search: String(params.term || '').trim().slice(0, 80), page: params.page || 1 };
            },
            // Refresh suggestions from the current response and retain catalog pagination.
            processResults(data, params) {
                if (!medicineSearch.complete(params, data.results.length, data.pagination?.more)) {
                    return { results: [], pagination: { more: false } };
                }
                renderSuggestions(data.results);
                return {
                    results: data.results.map(medicine => this._normalizeItem({ ...medicine, text: medicine.name })),
                    pagination: data.pagination,
                };
            },
        },
    });
    medicineSearch.bindSearch();

    $search.on('select2:select.prescription', event => {
        $search.select2('close');
        const $row = addMedicine(event.params.data);
        focusMedicineInput(firstPrescriptionDoseControl($row));
    });
    $search.on('select2:unselect.prescription', event => removeChoice(String(event.params.data.id)));
    $search.on('select2:opening.prescription', () => closeMedicineControls($rows.find('select.select2-hidden-accessible')));
    $modal.on('shown.bs.modal.prescription', focusOpenedModal);
    $modal.on('hide.bs.modal.prescription', closeEditor);
    $('#prescription-form').on('submit.prescriptionInstruction', instructions.preventWhileSaving);

    // A selected dose preset fills the same field used by manual directions.
    $rows.on('click.prescription', '.prescription-dose-preset', selectDosePreset);
    $rows.on('click.prescription', '.prescription-custom-dose', selectCustomDose);
    unitPalette.bindToggle($rows, '.prescription-unit-toggle', openUnitPalette);

    // Select a unit without disturbing the row's dose, duration or instruction values.
    $unitPalette.on('click.prescriptionUnit', '.prescription-unit-choice', selectUnit);
    $rows.on('input.prescription change.prescription',
        '.prescription-duration-count, .prescription-duration-unit', function () {
            updateDuration($(this).closest('.prescription-medicine-row'));
        });
    // Update only the editor affected by a field; refresh its pad preview afterward.
    $rows.on('input.prescription change.prescription', '[name$="[medicine_name]"]', detachRenamedMedicine);
    $rows.on('input.prescription change.prescription', '[name$="[dose]"]', function () {
        refreshUnitToggle($(this).closest('.prescription-medicine-row'));
    });
    $rows.on('input.prescription change.prescription', '[name$="[frequency]"]', function () {
        syncFrequencyPicker($(this).closest('.prescription-medicine-row'));
    });
    $rows.on('input.prescription change.prescription', '.prescription-dosage-type', changeDosageType);
    $rows.on('input.prescription change.prescription', '[name]', function () {
        updatePreview($(this).closest('.prescription-medicine-row'));
    });
    $rows.on('click.prescription', '.prescription-medicine-remove', function () {
        removeRow($(this).closest('.prescription-medicine-row'));
    });

    initializePrescriptionKeyboard($modal, $search, $rows);
}
