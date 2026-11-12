import $ from './jquery';

// Expand one optional measurement and focus its first input.
function toggleMeasurement(event) {
    const $button = $(event.currentTarget);
    const $row = $('#' + $.escapeSelector($button.attr('aria-controls')));
    const expanded = $button.attr('aria-expanded') !== 'true';

    $button.attr('aria-expanded', String(expanded)).toggleClass('active', expanded);
    $row.prop('hidden', !expanded);
    if (expanded) $row.find('input').first().trigger('focus');
}

// Bind the optional measurement buttons once on their own pane.
export function initializePrescriptionExamination() {
    $('#examination-general-pane').off('.prescriptionExamination')
        .on('click.prescriptionExamination', '.prescription-examination-metric-toggle', toggleMeasurement);
}
