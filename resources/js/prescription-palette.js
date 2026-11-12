import $ from './jquery';
import { createPopper } from '@popperjs/core';

// Manage one reusable dose or unit palette anchored to its active button.
export function createPrescriptionPalette($modal, $menu, placement = 'bottom-start') {
    let $trigger = $();
    let position = null;

    // Release the floating position and return the hidden palette to its modal.
    function close() {
        if (!position) return;

        position.destroy();
        position = null;
        $trigger.attr('aria-expanded', 'false');
        $trigger = $();
        $menu.prop('hidden', true).removeClass('show').removeAttr('aria-labelledby').appendTo($modal);
    }

    // Place the palette beside the chosen button and keep it inside the viewport.
    function open($button) {
        close();
        $trigger = $button;
        $trigger.attr('aria-expanded', 'true');
        $menu.attr('aria-labelledby', $button.attr('id')).insertAfter($button)
            .prop('hidden', false).addClass('show');
        position = createPopper($button.get(0), $menu.get(0), {
            placement,
            strategy: 'fixed',
            modifiers: [
                { name: 'offset', options: { offset: [0, 2] } },
                { name: 'preventOverflow', options: { boundary: [], rootBoundary: 'viewport', padding: 8 } },
                { name: 'flip', options: { boundary: [], rootBoundary: 'viewport', padding: 8 } },
            ],
        });
    }

    // Dose and Unit use the same click, arrow and Escape behavior.
    function bindToggle($scope, selector, openPalette) {
        $scope.on('click.prescriptionPalette', selector, function (event) {
            event.preventDefault();
            event.stopPropagation();
            openPalette($(this));
        });
        $scope.on('keydown.prescriptionPalette', selector, function (event) {
            if (!['ArrowDown', 'ArrowUp', 'Enter', ' ', 'Escape'].includes(event.key)) return;

            event.preventDefault();
            event.stopPropagation();
            if (event.key === 'Escape') {
                if ($menu.hasClass('show')) close();
                else $modal.modal('hide');
                return;
            }

            const $button = $(this);
            if (!$trigger.is($button) || !$menu.hasClass('show')) openPalette($button);
            const $selected = $menu.find('.dropdown-item.active');
            ($selected.length ? $selected : $menu.find('.dropdown-item').first()).trigger('focus');
        });
    }

    // Return focus to the opener when Escape closes its choice list.
    function closeOnEscape(event) {
        if (event.key !== 'Escape') return;

        event.preventDefault();
        event.stopPropagation();
        const $focus = $trigger;
        close();
        $focus.trigger('focus');
    }

    // Dismiss the palette when focus or a click moves outside it and its trigger.
    function closeOutside(event) {
        if (!position) return;
        if ($(event.target).closest($menu.add($trigger)).length) return;
        close();
    }

    $menu.on('keydown.prescriptionPalette', closeOnEscape);
    $(document).on('mousedown.prescriptionPalette focusin.prescriptionPalette', closeOutside);

    return { open, close, bindToggle, update: () => position?.update() };
}
