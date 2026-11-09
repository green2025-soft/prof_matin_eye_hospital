import $ from './jquery';
import 'bootstrap';
import flatpickr from 'flatpickr';
import { initializePrescriptionPickers } from './prescription-picker';
import { initializePrescriptionMedicines } from './prescription-medicines';
import { initializePrescriptionPreviews } from './prescription-preview';
import { initializePrescriptionClinicalItems } from './prescription-clinical-items';
import { initializePrescriptionExamination } from './prescription-examination';
import './prescription-print';
import './prescription-settings';

// Append catalog choices to history notes without adding the same name twice.
function initializeHistoryCatalog($select, $notes) {
    $select.attr('data-ajax-select', '');
    window.initAjaxSelects($select);
    $select.on('select2:select.prescription', event => {
        const choice = event.params.data;
        const name = choice.name || choice.text;
        const lines = String($notes.val() || '').split('\n').filter(Boolean);
        if (!lines.includes(name)) lines.push(name);

        $notes.val(lines.join('\n')).trigger('input');
        $select.val(null).trigger('change');
    });
}

// Connect the existing Medicine and Investigation catalogs to their history fields.
function initializeClinicalNotes() {
    initializeHistoryCatalog($('#prescription-history-medicine-search'), $('#history_drug'));
    initializeHistoryCatalog($('#prescription-history-investigation-search'), $('#history_investigation'));
}

// Keep the follow-up interval and date synchronized without resetting programmatic changes.
function initializeFollowUp() {
    const $interval = $('#prescription-follow-up-interval');
    const $unit = $('#prescription-follow-up-unit');
    const $date = $('#follow_up_date');
    const $visitDate = $('#prescription_date');
    let updatingDateFromInterval = false;

    // Refresh the pad after either an interval or a date changes.
    function notifyFollowUp() {
        $date.trigger('prescription:follow-up');
    }

    // Calculate the follow-up date, clamping month-end dates to the destination month.
    function updateFromInterval() {
        const count = Number($interval.val());
        const date = flatpickr.parseDate($visitDate.val(), 'Y-m-d');
        if (!$interval.val() || !$interval.prop('validity').valid || !date) {
            notifyFollowUp();
            return;
        }

        if ($unit.val() === 'months') {
            const day = date.getDate();
            date.setDate(1);
            date.setMonth(date.getMonth() + count);
            const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
            date.setDate(Math.min(day, lastDay));
        } else {
            date.setDate(date.getDate() + count);
        }

        const picker = $date.closest('[data-datepicker]').get(0)?._flatpickr;
        updatingDateFromInterval = true;
        if (picker) picker.setDate(date, true);
        else $date.val(flatpickr.formatDate(date, 'Y-m-d')).trigger('change');
        updatingDateFromInterval = false;
        notifyFollowUp();
    }

    $interval.add($unit).on('input.prescriptionFollowUp change.prescriptionFollowUp', updateFromInterval);
    $visitDate.on('change.prescriptionFollowUp', updateFromInterval);
    $date.on('change.prescriptionFollowUp', () => {
        if (updatingDateFromInterval) return;

        $interval.val('');
        $unit.val('days');
        notifyFollowUp();
    });
}

// Show a modal's preset search only while its catalog tab is active.
function bindTabSearch($modal, $pane, $search) {
    // Match search visibility to the active tab after Bootstrap completes the switch.
    function updateSearchVisibility() {
        $search.prop('hidden', !$pane.hasClass('active'));
    }
    $modal.find('[data-bs-toggle="tab"]').on('shown.bs.tab.prescription', updateSearchVisibility);
    updateSearchVisibility();
}

// Keep Select2's compact header search hint visible after choices change.
function initializeHeaderSearches() {
    $('.app-prescription-section-modal .modal-header select[multiple]').each(function () {
        const $select = $(this);
        const $input = $select.data('select2').$container.find('.select2-search__field');
        // Select2 clears this hint when a multiple selection changes.
        function restorePlaceholder() {
            $input.attr('placeholder', 'Search/Add Item...');
        }

        $select.on('change.select2.prescriptionHeader', restorePlaceholder);
        restorePlaceholder();
    });
}

// Open the modal and tab containing the first error returned by the server.
function openValidationModal() {
    const $error = $('.app-prescription-section-modal').find('.is-invalid, .invalid-feedback').first();
    const $modal = $error.closest('.modal');
    if (!$modal.length) return;

    const paneId = $error.closest('.tab-pane').attr('id');
    if (paneId) $('[data-bs-target="#' + paneId + '"]').tab('show');
    const trigger = $('[data-bs-target="#' + $modal.attr('id') + '"]').get(0);
    $modal.modal('show', trigger);
}

// Initialize the prescription editor once its own form is present.
$(function initializePrescriptionForm() {
    if (!$('#prescription-form').length) return;

    initializePrescriptionPickers();
    initializePrescriptionMedicines();
    initializeHeaderSearches();
    initializePrescriptionClinicalItems();
    initializePrescriptionExamination();
    initializePrescriptionPreviews();
    initializeClinicalNotes();
    initializeFollowUp();
    bindTabSearch($('#prescription-history-modal'), $('#history-general-pane'), $('.prescription-history-header-search'));
    bindTabSearch(
        $('#prescription-examination-modal'), $('#examination-observation-pane'), $('.prescription-examination-header-search'),
    );
    window.initDateTimePickers($('.app-prescription-section-modal'));
    $('.prescription-picker-search').closest('.modal').on('shown.bs.modal.prescriptionFocus', function () {
        $(this).find('.is-invalid:visible, .modal-header .select2-search__field:visible').first().trigger('focus');
    });
    openValidationModal();
});
