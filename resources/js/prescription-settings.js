import $ from './jquery';

// Keep each General/Glass paper-size group independent.
$(function initializePrescriptionPaperSettings() {
    $('#prescription-settings-form .prescription-paper-settings').each(function () {
        const $group = $(this);
        const $paper = $group.find('.prescription-paper-size');
        const $dimensions = $group.find('.prescription-custom-paper');

        // Enable custom dimensions only when Custom paper is selected.
        function updatePaperSize() {
            const custom = $paper.val() === 'custom';
            $dimensions.prop('hidden', !custom).find(':input')
                .prop('required', custom).prop('disabled', !custom);
        }

        $paper.on('change.prescriptionPaper', updatePaperSize);
        updatePaperSize();
    });
});
