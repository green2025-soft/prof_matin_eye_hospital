import $ from './jquery';

// Rebuild the year choices only when the picker's allowed range changes.
function updateYearOptions($select, picker) {
    const firstYear = picker.config.minDate?.getFullYear() ?? picker.currentYear - 100;
    const lastYear = picker.config.maxDate?.getFullYear() ?? picker.currentYear + 100;
    const $options = $select.children('option');
    const rangeMatches = Number($options.first().val()) === firstYear
        && Number($options.last().val()) === lastYear;

    if (!rangeMatches) {
        let options = '';
        for (let year = firstYear; year <= lastYear; year++) {
            options += `<option value="${year}">${year}</option>`;
        }
        $select.html(options);
    }

    $select.val(String(picker.currentYear)).prop('disabled', firstYear === lastYear);
}

// Replace Flatpickr's numeric year with the same dropdown behavior as its month.
export function synchronizeDatepickerYear(_, __, picker) {
    if (!picker.currentYearElement) return;

    const $number = $(picker.currentYearElement).closest('.numInputWrapper');
    let $select = $number.siblings('.app-datepicker-year');

    if (!$select.length) {
        $select = $('<select class="app-datepicker-year border-0 bg-transparent text-reset">')
            .attr('aria-label', picker.l10n.yearAriaLabel)
            .on('change.datepickerYear', function () {
                picker.changeYear(Number($(this).val()));
            })
            .on('keydown.datepickerYear', event => event.stopPropagation())
            .insertAfter($number);
    }

    $number.prop('hidden', true);
    updateYearOptions($select, picker);
}

// jQuery removes the dropdown's event handlers with its element.
export function destroyDatepickerYear(_, __, picker) {
    $(picker.calendarContainer).find('.app-datepicker-year').remove();
}
