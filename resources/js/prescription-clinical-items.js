import $ from './jquery';

const statesByField = new Map();

function selectedNames($value) {
    return String($value.val() || '').split(/\r?\n/).map(name => name.trim()).filter(Boolean);
}

function rowControls($element) {
    return {
        $element,
        $name: $element.find('input[name$="[name]"]'),
        $duration: $element.find('input[name$="[duration]"]'),
        $unit: $element.find('select[name$="[duration_unit]"]'),
        $notes: $element.find('textarea[name$="[notes]"]'),
    };
}

function allocateIndex(state) {
    while (state.usedIndices.has(state.nextIndex)) state.nextIndex++;
    const index = state.nextIndex++;
    state.usedIndices.add(index);
    return index;
}

function createRow(state, name) {
    const index = allocateIndex(state);
    const markup = state.$template.html().replaceAll('__INDEX__', String(index));
    const row = rowControls($(markup));
    row.$name.val(name);
    row.$element.find('.prescription-clinical-item-title').text(name);
    row.$element.find('.prescription-clinical-item-remove').attr('aria-label', 'Remove ' + name);
    return row;
}

function syncRows(state) {
    const remaining = state.rows;
    const ordered = new Map();

    for (const name of selectedNames(state.$value)) {
        if (ordered.has(name)) continue;
        const row = remaining.get(name) || createRow(state, name);
        remaining.delete(name);
        ordered.set(name, row);
        row.$element.insertBefore(state.$template);
    }

    for (const row of remaining.values()) row.$element.remove();
    state.rows = ordered;
}

function restoreSavedRows(state) {
    state.$container.children('.app-prescription-clinical-item').each(function () {
        const row = rowControls($(this));
        const name = row.$name.val();
        const inputName = row.$name.attr('name') || '';
        const indexMatch = inputName.slice(state.namePrefix.length).match(/^\[(\d+)\]\[name\]$/);
        const index = indexMatch ? Number(indexMatch[1]) : NaN;

        if (typeof name !== 'string' || !name.trim() || !Number.isSafeInteger(index) || state.rows.has(name.trim())) {
            row.$element.remove();
            return;
        }

        state.usedIndices.add(index);
        state.rows.set(name.trim(), row);
    });
}

function initializeContainer($container) {
    const $value = $($container.attr('data-value'));
    if (!$value.is('textarea')) return;

    const state = {
        $container,
        $value,
        $template: $container.children('.prescription-clinical-item-template'),
        namePrefix: $container.attr('data-name'),
        rows: new Map(),
        usedIndices: new Set(),
        nextIndex: 0,
    };

    restoreSavedRows(state);
    $container.data('prescriptionClinicalItems', state);
    statesByField.set($value.attr('id'), state);
    $value.on('change.prescriptionClinicalItems', () => syncRows(state));
    $container.on('input.prescriptionClinicalItems change.prescriptionClinicalItems',
        'input[name$="[duration]"], select, textarea', () => $value.trigger('prescription:details'));
    $container.on('click.prescriptionClinicalItems', '.prescription-clinical-item-remove', function () {
        const $row = $(this).closest('.app-prescription-clinical-item');
        const name = String($row.find('input[name$="[name]"]').val() || '').trim();
        $value.val(selectedNames($value).filter(selected => selected !== name).join('\n')).trigger('change');
    });
    syncRows(state);
}

export function clinicalItemDescription($field, name) {
    const row = statesByField.get($field.attr('id'))?.rows.get(name.trim());
    if (!row) return name;

    const duration = String(row.$duration.val() || '').trim();
    const unit = row.$unit.val();
    const notes = String(row.$notes.val() || '').trim();
    let durationText = '';
    if (unit === 'long_ago') {
        durationText = 'Long time ago';
    } else if (duration) {
        durationText = 'for ' + duration + ' ' + row.$unit.children(':selected').text();
    }

    return [name, durationText, notes].filter(Boolean).join(' \u2014 ');
}

export function initializePrescriptionClinicalItems() {
    $('.app-prescription-clinical-items').each(function () {
        initializeContainer($(this));
    });
}
