import $ from './jquery';
import { formatAmount, parseAmount } from './money';

function paymentMinor(value) {
    return parseAmount(value) ?? 0n;
}

// Build one outstanding invoice allocation using escaped jQuery text and value updates.
function allocationRow($template, invoice, index) {
    const $row = $($template.html().replaceAll('__INDEX__', String(index)).trim()).filter('tr').first();
    $row.attr({ 'data-invoice-id': invoice.id, 'data-due': invoice.due });
    $row.find('[data-pharmacy-allocation-select]').attr('aria-label', `Pay invoice ${invoice.number}`);
    $row.find('[data-pharmacy-allocation-number]').text(invoice.number);
    $row.find('[data-pharmacy-allocation-due]').text(invoice.due);
    $row.find('input[type="hidden"]').val(invoice.id);
    $row.find('[name$="[amount]"]').val(invoice.due).attr({
        max: invoice.due, 'aria-label': `Cash for invoice ${invoice.number}`,
    });
    $row.find('[name$="[discount]"]').attr({
        max: invoice.due, 'aria-label': `Discount for invoice ${invoice.number}`,
    });
    updateAllocationLimits($row);

    return $row;
}

function updateCollectionFromDiscount($row) {
    const due = paymentMinor($row.attr('data-due'));
    const $discount = $row.find('[name$="[discount]"]');
    let discount = paymentMinor($discount.val());
    if (discount > due) {
        discount = due;
        $discount.val(formatAmount(due));
    }
    const collection = due > discount ? due - discount : 0n;
    $row.find('[name$="[amount]"]').val(formatAmount(collection));
    updateAllocationLimits($row);
}

function updateAllocationLimits($row) {
    const due = paymentMinor($row.attr('data-due'));
    const discount = paymentMinor($row.find('[name$="[discount]"]').val());
    const maxCollection = due > discount ? due - discount : 0n;
    $row.find('[name$="[amount]"]').attr('max', formatAmount(maxCollection));
    $row.find('[name$="[discount]"]').attr('max', formatAmount(due));
}

function limitCollectionToDue($row) {
    const $amount = $row.find('[name$="[amount]"]');
    const due = paymentMinor($row.attr('data-due'));
    const discount = paymentMinor($row.find('[name$="[discount]"]').val());
    const maxCollection = due > discount ? due - discount : 0n;
    if (paymentMinor($amount.val()) > maxCollection) $amount.val(formatAmount(maxCollection));
}

function validateAllocation($row) {
    const $amount = $row.find('[name$="[amount]"]');
    const amountInput = $amount.get(0);
    if (!amountInput) return;

    const collection = paymentMinor($amount.val());
    const discount = paymentMinor($row.find('[name$="[discount]"]').val());
    const due = paymentMinor($row.attr('data-due'));
    amountInput.setCustomValidity(collection + discount > due
        ? 'Collection and discount cannot exceed the due.'
        : '');
}

// A party change cancels the old request before loading its outstanding invoices.
function initializePayment(form) {
    const $form = $(form);
    const $table = $form.find('[data-pharmacy-allocations]');
    const $template = $form.find('[data-pharmacy-allocation-template]');
    const $more = $form.find('[data-pharmacy-more-invoices]');
    const $error = $form.find('[data-pharmacy-payment-error]');
    const $loading = $form.find('[data-pharmacy-payment-loading]');
    const $party = $form.find('[data-pharmacy-payment-party]');
    const $cashTotal = $form.find('[data-pharmacy-cash-total]');
    let page = 1;
    let nextIndex = Math.max(-1, ...$table.find('[name$="[amount]"]').map(function () {
        return Number($(this).attr('name').match(/^allocations\[(\d+)\]/)?.[1]) || 0;
    }).get()) + 1;
    let pending = null;

    function refreshCash() {
        let total = 0n;
        $table.find('[name$="[amount]"]:enabled').each(function () {
            total += paymentMinor($(this).val());
        });
        $cashTotal.text(formatAmount(total));
    }

    function clearInvoices() {
        const previous = pending;
        pending = null;
        previous?.abort();
        $table.empty();
        page = 1;
        nextIndex = 0;
        $more.addClass('d-none').text('Load more').prop('disabled', false);
        $loading.prop('hidden', true);
        $error.addClass('d-none');
        refreshCash();
    }

    async function loadInvoices(append) {
        if (!$party.val()) {
            window.showToast('Select a party first.', 'warning');
            return;
        }
        if (!append) clearInvoices();
        const partyId = $party.val();
        const params = { product_type: $form.find('[name="product_type"]').val(), page };
        params[$party.attr('name')] = partyId;
        $more.prop('disabled', true);
        $loading.prop('hidden', false);
        $error.addClass('d-none');
        const request = $.ajax({
            url: $form.attr('data-invoices-url'),
            data: params,
            dataType: 'json',
            timeout: 15000,
        });
        pending = request;
        try {
            const data = await request;
            if (pending !== request || $party.val() !== partyId) return;
            const existing = new Set($table.children().get().map(row => $(row).attr('data-invoice-id')));
            for (const invoice of data.results) {
                if (!existing.has(String(invoice.id))) {
                    const $row = allocationRow($template, invoice, nextIndex++);
                    validateAllocation($row);
                    $table.append($row);
                }
            }
            refreshCash();
            $more.text('Load more').toggleClass('d-none', !data.pagination.more);
            page += 1;
            if (!$table.children().length) {
                window.showToast('No outstanding invoices found.', 'info');
            }
        } catch (xhr) {
            if (pending !== request) return;
            if (xhr.statusText !== 'abort') {
                const message = xhr.responseJSON?.message || 'Invoices could not be loaded. Please try again.';
                if (xhr.status === 422) {
                    $error.text(message).removeClass('d-none');
                } else {
                    window.showToast(message, 'error');
                }
                $more.text('Retry loading').removeClass('d-none');
            }
        } finally {
            if (pending === request) {
                pending = null;
                $loading.prop('hidden', true);
                $more.prop('disabled', false);
            }
        }
    }

    $more.on('click.pharmacy', () => loadInvoices(true));
    $party.on('change.pharmacy', () => {
        if ($party.val()) loadInvoices(false);
        else clearInvoices();
    });
    $form.on('input.pharmacy change.pharmacy', '[name$="[discount]"], [name$="[amount]"]', function () {
        const $row = $(this).closest('[data-pharmacy-allocation]');
        if ($(this).is('[name$="[discount]"]')) updateCollectionFromDiscount($row);
        else limitCollectionToDue($row);
        updateAllocationLimits($row);
        validateAllocation($row);
    });
    $form.on('change.pharmacy', '[data-pharmacy-allocation-select]', function () {
        const checked = $(this).prop('checked');
        const $row = $(this).closest('[data-pharmacy-allocation]');
        $row.find('[name]').prop('disabled', !checked);
        const $amount = $row.find('[name$="[amount]"]');
        if (checked && !Number($amount.val())) updateCollectionFromDiscount($row);
        else updateAllocationLimits($row);
        validateAllocation($row);
    });
    $form.on('change.pharmacy input.pharmacy', refreshCash);
    $table.find('[data-pharmacy-allocation]').each(function () {
        updateAllocationLimits($(this));
        validateAllocation($(this));
    });
    refreshCash();
    if ($party.val() && !$table.children().length) loadInvoices(false);
}

$(function () {
    $('[data-pharmacy-payment]').each(function () { initializePayment(this); });
});
