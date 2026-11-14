import $ from './jquery';
import { formatAmount, parseAmount } from './money';

// Income and Expense use the same head rows and exact-cent voucher preview.
function initializeIncomeExpenseForm() {
    const $form = $('.income-expense-form');
    if (!$form.length) return;

    const $rows = $form.find('#income-expense-rows');
    const template = $form.find('#income-expense-row-template').html();
    let nextIndex = 0;

    $rows.find('select[name]').each(function () {
        const index = $(this).attr('name').match(/^items\[(\d+)\]/)?.[1];
        nextIndex = Math.max(nextIndex, Number(index ?? -1) + 1);
    });

    // Preview only: the server validates and saves every submitted amount.
    function updateTotal() {
        let total = 0n;
        $rows.find('.income-expense-amount').each(function () {
            total += parseAmount($(this).val()) ?? 0n;
        });
        $form.find('#income-expense-total').text(formatAmount(total));
        $rows.find('.income-expense-remove-row').prop('disabled', $rows.children().length === 1);
    }

    // Never reuse removed or failed-submit indices; validation fields stay stable.
    function addRow() {
        const $row = $(template.replaceAll('__INDEX__', String(nextIndex++)));
        $rows.append($row);
        window.initAjaxSelects($row);
        $row.find('select').select2('open');
        updateTotal();
    }

    // Destroy the row's picker before removal while retaining one required row.
    function removeRow() {
        if ($rows.children().length === 1) return;

        const $row = $(this).closest('tr');
        $row.find('select').select2('destroy');
        $row.remove();
        updateTotal();
    }

    $form.find('#income-expense-add-row').on('click', addRow);
    $rows.on('click', '.income-expense-remove-row', removeRow);
    $rows.on('input', '.income-expense-amount', updateTotal);
    updateTotal();
}

$(initializeIncomeExpenseForm);
