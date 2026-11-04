import $ from './jquery';
import flatpickr from 'flatpickr';
import { clinicalItemDescription } from './prescription-clinical-items';

function fieldValue($field) {
    return String($field.val() || '').trim();
}

function noteLines($field) {
    return String($field.val() || '').split(/\r?\n/).filter(line => line.trim());
}

function updatePreviewGroup($list) {
    const $group = $list.closest('.app-prescription-preview-group');
    $group.prop('hidden', !$group.find('li').length);
}

function restorePreviewFocus($list, index) {
    const $items = $list.children('li');
    const $button = $items.eq(Math.min(index, $items.length - 1)).find('button');
    if ($button.length) {
        $button.trigger('focus');
        return;
    }

    $list.closest('.app-prescription-note').find('.app-prescription-section-heading button').first().trigger('focus');
}

function appendPreviewItem($list, text, remove) {
    const $item = $('<li>', { class: 'app-prescription-preview-item' });
    const $button = $('<button>', {
        type: 'button',
        class: 'app-prescription-preview-remove',
        'aria-label': 'Remove ' + text,
    }).append($('<i>', { class: 'fa-solid fa-xmark', 'aria-hidden': 'true' }));

    $button.on('click.prescriptionPreview', () => {
        const index = $item.index();
        remove();
        restorePreviewFocus($list, index);
    });

    $item.append($('<span>').text(text), $button).appendTo($list);
}

function bindNotePreview($field, $list) {
    function refresh() {
        const lines = noteLines($field);
        $list.empty();
        lines.forEach((line, index) => {
            appendPreviewItem($list, clinicalItemDescription($field, line), () => {
                $field.val(lines.filter((_, position) => position !== index).join('\n')).trigger('change');
            });
        });
        $list.prop('hidden', !lines.length);
        updatePreviewGroup($list);
    }

    $field.on('input.prescriptionPreview change.prescriptionPreview prescription:details', refresh);
    refresh();
}

function initializePersonalHistory() {
    const $choices = $('#history-personal-pane :checkbox');
    const $list = $('#history-personal-preview');

    function refresh() {
        $list.empty();
        $choices.filter(':checked').each(function () {
            const $choice = $(this);
            const label = $choice.siblings('.form-check-label').text();
            appendPreviewItem($list, label, () => $choice.prop('checked', false).trigger('change'));
        });
        updatePreviewGroup($list);
    }

    $choices.on('change.prescriptionPreview', refresh);
    refresh();
}

function initializeHistoryPreviews() {
    bindNotePreview($('#history'), $('#history-general-preview'));
    bindNotePreview($('#history_drug'), $('#history-drug-preview'));
    bindNotePreview($('#history_drug'), $('#history-drug-selection'));
    bindNotePreview($('#history_investigation'), $('#history-investigation-preview'));
    bindNotePreview($('#history_investigation'), $('#history-investigation-selection'));
    bindNotePreview($('#history_personal_notes'), $('#history-personal-notes-preview'));
    bindNotePreview($('#history_family'), $('#history-family-preview'));
    bindNotePreview($('#history_surgical'), $('#history-surgical-preview'));
    bindNotePreview($('#history_allergic'), $('#history-allergic-preview'));
    bindNotePreview($('#history_other'), $('#history-other-preview'));
    initializePersonalHistory();
}

function appendMeasurement($list, $field, label, unit = '') {
    const value = fieldValue($field);
    if (!value) return;

    const text = label + ': ' + value + (unit ? ' ' + unit : '');
    appendPreviewItem($list, text, () => $field.val('').trigger('change'));
}

function appendBloodPressure($list) {
    const $systolic = $('#exam_bp_systolic');
    const $diastolic = $('#exam_bp_diastolic');
    const systolic = fieldValue($systolic);
    const diastolic = fieldValue($diastolic);
    if (!systolic && !diastolic) return;

    const text = 'BP: ' + (systolic || '\u2014') + '/' + (diastolic || '\u2014') + ' mmHg';
    appendPreviewItem($list, text, () => {
        $systolic.add($diastolic).val('');
        $systolic.trigger('change');
    });
}

function appendPulse($list) {
    const $pulse = $('#exam_pulse');
    const $rhythms = $('input[name="examination_details[pulse_rhythm]"]');
    const value = fieldValue($pulse);
    const rhythm = $rhythms.filter(':checked').next('label').text().trim();
    if (!value && !rhythm) return;

    const text = 'Pulse: ' + [value && value + ' BPM', rhythm].filter(Boolean).join(', ');
    appendPreviewItem($list, text, () => {
        $rhythms.prop('checked', false);
        $pulse.val('').trigger('change');
    });
}

function appendTemperature($list) {
    const $temperature = $('#exam_temperature');
    const $normal = $('#exam_temperature_normal');
    const value = fieldValue($temperature);
    const normal = $normal.is(':checked');
    if (!value && !normal) return;

    const unit = fieldValue($('#exam_temperature_unit')).toUpperCase();
    const text = 'Temperature: ' + (normal ? 'Normal' : value + ' \u00b0' + unit);
    appendPreviewItem($list, text, () => {
        $normal.prop('checked', false);
        $temperature.val('').trigger('change');
    });
}

function appendHeight($list) {
    if (!$('#exam_height_visible').is(':checked')) return;

    const $feet = $('#exam_height_feet');
    const $inches = $('#exam_height_inches');
    const feet = fieldValue($feet);
    const inches = fieldValue($inches);
    if (!feet && !inches) return;

    const text = 'Height: ' + [feet && feet + ' ft', inches && inches + ' in'].filter(Boolean).join(' ');
    appendPreviewItem($list, text, () => {
        $feet.add($inches).val('');
        $feet.trigger('change');
    });
}

function initializeExaminationPreviews() {
    const $fields = $('#prescription-examination-modal :input[name^="examination_details["]');
    const $list = $('#examination-measurements-preview');

    function refresh() {
        $list.empty();
        appendBloodPressure($list);
        appendPulse($list);
        appendTemperature($list);
        if ($('#exam_weight_visible').is(':checked')) {
            appendMeasurement($list, $('#exam_weight'), 'Weight', 'kg');
        }
        appendHeight($list);
        appendMeasurement($list, $('#exam_spo2'), 'SpO2', '%');
        appendMeasurement($list, $('#exam_bmi'), 'BMI');
        appendMeasurement($list, $('#exam_bsa'), 'BSA');
        appendMeasurement($list, $('#exam_bmr'), 'BMR');
        appendMeasurement($list, $('#exam_tdee'), 'TDEE');
        appendMeasurement($list, $('#exam_activity'), 'Activity');
        appendMeasurement($list, $('#exam_ideal_weight'), 'Ideal weight', 'kg');
        appendMeasurement($list, $('#exam_ideal_bmi'), 'Ideal BMI');
        appendMeasurement($list, $('#exam_extra_calories'), 'Extra Cal', 'Cal');
        appendMeasurement($list, $('#exam_target_calories'), 'Target daily Cal', 'Cal');
    }

    bindNotePreview($('#examination'), $('#examination-observation-preview'));
    $fields.on('input.prescriptionPreview change.prescriptionPreview', refresh);
    refresh();
}

function initializeFollowUpPreview() {
    const $notes = $('#follow_up_notes');
    const $date = $('#follow_up_date');
    const $interval = $('#prescription-follow-up-interval');
    const $unit = $('#prescription-follow-up-unit');
    const $list = $('#follow-up-preview');

    function schedulePrefix() {
        const count = Number($interval.val());
        const unit = $unit.val();
        if (Number.isInteger(count) && count > 0 && count <= 3650 && (unit === 'days' || unit === 'months')) {
            return count + ' ' + $unit.children(':selected').text() + ' পর';
        }

        const date = flatpickr.parseDate($date.val(), 'Y-m-d');
        return date ? flatpickr.formatDate(date, 'd-m-Y') + ' তারিখে' : '';
    }

    function clearFollowUp() {
        $interval.val('');
        $unit.val('days');
        $notes.val('');
        $date.closest('[data-datepicker]').get(0)?._flatpickr?.clear(false);
        $date.val('');
        $notes.trigger('change');
        $date.trigger('prescription:follow-up');
    }

    function refresh() {
        const prefix = schedulePrefix();
        const lines = noteLines($notes);
        $list.empty();
        if (!lines.length) {
            if (prefix) appendPreviewItem($list, prefix, clearFollowUp);
            return;
        }

        lines.forEach((line, index) => {
            appendPreviewItem($list, [prefix, line].filter(Boolean).join(' '), () => {
                const remaining = lines.filter((_, position) => position !== index);
                if (!remaining.length) {
                    clearFollowUp();
                    return;
                }

                $notes.val(remaining.join('\n')).trigger('change');
            });
        });
    }

    $notes.on('input.prescriptionFollowUpPreview change.prescriptionFollowUpPreview', refresh);
    $date.on('prescription:follow-up', refresh);
    refresh();
}

export function initializePrescriptionPreviews() {
    bindNotePreview($('#chief_complaints'), $('#chief_complaints-preview'));
    bindNotePreview($('#diagnosis'), $('#diagnosis-preview'));
    bindNotePreview($('#investigations'), $('#investigations-preview'));
    bindNotePreview($('#advice'), $('#advice-preview'));
    initializeHistoryPreviews();
    initializeExaminationPreviews();
    initializeFollowUpPreview();
}
