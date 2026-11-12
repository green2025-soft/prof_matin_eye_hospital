import $ from './jquery';

// Only the chosen age method participates in validation and form submission.
function updateAgeInput($fields) {
    const usesBirthDate = $fields.find('[name="age_input"]:checked').val() === 'date_of_birth';

    $fields.find('.patient-age-parts').prop('hidden', usesBirthDate)
        .find(':input').prop('disabled', usesBirthDate);
    $fields.find('.patient-birth-date').prop('hidden', !usesBirthDate)
        .find(':input').prop('disabled', !usesBirthDate);
    $fields.find('[name="date_of_birth"]').prop('required', usesBirthDate);
}

// Initialize the page or a newly opened Patient modal without duplicate handlers.
export function initializePatientAgeFields(root = document) {
    $(root).find('.patient-age-fields').addBack('.patient-age-fields').each(function () {
        const $fields = $(this);

        $fields.find('[name="age_input"]').off('change.patientAge').on('change.patientAge', () => updateAgeInput($fields));
        updateAgeInput($fields);
    });
}
