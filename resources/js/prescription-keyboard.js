import $ from './jquery';

function moveOnEnter(event, $next) {
    if (event.key !== 'Enter' || event.altKey || event.ctrlKey || event.metaKey || event.originalEvent?.isComposing) return;

    event.preventDefault();
    $next.trigger('focus');
}

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

export function initializePrescriptionKeyboard($modal, $search, $rows) {
    let $activeRow = $rows.children('.prescription-medicine-row').first();

    function currentRow() {
        if (!$activeRow.parent().is($rows)) $activeRow = $rows.children('.prescription-medicine-row').first();
        return $activeRow;
    }

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
            currentRow().find('.prescription-unit-toggle').trigger('focus').trigger('click');
        }
    }

    function bindSearchShortcuts() {
        // Select2 stops bubbling from its inline search, so bind to that input too.
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
    $rows.on('keydown.prescriptionKeyboard', '[name$="[medicine_name]"]', function (event) {
        const $row = $(this).closest('.prescription-medicine-row');
        moveOnEnter(event, $row.find('.prescription-frequency-value'));
    });
    $rows.on('keydown.prescriptionKeyboard', '.prescription-frequency-value', function (event) {
        moveOnEnter(event, $(this).closest('.prescription-medicine-row').find('.prescription-unit-toggle'));
    });
    $rows.on('keydown.prescriptionKeyboard', '.prescription-duration-count', function (event) {
        moveOnEnter(event, $(this).siblings('.prescription-duration-unit'));
    });
    $rows.on('keydown.prescriptionKeyboard', '.prescription-duration-value', function (event) {
        const $row = $(this).closest('.prescription-medicine-row');
        moveOnEnter(event, $row.find('.prescription-instruction-search').next('.select2-container').find('.select2-selection'));
    });
}
