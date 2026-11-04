import $ from './jquery';

let headerDropdown;

// Keep results below their search field when the modal's content changes height.
function AnchorBelow() {}

AnchorBelow.prototype._positionDropdown = function () {
    this.$dropdown.removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
    this.$container.removeClass('select2-container--above').addClass('select2-container--below');
    this.$dropdownContainer.css({ top: 'calc(100% + 6px)', left: 0 });
};

export function prescriptionHeaderDropdown() {
    if (headerDropdown) return headerDropdown;

    const modules = $.fn.select2.amd;
    const Utils = modules.require('select2/utils');
    const Dropdown = modules.require('select2/dropdown');
    const AttachBody = modules.require('select2/dropdown/attachBody');

    headerDropdown = Utils.Decorate(Utils.Decorate(Dropdown, AttachBody), AnchorBelow);
    return headerDropdown;
}
