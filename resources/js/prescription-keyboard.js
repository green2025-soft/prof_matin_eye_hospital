import $ from './jquery';
import { firstPrescriptionDoseControl } from './prescription-application-dose';

// Move to the next control on a plain Enter without interrupting shortcuts or IME typing.
function moveOnEnter(event, $next) {
    if (event.key !== 'Enter' || event.altKey || event.ctrlKey || event.metaKey || event.originalEvent?.isComposing) return;

    event.preventDefault();
    $next.trigger('focus');
}

// Navigate visible dose/unit choices with arrows, Home and End.
function bindPaletteNavigation($menu) {
    $menu.on('keydown.prescriptionKeyboard', '.dropdown-item', function (event) {
        if (!['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'].includes(event.key)) return;

        event.preventDefault();
        event.stopImmediatePropagation();
        const $choices = $menu.find('.dropdown-item:visible:not(:disabled)');
        const current = $choices.index(this);
        let next = current + (['ArrowLeft', 'ArrowUp'].includes(event.key) ? -1 : 1);
        if (event.key === 'Home') next = 0;
        if (event.key === 'End') next = $choices.length - 1;
        $choices.eq((next + $choices.length) % $choices.length).trigger('focus');
    });
}

// Wire Rx shortcuts and the medicine-entry focus order.
export function initializePrescriptionKeyboard($modal, $search, $rows) {
    let $activeRow = $rows.children('.prescription-medicine-row').first();

    // Use the last focused row, falling back when that medicine has been removed.
    function currentRow() {
        if (!$activeRow.parent().is($rows)) $activeRow = $rows.children('.prescription-medicine-row').first();
        return $activeRow;
    }

    // Open Rx when needed, then focus its medicine search while preserving return focus.
    function focusSearch() {
        if (!$modal.hasClass('show')) {
            const $returnFocus = $(':focus').first();
            $modal.one('hidden.bs.modal.prescriptionKeyboard', () => $returnFocus.trigger('focus'));
            $modal.modal('show');
            return;
        }

        $search.select2('open');
        $search.data('select2').$container.find('.select2-search__field').trigger('focus');
    }

    // Handle F2/Alt+M search, F4 dose/unit and F8 Done outside other open modals.
    function handleShortcut(event) {
        if (event.originalEvent?.isComposing || event.ctrlKey || event.metaKey) return;
        if ($('.modal.show').not($modal).length) return;

        const key = event.key;
        const searchShortcut = key === 'F2' || (event.altKey && key.toLowerCase() === 'm');
        if (searchShortcut) {
            event.preventDefault();
            event.stopImmediatePropagation();
            focusSearch();
            return;
        }
        if (!$modal.hasClass('show')) return;

        if (key === 'F8' && !event.altKey) {
            event.preventDefault();
            event.stopImmediatePropagation();
            $modal.modal('hide');
            if (!$modal.hasClass('show')) {
                $('[data-bs-target="#prescription-rx-modal"]').first().trigger('focus');
            }
            return;
        }
        if (key === 'F4' && !event.altKey) {
            event.preventDefault();
            event.stopImmediatePropagation();
            $modal.find('select.select2-hidden-accessible').select2('close');
            const $row = currentRow();
            const $unit = $row.find('.prescription-unit-toggle:visible');
            if ($unit.length) $unit.trigger('focus').trigger('click');
            else firstPrescriptionDoseControl($row).trigger('focus');
        }
    }

    // Bind directly because Select2 stops keyboard events bubbling from its inline search.
    function bindSearchShortcuts() {
        $modal.find('.select2-search__field')
            .off('.prescriptionKeyboard').on('keydown.prescriptionKeyboard', handleShortcut)
            .on('keyup.prescriptionKeyboard', event => {
                if (['F2', 'F4', 'F8'].includes(event.key) || (event.altKey && event.key.toLowerCase() === 'm')) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
            });
    }

    $(document).on('keydown.prescriptionKeyboard', handleShortcut);
    $modal.on('select2:open.prescriptionKeyboard', bindSearchShortcuts);
    bindSearchShortcuts();
    bindPaletteNavigation($('#prescription-dose-palette'));
    bindPaletteNavigation($('#prescription-unit-palette'));

    $rows.on('focusin.prescriptionKeyboard', '.prescription-medicine-row', function () {
        $activeRow = $(this);
    });
    $rows.on('keydown.prescriptionKeyboard', '[name$="[medicine_name]"], .prescription-dosage-type', function (event) {
        const $row = $(this).closest('.prescription-medicine-row');
        moveOnEnter(event, firstPrescriptionDoseControl($row));
    });
    $rows.on('keydown.prescriptionKeyboard', '.prescription-frequency-value', function (event) {
        if (event.altKey || event.ctrlKey || event.metaKey || event.originalEvent?.isComposing) return;

        const $row = $(this).closest('.prescription-medicine-row');
        if (event.key === 'ArrowDown' && !event.shiftKey) {
            const $picker = $row.find('.prescription-schedule-dose:visible').first();
            if ($picker.length) {
                event.preventDefault();
                $picker.trigger('focus');
            }
            return;
        }

        const $food = $row.find('.prescription-food-instruction:visible:enabled').first();
        const $next = $food.length ? $food : $row.find('.prescription-unit-toggle');
        if (event.key === 'Tab' && !event.shiftKey) {
            event.preventDefault();
            $next.trigger('focus');
            return;
        }
        moveOnEnter(event, $next);
    });
    $rows.on('focusin.prescriptionKeyboard', '.prescription-food-instruction', function () {
        $(this).siblings('.prescription-food-instruction').attr('tabindex', '-1');
        $(this).attr('tabindex', '0');
    });
    $rows.on('keydown.prescriptionKeyboard', '.prescription-food-instruction', function (event) {
        if (event.altKey || event.ctrlKey || event.metaKey) return;

        const $row = $(this).closest('.prescription-medicine-row');
        if (event.key === 'Tab' && !event.shiftKey) {
            event.preventDefault();
            $row.find('.prescription-unit-toggle').trigger('focus');
            return;
        }
        if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;

        event.preventDefault();
        const $choices = $row.find('.prescription-food-instruction:enabled');
        const current = $choices.index(this);
        let next = current + (event.key === 'ArrowLeft' ? -1 : 1);
        if (event.key === 'Home') next = 0;
        if (event.key === 'End') next = $choices.length - 1;
        $choices.eq((next + $choices.length) % $choices.length).trigger('focus');
    });
    $rows.on('keydown.prescriptionKeyboard', '.prescription-duration-count', function (event) {
        moveOnEnter(event, $(this).siblings('.prescription-duration-unit'));
    });
    $rows.on('keydown.prescriptionKeyboard', '.prescription-application-amount', function (event) {
        const $row = $(this).closest('.prescription-medicine-row');
        moveOnEnter(event, $row.find('.prescription-application-times'));
    });
    $rows.on('keydown.prescriptionKeyboard', '.prescription-application-times', function (event) {
        const $row = $(this).closest('.prescription-medicine-row');
        moveOnEnter(event, $row.find('.prescription-duration-count:visible, .prescription-duration-value:visible').first());
    });
    $rows.on('keydown.prescriptionKeyboard', '.prescription-duration-unit, .prescription-duration-value', function (event) {
        const $row = $(this).closest('.prescription-medicine-row');
        moveOnEnter(event, $row.find('.prescription-instruction-search').next('.select2-container').find('.select2-selection'));
    });
}
