import $ from './jquery';
import { prescriptionHeaderDropdown } from './prescription-header-dropdown';
import { initializePrescriptionKeyboard } from './prescription-keyboard';
import { initializePrescriptionInstructions } from './prescription-instructions';
import { createPrescriptionPalette } from './prescription-palette';
import { createEmptySearchGuard } from './select2-empty-search';

export function initializePrescriptionMedicines() {
    const $modal = $('#prescription-rx-modal');
    const $search = $('#prescription-medicine-search');
    const $rows = $('#prescription-medicines');
    const $preview = $('#prescription-medicine-preview');
    const $suggestions = $('#prescription-rx-options');
    const $template = $('#prescription-medicine-template');
    const $palette = $('#prescription-dose-palette');
    const $unitPalette = $('#prescription-unit-palette');
    const dosePalette = createPrescriptionPalette($modal, $palette);
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
    let nextIndex = 0;
    let $focusAfterOpen = $();
    let $activeDose = $();
    let $activeUnit = $();
    let unitOptionsRequest = null;
    let unitOptionsLoaded = false;

    function updateCount() {
        const count = $rows.children('.prescription-medicine-row').length;
        $('#prescription-medicine-count').text(count + (count === 1 ? ' medicine' : ' medicines'));
        $('#prescription-medicine-empty').prop('hidden', count > 0);
    }

    function updateSuggestion(choiceId) {
        const $button = suggestionButtons.get(choiceId);
        if (!$button) return;

        const selected = rowsByChoice.has(choiceId);
        $button.toggleClass('active', selected).attr('aria-pressed', String(selected));
    }

    function selectChoice(choiceId) {
        const selected = $search.val() || [];
        if (!selected.includes(choiceId)) {
            $search.val([...selected, choiceId]).trigger('change.select2');
        }
        syncSearchChecks();
        updateSuggestion(choiceId);
    }

    function deselectChoice(choiceId) {
        const selected = ($search.val() || []).filter(value => value !== choiceId);
        $search.val(selected).trigger('change.select2');
        syncSearchChecks();
        updateSuggestion(choiceId);
    }

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

    function previewDuration($row, duration) {
        const unit = $row.find('.prescription-duration-unit').val();
        if (!duration || unit === 'custom') return duration;

        const unitLabel = $row.find('.prescription-duration-unit option:selected').text();
        if (unit === 'continue') return unitLabel;

        const count = $row.find('.prescription-duration-count').val();
        return count ? count + ' ' + unitLabel : duration;
    }

    function updatePreview($row) {
        const $item = $row.data('preview');
        const name = $row.find('[name$="[medicine_name]"]').val();
        const dose = $row.find('[name$="[dose]"]').val();
        const frequency = String($row.find('[name$="[frequency]"]').val() || '').replace(/\s*\+\s*/g, ' + ');
        const duration = $row.find('[name$="[duration]"]').val();
        const instructions = $row.find('[name$="[instructions]"]').val();
        const title = [$row.data('dosageName'), name].filter(Boolean).join(' - ');
        const directions = [frequency, dose, instructions, previewDuration($row, duration)].filter(Boolean).join(' ');

        $item.find('.app-prescription-rx-preview-title').text(title).attr('aria-label', 'Edit medicine: ' + title);
        $item.find('.prescription-medicine-directions').text(directions);
    }

    function focusMedicine($row) {
        if (!$row.length) return;

        $focusAfterOpen = $row.find('.prescription-frequency-value');
        if ($modal.hasClass('show')) {
            $focusAfterOpen.trigger('focus');
            $focusAfterOpen = $();
        } else {
            $modal.modal('show');
        }
    }

    function attachPreview($row) {
        const $item = $('<li>');
        const $content = $('<div>').appendTo($item);
        $('<button type="button" class="app-prescription-rx-preview-title">')
            .on('click.prescription', () => focusMedicine($row)).appendTo($content);
        $('<p class="prescription-medicine-directions">').appendTo($content);
        $('<button type="button" class="app-prescription-rx-preview-remove" aria-label="Remove medicine">')
            .append('<i class="fa-solid fa-xmark" aria-hidden="true"></i>')
            .on('click.prescription', () => removeRow($row)).appendTo($item);

        $row.data('preview', $item);
        const $nextPreview = $row.next().data('preview');
        if ($nextPreview?.length) $item.insertBefore($nextPreview);
        else $preview.append($item);
        updatePreview($row);
    }

    function closeDosePalette() {
        dosePalette.close();
        $activeDose = $();
    }

    function openDosePalette($button) {
        closeUnitPalette();
        const alreadyOpen = $activeDose.is($button) && $palette.hasClass('show');
        closeDosePalette();
        if (alreadyOpen) return;

        $activeDose = $button;
        $palette.find('.prescription-dose-choice').each(function () {
            $(this).toggleClass('active', $(this).val() === $button.text().trim());
        });
        dosePalette.open($button);
    }

    function refreshUnitToggle($row) {
        const value = String($row.find('[name$="[dose]"]').val() || '');
        $row.find('.prescription-unit-toggle').val(value);
        $row.find('.prescription-unit-label').text(value || 'select');
    }

    function closeUnitPalette() {
        unitPalette.close();
        $activeUnit = $();
    }

    function appendUnitChoice(name, selected) {
        if (unitButtons.has(name)) return;

        const active = name === selected;
        const $button = $('<button type="button" class="dropdown-item prescription-unit-choice">')
            .attr({ role: 'menuitemradio', 'aria-checked': String(active) })
            .val(name).text(name || '—').toggleClass('active', active)
            .prop('disabled', name.length > 100 && !active).appendTo($unitPalette);
        unitButtons.set(name, $button);
    }

    function renderUnitPalette() {
        const selected = String($activeUnit.closest('.prescription-medicine-row').find('[name$="[dose]"]').val() || '');
        $unitPalette.empty();
        unitButtons.clear();
        appendUnitChoice('', selected);
        unitNames.forEach(name => appendUnitChoice(name, selected));
        if (selected) appendUnitChoice(selected, selected);
    }

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

    function openUnitPalette($button) {
        closeDosePalette();
        const alreadyOpen = $activeUnit.is($button) && $unitPalette.hasClass('show');
        closeUnitPalette();
        if (alreadyOpen) return;

        $activeUnit = $button;
        renderUnitPalette();
        unitPalette.open($button);
        loadUnitOptions();
    }

    function renderSchedule($row, count) {
        closeDosePalette();
        const values = $row.data('scheduleValues');
        const $controls = $row.find('.prescription-schedule-controls').empty();
        const fieldId = $row.find('.prescription-frequency-value').attr('id');
        for (let period = 0; period < count; period++) {
            if (period > 0) $('<span aria-hidden="true">').text('+').appendTo($controls);
            const $host = $('<div class="dropdown prescription-dose-picker">').appendTo($controls);
            $('<button type="button" class="btn btn-sm dropdown-toggle prescription-schedule-dose">')
                .attr({
                    id: fieldId + '-period-' + period,
                    'aria-expanded': 'false',
                    'aria-controls': 'prescription-dose-palette',
                    'aria-label': 'Dose ' + (period + 1),
                }).text(values[period] || '0').appendTo($host);
        }
    }

    function setFrequencyMode($row, mode) {
        const scheduled = mode !== 'custom';
        $row.find('.prescription-frequency-mode').val(mode).text(scheduled ? 'Schedule' : 'Custom frequency');
        $row.find('.prescription-schedule-controls').prop('hidden', !scheduled);
        $row.find('.prescription-schedule-count').each(function () {
            const selected = $(this).val() === mode;
            $(this).toggleClass('active', selected).attr('aria-checked', String(selected));
        });
        if (scheduled) renderSchedule($row, Number(mode));
        else closeDosePalette();
    }

    function updateFrequency($row) {
        const frequency = $row.find('.prescription-schedule-dose').map(function () {
            return $(this).text().trim();
        }).get().join('+');
        $row.find('.prescription-frequency-value').val(frequency).trigger('input');
    }

    function readSchedule(frequency) {
        const values = String(frequency).split('+').map(value => value.trim());
        const validDose = /^(?:\d+(?:\.\d+)?|\.\d+|\d*[\u00bc\u00bd\u00be\u2153\u2154])$/;
        const valid = values.length >= 1 && values.length <= 6 && values.every(value => validDose.test(value));
        return valid ? values : null;
    }

    function initializeFrequency($row) {
        const frequency = String($row.find('.prescription-frequency-value').val() || '');
        const values = readSchedule(frequency);
        const scheduled = !frequency || values !== null;
        const periods = values?.length || 3;
        $row.data('scheduleValues', values || []);
        setFrequencyMode($row, scheduled ? String(periods) : 'custom');
    }

    function syncFrequencyPicker($row) {
        const frequency = String($row.find('.prescription-frequency-value').val() || '');
        const parsed = readSchedule(frequency);
        if (parsed === null && frequency.trim()) return;

        const values = parsed ?? [];
        const periods = values.length || 3;
        const $buttons = $row.find('.prescription-schedule-dose');
        $row.data('scheduleValues', values);
        if ($buttons.length !== periods || $row.find('.prescription-frequency-mode').val() !== String(periods)) {
            setFrequencyMode($row, String(periods));
            return;
        }

        $buttons.each(function (index) { $(this).text(values[index] || '0'); });
    }

    function displayDurationMode($row, unit) {
        const custom = unit === 'custom';
        const hideCount = custom || unit === 'continue';
        $row.find('.prescription-duration-count').prop('hidden', hideCount).prop('disabled', hideCount);
        $row.find('.prescription-duration-value').prop('hidden', !custom);
    }

    function initializeDuration($row) {
        const duration = String($row.find('.prescription-duration-value').val() || '');
        const interval = duration.match(/^(\d+)\s+(day|week|month|year)s?$/i);
        const count = interval ? Number(interval[1]) : null;
        const structured = interval && Number.isSafeInteger(count) && count > 0;
        const continuous = duration === 'চলবে';
        const custom = Boolean(duration) && !structured && !continuous;
        let unit = 'days';
        if (custom) unit = 'custom';
        if (continuous) unit = 'continue';
        if (structured) unit = interval[2].toLowerCase() + 's';

        $row.find('.prescription-duration-unit').val(unit);
        displayDurationMode($row, unit);
        if (structured) $row.find('.prescription-duration-count').val(interval[1]);
    }

    function updateDuration($row) {
        const unit = $row.find('.prescription-duration-unit').val();
        const custom = unit === 'custom';
        const continuous = unit === 'continue';
        const count = $row.find('.prescription-duration-count').val();
        displayDurationMode($row, unit);
        if (custom) return;

        const duration = continuous ? 'চলবে' : (count ? count + ' ' + unit : '');
        $row.find('.prescription-duration-value').val(duration).trigger('input');
    }

    function showInvalidMedicine(event) {
        event.preventDefault();
        $focusAfterOpen = $(event.currentTarget);
        if ($modal.hasClass('show')) {
            $focusAfterOpen.trigger('focus');
            $focusAfterOpen = $();
        } else {
            $modal.modal('show');
        }
    }

    function attachRow($row, medicine) {
        initializeFrequency($row);
        initializeDuration($row);
        refreshUnitToggle($row);
        instructions.initializeRow($row);
        registerChoice($row, medicine);
        $row.data('dosageName', medicine.dosage?.name || '');
        attachPreview($row);
        $row.find(':input').on('invalid.prescription', showInvalidMedicine);

        $row.find('.prescription-medicine-dosage').text(medicine.dosage?.name || '');
    }

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
        $row.find('[name$="[product_id]"]').val(medicine.manual ? '' : medicine.id);
        $row.find('[name$="[medicine_name]"]').val(medicine.name || medicine.text);
        $rows.prepend($row);
        attachRow($row, medicine);
        updateCount();
        return $row;
    }

    function removeRow($row) {
        if (!instructions.canRemoveRow($row)) return;
        closeDosePalette();
        closeUnitPalette();
        $row.find('.select2-hidden-accessible').each(function () { $(this).select2('destroy'); });
        detachChoice($row);
        $row.data('preview').remove();
        $row.remove();
        updateCount();
    }

    function removeChoice(choiceId) {
        const $selectedRows = rowsByChoice.get(choiceId);
        $selectedRows?.each(function () { removeRow($(this)); });
        if (rowsByChoice.has(choiceId)) selectChoice(choiceId);
    }

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
                        focusMedicine(addMedicine(medicine));
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

    function medicineResult(medicine) {
        if (!medicine.id) return medicine.text;

        const $result = $('<div class="app-prescription-rx-result">').data('choiceId', String(medicine.id));
        $('<span class="prescription-medicine-result-check" aria-hidden="true">').appendTo($result);
        const $description = $('<span>').appendTo($result);
        $('<strong>').text(medicine.name || medicine.text).appendTo($description);
        const details = [medicine.dosage?.name, medicine.supplier?.name, medicine.code].filter(Boolean).join(' / ');
        if (details) $('<small>').text(details).appendTo($description);
        return $result;
    }

    $rows.children('.prescription-medicine-row').each(function () {
        const $row = $(this);
        const name = $row.find('[name$="[medicine_name]"]').attr('name');
        const index = name.match(/^medicines\[(\d+)\]/);
        if (index) nextIndex = Math.max(nextIndex, Number(index[1]) + 1);

        const productId = $row.find('[name$="[product_id]"]').val();
        const medicineName = $row.find('[name$="[medicine_name]"]').val();
        attachRow($row, {
            id: productId || 'manual:' + medicineName,
            name: medicineName,
            manual: !productId,
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
            data(params) {
                medicineSearch.begin(params);
                return { search: String(params.term || '').trim().slice(0, 80), page: params.page || 1 };
            },
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
        focusMedicine(addMedicine(event.params.data));
    });
    $search.on('select2:unselect.prescription', event => removeChoice(String(event.params.data.id)));
    $search.on('select2:opening.prescription', () => {
        $rows.find('select.select2-hidden-accessible').select2('close');
        closeDosePalette();
        closeUnitPalette();
    });
    $modal.on('shown.bs.modal.prescription', () => {
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
    });
    $modal.on('hide.bs.modal.prescription', event => {
        instructions.preventWhileSaving(event);
        if (event.isDefaultPrevented()) return;
        $modal.find('select.select2-hidden-accessible').select2('close');
        closeDosePalette();
        closeUnitPalette();
    });
    $('#prescription-form').on('submit.prescriptionInstruction', instructions.preventWhileSaving);

    $rows.on('click.prescription', '.prescription-schedule-count', function () {
        const $row = $(this).closest('.prescription-medicine-row');
        const mode = $(this).val();
        const values = readSchedule($row.find('.prescription-frequency-value').val());
        values?.forEach((value, index) => { $row.data('scheduleValues')[index] = value; });
        setFrequencyMode($row, mode);
        if (mode !== 'custom') updateFrequency($row);
    });
    $rows.on('click.prescription', '.prescription-schedule-dose', function (event) {
        event.preventDefault();
        event.stopPropagation();
        openDosePalette($(this));
    });
    $rows.on('keydown.prescription', '.prescription-schedule-dose', function (event) {
        if (/^[0-6]$/.test(event.key) && !event.altKey && !event.ctrlKey && !event.metaKey && !event.originalEvent?.isComposing) {
            event.preventDefault();
            event.stopImmediatePropagation();
            const $row = $(this).closest('.prescription-medicine-row');
            const position = $row.find('.prescription-schedule-dose').index(this);
            $row.data('scheduleValues')[position] = event.key;
            $(this).text(event.key);
            updateFrequency($row);
            const $nextDose = $row.find('.prescription-schedule-dose').eq(position + 1);
            const $nextControl = $nextDose.length ? $nextDose
                : $row.find('.prescription-duration-count:visible, .prescription-duration-value:visible').first();
            $nextControl.trigger('focus');
            return;
        }
        if (!['ArrowDown', 'ArrowUp', 'Enter', ' ', 'Escape'].includes(event.key)) return;
        event.preventDefault();
        event.stopPropagation();
        if (event.key === 'Escape') {
            if ($palette.hasClass('show')) closeDosePalette();
            else $modal.modal('hide');
            return;
        }
        const $button = $(this);
        if (!$activeDose.is($button) || !$palette.hasClass('show')) openDosePalette($button);
        const $selected = $palette.find('.prescription-dose-choice.active');
        const $choice = $selected.length ? $selected : $palette.find('.prescription-dose-choice').first();
        $choice.trigger('focus');
    });
    $palette.on('click.prescription', '.prescription-dose-choice', function () {
        const $row = $activeDose.closest('.prescription-medicine-row');
        const position = $row.find('.prescription-schedule-dose').index($activeDose);
        const value = $(this).val();
        $row.data('scheduleValues')[position] = value;
        $activeDose.text(value);
        updateFrequency($row);
        const $focus = $activeDose;
        closeDosePalette();
        $focus.trigger('focus');
    });
    $palette.on('keydown.prescription', function (event) {
        if (event.key !== 'Escape') return;
        event.preventDefault();
        event.stopPropagation();
        const $focus = $activeDose;
        closeDosePalette();
        $focus.trigger('focus');
    });
    $rows.on('click.prescriptionUnit', '.prescription-unit-toggle', function (event) {
        event.preventDefault();
        event.stopPropagation();
        openUnitPalette($(this));
    });
    $rows.on('keydown.prescriptionUnit', '.prescription-unit-toggle', function (event) {
        if (!['ArrowDown', 'ArrowUp', 'Enter', ' ', 'Escape'].includes(event.key)) return;
        event.preventDefault();
        event.stopPropagation();
        if (event.key === 'Escape') {
            if ($unitPalette.hasClass('show')) closeUnitPalette();
            else $modal.modal('hide');
            return;
        }
        const $button = $(this);
        if (!$activeUnit.is($button) || !$unitPalette.hasClass('show')) openUnitPalette($button);
        const $selected = $unitPalette.find('.prescription-unit-choice.active');
        const $choice = $selected.length ? $selected : $unitPalette.find('.prescription-unit-choice').first();
        $choice.trigger('focus');
    });
    $unitPalette.on('click.prescriptionUnit', '.prescription-unit-choice', function () {
        const $row = $activeUnit.closest('.prescription-medicine-row');
        const $focus = $activeUnit;
        $row.find('[name$="[dose]"]').val($(this).val()).trigger('input');
        closeUnitPalette();
        $focus.trigger('focus');
    });
    $unitPalette.on('keydown.prescriptionUnit', function (event) {
        if (event.key !== 'Escape') return;
        event.preventDefault();
        event.stopPropagation();
        const $focus = $activeUnit;
        closeUnitPalette();
        $focus.trigger('focus');
    });
    $rows.on('input.prescription change.prescription',
        '.prescription-duration-count, .prescription-duration-unit', function () {
            updateDuration($(this).closest('.prescription-medicine-row'));
        });
    $rows.on('input.prescription change.prescription', '[name]', function () {
        const $input = $(this);
        const $row = $input.closest('.prescription-medicine-row');
        if ($input.is('[name$="[medicine_name]"]')) {
            detachChoice($row);
            $row.removeData('dosageName');
            $row.find('[name$="[product_id]"]').val('');
            $row.find('.prescription-medicine-dosage').text('');
        }
        if ($input.is('[name$="[dose]"]')) refreshUnitToggle($row);
        if ($input.is('[name$="[frequency]"]')) syncFrequencyPicker($row);
        updatePreview($row);
    });
    $rows.on('click.prescription', '.prescription-medicine-remove', function () {
        removeRow($(this).closest('.prescription-medicine-row'));
    });

    initializePrescriptionKeyboard($modal, $search, $rows);
}
