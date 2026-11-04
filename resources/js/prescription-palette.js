import $ from './jquery';
import { createPopper } from '@popperjs/core';

export function createPrescriptionPalette($modal, $menu, placement = 'bottom-start') {
    let $trigger = $();
    let position = null;

    function close() {
        if (!position) return;

        position.destroy();
        position = null;
        $trigger.attr('aria-expanded', 'false');
        $trigger = $();
        $menu.prop('hidden', true).removeClass('show').removeAttr('aria-labelledby').appendTo($modal);
    }

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

    $(document).on('mousedown.prescriptionPalette focusin.prescriptionPalette', event => {
        if (!position) return;
        if ($(event.target).closest($menu).length || $(event.target).closest($trigger).length) return;
        close();
    });

    return { open, close, update: () => position?.update() };
}
