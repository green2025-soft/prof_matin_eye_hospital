import $ from './jquery';

let headerDropdown;

// Select2 decorator used to anchor results below a modal's header search.
function AnchorBelow() {}

// Keep the dropdown below its search even when the modal content changes height.
AnchorBelow.prototype._positionDropdown = function () {
    this.$dropdown.removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
    this.$container.removeClass('select2-container--above').addClass('select2-container--below');
    this.$dropdownContainer.css({ top: 'calc(100% + 6px)', left: 0 });
};

// Build the Select2 dropdown adapter once and reuse it for all header searches.
export function prescriptionHeaderDropdown() {
    if (headerDropdown) return headerDropdown;

    const modules = $.fn.select2.amd;
    const Utils = modules.require('select2/utils');
    const Dropdown = modules.require('select2/dropdown');
    const AttachBody = modules.require('select2/dropdown/attachBody');

    const DropdownWithBody = Utils.Decorate(Dropdown, AttachBody);
    headerDropdown = Utils.Decorate(DropdownWithBody, AnchorBelow);
    return headerDropdown;
}
