import $ from './jquery';
import { prescriptionHeaderDropdown } from './prescription-header-dropdown';
import { createEmptySearchGuard } from './select2-empty-search';

const pickerSelector = '.app-prescription-picker';

// Compare names without leading spaces or letter-case differences.
function itemKey(name) {
    return name.trim().toLocaleLowerCase();
}

// Read the selected names stored in the submitted textarea.
function selectedNames(state) {
    return String(state.$value.val() || '').split(/\r?\n/).filter(name => name.trim());
}

// Show or clear feedback for this picker only.
function showError(state, message = '') {
    state.$error.text(message).prop('hidden', !message);
}

// A preset can be created only after an empty search and when its name is unknown.
function canCreatePreset(state) {
    return state.emptySearch.canCreate(state.term) && !state.knownNames.has(itemKey(state.term));
}

// Keep the Create button consistent with the current search result.
function updateCreateButton(state) {
    state.$create.prop('hidden', !canCreatePreset(state));
}

// Remember the typed name and reset creation eligibility for a changed search.
function setSearchTerm(state, term) {
    state.term = String(term || '').trim();
    state.emptySearch.setTerm(state.term);
    updateCreateButton(state);
}

// Mirror the textarea selection in Select2, suggestion chips and result checkmarks.
function syncSelection(state) {
    const names = selectedNames(state);
    const selected = new Set(names.map(itemKey));
    const nativeNames = new Set(state.$search.find('option').map(function () {
        return $(this).val();
    }).get());

    for (const name of names) {
        if (!nativeNames.has(name)) {
            state.$search.append($('<option>', { value: name, text: name }));
            nativeNames.add(name);
        }
    }
    state.$search.val(names).trigger('change.select2');
    state.$options.children('button').each(function () {
        const $button = $(this);
        $button.attr('aria-pressed', String(selected.has(itemKey($button.data('name')))));
    });
    state.$search.data('select2').$dropdown.find('.prescription-option-check').each(function () {
        const $checkbox = $(this);
        const checked = selected.has(itemKey($checkbox.data('name')));
        $checkbox.toggleClass('is-checked', checked);
        $checkbox.closest('.select2-results__option')
            .toggleClass('select2-results__option--selected', checked).attr('aria-selected', String(checked));
    });
}

// Write the submitted names and clear previous picker feedback.
function writeSelection(state, names) {
    showError(state);
    state.$value.val(names.join('\n')).trigger('change');
}

// Add one new name at the top without duplicating an existing selection.
function selectName(state, name) {
    const names = selectedNames(state);
    const key = itemKey(name);
    if (names.some(selected => itemKey(selected) === key)) {
        syncSelection(state);
        return;
    }

    writeSelection(state, [name, ...names]);
}

// Remove a selected name while preserving the order of the remaining items.
function removeName(state, name) {
    const key = itemKey(name);
    const remaining = selectedNames(state).filter(selected => itemKey(selected) !== key);
    writeSelection(state, remaining);
}

// Rebuild the suggestion chips and retain the user's scroll position.
function renderGrid(state) {
    const selected = new Set(selectedNames(state).map(itemKey));
    const scrollTop = state.$options.scrollTop();
    state.$options.empty();

    for (const name of state.options.values()) {
        $('<button>', {
            type: 'button',
            class: 'app-prescription-picker-chip',
            text: name,
            'aria-pressed': String(selected.has(itemKey(name))),
        }).data('name', name).appendTo(state.$options);
    }
    if (!state.options.size) state.$options.text('No items found. Search or type a name to add it.');
    state.$options.scrollTop(scrollTop);
}

// Reuse a cached catalog page or request it from the existing selector route.
function fetchOptions(state, search, page) {
    const key = JSON.stringify([search, page]);
    const cached = state.cache.get(key);
    if (cached) {
        // Select2 can cancel a previous request even when its result came from cache.
        return $.Deferred().resolve(cached).promise({ abort() {} });
    }

    return $.ajax({
        url: state.$picker.attr('data-url'),
        data: { search, page },
        dataType: 'json',
        headers: { Accept: 'application/json' },
        timeout: 15000,
    }).done(data => {
        state.cache.set(key, data);
        for (const option of data.results) state.knownNames.add(itemKey(option.name));
        updateCreateButton(state);
    });
}

// Load one suggestion page; ignore responses superseded by another request.
function loadGrid(state, page = 1) {
    if (!state.$picker.is(':visible') || state.gridRequest) return;

    state.$options.attr('aria-busy', 'true');
    if (page === 1) state.$options.text('Loading items...');

    const request = fetchOptions(state, '', page);
    state.gridRequest = request;
    request.done(data => {
        if (state.gridRequest !== request) return;

        for (const option of data.results) state.options.set(itemKey(option.name), option.name);
        state.page = page;
        state.loaded = true;
        state.more = Boolean(data.pagination?.more);
        renderGrid(state);
    }).fail((xhr, status) => {
        if (state.gridRequest !== request || status === 'abort') return;

        if (page === 1) state.$options.text('Items could not be loaded. Reopen this section to try again.');
        window.showToast('Items could not be loaded. Please try again.', 'error');
    }).always(() => {
        if (state.gridRequest !== request) return;

        state.gridRequest = null;
        state.$options.attr('aria-busy', 'false');
        if (state.page === page) state.$options.trigger('scroll');
    });
}

// Build an escaped search result with its current selection checkmark.
function optionResult(state, option) {
    if (option.loading) return option.text;

    const name = option.name || option.text;
    const key = itemKey(name);
    const selected = selectedNames(state).some(value => itemKey(value) === key);
    const $checkbox = $('<span>', {
        class: 'prescription-option-check',
        'aria-hidden': 'true',
    }).data('name', name).toggleClass('is-checked', selected);

    return $('<span>', { class: 'app-prescription-option-result' })
        .append($checkbox, $('<span>', { text: name }));
}

// Follow typing in both Select2 search fields without adding duplicate handlers.
function bindSearchField(state) {
    const select = state.$search.data('select2');
    const $fields = select.$container.add(select.$dropdown).find('.select2-search__field');
    $fields.off('input.prescriptionPicker').on('input.prescriptionPicker', function () {
        setSearchTerm(state, $(this).val());
    });
}

// Connect Select2 search, paging and selection to this picker's saved value.
function initializeSearch(state) {
    state.emptySearch = createEmptySearchGuard(state.$search);
    state.$search.on('prescription:searchEligibility', () => updateCreateButton(state));
    state.$search.select2({
        width: '100%',
        placeholder: 'Search / Add item...',
        dropdownParent: state.$search.closest('.app-prescription-picker-search'),
        dropdownAdapter: prescriptionHeaderDropdown(),
        tags: true,
        closeOnSelect: false,
        maximumInputLength: 255,
        templateResult: option => optionResult(state, option),
        // Allow a typed name only after this exact search returned no matches.
        createTag(params) {
            const name = String(params.term || '').trim();
            return state.emptySearch.canCreate(name) ? { id: name, name, text: name, newTag: true } : null;
        },
        ajax: {
            delay: 250,
            // Associate the request with the term used to determine creation eligibility.
            data(params) {
                setSearchTerm(state, params.term);
                state.emptySearch.begin(params);
                return { search: state.term.slice(0, 80), page: params.page || 1 };
            },
            // Deliver only the active request, including cached selector responses.
            transport(params, success, failure) {
                const request = fetchOptions(state, params.data.search, params.data.page);
                state.searchRequest = request;
                request.done(data => {
                    if (state.searchRequest === request) success(data);
                }).fail((xhr, status) => {
                    if (status !== 'abort' && state.searchRequest === request) {
                        failure(xhr);
                        window.showToast('Items could not be loaded. Please try again.', 'error');
                    }
                }).always(() => {
                    if (state.searchRequest === request) state.searchRequest = null;
                });
                return request;
            },
            // Normalize catalog names for Select2 and reject stale search results.
            processResults(data, params) {
                if (!state.emptySearch.complete(params, data.results.length, data.pagination?.more)) {
                    return { results: [], pagination: { more: false } };
                }
                // Select2 4.1's AJAX normalization needs the adapter context preserved.
                return {
                    results: data.results.map(option => this._normalizeItem({
                        id: option.name,
                        name: option.name,
                        text: option.name,
                    })),
                    pagination: data.pagination,
                };
            },
        },
    });
    state.$search.on('select2:open.prescriptionPicker', () => bindSearchField(state))
        .on('select2:select.prescriptionPicker', event => {
            selectName(state, event.params.data.name || event.params.data.text);
            setSearchTerm(state, '');
        })
        .on('select2:unselect.prescriptionPicker', event => {
            removeName(state, event.params.data.name || event.params.data.text);
        });
    bindSearchField(state);
    state.emptySearch.bindSearch();
}

// Save a new preset once, then select it and refresh the local suggestions.
function savePreset($button) {
    const state = $($button.attr('data-picker')).data('prescriptionPicker');
    const name = state.term;
    if (!canCreatePreset(state) || $button.data('prescriptionPresetRequest')) return;

    showError(state);
    const $spinner = $('<span>', {
        class: 'spinner-border spinner-border-sm me-2',
        'aria-hidden': 'true',
    }).prependTo($button);
    $button.prop('disabled', true).attr('aria-busy', 'true');

    const request = $.ajax({
        url: $button.attr('data-url'),
        method: 'POST',
        data: {
            section: $button.attr('data-section'),
            name,
            ordering: 0,
            _token: $('#prescription-form input[name="_token"]').val(),
        },
        dataType: 'json',
        headers: { Accept: 'application/json' },
        timeout: 30000,
    });
    $button.data('prescriptionPresetRequest', request);
    request.done(option => {
        state.cache.clear();
        state.knownNames.add(itemKey(option.name));
        state.options.set(itemKey(option.name), option.name);
        selectName(state, option.name);
        renderGrid(state);
        state.$search.select2('close');
        state.$search.data('select2').$container.find('.select2-search__field').val('');
        setSearchTerm(state, '');
        window.showToast('Item saved to list.', 'success');
    }).fail((xhr, status) => {
        if (status === 'abort') return;

        if (xhr.status === 422) {
            const messages = Object.values(xhr.responseJSON?.errors || {}).flat().join(' ');
            showError(state, messages || 'Check the item name and try again.');
        } else {
            window.showToast('The item could not be saved. Please try again.', 'error');
        }
    }).always(() => {
        $button.removeData('prescriptionPresetRequest').prop('disabled', false).removeAttr('aria-busy');
        $spinner.remove();
    });
}

// Initialize one picker and attach its chip and incremental-loading events.
function initializePicker($picker) {
    if ($picker.data('prescriptionPicker')) return;

    const target = '#' + $picker.attr('id');
    const state = {
        $picker,
        $value: $picker.find('.prescription-picker-value'),
        $search: $($picker.attr('data-search')),
        $create: $('.prescription-preset-save').filter(function () {
            return $(this).attr('data-picker') === target;
        }),
        $options: $picker.find('.app-prescription-picker-options'),
        $error: $picker.find('.prescription-picker-error'),
        options: new Map(),
        knownNames: new Set(),
        cache: new Map(),
        gridRequest: null,
        searchRequest: null,
        loaded: false,
        more: false,
        page: 0,
        term: '',
    };
    $picker.data('prescriptionPicker', state);
    initializeSearch(state);
    state.$value.on('change.prescriptionPicker', () => syncSelection(state));
    state.$options.on('click.prescriptionPicker', 'button', function () {
        const $button = $(this);
        const name = $button.data('name');
        if ($button.attr('aria-pressed') === 'true') removeName(state, name);
        else selectName(state, name);
    });
    state.$options.on('scroll.prescriptionPicker', () => {
        const remaining = state.$options.prop('scrollHeight')
            - state.$options.scrollTop() - state.$options.innerHeight();
        if (state.more && remaining <= 30) loadGrid(state, state.page + 1);
    });
    updateCreateButton(state);
    syncSelection(state);
}

// Load newly visible pickers, or continue paging an already loaded picker.
function loadVisiblePickers($container) {
    $container.find(pickerSelector).addBack(pickerSelector).filter(':visible').each(function () {
        const state = $(this).data('prescriptionPicker');
        if (!state) return;
        if (!state.loaded) loadGrid(state);
        else state.$options.trigger('scroll');
    });
}

// Initialize clinical pickers and coordinate their modal, tab and Create events.
export function initializePrescriptionPickers(root = document) {
    $(root).find(pickerSelector).addBack(pickerSelector).each(function () {
        initializePicker($(this));
    });

    $(document).off('.prescriptionPickers')
        .on('shown.bs.modal.prescriptionPickers', '.modal', function () {
            loadVisiblePickers($(this));
        })
        .on('shown.bs.tab.prescriptionPickers', '[data-bs-toggle="tab"], [data-bs-toggle="pill"]', function () {
            loadVisiblePickers($($(this).attr('data-bs-target')));
        })
        .on('hidden.bs.modal.prescriptionPickers', '.modal', function () {
            $(this).find(pickerSelector).each(function () {
                const state = $(this).data('prescriptionPicker');
                if (!state) return;

                state.$search.select2('close');
                state.gridRequest?.abort();
                state.searchRequest?.abort();
            });
        })
        .on('click.prescriptionPickers', '.prescription-preset-save', function () {
            savePreset($(this));
        });
}
