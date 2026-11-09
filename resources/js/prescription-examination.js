import $ from './jquery';

// Expand optional examination measurements and focus their first input.
export function initializePrescriptionExamination() {
    const $pane = $('#examination-general-pane');
    $pane.off('.prescriptionExamination')
        .on('click.prescriptionExamination', '.prescription-examination-metric-toggle', function () {
            const $button = $(this);
            const $row = $('#' + $.escapeSelector($button.attr('aria-controls')));
            const expanded = $button.attr('aria-expanded') !== 'true';

            $button.attr('aria-expanded', String(expanded)).toggleClass('active', expanded);
            $row.prop('hidden', !expanded);
            if (expanded) $row.find('input').first().trigger('focus');
        });
}
