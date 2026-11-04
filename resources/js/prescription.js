import $ from './jquery';
import 'bootstrap';
import flatpickr from 'flatpickr';
import { initializePrescriptionPickers } from './prescription-picker';
import { initializePrescriptionMedicines } from './prescription-medicines';
import { initializePrescriptionPreviews } from './prescription-preview';
import { initializePrescriptionClinicalItems } from './prescription-clinical-items';
import { initializePrescriptionExamination } from './prescription-examination';

function initializeHistoryCatalog($select, $notes) {
    $select.attr('data-ajax-select', '');
    window.initAjaxSelects($select);
    $select.on('select2:select.prescription', event => {
        const medicine = event.params.data;
        const name = medicine.name || medicine.text;
        const lines = String($notes.val() || '').split('\n').filter(Boolean);
        if (!lines.includes(name)) lines.push(name);
        const value = lines.join('\n');
        if (value.length > 5000) {
            window.showToast('History notes cannot exceed 5,000 characters.', 'warning');
            return;
        }
        $notes.val(value).trigger('input');
        $select.val(null).trigger('change');
    });
}

function initializeClinicalNotes() {
    initializeHistoryCatalog($('#prescription-history-medicine-search'), $('#history_drug'));
    initializeHistoryCatalog($('#prescription-history-investigation-search'), $('#history_investigation'));
}

function initializeFollowUp() {
    const $interval = $('#prescription-follow-up-interval');
    const $unit = $('#prescription-follow-up-unit');
    const $date = $('#follow_up_date');
    let settingDate = false;

    function notifyFollowUp() {
        $date.trigger('prescription:follow-up');
    }

    function updateFromInterval() {
        const count = Number($interval.val());
        const date = flatpickr.parseDate($('#prescription_date').val(), 'Y-m-d');
        if (!Number.isInteger(count) || count < 1 || count > 3650 || !date) {
            notifyFollowUp();
            return;
        }

        const unit = $unit.val() === 'months' ? 'months' : 'days';
        $unit.val(unit);
        if (unit === 'months') {
            const day = date.getDate();
            date.setDate(1);
            date.setMonth(date.getMonth() + count);
            const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
            date.setDate(Math.min(day, lastDay));
        } else {
            date.setDate(date.getDate() + count);
        }

        const picker = $date.closest('[data-datepicker]').get(0)?._flatpickr;
        settingDate = true;
        if (picker) picker.setDate(date, true);
        else $date.val(flatpickr.formatDate(date, 'Y-m-d')).trigger('change');
        settingDate = false;
        notifyFollowUp();
    }

    $interval.add($unit).on('input.prescriptionFollowUp change.prescriptionFollowUp', updateFromInterval);
    $('#prescription_date').on('change.prescriptionFollowUp', updateFromInterval);
    $date.on('change.prescriptionFollowUp', () => {
        if (settingDate) return;

        $interval.val('');
        $unit.val('days');
        notifyFollowUp();
    });
}

function bindTabSearch($modal, $pane, $search) {
    function update() {
        $search.prop('hidden', !$pane.hasClass('active'));
    }
    $modal.find('[data-bs-toggle="tab"]').on('shown.bs.tab.prescription', update);
    update();
}

function initializeHeaderSearches() {
    $('.app-prescription-section-modal .modal-header select[multiple]').each(function () {
        const $select = $(this);
        const $input = $select.data('select2').$container.find('.select2-search__field');
        const restorePlaceholder = () => $input.attr('placeholder', 'Search/Add Item...');

        $select.on('change.select2.prescriptionHeader', restorePlaceholder);
        restorePlaceholder();
    });
}

function openValidationModal() {
    const $error = $('.app-prescription-section-modal').find('.is-invalid, .invalid-feedback').first();
    const $modal = $error.closest('.modal');
    if (!$modal.length) return;

    const paneId = $error.closest('.tab-pane').attr('id');
    if (paneId) $('[data-bs-target="#' + paneId + '"]').tab('show');
    const trigger = $('[data-bs-target="#' + $modal.attr('id') + '"]').get(0);
    $modal.modal('show', trigger);
}

$(function () {
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
