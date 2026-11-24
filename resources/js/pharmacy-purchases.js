import $ from './jquery';
import { initializeTradeEditor } from './pharmacy-trade-editor';
import { formatAmount, roundRatio } from './money';
import {
    applyProductData,
    initializeEntryData,
    initializePaidAmountAutoFill,
    pharmacyInteger,
    pharmacyMinor,
    pharmacyQuantityFormatter,
    refreshPurchaseTotal,
    setTradeValidity,
    updatePaidAmountFromCommittedTotal,
} from './pharmacy';

function purchaseRateForFactor(product, factorValue) {
    if (!product) return null;

    const factor = pharmacyInteger(factorValue);
    if (factor < 1n) return null;

    const lastPurchaseFactor = pharmacyInteger(product.lastPurchaseFactor);
    if (product.lastPurchaseRate !== null && product.lastPurchaseRate !== undefined
        && lastPurchaseFactor > 0n) {
        return roundRatio(pharmacyMinor(product.lastPurchaseRate) * factor, lastPurchaseFactor);
    }

    const purchasePrice = pharmacyMinor(product.purchasePrice);
    return purchasePrice > 0n ? purchasePrice * factor : null;
}

function selectPurchaseVariant(row, data) {
    const $row = $(row);
    applyProductData($row, data);
    $row.find('input[name$="[sale_price_per_piece]"]').attr(
        'placeholder',
        pharmacyMinor(data.sale_price) > 0n ? data.sale_price : '0',
    );
    if ($row.find('[data-pharmacy-purchase-unit]').length) {
        populatePurchaseUnits(row, Array.isArray(data.units) ? data.units : []);
    }
    setPurchasePrice(row);
}

function refreshPurchaseEntryTotal(form) {
    const $form = $(form);
    const payable = refreshPurchaseTotal(form);
    const subtotal = pharmacyMinor($form.find('[data-pharmacy-subtotal]').text());
    const lineDiscount = pharmacyMinor($form.find('[data-pharmacy-line-discount]').text());
    const discountLimit = subtotal > lineDiscount ? subtotal - lineDiscount : 0n;
    const paidLimit = payable > 0n ? payable : 0n;
    let amountWasLimited = false;

    for (const [selector, limit] of [
        ['[name="discount"]', discountLimit],
        ['[name="paid_amount"]', paidLimit],
    ]) {
        const $input = $form.find(selector);
        $input.attr('max', formatAmount(limit));
        if (pharmacyMinor($input.val()) > limit) {
            $input.val(formatAmount(limit));
            amountWasLimited = true;
        }
    }

    return amountWasLimited ? refreshPurchaseTotal(form) : payable;
}

function updatePurchasePaidAmount(form, total) {
    updatePaidAmountFromCommittedTotal(form, total);
    refreshPurchaseEntryTotal(form);
}

function setPurchasePrice(row) {
    const $row = $(row);
    const product = $row.data('pharmacyProduct');
    if (!product) return;

    const price = purchaseRateForFactor(product, $row.find('input[name$="[unit_factor]"]').val());
    console.log(price);

    $row.find('[name$="[unit_price]"]').val(price === null ? '' : formatAmount(price));
    refreshPurchaseEntryTotal($row.closest('form')[0]);
}

function useLatestPurchaseRateOnEdit(row) {
    const $row = $(row);
    const product = $row.data('pharmacyProduct');
    if ($row.attr('data-preserve-rate-on-edit') === '1'
        || !product || product.lastPurchaseRate === null || product.lastPurchaseRate === undefined) return;

    const rate = purchaseRateForFactor(product, $row.find('input[name$="[unit_factor]"]').val());
    if (rate !== null) $row.find('[name$="[unit_price]"]').val(formatAmount(rate));
}

// Hidden saved per-unit amounts follow pack edits without compounding earlier rounding.
function rescaleHiddenUnitAmount(row, input, basis, previousFactor, nextFactor) {
    const $input = $(input);
    if (!$input.length || basis !== 'unit' || nextFactor < 1n
        || !$input.closest('[data-pharmacy-hidden-item-field]').length
        || !$(row).find('[name$="[original_line_id]"]').val()) return;

    let originalFactor = pharmacyInteger($input.attr('data-original-unit-factor'));
    if (originalFactor < 1n || !$input.attr('data-original-unit-amount')) {
        if (previousFactor < 1n || previousFactor === nextFactor) return;
        $input.attr({
            'data-original-unit-factor': String(previousFactor),
            'data-original-unit-amount': $input.val() || '0',
        });
        originalFactor = previousFactor;
    }

    const originalAmount = pharmacyMinor($input.attr('data-original-unit-amount'));
    $input.val(formatAmount(roundRatio(originalAmount * nextFactor, originalFactor)));
}

// Apply one saved pack and preserve hidden saved discount/VAT when its size changes.
function applyPurchaseUnit(row, option, clearPrice = false) {
    const $row = $(row);
    const $option = $(option);
    if (!$option.attr('data-factor')) return;
    const $unitId = $row.find('input[name$="[unit_id]"]');
    const $factor = $row.find('input[name$="[unit_factor]"]');
    const unitName = $option.attr('data-unit-name');
    const changed = $unitId.val() !== $option.attr('data-unit-id') || $factor.val() !== $option.attr('data-factor');
    const previousFactor = pharmacyInteger($factor.val());
    const nextFactor = pharmacyInteger($option.attr('data-factor'));
    rescaleHiddenUnitAmount(
        row,
        $row.find('input[name$="[discount]"]')[0],
        $row.find('select[name$="[discount_input]"]').val(),
        previousFactor,
        nextFactor,
    );
    rescaleHiddenUnitAmount(
        row,
        $row.find('input[name$="[vat_amount]"]')[0],
        $row.find('select[name$="[vat_input]"]').val(),
        previousFactor,
        nextFactor,
    );
    $unitId.val($option.attr('data-unit-id'));
    $factor.val($option.attr('data-factor'));
    $row.attr('data-selected-purchase-unit', $option.val());
    setTradeValidity($row.find('[data-pharmacy-purchase-unit]')[0], '');
    $row.find('[data-pharmacy-edit-unit]').prop('disabled', $option.attr('data-fixed') === '1');
    $row.find('[data-pharmacy-quantity-unit], [data-pharmacy-rate-unit]').text(unitName);
    $row.find('select[name$="[vat_input]"] option[value="unit"], select[name$="[discount_input]"] option[value="unit"]')
        .text(`/ ${unitName}`);
    const name = $row.find('.app-pharmacy-entry-name').text();
    const $price = $row.find('[name$="[unit_price]"]').attr('aria-label', `Rate per ${unitName}`);
    $row.find('[name$="[quantity]"]').attr('aria-label', `Number of ${unitName} for ${name}`);
    if (clearPrice && changed) $price.val('');
}

// Reuse catalog units and distinct pack sizes; repeated edits must not grow profile data.
function populatePurchaseUnits(row, profiles) {
    const $row = $(row);
    const $select = $row.find('[data-pharmacy-purchase-unit]');
    const $form = $row.closest('form');
    let units = $row.data('purchaseUnits');
    if (!units) {
        units = $select.find('option[data-unit-id]').map(function () {
            const $option = $(this);
            return {
                id: $option.attr('data-unit-id'),
                name: $option.text(),
                symbol: $option.attr('data-symbol')?.toLowerCase() || '',
            };
        }).get();
        $row.data('purchaseUnits', units);
    }
    const remembered = $form.data('purchasePacks')?.get($row.data('pharmacyProduct').id) || [];
    const unitId = $row.find('input[name$="[unit_id]"]').val();
    const factor = $row.find('input[name$="[unit_factor]"]').val();
    const allProfiles = [];
    const sizesByUnit = new Map();

    for (const profile of [...profiles, ...remembered, { unit_id: unitId, factor }]) {
        const id = String(profile.unit_id);
        const size = Number(profile.factor);
        if (!/^\d+$/.test(id) || !Number.isInteger(size) || size < 1 || size > 1000000) continue;

        if (!sizesByUnit.has(id)) sizesByUnit.set(id, new Set());
        const sizes = sizesByUnit.get(id);
        if (sizes.has(size)) continue;

        sizes.add(size);
        allProfiles.push({ unit_id: id, factor: String(size) });
    }
    const pieces = units.find(unit => unit.symbol === 'pcs');
    $select.empty().append($('<option>', { text: 'Unit', value: '' }));
    for (const unit of units) {
        const fixed = unit.symbol === 'pcs' ? 1 : null;
        const sizes = fixed === null ? [...(sizesByUnit.get(unit.id) || [])].sort((a, b) => a - b) : [fixed];
        for (const size of sizes) {
            const label = fixed !== null ? unit.name : `${unit.name} · ${size} Pcs`;
            $select.append($('<option>', {
                text: label,
                value: `pack:${unit.id}:${size}`,
                'data-unit-id': unit.id,
                'data-factor': String(size),
                'data-unit-name': unit.name,
                'data-fixed': fixed !== null ? '1' : '0',
            }));
        }
        if (fixed === null) {
            $select.append($('<option>', {
                text: `${unit.name} (new size)`,
                value: `unit:${unit.id}`,
                'data-unit-id': unit.id,
                'data-unit-name': unit.name,
            }));
        }
    }
    const selected = `pack:${unitId}:${factor}`;
    const hasOldSelection = unitId !== '' || factor !== '';
    const hasSelectedPack = $select.find('option').get().some(option => $(option).val() === selected);
    $select.val(hasSelectedPack ? selected : (!hasOldSelection && pieces ? `pack:${pieces.id}:1` : ''));
    $row.data('unitProfiles', allProfiles);
    applyPurchaseUnit(row, $select.find('option:selected')[0]);
}

function purchaseUnitOptions($row, symbol) {
    const choice = $row.data('purchaseUnits')?.find(item => item.symbol === symbol);
    return $row.find('[data-pharmacy-purchase-unit] option').filter(function () {
        return choice && $(this).attr('data-unit-id') === choice.id;
    });
}

// Purchases allow repeated product rows and editable packs.
function initializePurchaseEntry(form) {
    initializeEntryData(form);
    const $form = $(form);
    initializePaidAmountAutoFill(form);
    const rowSelector = 'fieldset[data-pharmacy-line]';
    const $picker = $form.find('#pharmacy_product_picker');
    const $packModal = $('[data-pharmacy-pack-modal]');
    const $packSize = $packModal.find('[data-pharmacy-pack-size]');
    const purchasePacks = new Map();
    let pendingPack = null;
    let packFocusTarget = null;
    let lastVatInput = null;
    let lastVatMode = null;
    $form.data('purchasePacks', purchasePacks);
    $form.find('fieldset[data-pharmacy-line]:has([data-pharmacy-purchase-unit])').each(function () {
        populatePurchaseUnits(this, $(this).data('unitProfiles') || []);
    });

    function startPurchaseProduct(product, editor) {
        if (!editor.finishCurrentItem() || !editor.canAddItem()) return;
        editor.addProductRow(product);
    }

    $form.on('change.pharmacyTrade', '[data-pharmacy-purchase-unit]', function () {
        const $unit = $(this);
        const $row = $unit.closest(rowSelector);
        const $option = $unit.find('option:selected');
        const factor = $option.attr('data-factor');
        $row.find('[data-pharmacy-edit-unit]').prop('disabled', !factor || $option.attr('data-fixed') === '1');
        if (factor) applyPurchaseUnit($row.get(0), $option.get(0), true);
        else openPackEditor($row, $option.get(0), this);
    });
    $form.on('change.pharmacyPurchase', 'select[name$="[vat_input]"]', function () {
        const $input = $(this);
        lastVatInput = $input.val();
        if (lastVatInput !== 'percent') {
            $input.closest('fieldset[data-pharmacy-line]').find('input[name$="[vat_amount]"]').val('');
        }
    });
    $form.on('change.pharmacyPurchase', 'select[name$="[vat_mode]"]', function () { lastVatMode = $(this).val(); });
    $form.on('pharmacy:itemCreated', 'fieldset[data-pharmacy-line]', function () {
        const $row = $(this);
        for (const [selector, value] of [
            ['select[name$="[vat_input]"]', lastVatInput],
            ['select[name$="[vat_mode]"]', lastVatMode],
        ]) {
            const $input = $row.find(selector);
            if (value !== null && !$input.closest('[data-pharmacy-hidden-item-field]').length) $input.val(value);
        }
    });

    const editor = initializeTradeEditor(form, {
        selectVariant: selectPurchaseVariant,
        setDefaultPrice: setPurchasePrice,
        refreshEntryTotal: refreshPurchaseEntryTotal,
        onEntryOpened: useLatestPurchaseRateOnEdit,
        onProductSelected: startPurchaseProduct,
        onItemsCommitted: updatePurchasePaidAmount,
    });
    refreshPurchaseEntryTotal(form);

    function openPackEditor($row, option, trigger) {
        const $option = $(option);
        if (!$packModal.length || !$option.attr('data-unit-id') || $option.attr('data-fixed') === '1') return;
        if ($picker.hasClass('select2-hidden-accessible')) $picker.select2('close');
        editor.closeDatePickers($row);
        pendingPack = { $row, $option };
        packFocusTarget = trigger;
        $packModal.find('[data-pharmacy-pack-label]').text(`Pieces Per ${$option.attr('data-unit-name')}`);
        $packSize.val($option.attr('data-factor') || '').get(0).setCustomValidity('');
        $packModal.modal('show');
    }

    function confirmPack() {
        if (!pendingPack || !editor.activeRow || !pendingPack.$row.is(editor.activeRow)) return;
        const factor = pharmacyInteger($packSize.val());
        const packSize = $packSize.get(0);
        packSize.setCustomValidity(factor > 0n && factor <= 1000000n ? '' : 'Enter the pieces per pack.');
        if (!packSize.reportValidity()) return;
        const { $row, $option } = pendingPack;
        const productId = $row.data('pharmacyProduct').id;
        const profile = { unit_id: $option.attr('data-unit-id'), factor: String(factor) };
        const profiles = purchasePacks.get(productId) || [];
        if (!profiles.some(item => String(item.unit_id) === profile.unit_id && String(item.factor) === profile.factor)) {
            profiles.push(profile);
        }
        purchasePacks.set(productId, profiles);
        $form.find(rowSelector).each(function () {
            const $item = $(this);
            if ($item.data('pharmacyProduct').id === productId) {
                populatePurchaseUnits(this, $item.data('unitProfiles') || []);
            }
        });
        const $unit = $row.find('[data-pharmacy-purchase-unit]');
        $unit.val(`pack:${profile.unit_id}:${profile.factor}`);
        applyPurchaseUnit($row.get(0), $unit.find('option:selected').get(0), true);
        setPurchasePrice($row.get(0));
        pendingPack = null;
        packFocusTarget = $row.find('[name$="[quantity]"]').get(0);
        $packModal.modal('hide');
        editor.refreshRows();
    }

    $packModal.on('shown.bs.modal.pharmacyTrade', () => editor.focusControl($packSize.get(0)));
    $packModal.on('hidden.bs.modal.pharmacyTrade', () => {
        if (pendingPack?.$row.prop('isConnected')) {
            const { $row } = pendingPack;
            const $unit = $row.find('[data-pharmacy-purchase-unit]');
            $unit.val($row.attr('data-selected-purchase-unit') || '');
            applyPurchaseUnit($row.get(0), $unit.find('option:selected').get(0));
        }
        pendingPack = null;
        editor.focusControl($(packFocusTarget).prop('isConnected') ? packFocusTarget : null);
        packFocusTarget = null;
    });
    $packModal.on('click.pharmacyTrade', '[data-pharmacy-pack-confirm]', confirmPack);
    $packSize.on('input.pharmacyTrade', function () { this.setCustomValidity(''); });
    $packSize.on('keydown.pharmacyTrade', event => {
        if (event.key !== 'Enter') return;
        event.preventDefault();
        confirmPack();
    });

    $form.on('click.pharmacyTrade', '[data-pharmacy-edit-unit]', function () {
        const $row = $(this).closest(rowSelector);
        openPackEditor($row, $row.find('[data-pharmacy-purchase-unit] option:selected').get(0), this);
    });
    $form.on('pharmacy:itemRestored', 'fieldset[data-pharmacy-line]', function () {
        const $row = $(this);
        applyPurchaseUnit(this, $row.find('[data-pharmacy-purchase-unit] option:selected')[0]);
    });
    $form.on('focusout.pharmacyPurchase', '[data-pharmacy-purchase-unit]', function () {
        $(this).data('touched', true);
        editor.refreshRows();
    });
    $form.on('pharmacy:unitChooserRequested', () => editor.openUnitChooser(editor.activeRow.find('[data-pharmacy-purchase-unit]')));
    $form.on('pharmacy:unitShortcutRequested', (_, symbol) => {
        const $row = editor.activeRow;
        editor.selectUnitShortcut($row.find('[data-pharmacy-purchase-unit]'), purchaseUnitOptions($row, symbol));
    });
    $form.on('pharmacy:unitSelected', (_, $row) => {
        if (!pendingPack) editor.focusControl($row.find('[name$="[quantity]"]')[0]);
    });

    // Pack editing keeps its own shortcut, including Select2's detached search field.
    $(document).on('keydown.pharmacyPurchase', event => {
        const keyboard = event.originalEvent || event;
        if (keyboard.isComposing || keyboard.repeat || $('.modal.show').length) return;
        if (!$(event.target).closest($form).length && !$picker.data('select2')?.isOpen()) return;
        const $row = editor.activeRow;
        if (!event.altKey || event.key.toLowerCase() !== 'u' || !$row) return;
        const $unit = $row.find('[data-pharmacy-purchase-unit]');
        const $edit = $row.find('[data-pharmacy-edit-unit]');
        if (!$unit.length || !$edit.length || $edit.prop('disabled')) return;
        event.preventDefault();
        event.stopImmediatePropagation();
        openPackEditor($row, $unit.find('option:selected')[0], event.target);
    });
}

$(function () {
    $('[data-type="purchase"][data-pharmacy-entry], .app-pharmacy-trade-layout[data-type="purchase"]')
        .each(function () { initializePurchaseEntry(this); });
});
