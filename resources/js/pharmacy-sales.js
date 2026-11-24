import $ from './jquery';
import { initializeTradeEditor } from './pharmacy-trade-editor';
import {
    applyProductData,
    initializeEntryData,
    initializePaidAmountAutoFill,
    pharmacyInteger,
    populateProductUnits,
    refreshSaleTotal,
    setUnitPrice,
    updatePaidAmountFromCommittedTotal,
} from './pharmacy';

function selectSaleVariant(row, data) {
    const $row = $(row);
    applyProductData($row, data);
    $row.find('[data-pharmacy-product-stock]').val(data.available_quantity);
    populateProductUnits($row, data.units);
    setSalePrice(row);
}

function setSalePrice(row) {
    setUnitPrice(row, $(row).data('pharmacyProduct').salePrice, refreshSaleTotal);
}

function saleUnitOptions($row, symbol) {
    return $row.find('select[name$="[product_unit_id]"] option').filter(function () {
        const $option = $(this);
        return $option.attr('data-symbol')?.toLowerCase() === symbol
            && (symbol !== 'pcs' || $option.attr('data-factor') === '1');
    });
}

// Sales reuse existing product rows and enforce their own stock and base-unit rules.
function initializeSaleEntry(form) {
    initializeEntryData(form);
    const $form = $(form);
    initializePaidAmountAutoFill(form);

    const options = {
        ...$form.data('pharmacyOptions'),
        revisionStock: {},
        allowWithoutStock: false,
        ...JSON.parse($form.find('#pharmacy-sale-options').text()),
    };
    $form.data('pharmacyOptions', options);

    function startSaleProduct(product, editor) {
        const $existing = $form.find('fieldset[data-pharmacy-line]').filter(function () {
            return $(this).find('[name$="[variant_id]"]').val() === String(product.id);
        }).first();
        const available = pharmacyInteger(product.available_quantity) + pharmacyInteger(options.revisionStock[product.id]);
        if ($existing.length && editor.activeRow?.is($existing)) {
            editor.focusControl($existing.find('[name$="[quantity]"]')[0]);
            return;
        }
        if (!editor.finishCurrentItem()) return;
        if ($existing.length) {
            editor.openEntry($existing);
            editor.announce('This product is already added. Update its quantity.');
            return;
        }
        if (!options.allowWithoutStock && available < 1n) {
            editor.announce('This product has no available stock.', true);
            editor.focusPicker();
            return;
        }
        if (!editor.canAddItem()) return;
        if (!Array.isArray(product.units) || product.units.length === 0) {
            editor.announce('Set up a base unit for this product before adding it.', true);
            return;
        }
        editor.addProductRow({ ...product, available_quantity: String(available) });
    }

    const editor = initializeTradeEditor(form, {
        selectVariant: selectSaleVariant,
        setDefaultPrice: setSalePrice,
        refreshEntryTotal: refreshSaleTotal,
        onProductSelected: startSaleProduct,
        onItemsCommitted: updatePaidAmountFromCommittedTotal,
    });
    $form.on('change.pharmacySale input.pharmacySale', '[name="vat_mode"], [name="vat_percent"]', () => editor.refreshRows());
    $form.on('pharmacy:unitChooserRequested', () => editor.openUnitChooser(editor.activeRow.find('select[name$="[product_unit_id]"]')));
    $form.on('pharmacy:unitShortcutRequested', (_, symbol) => {
        const $row = editor.activeRow;
        editor.selectUnitShortcut($row.find('select[name$="[product_unit_id]"]'), saleUnitOptions($row, symbol));
    });
    $form.on('pharmacy:unitSelected', (_, $row) => editor.focusControl($row.find('[name$="[quantity]"]')[0]));
}

$(function () {
    $('#pharmacy-sale-options').closest('form').each(function () { initializeSaleEntry(this); });
});
