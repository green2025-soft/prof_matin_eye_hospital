import $ from './jquery';

// Identify the quick-entry mode for the selected dosage group.
function applicationType($row) {
    const type = $row.find('.prescription-dosage-type').val();

    if (type === 'drop') return 'drop';
    if (type === 'gel_ointment') return 'gel';

    return '';
}

// Group explicit catalog dosage labels; unknown medicine names remain Other.
function dosageGroup(type) {
    const words = String(type || '').replace(/_/g, ' ').replace(/([a-z0-9])([A-Z])/g, '$1 $2');

    if (/\bdrops?\b|\b[EN]\s*\/\s*D\b|ড্রপ|ফোঁ?টা/i.test(words)) return 'drop';
    if (/\b(?:gel|ointment|oint|cream)\b|জেল|মলম/i.test(words)) return 'gel_ointment';
    if (/\b(?:tablet|tab|capsule|cap)s?\b|ট্যাবলেট|ক্যাপসুল/i.test(words)) return 'tablet_capsule';

    return 'other';
}

// Restore quantity and daily uses only from this editor's saved quick-entry format.
function readApplicationDose(frequency, type) {
    const expression = type === 'drop'
        ? /^(.+?) ফোঁটা করে ([0-9০-৯]+) বার$/
        : /^(?:(.+?) করে )?([0-9০-৯]+) বার লাগাবেন$/;

    return String(frequency || '').match(expression);
}

// Keep quick-entry values in the existing frequency field used by save and preview.
function updateApplicationDose($row) {
    const type = applicationType($row);
    const amount = String($row.find('.prescription-application-amount').val() || '').trim();
    const times = String($row.find('.prescription-application-times').val() || '').trim();
    const hasValues = Boolean(amount || times);
    let frequency = '';

    $row.find('.prescription-application-amount').prop('required', type === 'drop' && hasValues);
    $row.find('.prescription-application-times').prop('required', hasValues);

    if (type === 'drop' && amount && times) {
        frequency = amount + ' ফোঁটা করে ' + times + ' বার';
    }
    if (type === 'gel' && times) {
        frequency = (amount ? amount + ' করে ' : '') + times + ' বার লাগাবেন';
    }

    $row.find('[name$="[dose]"]').val('');
    $row.find('.prescription-frequency-label').prop('hidden', true);
    $row.find('.prescription-frequency-value').val(frequency).prop('hidden', true).trigger('input');
}

// Switch dose controls while preserving any saved directions that use a custom format.
export function updatePrescriptionDoseType($row) {
    const type = applicationType($row);
    const application = Boolean(type);
    const frequency = String($row.find('.prescription-frequency-value').val() || '');
    const saved = application ? readApplicationDose(frequency, type) : null;
    const hideFrequency = application && (!frequency || saved !== null);

    $row.toggleClass('prescription-application-entry', application);
    $row.find('.prescription-application-dose').prop('hidden', !application);
    $row.find('.prescription-application-dose :input').prop('disabled', !application).prop('required', false);
    $row.find('.prescription-schedule-menu, .app-prescription-food-options, .prescription-dose-field')
        .prop('hidden', application);
    $row.find('.prescription-food-instruction').prop('disabled', application);
    $row.find('.prescription-schedule-controls')
        .prop('hidden', application || $row.find('.prescription-frequency-mode').val() === 'custom');
    $row.find('.prescription-frequency-value').prop('hidden', hideFrequency);
    $row.find('.prescription-frequency-label').text(application ? 'বর্তমান নির্দেশ' : 'Dose')
        .prop('hidden', hideFrequency);
    $row.find('.prescription-application-unit').text(type === 'drop' ? 'ফোঁটা করে' : 'করে');
    $row.find('.prescription-application-amount').val(saved?.[1] || '')
        .attr('inputmode', type === 'drop' ? 'decimal' : 'text');
    $row.find('.prescription-application-times').val(saved?.[2] || '');
}

// Return the starting field for keyboard entry in the current dosage mode.
export function firstPrescriptionDoseControl($row) {
    const $amount = $row.find('.prescription-application-dose:not([hidden]) .prescription-application-amount');

    return $amount.length ? $amount : $row.find('.prescription-frequency-value');
}

// Select the saved or catalog dosage group and connect its quick-entry fields.
export function initializePrescriptionDosage($row, medicine) {
    const $type = $row.find('.prescription-dosage-type');
    const type = String($type.val() || medicine.dosage?.name || medicine.name || '');

    $type.val(dosageGroup(type));
    $type.find('option[hidden]').remove();
    updatePrescriptionDoseType($row);

    $row.find('.prescription-application-amount, .prescription-application-times')
        .on('input.prescriptionApplication change.prescriptionApplication', () => updateApplicationDose($row));
}
