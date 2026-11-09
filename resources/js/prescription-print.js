import $ from './jquery';
import JsBarcode from 'jsbarcode';

// Render the Patient ID as CODE128; hide its container when the value cannot be encoded.
function renderPatientBarcodes() {
    $('.app-prescription-barcode').each(function () {
        const $barcode = $(this);
        const patientId = $barcode.attr('data-patient-id');
        if (!patientId) return;

        JsBarcode(this, patientId, {
            format: 'CODE128',
            width: 2,
            height: 38,
            displayValue: false,
            margin: 0,
            marginLeft: 20,
            marginRight: 20,
            valid: valid => $barcode.closest('.app-prescription-patient-barcode').prop('hidden', !valid),
        });
    });
}

// Apply the selected paper size, margins and printable height to the existing CSS rules.
function applyPrintRules(rules, settings) {
    $.each(rules, (_, rule) => {
        if (rule.type === CSSRule.PAGE_RULE && rule.selectorText === settings.page) {
            rule.style.margin = settings.margins;
            rule.style.size = settings.paperSize;
        }

        if (rule.selectorText === '.app-prescription-paper-custom' && !rule.parentRule) {
            rule.style.maxWidth = settings.paperWidth;
            rule.style.minHeight = settings.paperHeight;
        }

        if (rule.selectorText === settings.documentSelector && rule.parentRule?.conditionText === 'print') {
            rule.style.minHeight = settings.minHeight;
        }

        if (rule.cssRules) {
            applyPrintRules(rule.cssRules, settings);
        }
    });
}

// Configure barcode and paper geometry on the prescription's existing print page.
$(function () {
    renderPatientBarcodes();

    const $printButton = $('[data-print-config]').first();
    if (!$printButton.length) return;

    const settings = JSON.parse($printButton.attr('data-print-config'));

    $.each(document.styleSheets, (_, sheet) => {
        let rules;
        try {
            rules = sheet.cssRules;
        } catch {
            // Browsers prevent reading stylesheets hosted on another origin.
            return;
        }

        applyPrintRules(rules, settings);
    });
});
