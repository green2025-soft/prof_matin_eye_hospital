import $ from './jquery';
import { formatAmount, parseAmount } from './money';

// Preserve the voucher preview's existing +, .50 and 1. input formats.
function minorAmount(value) {
    const decimal = String(value ?? '').trim().replace(/^\+/, '').replace(/^\./, '0.').replace(/\.$/, '.0');
    return parseAmount(decimal) ?? 0n;
}

// Describe the preview balance without changing any submitted amount.
function balanceMessage(difference, balanced) {
    if (difference === 0n) return balanced ? 'Balanced' : 'Enter Debit and Credit amounts.';

    const side = difference > 0n ? 'Debit' : 'Credit';
    const amount = difference > 0n ? difference : -difference;
    return `${side} is higher by ${formatAmount(amount)}`;
}

// Keep journal rows and their exact-cent preview inside the voucher form.
function initializeJournalForm() {
    const $form = $('#accounting-journal-form');
    if (!$form.length) return;

    const $rows = $('#accounting-journal-lines');
    const $addLine = $('#accounting-add-line');
    const $balanceState = $('#accounting-balance-state');
    const template = $('#accounting-journal-line-template').html();
    let nextIndex = 0;
    $rows.find('select[name]').each(function () {
        const index = $(this).attr('name').match(/^lines\[(\d+)\]/)?.[1];
        nextIndex = Math.max(nextIndex, Number(index ?? -1) + 1);
    });

    // Totals are feedback; JournalService validates the submitted balance.
    function updateTotals() {
        let debit = 0n;
        let credit = 0n;
        $rows.find('.accounting-journal-line').each(function () {
            const $row = $(this);
            debit += minorAmount($row.find('.journal-debit').val());
            credit += minorAmount($row.find('.journal-credit').val());
        });

        const difference = debit - credit;
        const balanced = difference === 0n && debit > 0n;
        const rowCount = $rows.children().length;
        $('#accounting-debit-total').text(formatAmount(debit));
        $('#accounting-credit-total').text(formatAmount(credit));
        $balanceState.text(balanceMessage(difference, balanced))
            .toggleClass('text-success', balanced).toggleClass('text-danger', difference !== 0n);
        $rows.find('.accounting-remove-line').prop('disabled', rowCount <= 2);
        $addLine.prop('disabled', rowCount >= 100);
    }

    // New indices never reuse a removed row's names or validation IDs.
    $addLine.on('click', function () {
        if ($rows.children().length >= 100) return;

        const $row = $(template.replaceAll('__INDEX__', String(nextIndex++)));
        $rows.append($row);
        $row.find('select').trigger('focus');
        updateTotals();
    });
    $rows.on('click', '.accounting-remove-line', function () {
        if ($rows.children().length <= 2) return;

        $(this).closest('tr').remove();
        updateTotals();
    });
    $rows.on('input', '.journal-debit, .journal-credit', function () {
        const $input = $(this);
        if (minorAmount($input.val()) > 0n) {
            const opposite = $input.hasClass('journal-debit') ? '.journal-credit' : '.journal-debit';
            $input.closest('tr').find(opposite).val('');
        }
        updateTotals();
    });

    updateTotals();
}

// Both root groups and child accounts use the same saved open/closed state.
function toggleSavedItem(items, id) {
    if (items.has(id)) items.delete(id);
    else items.add(id);
}

// Connect chart browsing, search and expansion saved for this browser tab.
function initializeAccountChart() {
    const $chart = $('#account-chart');
    if (!$chart.length) return;

    const $search = $('#account-chart-search');
    const $rootGroups = $chart.find('.account-chart-group');
    const sessionKey = $chart.attr('data-session-key');
    const accounts = new Map();
    const groups = new Map();
    const expanded = new Set();
    const openRoots = new Set();

    // Cache the server-rendered chart once; rendering never queries or reloads accounts.
    $rootGroups.each(function () {
        const $group = $(this);
        const id = $group.attr('id');
        const $name = $group.find('.account-root-name');
        groups.set(id, {
            $group, $name,
            searchText: `${$group.find('.account-root-code').text()} ${$name.text()}`.toLowerCase(),
        });
        if ($group.prop('open')) openRoots.add(id);
    });
    $chart.find('.account-chart-row').each(function () {
        const $row = $(this);
        const $toggle = $row.find('.account-tree-toggle');
        const name = $row.find('.account-name').text();
        accounts.set($row.attr('id'), {
            $row, $toggle, name,
            parentId: `account-row-${$row.attr('data-parent-id')}`,
            rootId: $row.closest('.account-chart-group').attr('id'),
            hasChildren: $toggle.length > 0,
            searchText: `${$row.find('.account-code').text()} ${name}`.toLowerCase(),
        });
    });

    // Restore this tab's valid chart IDs; unavailable browser storage stays optional.
    function restoreChartState() {
        if (!sessionKey) return;
        let state;
        try {
            state = JSON.parse(window.sessionStorage.getItem(sessionKey) || 'null');
        } catch {
            return;
        }
        if (!state) return;

        expanded.clear();
        openRoots.clear();
        const savedRows = Array.isArray(state.expandedRows) ? state.expandedRows : [];
        const savedRoots = Array.isArray(state.openRoots) ? state.openRoots : [];
        savedRows.forEach(id => {
            if (accounts.get(id)?.hasChildren) expanded.add(id);
        });
        savedRoots.forEach(id => {
            if (groups.has(id)) openRoots.add(id);
        });
        saveChartState();
    }

    // Persist expansion IDs only; a search never replaces this saved state.
    function saveChartState() {
        if (!sessionKey) return;
        try {
            window.sessionStorage.setItem(sessionKey, JSON.stringify({
                expandedRows: [...expanded],
                openRoots: [...openRoots],
            }));
        } catch {
            // Every chart control still works when the browser blocks storage.
        }
    }

    // Search reveals matches and their parents without changing the saved expansion.
    function chartVisibility(query) {
        const visible = new Set();
        const matches = new Set();
        const visibleParents = new Set();
        const visibleRoots = new Set();
        const matchingRoots = new Set();
        if (query) groups.forEach((group, id) => {
            if (group.searchText.includes(query)) matchingRoots.add(id);
        });

        accounts.forEach((account, id) => {
            if (!query) {
                if (!accounts.has(account.parentId) || (visible.has(account.parentId) && expanded.has(account.parentId))) {
                    visible.add(id);
                }
                return;
            }

            const matchesAccount = account.searchText.includes(query);
            if (!matchingRoots.has(account.rootId) && !matchesAccount) return;
            if (matchesAccount) matches.add(id);
            visible.add(id);
            visibleRoots.add(account.rootId);
            let parentId = account.parentId;
            while (accounts.has(parentId)) {
                visible.add(parentId);
                visibleParents.add(parentId);
                parentId = accounts.get(parentId).parentId;
            }
        });

        return { visible, matches, visibleParents, visibleRoots, matchingRoots };
    }

    // Apply visibility, carets and counts to the existing chart controls.
    function renderChart() {
        const query = $search.val().trim().toLowerCase();
        const { visible, matches, visibleParents, visibleRoots, matchingRoots } = chartVisibility(query);
        accounts.forEach((account, id) => {
            const open = query ? visibleParents.has(id) : expanded.has(id);
            account.$row.prop('hidden', !visible.has(id)).toggleClass('table-warning', matches.has(id));
            account.$toggle.prop('disabled', Boolean(query))
                .attr('aria-expanded', String(open))
                .attr('aria-label', `${open ? 'Collapse' : 'Expand'} ${account.name}`)
                .find('i').toggleClass('fa-chevron-down', open).toggleClass('fa-chevron-right', !open);
        });

        let visibleGroups = 0;
        groups.forEach((group, id) => {
            const show = !query || matchingRoots.has(id) || visibleRoots.has(id);
            group.$group.prop('hidden', !show).prop('open', query ? show : openRoots.has(id));
            group.$name.toggleClass('bg-warning', matchingRoots.has(id));
            if (show) visibleGroups++;
        });
        $('#account-chart-empty').prop('hidden', !query || visibleGroups > 0);
        $('#account-chart-count').text(query
            ? `${visible.size} accounts shown`
            : `${accounts.size} accounts in ${groups.size} root groups`);
    }

    // Expansion controls render and save together; a search only renders temporary state.
    function updateChart() {
        renderChart();
        saveChartState();
    }

    $chart.on('click', '.account-chart-group > summary', function (event) {
        if ($(event.target).closest('a, button').length) return;
        event.preventDefault();

        const $group = $(this).parent();
        if ($search.val().trim()) {
            $group.prop('open', !$group.prop('open'));
            return;
        }
        toggleSavedItem(openRoots, $group.attr('id'));
        updateChart();
    });
    $rootGroups.on('toggle', function () {
        const open = $(this).prop('open');
        $(this).find('.account-root-caret')
            .toggleClass('fa-chevron-down', open).toggleClass('fa-chevron-right', !open);
    });
    $chart.on('click', '.account-tree-toggle', function () {
        toggleSavedItem(expanded, $(this).closest('.account-chart-row').attr('id'));
        updateChart();
    });
    $('#account-chart-expand').on('click', function () {
        $search.val('');
        accounts.forEach((account, id) => {
            if (account.hasChildren) expanded.add(id);
        });
        groups.forEach((group, id) => openRoots.add(id));
        updateChart();
    });
    $('#account-chart-collapse').on('click', function () {
        $search.val('');
        expanded.clear();
        openRoots.clear();
        updateChart();
    });
    $('.account-chart-clear').on('click', function () {
        $search.val('');
        renderChart();
        $search.trigger('focus');
    });
    $search.on('input', renderChart);
    restoreChartState();
    renderChart();
}

$(function initializeAccounting() {
    initializeJournalForm();
    initializeAccountChart();
});
