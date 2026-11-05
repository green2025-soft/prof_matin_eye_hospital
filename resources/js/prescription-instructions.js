import $ from './jquery';
import { createEmptySearchGuard } from './select2-empty-search';

export function initializePrescriptionInstructions($modal, $rows) {
    let pendingInstructionWrites = 0;

    function updateRow($row) {
        const instruction = String($row.find('[name$="[instructions]"]').val() || '').trim();
        let isFoodChoice = false;
        const $foodChoices = $row.find('.prescription-food-instruction').attr('tabindex', '-1');
        $foodChoices.each(function () {
            const selected = $(this).text().trim() === instruction;
            $(this).toggleClass('active', selected).attr('aria-pressed', String(selected));
            if (selected) isFoodChoice = true;
        });
        const $selectedFood = $foodChoices.filter('[aria-pressed="true"]');
        ($selectedFood.length ? $selectedFood : $foodChoices.first()).attr('tabindex', '0');
        const $selection = $row.find('.prescription-instruction-selection').empty();
        if (!instruction || isFoodChoice) return;

        const $badge = $('<span class="prescription-instruction-badge">').appendTo($selection);
        $('<span>').text(instruction).appendTo($badge);
        $('<button type="button" aria-label="Remove instruction">')
            .append('<i class="fa-solid fa-xmark" aria-hidden="true"></i>')
            .on('click.prescription', () => {
                $row.find('.prescription-instruction-search').val(null).trigger('change');
            }).appendTo($badge);
    }

    function instructionResult(choice, createUrl) {
        if (!choice.id) return choice.text;

        const $result = $('<div class="app-prescription-text-choice">')
            .append('<span class="prescription-text-choice-marker" aria-hidden="true"></span>')
            .append($('<span>').text(choice.name || choice.text));
        if (choice.newTag && createUrl) {
            $result.addClass('prescription-instruction-create');
            $result.children('span').last().text('Create "' + choice.name + '"');
        }
        return $result;
    }

    function initializeInstructionChoice($select, $value, createUrl) {
        const emptySearch = createEmptySearchGuard($select);
        const localChoices = new Set($select.find('option').map(function () {
            return $(this).val();
        }).get().filter(Boolean));

        $select.select2({
            width: '100%',
            dropdownParent: $modal,
            dropdownCssClass: 'app-prescription-instruction-dropdown',
            placeholder: $select.siblings('label').text().trim(),
            allowClear: true,
            tags: true,
            minimumInputLength: 0,
            maximumInputLength: 255,
            templateResult: choice => instructionResult(choice, createUrl),
            createTag(params) {
                const name = String(params.term || '').trim();
                return emptySearch.canCreate(name) ? { id: name, text: name, name, newTag: true } : null;
            },
            ajax: {
                url: $select.attr('data-url'),
                dataType: 'json',
                delay: 250,
                timeout: 15000,
                data(params) {
                    emptySearch.begin(params);
                    return { search: String(params.term || '').trim().slice(0, 80), page: params.page || 1 };
                },
                processResults(data, params) {
                    const names = new Set();
                    const search = String(params.term || '').trim().toLocaleLowerCase();
                    if (!params.page || params.page === 1) {
                        localChoices.forEach(name => {
                            if (name.toLocaleLowerCase().includes(search)) names.add(name);
                        });
                    }
                    data.results.forEach(choice => names.add(choice.name));

                    if (!emptySearch.complete(params, names.size, data.pagination?.more)) {
                        return { results: [], pagination: { more: false } };
                    }

                    return {
                        results: [...names].map(name => this._normalizeItem({
                            id: name,
                            text: name,
                            name,
                            disabled: name.length > 255,
                        })),
                        pagination: data.pagination,
                    };
                },
            },
        });
        emptySearch.bindSearch();
        $select.on('prescription:optionSaved.prescription', (_, name) => localChoices.add(name));
        $select.on('change.prescription', () => {
            const selected = $select.select2('data')[0];
            $value.val(selected?.name || selected?.text || '').trigger('input');
        });
    }

    function selectInstruction($row, name) {
        const $select = $row.find('.prescription-instruction-search');
        const exists = $select.find('option').filter(function () { return $(this).val() === name; }).length;
        if (!exists) $('<option>').val(name).text(name).appendTo($select);
        $select.val(name || null).trigger('change');
    }

    function clearInstructionError($row) {
        $row.find('.prescription-instruction-search').removeClass('is-invalid');
        $row.find('.prescription-instruction-error').removeClass('invalid-feedback d-block').empty();
    }

    function showInstructionFailure($row, xhr) {
        const errors = xhr.responseJSON?.errors || {};
        const fieldMessages = Object.entries(errors)
            .filter(([field]) => field !== 'record').flatMap(([, messages]) => messages).join(' ');
        if (xhr.status === 422 && fieldMessages) {
            $row.find('.prescription-instruction-search').addClass('is-invalid');
            $row.find('.prescription-instruction-error').addClass('invalid-feedback d-block').text(fieldMessages);
            return;
        }

        const recordMessage = (errors.record || []).join(' ');
        window.showToast(recordMessage || 'The instruction could not be saved. Please try again.', 'error');
    }

    function createInstruction($row, name, url) {
        const $select = $row.find('.prescription-instruction-search');
        if ($select.data('instructionCreateRequest')) return;

        clearInstructionError($row);
        $select.select2('close').prop('disabled', true).attr('aria-busy', 'true');
        $row.find('.prescription-food-instruction, .prescription-instruction-badge button').prop('disabled', true);
        pendingInstructionWrites++;
        const request = $.ajax({
            url,
            method: 'POST',
            data: {
                section: 'medicine_instruction',
                name,
                ordering: 0,
                _token: $('#prescription-form input[name="_token"]').val(),
            },
            dataType: 'json',
            headers: { Accept: 'application/json' },
            timeout: 30000,
        });
        $select.data('instructionCreateRequest', request);
        request.done((option, _, xhr) => {
            if (xhr.status !== 201 || typeof option?.name !== 'string' || !option.name.trim()) {
                window.showToast('The saved instruction response could not be read. Refresh the page.', 'error');
                return;
            }

            $rows.find('.prescription-instruction-search').trigger('prescription:optionSaved', [option.name]);
            selectInstruction($row, option.name);
            window.showToast('Instruction saved to list.', 'success');
        }).fail((xhr, status) => {
            if (status !== 'abort') showInstructionFailure($row, xhr);
        }).always(() => {
            pendingInstructionWrites--;
            $select.removeData('instructionCreateRequest').prop('disabled', false).removeAttr('aria-busy');
            $row.find('.prescription-food-instruction, .prescription-instruction-badge button').prop('disabled', false);
            const $error = $row.find('.prescription-instruction-error');
            const $focus = $error.text().trim()
                ? $error.attr('tabindex', '-1')
                : $select.next('.select2-container').find('.select2-selection');
            $focus.trigger('focus');
        });
    }

    function initializeRow($row) {
        const $select = $row.find('.prescription-instruction-search');
        const createUrl = $select.attr('data-create-url');
        initializeInstructionChoice($select, $row.find('[name$="[instructions]"]'), createUrl);
        updateRow($row);

        $select.on('select2:selecting.prescriptionInstruction', event => {
            if (event.isDefaultPrevented()) return;

            const choice = event.params.args.data;
            if (!choice.newTag || !createUrl) return;
            event.preventDefault();
            createInstruction($row, choice.name, createUrl);
        });
        $select.on('change.prescriptionInstruction', () => clearInstructionError($row));
    }

    function preventWhileSaving(event) {
        if (pendingInstructionWrites === 0) return;

        event.preventDefault();
        window.showToast('Please wait for the instruction to finish saving.', 'info');
    }

    function canRemoveRow($row) {
        if (!$row.find('.prescription-instruction-search').data('instructionCreateRequest')) return true;

        window.showToast('Please wait for the instruction to finish saving.', 'info');
        return false;
    }

    $rows.on('click.prescriptionInstruction', '.prescription-food-instruction', function () {
        const $row = $(this).closest('.prescription-medicine-row');
        selectInstruction($row, $(this).text().trim());
        $row.find('.prescription-unit-toggle').trigger('focus');
    });
    $rows.on('input.prescriptionInstruction change.prescriptionInstruction', '[name$="[instructions]"]', function () {
        updateRow($(this).closest('.prescription-medicine-row'));
    });

    return { initializeRow, canRemoveRow, preventWhileSaving };
}
