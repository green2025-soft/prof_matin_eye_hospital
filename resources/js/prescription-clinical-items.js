import $ from './jquery';

const statesByField = new Map();

// Read the selected preset names in their displayed order.
function selectedNames($value) {
    return String($value.val() || '').split(/\r?\n/).map(name => name.trim()).filter(Boolean);
}

// Cache the editable fields belonging to one clinical item.
function rowControls($element) {
    return {
        $element,
        $name: $element.find('input[name$="[name]"]'),
        $duration: $element.find('input[name$="[duration]"]'),
        $unit: $element.find('select[name$="[duration_unit]"]'),
        $notes: $element.find('textarea[name$="[notes]"]'),
    };
}

// Build one named item using an index above all previously rendered rows.
function createRow(state, name) {
    const index = state.nextIndex++;
    const markup = state.$template.html().replaceAll('__INDEX__', String(index));
    const row = rowControls($(markup));
    row.$name.val(name);
    row.$element.find('.prescription-clinical-item-title').text(name);
    row.$element.find('.prescription-clinical-item-remove').attr('aria-label', 'Remove ' + name);
    return row;
}

// Reuse rows for retained names so duration and notes survive selection changes.
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

// Restore valid saved rows and advance the counter to prevent duplicate input names.
function restoreSavedRows(state) {
    state.$container.children('.app-prescription-clinical-item').each(function () {
        const row = rowControls($(this));
        const name = row.$name.val().trim();
        const inputName = row.$name.attr('name');
        const indexMatch = inputName.slice(state.namePrefix.length).match(/^\[(\d+)\]\[name\]$/);
        const index = indexMatch ? Number(indexMatch[1]) : NaN;

        if (!name || !Number.isSafeInteger(index) || state.rows.has(name)) {
            row.$element.remove();
            return;
        }

        state.nextIndex = Math.max(state.nextIndex, index + 1);
        state.rows.set(name, row);
    });
}

// Connect one picker value to its editable duration, unit and notes rows.
function initializeContainer($container) {
    const $value = $($container.attr('data-value'));
    const state = {
        $container,
        $value,
        $template: $container.children('.prescription-clinical-item-template'),
        namePrefix: $container.attr('data-name'),
        rows: new Map(),
        nextIndex: 0,
    };

    restoreSavedRows(state);
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

// Format the item's saved details for the prescription pad preview.
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

// Initialize each of the six identical clinical item editors.
export function initializePrescriptionClinicalItems() {
    $('.app-prescription-clinical-items').each(function () {
        initializeContainer($(this));
    });
}
