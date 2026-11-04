import $ from './jquery';

export function initializePrescriptionMedicineEditor($modal, $rows) {
    const $selectionPane = $('#prescription-rx-selection');
    const $editorPane = $('#prescription-rx-editor');
    const $selected = $('#prescription-rx-selected');
    const $jump = $('#prescription-rx-medicine-jump');
    const $position = $('#prescription-rx-position');
    const $start = $('#prescription-rx-set-doses');
    const $previous = $('#prescription-rx-previous');
    const $next = $('#prescription-rx-next');
    const entries = new Map();
    let $activeRow = $();
    let editing = false;

    function closePickers() {
        $modal.find('select.select2-hidden-accessible').select2('close');
        $modal.trigger('prescription:close-palettes');
    }

    function updateNavigation() {
        const $medicines = $rows.children('.prescription-medicine-row');
        const count = $medicines.length;
        const index = $medicines.index($activeRow);
        $('#prescription-rx-selected-count').text(count);
        $('#prescription-rx-selected-empty').toggleClass('d-none', count > 0);
        $start.prop('disabled', count === 0);
        $jump.prop('disabled', count === 0).val($activeRow.attr('id') || null);
        $previous.prop('disabled', index <= 0);
        $next.text(index === count - 1 ? 'Finish' : 'Next medicine');
        $position.text(editing ? (index + 1) + ' / ' + count : '');
    }

    function updateName($row) {
        const entry = entries.get($row.attr('id'));
        if (!entry) return;

        const name = String($row.find('[name$="[medicine_name]"]').val() || '').trim() || 'Medicine';
        entry.$label.text(name).attr('title', name);
        entry.$option.text(name);
        entry.$remove.attr('aria-label', 'Remove ' + name);
    }

    function requestEdit($row) {
        if ($row.length) $row.trigger('prescription:edit-medicine');
    }

    function registerRow($row) {
        const id = $row.attr('id');
        if (entries.has(id)) return;

        const $option = $('<option>').val(id);
        const $item = $('<li class="app-prescription-selected-medicine">');
        const $label = $('<button type="button" class="app-prescription-selected-name">')
            .on('click.prescriptionEditor', () => requestEdit($row)).appendTo($item);
        const $remove = $('<button type="button" class="app-prescription-selected-remove">')
            .append('<i class="fa-solid fa-xmark" aria-hidden="true"></i>')
            .on('click.prescriptionEditor', () => $row.find('.prescription-medicine-remove').trigger('click'))
            .appendTo($item);
        const nextEntry = entries.get($row.next('.prescription-medicine-row').attr('id'));
        if (nextEntry) {
            $option.insertBefore(nextEntry.$option);
            $item.insertBefore(nextEntry.$item);
        } else {
            $option.appendTo($jump);
            $item.appendTo($selected);
        }

        entries.set(id, { $row, $option, $item, $label, $remove });
        $row.addClass('d-none');
        updateName($row);
        updateNavigation();
    }

    function chooseMedicines() {
        closePickers();
        editing = false;
        $rows.children('.prescription-medicine-row').addClass('d-none');
        $selectionPane.removeClass('d-none');
        $editorPane.addClass('d-none');
        $('#prescription-rx-selection-actions').removeClass('d-none');
        $('#prescription-rx-editor-actions').addClass('d-none');
        updateNavigation();
    }

    function editMedicine($row) {
        if (!$row.length || !$row.parent().is($rows)) return false;

        closePickers();
        editing = true;
        $activeRow = $row;
        $rows.children('.prescription-medicine-row').addClass('d-none');
        $row.removeClass('d-none');
        $selectionPane.addClass('d-none');
        $editorPane.removeClass('d-none');
        $('#prescription-rx-selection-actions').addClass('d-none');
        $('#prescription-rx-editor-actions').removeClass('d-none');
        updateNavigation();
        return true;
    }

    function removeRow($row) {
        const entry = entries.get($row.attr('id'));
        if (!entry) return;

        const index = entry.$option.index();
        const wasActive = $activeRow.is($row);
        entry.$option.remove();
        entry.$item.remove();
        entries.delete($row.attr('id'));
        if (wasActive) $activeRow = $();

        const $remaining = $rows.children('.prescription-medicine-row');
        if (editing && wasActive && $remaining.length) {
            requestEdit($remaining.eq(Math.min(index, $remaining.length - 1)));
        } else if (!$remaining.length) {
            chooseMedicines();
        }
        updateNavigation();
    }

    function nextMedicine() {
        const $medicines = $rows.children('.prescription-medicine-row');
        const index = $medicines.index($activeRow);
        const $nextRow = $medicines.eq(index + 1);
        if ($nextRow.length) requestEdit($nextRow);
        else $modal.modal('hide');
    }

    function previousMedicine() {
        const $medicines = $rows.children('.prescription-medicine-row');
        const index = $medicines.index($activeRow);
        if (index > 0) requestEdit($medicines.eq(index - 1));
    }

    $start.on('click.prescriptionEditor', () => {
        const $first = $rows.children('.prescription-medicine-row').first();
        requestEdit($activeRow.parent().is($rows) ? $activeRow : $first);
    });
    $('#prescription-rx-choose-medicines').on('click.prescriptionEditor', () => {
        chooseMedicines();
        $('#prescription-medicine-search').select2('open');
    });
    $previous.on('click.prescriptionEditor', previousMedicine);
    $next.on('click.prescriptionEditor', nextMedicine);
    $jump.on('change.prescriptionEditor', () => requestEdit(entries.get($jump.val())?.$row || $()));
    $rows.on('input.prescriptionEditor change.prescriptionEditor', '[name$="[medicine_name]"]', function () {
        updateName($(this).closest('.prescription-medicine-row'));
    });
    updateNavigation();

    return { registerRow, removeRow, chooseMedicines, editMedicine, nextMedicine, previousMedicine };
}
