import { Modal, Offcanvas } from 'bootstrap';
import $ from 'jquery';
import flatpickr from 'flatpickr';
import attachSelect2 from 'select2';

window.$ = window.jQuery = $;
attachSelect2(window, $);

window.initAjaxSelects = function (root = document) {
    $(root).find('select[data-ajax-select]').addBack('select[data-ajax-select]').each(function () {
        const select = this;
        const $select = $(select);
        if ($select.hasClass('select2-hidden-accessible')) {
            return;
        }

        const valueField = select.dataset.valueField || 'id';
        const labelField = select.dataset.labelField || 'name';
        const parent = select.dataset.parentSelect ? document.getElementById(select.dataset.parentSelect) : null;
        const $modal = $select.closest('.modal');
        $select.select2({
            width: '100%',
            dropdownParent: $modal.length ? $modal : $(document.body),
            placeholder: select.dataset.placeholder || 'Select',
            allowClear: select.dataset.allowClear !== 'false',
            minimumInputLength: Number(select.dataset.minimumInputLength || 0),
            maximumInputLength: 80,
            language: {
                noResults: () => select.dataset.noResultsText || 'No results found',
            },
            ajax: {
                url: () => select.dataset.url,
                dataType: 'json',
                delay: 250,
                timeout: 15000,
                data: params => {
                    const data = { search: params.term || '', page: params.page || 1 };
                    if (parent && select.dataset.parentField) {
                        data[select.dataset.parentField] = parent.value;
                    }
                    return data;
                },
                transport(params, success, failure) {
                    const parentValue = parent && select.dataset.parentField
                        ? String(params.data?.[select.dataset.parentField] ?? '') : null;
                    const parentUnchanged = () => parentValue === null || parent.value === parentValue;
                    const request = $.ajax(params);
                    request.done(data => {
                        if (parentUnchanged()) {
                            success(data);
                        }
                    }).fail((xhr, status, error) => {
                        if (parentUnchanged()) {
                            failure(xhr, status, error);
                        }
                    });

                    return request;
                },
                processResults(data) {
                    // Select2 4.1.0's later AJAX normalization loses the adapter context.
                    return {
                        results: data.results.map(row => this._normalizeItem({ ...row, id: row[valueField], text: row[labelField] })),
                        pagination: data.pagination,
                    };
                },
            },
        });

        if (parent) {
            $select.prop('disabled', !parent.value);
            $(parent).on('change', () => {
                $select.select2('close');
                $select.find('option').not('[value=""]').remove();
                $select.val(null).prop('disabled', !parent.value).trigger('change');
            });
        }

        const $selection = $select.next('.select2-container').find('.select2-selection');
        for (const attribute of ['aria-invalid', 'aria-describedby']) {
            const value = select.getAttribute(attribute);
            if (value !== null) {
                $selection.attr(attribute, value);
            }
        }

        select.addEventListener('invalid', event => {
            if (select.form?.querySelector(':invalid') === select) {
                event.preventDefault();
                $select.select2('open');
            }
        });
    });
};

$(() => window.initAjaxSelects());

const desktopNavigation = window.matchMedia('(min-width: 992px)');

function updateNavigationState() {
    const sidebar = document.getElementById('appSidebar');

    if (!sidebar) {
        return;
    }

    const expanded = desktopNavigation.matches
        ? !document.body.classList.contains('sidebar-collapsed')
        : sidebar.classList.contains('show');

    $('[data-sidebar-toggle]').attr('aria-expanded', String(expanded))
        .attr('aria-label', expanded ? 'Collapse navigation' : 'Expand navigation');
}

$(() => {
    try {
        document.body.classList.toggle('sidebar-collapsed', localStorage.getItem('admin.sidebar.collapsed') === '1');
    } catch {
        // Navigation remains usable when browser storage is unavailable.
    }

    updateNavigationState();
    desktopNavigation.addEventListener('change', updateNavigationState);

    const sidebar = document.getElementById('appSidebar');
    sidebar?.addEventListener('shown.bs.offcanvas', updateNavigationState);
    sidebar?.addEventListener('hidden.bs.offcanvas', updateNavigationState);

    if (!document.fullscreenEnabled) {
        $('[data-fullscreen-toggle]').remove();
    }
});

$(document).on('click', '[data-sidebar-toggle]', function () {
    const sidebar = document.getElementById('appSidebar');

    if (!sidebar) {
        return;
    }

    if (desktopNavigation.matches) {
        document.body.classList.toggle('sidebar-collapsed');

        try {
            localStorage.setItem('admin.sidebar.collapsed', document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
        } catch {
            // The current page still remembers the chosen state.
        }

        updateNavigationState();
    } else {
        Offcanvas.getOrCreateInstance(sidebar).toggle();
    }
});

$(document).on('click', '#appSidebar a', function () {
    if (!desktopNavigation.matches) {
        Offcanvas.getInstance(document.getElementById('appSidebar'))?.hide();
    }
});

$(document).on('click', '[data-fullscreen-toggle]', async function () {
    try {
        if (document.fullscreenElement) {
            await document.exitFullscreen();
        } else {
            await document.documentElement.requestFullscreen();
        }
    } catch {
        $(this).prop('disabled', true).attr('title', 'Fullscreen is unavailable');
    }
});

document.addEventListener('fullscreenchange', () => {
    const active = Boolean(document.fullscreenElement);
    $('[data-fullscreen-toggle]').attr('aria-pressed', String(active))
        .attr('aria-label', active ? 'Exit fullscreen' : 'Enter fullscreen')
        .attr('title', active ? 'Exit fullscreen' : 'Enter fullscreen')
        .find('i').toggleClass('fa-expand', !active).toggleClass('fa-compress', active);
});

$(document).on('click', '[data-password-toggle]', function () {
    const input = document.getElementById($(this).attr('aria-controls'));

    if (!input) {
        return;
    }

    const visible = input.type === 'password';
    input.type = visible ? 'text' : 'password';
    $(this).attr('aria-pressed', String(visible))
        .attr('aria-label', visible ? 'Hide password' : 'Show password')
        .attr('title', visible ? 'Hide password' : 'Show password')
        .find('i').toggleClass('fa-eye', !visible).toggleClass('fa-eye-slash', visible);
});

const submittingForms = new Map();

function resetFormSubmission(form) {
    const state = submittingForms.get(form);

    if (!state) {
        return;
    }

    window.clearTimeout(state.timer);
    $(form).removeAttr('aria-busy');

    if (state.loading) {
        state.buttons.forEach(button => { button.disabled = false; });

        if (state.button) {
            $(state.button).removeAttr('aria-busy');

            if (state.button.tagName === 'BUTTON') {
                state.button.innerHTML = state.content;
            } else {
                state.button.value = state.value;
            }
        }
    }

    submittingForms.delete(form);
}

function startFormSubmission(form, submitter, event = null) {
    if (submittingForms.has(form)) {
        return false;
    }

    const confirmation = form.getAttribute('data-confirm');
    if (confirmation && !window.confirm(confirmation)) {
        return false;
    }

    const buttons = Array.from(form.elements).filter(button => button.type === 'submit' && !button.disabled);
    const button = buttons.includes(submitter) ? submitter : buttons[0];
    const state = { buttons, button, content: button?.innerHTML, value: button?.value };
    submittingForms.set(form, state);
    $(form).attr('aria-busy', 'true');

    // Keep named button values in the native payload before changing or disabling controls.
    state.timer = window.setTimeout(() => {
        if (event?.isDefaultPrevented()) {
            resetFormSubmission(form);
            return;
        }

        state.loading = true;
        buttons.forEach(control => { control.disabled = true; });

        if (button) {
            $(button).attr('aria-busy', 'true');

            if (button.tagName === 'BUTTON') {
                const textClass = button.textContent.trim() ? '' : ' class="visually-hidden"';
                button.innerHTML = `<span class="d-inline-flex align-items-center gap-2 m-auto"><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span${textClass}>Loading...</span></span>`;
            } else {
                button.value = 'Loading...';
            }
        }
    }, 0);

    return true;
}

$(document).on('submit', 'form', function (event) {
    if (event.isDefaultPrevented() || this.matches('[data-patient-create-form]')) {
        return;
    }

    if (!startFormSubmission(this, event.originalEvent?.submitter, event)) {
        event.preventDefault();
    }
});

$(window).on('pageshow', () => {
    submittingForms.forEach((_, form) => resetFormSubmission(form));
});

function clearPatientModalErrors(form) {
    $(form).find('[data-patient-field-error]').remove();
    $(form).find('[data-patient-invalid]').each((_, field) => {
        const attributes = $(field).data('patientErrorAttributes');
        $(field).removeClass('is-invalid').removeAttr('data-patient-invalid');
        for (const attribute of ['aria-invalid', 'aria-describedby']) {
            $(field).attr(attribute, attributes?.[attribute] ?? null);
            $(field).next('.select2-container').find('.select2-selection').attr(attribute, attributes?.[attribute] ?? null);
        }
        $(field).removeData('patientErrorAttributes');
    });
    $(form).closest('.modal').find('[data-patient-modal-error]').prop('hidden', true).text('');
}

function patientRequestError(status) {
    const messages = {
        401: 'Your session has expired. Sign in again before creating a patient.',
        403: 'Your account or plan does not allow creating patients.',
        409: 'Your branch selection has changed. Reload this page before continuing.',
        419: 'Your session has expired. Refresh this page before continuing.',
    };

    return messages[status] ?? 'The request could not be completed. Check your connection and try again.';
}

function showPatientModalError(modal, message) {
    $(modal).find('[data-patient-modal-error]').text(message).prop('hidden', false).attr('tabindex', '-1').trigger('focus');
}

function showPatientValidationErrors(form, errors) {
    const messages = [];
    let firstField = null;

    for (const [name, errorsForField] of Object.entries(errors ?? {})) {
        const text = (Array.isArray(errorsForField) ? errorsForField : [errorsForField])
            .filter(message => typeof message === 'string').join(' ');
        if (!text) {
            continue;
        }

        const field = Array.from(form.elements).find(input => input.name === name && input.type !== 'hidden'
            && ($(input).is(':visible') || $(input).hasClass('select2-hidden-accessible')));
        if (!field) {
            messages.push(text);
            continue;
        }

        const errorId = (field.id || 'patient-modal-' + name.replace(/[^a-z0-9_-]/gi, '-')) + '-ajax-error';
        const $field = $(field);
        $field.data('patientErrorAttributes', {
            'aria-invalid': field.getAttribute('aria-invalid'),
            'aria-describedby': field.getAttribute('aria-describedby'),
        }).addClass('is-invalid').attr({ 'data-patient-invalid': '', 'aria-invalid': 'true' });
        const describedBy = [field.getAttribute('aria-describedby'), errorId].filter(Boolean).join(' ');
        $field.attr('aria-describedby', describedBy);
        let $anchor = $field;
        if ($field.hasClass('select2-hidden-accessible')) {
            $anchor = $field.next('.select2-container');
            $anchor.find('.select2-selection').attr({ 'aria-invalid': 'true', 'aria-describedby': describedBy });
        } else if ($field.closest('.app-input-wrap').length) {
            $anchor = $field.closest('.app-input-wrap');
        }
        $('<div>', { id: errorId, class: 'invalid-feedback d-block', 'data-patient-field-error': '' }).text(text).insertAfter($anchor);
        if (!firstField && !field.disabled) {
            firstField = field;
        }
    }

    const modal = form.closest('.modal');
    showPatientModalError(modal, ['Check the highlighted patient fields.', ...messages].join(' '));
    if (firstField) {
        if ($(firstField).hasClass('select2-hidden-accessible')) {
            $(firstField).select2('open');
        } else {
            firstField.focus();
        }
    }
}

let patientTargetSelect = null;
let patientFieldsRequest = null;

function clearPatientModal(modal) {
    if (patientFieldsRequest) {
        patientFieldsRequest.abort();
        patientFieldsRequest = null;
    }
    const form = modal.querySelector('[data-patient-create-form]');
    if (form) {
        resetFormSubmission(form);
        clearPatientModalErrors(form);
    }
    $(modal).find('[data-patient-modal-content] select.select2-hidden-accessible').each(function () {
        $(this).select2('close').select2('destroy');
    });
    $(modal).find('[data-patient-modal-content]').empty();
    $(modal).find('[data-patient-modal-save]').prop('disabled', true);
}

$(document).on('click', '[data-patient-target]', function (event) {
    event.preventDefault();
    const select = document.getElementById(this.dataset.patientTarget);
    const modal = document.getElementById('patient-create-modal');
    if (!select?.matches('select[data-patient-select]') || !modal) {
        return;
    }

    if (this.dataset.bsToggle !== 'modal') {
        Modal.getOrCreateInstance(modal).show(this);
    }
});

$(document).on('show.bs.modal', '#patient-create-modal', function (event) {
    const modal = this;
    const select = document.getElementById(event.relatedTarget?.dataset.patientTarget);
    if (!select?.matches('select[data-patient-select]')) {
        event.preventDefault();
        return;
    }
    patientTargetSelect = select;
    clearPatientModal(modal);
    const $content = $(modal).find('[data-patient-modal-content]');
    $content.text('Loading patient form...');
    const request = $.ajax({
        url: modal.dataset.fieldsUrl,
        method: 'GET',
        dataType: 'html',
        headers: { Accept: 'application/json' },
        timeout: 15000,
    });
    patientFieldsRequest = request;
    request.done(html => {
        if (patientFieldsRequest !== request) {
            return;
        }
        $content.html(html);
        window.initAjaxSelects($content[0]);
        $(modal).find('[data-patient-modal-save]').prop('disabled', false);
        $content.find(':input:visible').first().trigger('focus');
    }).fail((xhr, status) => {
        if (status !== 'abort') {
            $content.empty();
            showPatientModalError(modal, patientRequestError(xhr.status));
        }
    }).always(() => {
        if (patientFieldsRequest === request) {
            patientFieldsRequest = null;
        }
    });
});

$(document).on('shown.bs.modal', '#patient-create-modal', function () {
    $(this).find('[data-patient-modal-content] :input:visible').first().trigger('focus');
});

$(document).on('hide.bs.modal', '#patient-create-modal', function (event) {
    const form = this.querySelector('[data-patient-create-form]');
    if (form && submittingForms.has(form)) {
        event.preventDefault();
    }
});

$(document).on('hidden.bs.modal', '#patient-create-modal', function () {
    clearPatientModal(this);
    patientTargetSelect = null;
});

$(document).on('submit', '[data-patient-create-form]', function (event) {
    event.preventDefault();
    const form = this;
    const modal = form.closest('.modal');
    const select = patientTargetSelect;
    const submitter = event.originalEvent?.submitter;
    if (!select || submittingForms.has(form) || !form.reportValidity()) {
        return;
    }

    const values = new FormData(form);
    if (submitter?.name && !submitter.disabled) {
        values.append(submitter.name, submitter.value);
    }
    if (!startFormSubmission(form, submitter)) {
        return;
    }
    clearPatientModalErrors(form);
    $.ajax({
        url: form.action,
        method: 'POST',
        data: values,
        processData: false,
        contentType: false,
        dataType: 'json',
        headers: { Accept: 'application/json' },
        timeout: 30000,
    }).done((patient, _, xhr) => {
        const validId = (typeof patient?.id === 'string' && /^[1-9][0-9]*$/.test(patient.id))
            || (Number.isSafeInteger(patient?.id) && patient.id > 0);
        if (xhr.status !== 201 || !validId || typeof patient.name !== 'string'
            || !patient.name.trim() || typeof patient.patient_id !== 'string' || !patient.patient_id.trim()
            || typeof patient.text !== 'string' || !patient.text.trim()) {
            showPatientModalError(modal, 'The patient response could not be read. Refresh the page before trying again.');
            return;
        }

        const data = { ...patient, id: String(patient.id) };
        const option = new Option(data.text, data.id, true, true);
        $(option).data('data', data);
        const $select = $(select).append(option).trigger('change');
        if ($select.hasClass('select2-hidden-accessible')) {
            Object.assign($select.select2('data')[0], data);
        }
        $select.trigger({ type: 'select2:select', params: { data } });
        resetFormSubmission(form);
        Modal.getOrCreateInstance(modal).hide();
    }).fail(xhr => {
        if (xhr.status === 422) {
            showPatientValidationErrors(form, xhr.responseJSON?.errors);
        } else {
            showPatientModalError(modal, patientRequestError(xhr.status));
        }
    }).always(() => resetFormSubmission(form));
});

$(document).on('change', '[data-page-size]', function () {
    this.form?.requestSubmit();
});

function updateDomainRows($editor) {
    const $rows = $editor.find('[data-domain-row]');

    $rows.each(function (index) {
        const id = `tenant-domain-${index}`;
        $(this).find('input').attr('id', id);
        $(this).find('[data-domain-label]').attr('for', id).text(`Domain ${index + 1}`);
        $(this).find('[data-domain-remove]').prop('disabled', $rows.length === 1).attr('aria-label', `Remove domain ${index + 1}`);
    });

    $editor.find('[data-domain-add]').prop('disabled', $rows.length >= 20);
    $editor.find('[data-domain-announcement]').text(`${$rows.length} domain fields`);
}

function initializeTenantForm(form) {
    const $form = $(form);

    if ($form.attr('data-tenant-create') !== '1') {
        return;
    }

    const $name = $form.find('[name="name"]');
    const $slug = $form.find('[name="slug"]');
    const baseDomain = $form.attr('data-tenant-base-domain');
    const $automatic = $form.find('[data-tenant-domain-auto]');
    const firstDomain = () => $form.find('[name="domains[]"]').first();
    const initialDomain = String(firstDomain().val() ?? '');
    let automaticDomain = initialDomain === '' || initialDomain === `${$slug.val()}.${baseDomain}`;

    function updatePreview() {
        let slug = String($name.val() ?? '').normalize('NFKD')
            .replace(/[\u0300-\u036f]/g, '').toLowerCase()
            .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');

        if (slug === '') {
            slug = 'tenant';
        }

        slug = slug.slice(0, 63).replace(/-+$/, '');
        $slug.val($name.val() ? slug : '');

        if (automaticDomain) {
            firstDomain().val($name.val() ? `${slug}.${baseDomain}` : '');
            $automatic.val('1');
        }
    }

    $name.on('input', updatePreview);
    $form.on('input', '[name="domains[]"]', function () {
        if (this === firstDomain()[0]) {
            automaticDomain = this.value.trim() === '';
            $automatic.val(automaticDomain ? '1' : '0');
        }
    });
    $form.on('click', '[data-domain-remove]', function () {
        automaticDomain = false;
        $automatic.val('0');
    });

    updatePreview();
}

$(() => {
    $('[data-domain-editor]').each(function () {
        updateDomainRows($(this));
    });
    $('[data-tenant-form]').each(function () {
        initializeTenantForm(this);
    });
});

$(document).on('click', '[data-domain-add]', function () {
    const $editor = $(this).closest('[data-domain-editor]');

    if ($editor.find('[data-domain-row]').length >= 20) {
        return;
    }

    const template = $editor.find('[data-domain-template]')[0];
    $editor.find('[data-domain-list]').append(template.content.cloneNode(true));
    updateDomainRows($editor);
    $editor.find('[data-domain-row]').last().find('input').trigger('focus');
});

$(document).on('click', '[data-domain-remove]', function () {
    const $editor = $(this).closest('[data-domain-editor]');

    if ($editor.find('[data-domain-row]').length <= 1) {
        return;
    }

    const index = $(this).closest('[data-domain-row]').index();
    $(this).closest('[data-domain-row]').remove();
    updateDomainRows($editor);
    const $rows = $editor.find('[data-domain-row]');
    $rows.eq(Math.min(index, $rows.length - 1)).find('input').trigger('focus');
});

function initializePermissionEditor(editor) {
    const $editor = $(editor);
    const $rows = $editor.find('[data-permission-row]');
    const $items = $editor.find('[data-permission-item]');
    const $area = $editor.find('[data-permission-area]');
    const $group = $editor.find('[data-permission-group]');
    const $search = $editor.find('[data-permission-search]');
    const $recordSelect = $editor.find('[data-permission-record-select]');
    const originalRecord = $recordSelect.val();
    const $unrestricted = $editor.find('[data-permission-unrestricted]');
    const initialUnrestricted = $editor.attr('data-permission-unrestricted-initial') === '1';
    const readOnly = $editor.attr('data-permission-readonly') === '1' || !$items.is(':enabled');
    const total = Number($editor.attr('data-permission-total') ?? $items.length);
    const initialValue = $editor.attr('data-permission-initial');
    const initialSelected = initialValue === undefined
        ? selectedIds()
        : new Set(initialValue.split(',').filter(id => id !== ''));
    let dirty = false;

    function selectedIds() {
        return new Set($items.filter(':checked').map((_, item) => item.value).get());
    }

    function visibleItems() {
        return $rows.filter((_, row) => !row.hidden).find('[data-permission-item]').filter((_, item) => {
            const option = item.closest('.app-permission-option');
            return !option || !option.hidden;
        });
    }

    function matchingItems($visible, attribute, key) {
        return $visible.filter((_, item) => item.getAttribute(attribute) === key);
    }

    function updateToggle(toggle, $matching) {
        const selected = $matching.filter(':checked').length;
        const checked = $matching.length > 0 && selected === $matching.length;
        const partial = selected > 0 && selected < $matching.length;

        $(toggle).prop({ checked, indeterminate: partial, disabled: readOnly || !$matching.is(':enabled') })
            .attr('aria-checked', partial ? 'mixed' : String(checked));
    }

    function updateState(selectionChanged = false) {
        const $visible = visibleItems();
        const selected = selectedIds();

        if (selectionChanged && selected.size !== $items.length) {
            $unrestricted.prop('checked', false);
        }

        dirty = !readOnly && (selected.size !== initialSelected.size
            || [...selected].some(id => !initialSelected.has(id))
            || $unrestricted.is(':checked') !== initialUnrestricted);

        $editor.find('[data-permission-visible-toggle]').each((_, toggle) => updateToggle(toggle, $visible));
        $editor.find('[data-permission-group-toggle]').each((_, toggle) => {
            updateToggle(toggle, matchingItems($visible, 'data-group-key', toggle.getAttribute('data-group-key')));
        });
        $editor.find('[data-permission-area-toggle]').each((_, toggle) => {
            updateToggle(toggle, matchingItems($visible, 'data-area-key', toggle.getAttribute('data-area-key')));
        });

        $editor.find('[data-permission-count]').text(`${selected.size} of ${total} selected`);
        $editor.find('[data-permission-state]').text(readOnly ? 'Read-only access' : dirty ? 'Unsaved changes' : 'All changes saved')
            .toggleClass('is-dirty', dirty);
        $editor.find('[data-permission-save]').prop('disabled', readOnly || !dirty
            || submittingForms.has($editor.find('[data-permission-form]')[0]));
    }

    function filterRows() {
        const area = String($area.val() ?? '');
        const group = String($group.val() ?? '');
        const search = String($search.val() ?? '').trim().toLocaleLowerCase();
        let visibleRows = 0;

        $rows.each((_, row) => {
            const matchesScope = (!area || row.getAttribute('data-permission-area-key') === area)
                && (!group || row.getAttribute('data-permission-group-key') === group);
            const context = [
                row.querySelector('.app-permission-area span')?.textContent ?? '',
                row.querySelector('.app-permission-group span')?.textContent ?? '',
            ].join(' ');
            let matches = false;

            $(row).find('.app-permission-option').each((_, option) => {
                const text = `${context} ${option.getAttribute('title') ?? ''} ${option.textContent}`
                    .replace(/\s+/g, ' ').trim().toLocaleLowerCase();
                option.hidden = !matchesScope || (search !== '' && !text.includes(search));

                if (!option.hidden) {
                    matches = true;
                }
            });

            row.hidden = !matches;
            visibleRows += Number(matches);
        });

        // Filtering keeps every permission input enabled and available for submission.
        $editor.find('[data-permission-empty]').prop('hidden', visibleRows !== 0);
        updateState();
    }

    function updateGroupOptions() {
        const area = String($area.val() ?? '');
        const availableGroups = new Set($rows.filter((_, row) => !area
            || row.getAttribute('data-permission-area-key') === area)
            .map((_, row) => row.getAttribute('data-permission-group-key')).get());

        $group.find('option').each((_, option) => {
            const optionArea = option.getAttribute('data-area-key');
            const matchesArea = !area || !optionArea || optionArea === area;
            option.hidden = option.value !== '' && (!availableGroups.has(option.value) || !matchesArea);
        });
    }

    $search.on('input', filterRows);
    $search.on('keydown', event => {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });
    $group.on('change', filterRows);
    $area.on('change', () => {
        $group.val('');
        updateGroupOptions();
        filterRows();
    });
    $unrestricted.on('change', function () {
        if (readOnly) {
            return;
        }

        if (this.checked) {
            $items.filter(':enabled').prop('checked', true);
        }

        updateState();
    });
    $editor.on('change', '[data-permission-item]', () => updateState(true));
    $editor.on('change', '[data-permission-visible-toggle], [data-permission-group-toggle], [data-permission-area-toggle]', function () {
        if (readOnly) {
            return;
        }

        let $matching = visibleItems();

        if (this.hasAttribute('data-permission-group-toggle')) {
            $matching = matchingItems($matching, 'data-group-key', this.getAttribute('data-group-key'));
        } else if (this.hasAttribute('data-permission-area-toggle')) {
            $matching = matchingItems($matching, 'data-area-key', this.getAttribute('data-area-key'));
        }

        $matching.filter(':enabled').prop('checked', this.checked);
        updateState(true);
    });

    $recordSelect.on('change', function () {
        this.form?.requestSubmit();
    });
    $editor.on('submit', '[data-permission-picker]', function (event) {
        if (dirty && !window.confirm('Discard unsaved permission changes?')) {
            event.preventDefault();
            $recordSelect.val(originalRecord);
        }
    });
    $editor.on('submit', '[data-permission-form]', event => {
        if (readOnly) {
            event.preventDefault();
        }
    });
    window.addEventListener('beforeunload', event => {
        const formPending = $editor.find('form').toArray().some(form => submittingForms.has(form));

        if (dirty && !formPending) {
            event.preventDefault();
            event.returnValue = '';
        }
    });
    window.addEventListener('pageshow', () => updateState());

    updateGroupOptions();
    filterRows();
}

$(() => {
    $('[data-permission-editor]').each((_, editor) => initializePermissionEditor(editor));
});

function parseClinicalDecimal(value, maxWholeDigits = 10) {
    const match = String(value ?? '').trim().match(/^([0-9]+)(?:\.([0-9]{1,2}))?$/);
    if (!match || match[1].length > maxWholeDigits) {
        return null;
    }

    return BigInt(match[1]) * 100n + BigInt((match[2] ?? '').padEnd(2, '0'));
}

function parseClinicalPercent(value) {
    const percent = parseClinicalDecimal(value, 3);

    return percent !== null && percent <= 10000n ? percent : null;
}

function formatClinicalDecimal(value) {
    return (value / 100n).toString() + '.' + (value % 100n).toString().padStart(2, '0');
}

function roundClinicalRatio(numerator, denominator) {
    return denominator === 0n ? 0n : (numerator + denominator / 2n) / denominator;
}

function initializeRefundForm(form) {
    const $form = $(form);
    const invoice = form.querySelector('[data-refund-invoice]');
    const service = form.elements.namedItem('service');
    const $items = $form.find('[data-refund-items]');
    const $itemsSection = $form.find('[data-refund-items-section]');
    const $error = $form.find('[data-refund-error]');
    const $status = $form.find('[data-refund-status]');
    let preview = null;
    let request = null;
    let requestVersion = 0;
    let selectedInvoice = '';

    function showAmounts(credit, dueAdjustment, cash) {
        $form.find('[data-refund-credit]').val(formatClinicalDecimal(credit));
        $form.find('[data-refund-due-adjustment]').val(formatClinicalDecimal(dueAdjustment));
        $form.find('[data-refund-amount]').val(formatClinicalDecimal(cash));
    }

    if (form.dataset.recordExists === '1') {
        const cash = parseClinicalDecimal(form.dataset.fixedAmount, 18) ?? 0n;
        const dueAdjustment = parseClinicalDecimal(form.dataset.fixedDueAdjustment, 18) ?? 0n;
        showAmounts(cash + dueAdjustment, dueAdjustment, cash);

        return;
    }

    function showError(message) {
        $error.text(message).prop('hidden', !message);
        invoice.setCustomValidity(message);
    }

    function updateAmounts() {
        if (!preview) {
            showAmounts(0n, 0n, 0n);

            return;
        }

        let credit = parseClinicalDecimal(preview.remaining_amount, 18) ?? 0n;
        if (preview.service === 'investigation') {
            const choices = $items.find('[data-refund-item]').toArray();
            const checked = choices.filter(choice => choice.checked);
            choices.forEach(choice => choice.setCustomValidity(''));
            choices[0]?.setCustomValidity(checked.length ? '' : 'Select at least one investigation item.');
            credit = checked.reduce((total, choice) => total + (parseClinicalDecimal(choice.dataset.amount, 18) ?? 0n), 0n);
        }

        const remainingDue = parseClinicalDecimal(preview.remaining_due, 18) ?? 0n;
        const remainingPaid = parseClinicalDecimal(preview.remaining_paid, 18) ?? 0n;
        const remainingAmount = parseClinicalDecimal(preview.remaining_amount, 18) ?? 0n;
        const dueAdjustment = credit < remainingDue ? credit : remainingDue;
        const cash = credit - dueAdjustment;
        showAmounts(credit, dueAdjustment, cash);
        showError(credit > remainingAmount || cash > remainingPaid
            ? 'The available refund value has changed. Select the invoice again.' : '');
    }

    function renderPreview(data, selectedIds = null) {
        preview = data;
        $items.empty();
        const investigation = data.service === 'investigation';
        $itemsSection.prop('hidden', !investigation);

        if (investigation) {
            const items = Array.isArray(data.items) ? data.items : [];
            const defaults = Array.isArray(data.selected_item_ids) && data.selected_item_ids.length
                ? data.selected_item_ids : items.map(item => item.id);
            const selected = new Set((selectedIds ?? defaults).map(String));
            for (const item of items) {
                const id = 'refund_item_' + item.id;
                const $choice = $('<input>', {
                    class: 'form-check-input', id, name: 'item_ids[]', type: 'checkbox',
                    value: item.id, checked: selected.has(String(item.id)),
                    'data-refund-item': '', 'data-amount': item.amount,
                    'aria-describedby': 'refund_items-help item_ids-error',
                });
                $('<tr>')
                    .append($('<td>').append($choice))
                    .append($('<th>', { class: 'fw-normal text-break', scope: 'row' })
                        .append($('<label>', { class: 'mb-0', for: id }).text(item.name)))
                    .append($('<td>').text(item.room || '-'))
                    .append($('<td>').text(item.eye_label || '-'))
                    .append($('<td>', { class: 'text-end text-nowrap' }).text(item.amount))
                    .appendTo($items);
            }

            if (!items.length) {
                showAmounts(0n, 0n, 0n);
                showError('No investigation items remain available for refund.');

                return;
            }
        }

        updateAmounts();
    }

    function loadPreview() {
        const encodedInvoice = invoice.value;
        const selectedService = service?.value ?? '';
        if (encodedInvoice && encodedInvoice === selectedInvoice) {
            return;
        }

        const version = ++requestVersion;
        request?.abort();
        selectedInvoice = encodedInvoice;
        preview = null;
        $items.empty();
        $itemsSection.prop('hidden', true);
        $status.prop('hidden', true).text('');
        $form.attr('aria-busy', 'false');
        showError('');
        showAmounts(0n, 0n, 0n);
        if (!encodedInvoice) {
            return;
        }

        $status.text('Loading invoice details…').prop('hidden', false);
        $form.attr('aria-busy', 'true');
        invoice.setCustomValidity('Wait for the invoice details to finish loading.');
        const currentSelection = () => version === requestVersion
            && invoice.value === encodedInvoice && (service?.value ?? '') === selectedService;
        request = $.ajax({
            url: form.dataset.previewUrl,
            data: { invoice: encodedInvoice },
            dataType: 'json',
            headers: { Accept: 'application/json' },
            timeout: 15000,
        }).done(data => {
            if (currentSelection()) {
                renderPreview(data);
            }
        }).fail((xhr, status) => {
            if (currentSelection() && status !== 'abort') {
                const message = xhr.status < 500
                    ? xhr.responseJSON?.errors?.invoice?.[0] ?? xhr.responseJSON?.message : null;
                showError(message || 'Invoice details could not be loaded. Select the invoice again to retry.');
                selectedInvoice = '';
            }
        }).always(() => {
            if (currentSelection()) {
                request = null;
                $status.prop('hidden', true).text('');
                $form.attr('aria-busy', 'false');
            }
        });
    }

    $items.on('change', '[data-refund-item]', updateAmounts);
    $(invoice).on('change', loadPreview);
    try {
        const initialPreview = JSON.parse(form.dataset.initialPreview ?? 'null');
        const selectedIds = JSON.parse(form.dataset.selectedItemIds ?? '[]');
        if (invoice.value && initialPreview) {
            selectedInvoice = invoice.value;
            renderPreview(initialPreview, Array.isArray(selectedIds) ? selectedIds : []);

            return;
        }
    } catch {
        // The selected invoice can still load its current details from the server.
    }

    loadPreview();
}

function initializeDueCollectionForm(form) {
    const $form = $(form);
    const invoice = form.querySelector('[data-due-collection-invoice]');
    const service = form.elements.namedItem('service');
    const amount = form.querySelector('[data-due-collection-amount]');
    const $currentDue = $form.find('[data-due-collection-current-due]');
    const $error = $form.find('[data-due-collection-error]');
    const $amountError = $form.find('[data-due-collection-amount-error]');
    const $status = $form.find('[data-due-collection-status]');
    let amountServerInvalid = amount.getAttribute('aria-invalid') === 'true';
    let invoiceServerInvalid = invoice.getAttribute('aria-invalid') === 'true';
    let availableDue = null;
    let selectedInvoice = '';
    let request = null;
    let requestVersion = 0;

    function updateAmountValidity() {
        const value = amount.value.trim();
        const received = parseClinicalDecimal(value);
        let message = '';
        if (value && (received === null || received < 1n)) {
            message = 'Enter an amount of at least 0.01 with up to two decimal places.';
        } else if (value && availableDue === null) {
            message = 'Wait until the invoice due is available before saving.';
        } else if (received !== null && availableDue !== null && received > availableDue) {
            message = 'The amount cannot exceed the current due of ' + formatClinicalDecimal(availableDue) + '.';
        }

        amount.setCustomValidity(message);
        $(amount).attr('aria-invalid', String(amountServerInvalid || Boolean(message)))
            .toggleClass('is-invalid', amountServerInvalid || Boolean(message));
        $amountError.text(message);
    }

    function showError(message) {
        $error.text(message).prop('hidden', !message);
        invoice.setCustomValidity(message);
        const invalid = invoiceServerInvalid || Boolean(message);
        $(invoice).attr('aria-invalid', String(invalid)).toggleClass('is-invalid', invalid)
            .next('.select2-container').find('.select2-selection').attr('aria-invalid', String(invalid));
    }

    function applyPreview(preview) {
        availableDue = parseClinicalDecimal(preview.available_due, 18);
        if (availableDue === null) {
            showError('The invoice due is unavailable. Reload the page to try again.');
        } else {
            $currentDue.val(formatClinicalDecimal(availableDue));
            amount.dataset.maxAmount = formatClinicalDecimal(availableDue);
            showError('');
        }
        updateAmountValidity();
    }

    function loadPreview(clearAmount = false) {
        const encodedInvoice = invoice.value;
        const selectedService = service?.value ?? '';
        if (encodedInvoice && encodedInvoice === selectedInvoice) {
            return;
        }

        const version = ++requestVersion;
        request?.abort();
        selectedInvoice = encodedInvoice;
        availableDue = null;
        delete amount.dataset.maxAmount;
        if (clearAmount) {
            amount.value = '';
            amountServerInvalid = false;
            $form.find('#' + amount.id + '-error').empty();
        }
        $currentDue.val('0.00');
        $status.prop('hidden', true).text('');
        $form.attr('aria-busy', 'false');
        showError('');
        updateAmountValidity();
        if (!encodedInvoice) {
            return;
        }

        $status.text('Loading invoice due…').prop('hidden', false);
        $form.attr('aria-busy', 'true');
        invoice.setCustomValidity('Wait for the invoice due to finish loading.');
        const currentSelection = () => version === requestVersion
            && invoice.value === encodedInvoice && (service?.value ?? '') === selectedService;
        request = $.ajax({
            url: form.dataset.previewUrl,
            data: { invoice: encodedInvoice },
            dataType: 'json',
            headers: { Accept: 'application/json' },
            timeout: 15000,
        }).done(preview => {
            if (currentSelection()) {
                applyPreview(preview);
            }
        }).fail((xhr, status) => {
            if (currentSelection() && status !== 'abort') {
                const message = xhr.status < 500
                    ? xhr.responseJSON?.errors?.invoice?.[0] ?? xhr.responseJSON?.message : null;
                showError(message || 'The invoice due could not be loaded. Select the invoice again to retry.');
                selectedInvoice = '';
            }
        }).always(() => {
            if (currentSelection()) {
                request = null;
                $status.prop('hidden', true).text('');
                $form.attr('aria-busy', 'false');
            }
        });
    }

    $(amount).on('input change', () => {
        amountServerInvalid = false;
        $form.find('#' + amount.id + '-error').empty();
        updateAmountValidity();
    });
    try {
        const initialPreview = JSON.parse(form.dataset.initialPreview ?? 'null');
        if (invoice.value && initialPreview) {
            selectedInvoice = invoice.value;
            applyPreview(initialPreview);
        }
    } catch {
        // A new collection can still load the selected invoice from the server.
    }

    if (form.dataset.recordExists === '1') {
        if (availableDue === null) {
            showError('The invoice due is unavailable. Reload the page before saving.');
            updateAmountValidity();
        }

        return;
    }

    $(invoice).on('change', () => {
        invoiceServerInvalid = false;
        $form.find('#' + invoice.id + '-error').empty();
        showError($error.text());
        loadPreview(true);
    });
    if (availableDue === null) {
        loadPreview();
    }
}

$(document).on('select2:selecting', '[data-refund-invoice], [data-due-collection-invoice]', function (event) {
    const service = this.form?.elements.namedItem('service')?.value;
    const invoice = event.params?.args?.data;
    if (service !== 'all' && invoice?.service !== service) {
        event.preventDefault();
    }
});

function selectedClinicalItems(select) {
    if (!select) {
        return [];
    }
    const selectedData = $(select).hasClass('select2-hidden-accessible') ? $(select).select2('data') : [];
    const dataById = new Map(selectedData.map(item => [String(item.id), item]));

    return Array.from(select.selectedOptions).filter(option => option.value).map(option => {
        const item = dataById.get(option.value) ?? {};

        return {
            id: option.value,
            name: item.name ?? option.textContent,
            categoryName: item.category?.name ?? option.dataset.categoryName ?? '-',
            consultationFee: item.consultation_fee ?? option.dataset.consultationFee,
            followUpFee: item.follow_up_fee ?? option.dataset.followUpFee,
            singleRate: item.single_rate ?? option.dataset.singleRate,
            bothRate: item.both_rate ?? option.dataset.bothRate,
            entryRate: option.dataset.entryRate,
            entryEye: option.dataset.entryEye,
        };
    });
}

function clinicalSourceState(form, kind, select) {
    return {
        ids: Array.from(select?.selectedOptions ?? []).map(option => option.value).filter(Boolean).sort(),
        patientType: kind === 'consultation' ? (form.elements.namedItem('patient_type')?.value ?? '') : '',
        eyeType: kind === 'consultation' ? '' : (form.elements.namedItem('eye_type')?.value ?? ''),
    };
}

function originalClinicalSourceState(form, current) {
    let ids = current.ids;
    try {
        const originalIds = JSON.parse(form.dataset.originalSourceIds ?? 'null');
        if (Array.isArray(originalIds)) {
            ids = originalIds.filter(id => id !== null && id !== '').map(String).sort();
        }
    } catch {
        // Missing snapshot metadata keeps the current selection as the edit baseline.
    }

    return {
        ids,
        patientType: form.dataset.clinicalEntry === 'consultation' ? (form.dataset.originalPatientType ?? current.patientType) : '',
        eyeType: form.dataset.originalEyeType ?? current.eyeType,
    };
}

function initializeClinicalEntry(form) {
    const kind = form.dataset.clinicalEntry;
    if (!['consultation', 'investigation', 'operation'].includes(kind)) {
        return;
    }

    const $form = $(form);
    const source = kind === 'consultation' ? form.querySelector('select[name="doctor_id"]')
        : kind === 'investigation' ? form.querySelector('[data-investigation-items]')
            : form.querySelector('select[name="operation_id"]');
    const doctorSelect = form.querySelector('select[name="doctor_id"]');
    const investigationPicker = form.querySelector('[data-investigation-picker]');
    const discountPercent = form.querySelector('[name="discount_percent"]');
    const discountAmount = form.querySelector('[name="discount_amount"]');
    const discountModeField = form.querySelector('[name="discount_mode"]');
    const extraDiscountPercent = form.querySelector('[name="extra_discount_percent"]');
    const paidAmount = form.querySelector('[name="paid_amount"]');
    let discountMode = kind === 'operation' || discountModeField?.value === 'amount' ? 'amount' : 'percent';
    let items = selectedClinicalItems(source);
    const investigationRows = new Map();
    const original = originalClinicalSourceState(form, clinicalSourceState(form, kind, source));
    const originalIds = new Set(original.ids);
    let presetEyes = {};
    let originalInvestigationEyes = {};
    let originalInvestigationRates = {};
    if (kind === 'investigation') {
        try {
            presetEyes = JSON.parse(form.dataset.investigationEyes ?? '{}') ?? {};
            originalInvestigationEyes = JSON.parse(form.dataset.originalInvestigationEyes ?? '{}') ?? {};
            originalInvestigationRates = JSON.parse(form.dataset.originalInvestigationRates ?? '{}') ?? {};
        } catch {
            presetEyes = {};
            originalInvestigationEyes = {};
            originalInvestigationRates = {};
        }
    }

    function syncInvestigationRows(initial = false) {
        const selected = selectedClinicalItems(source);
        const selectedById = new Map(selected.map(item => [item.id, item]));
        for (const id of investigationRows.keys()) {
            if (!selectedById.has(id)) {
                investigationRows.delete(id);
            }
        }
        for (const item of selected) {
            if (!investigationRows.has(item.id)) {
                const defaultEye = form.elements.namedItem('eye_type')?.value || 'left';
                const eye = initial ? (presetEyes[item.id] ?? item.entryEye ?? originalInvestigationEyes[item.id] ?? defaultEye) : defaultEye;
                investigationRows.set(item.id, { eye, editing: false });
            }
        }
        items = Array.from(investigationRows.keys()).map(id => selectedById.get(id));
        for (const item of items) {
            const option = Array.from(source.options).find(option => option.value === item.id);
            if (option) {
                source.append(option);
            }
        }
    }

    function sourceState() {
        const current = clinicalSourceState(form, kind, source);
        if (kind === 'investigation') {
            current.eyeType = '';
            current.investigationEyes = current.ids.map(id => [id, investigationRows.get(id)?.eye]);
        }

        return current;
    }

    if (kind === 'investigation') {
        syncInvestigationRows(true);
        original.eyeType = '';
        original.investigationEyes = original.ids.map(id => {
            const item = items.find(item => item.id === id);

            return [id, originalInvestigationEyes[id] || item?.entryEye || form.dataset.originalEyeType || 'left'];
        });
    }
    let state = sourceState();
    let stateKey = JSON.stringify(state);
    let preserveSnapshot = form.dataset.recordExists === '1' && stateKey === JSON.stringify(original);

    function itemRate(item) {
        const eye = kind === 'investigation' ? investigationRows.get(item.id)?.eye : state.eyeType;
        const savedEye = originalInvestigationEyes[item.id] || item.entryEye || form.dataset.originalEyeType || 'left';
        if (kind === 'investigation' && originalIds.has(item.id) && eye === savedEye) {
            const savedRate = parseClinicalDecimal(originalInvestigationRates[item.id] ?? item.entryRate, 18);
            if (savedRate !== null) {
                return savedRate;
            }
        }

        if (!['left', 'right', 'both'].includes(eye)) {
            return null;
        }

        return parseClinicalDecimal(eye === 'both' ? item.bothRate : item.singleRate);
    }

    function currentAmount() {
        if (kind === 'consultation') {
            const doctor = items[0];
            if (!doctor || !['new', 'follow_up'].includes(state.patientType)) {
                return 0n;
            }

            return parseClinicalDecimal(state.patientType === 'follow_up' ? doctor.followUpFee : doctor.consultationFee) ?? 0n;
        }
        let total = 0n;
        for (const item of items) {
            const rate = itemRate(item);
            if (rate === null) {
                return 0n;
            }
            total += rate;
        }

        return total;
    }

    // Editing unrelated fields keeps the saved price until its source actually changes.
    let amount = preserveSnapshot ? (parseClinicalDecimal(form.dataset.initialAmount, 18) ?? 0n) : currentAmount();

    function updateInvestigationPicker() {
        const hasDoctor = Boolean(doctorSelect?.value);
        investigationPicker.disabled = !hasDoctor || items.length >= 100;
        investigationPicker.setCustomValidity(items.length === 0 ? 'Select at least one investigation.' : '');
        $form.find('[data-investigation-doctor-help]').prop('hidden', hasDoctor);
        $form.find('[data-investigation-picker-help]').text(items.length >= 100
            ? 'The maximum is 100 tests. Remove a test to add another.'
            : 'Select one test at a time. Each selection is added below.');
        renderInvestigationItems();
    }

    function renderInvestigationItems() {
        const $body = $form.find('[data-clinical-investigation-items]');
        if (!$body.length) {
            return;
        }
        $body.empty();
        if (items.length === 0) {
            $body.append($('<tr>').append($('<td>', { colspan: 6, class: 'small text-body-secondary text-center py-3' }).text('No investigations selected.')));
            return;
        }

        const eyeLabels = { left: 'Left', right: 'Right', both: 'Both' };
        const doctorName = doctorSelect?.value ? doctorSelect.selectedOptions[0]?.textContent.trim() : '-';
        for (const item of items) {
            const row = investigationRows.get(item.id);
            const $eye = $('<select>', {
                class: 'form-select form-select-sm',
                'data-investigation-eye': item.id,
                'aria-label': 'Eye for ' + item.name,
            }).prop('hidden', !row.editing);
            for (const [value, label] of Object.entries(eyeLabels)) {
                $eye.append(new Option(label, value, false, value === row.eye));
            }
            const $eyeCell = $('<td>', { class: 'small' })
                .append($('<span>', { 'data-investigation-eye-label': '' }).text(eyeLabels[row.eye] ?? '-').prop('hidden', row.editing))
                .append($eye)
                .append($('<input>', { type: 'hidden', name: 'investigation_eyes[' + item.id + ']', value: row.eye, 'data-investigation-eye-value': '' }));
            const $edit = $('<button>', {
                type: 'button',
                class: 'btn btn-outline-primary btn-sm',
                'data-edit-investigation': item.id,
                'aria-label': (row.editing ? 'Finish editing ' : 'Edit eye for ') + item.name,
                title: row.editing ? 'Done' : 'Edit eye',
            }).append($('<i>', { class: row.editing ? 'fa-solid fa-check' : 'fa-solid fa-pen-to-square', 'aria-hidden': 'true' }));
            const $remove = $('<button>', {
                type: 'button',
                class: 'btn btn-outline-danger btn-sm',
                'data-remove-investigation': item.id,
                'aria-label': 'Remove ' + item.name,
                title: 'Remove',
            }).append($('<i>', { class: 'fa-solid fa-xmark', 'aria-hidden': 'true' }));
            $body.append($('<tr>')
                .append($('<td>', { class: 'small text-break' }).text(doctorName))
                .append($('<td>', { class: 'small text-break' }).text(item.name))
                .append($('<td>', { class: 'small text-break' }).text(item.categoryName))
                .append($eyeCell)
                .append($('<td>', { class: 'text-end text-nowrap', 'data-investigation-row-rate': '' }).text(formatClinicalDecimal(itemRate(item) ?? 0n)))
                .append($('<td>', { class: 'text-end text-nowrap' }).append($edit, ' ', $remove)));
        }
    }

    function renderPayment() {
        $form.find('[data-clinical-amount]').val(formatClinicalDecimal(amount));
        $form.find('[data-clinical-payable], [data-clinical-due]').val('0.00');
        let discount;

        if (discountMode === 'percent') {
            const percent = parseClinicalPercent(discountPercent?.value);
            if (percent === null) {
                return;
            }
            discount = roundClinicalRatio(amount * percent, 10000n);
            if (discountAmount) {
                discountAmount.value = formatClinicalDecimal(discount);
            }
        } else {
            discount = parseClinicalDecimal(discountAmount?.value);
            if (discount === null) {
                return;
            }
            if (discountPercent) {
                discountPercent.value = formatClinicalDecimal(roundClinicalRatio(discount * 10000n, amount));
            }
        }
        if (discount > amount) {
            return;
        }

        let payable = amount - discount;
        if (kind === 'investigation') {
            const extraPercent = parseClinicalPercent(extraDiscountPercent?.value);
            if (extraPercent === null) {
                return;
            }
            payable -= roundClinicalRatio(payable * extraPercent, 10000n);
        }
        $form.find('[data-clinical-payable]').val(formatClinicalDecimal(payable));
        const paid = parseClinicalDecimal(paidAmount?.value);
        if (paid !== null && paid <= payable) {
            $form.find('[data-clinical-due]').val(formatClinicalDecimal(payable - paid));
        }
    }

    $form.on('change', '[name="doctor_id"], [name="patient_type"], [name="eye_type"], [name="investigation_ids[]"], [name="operation_id"]', function () {
        if (kind === 'investigation') {
            if (this.name === 'doctor_id') {
                updateInvestigationPicker();
                return;
            }
            if (this.name === 'eye_type') {
                return;
            }
            const currentIds = new Set(items.map(item => item.id));
            if (!doctorSelect?.value && Array.from(source.selectedOptions).some(option => !currentIds.has(option.value))) {
                $(source).val(items.map(item => item.id)).trigger('change.select2');
                return;
            }
            syncInvestigationRows();
        }
        const nextState = sourceState();
        const nextKey = JSON.stringify(nextState);
        if (nextKey === stateKey) {
            return;
        }
        state = nextState;
        stateKey = nextKey;
        preserveSnapshot = form.dataset.recordExists === '1' && stateKey === JSON.stringify(original);
        if (kind !== 'investigation') {
            items = selectedClinicalItems(source);
        }
        amount = preserveSnapshot ? (parseClinicalDecimal(form.dataset.initialAmount, 18) ?? 0n) : currentAmount();
        if (kind === 'investigation') {
            updateInvestigationPicker();
        }
        renderPayment();
    });
    $form.on('input change', '[name="discount_percent"], [name="discount_amount"], [name="extra_discount_percent"], [name="paid_amount"]', function () {
        if (this.name === 'discount_percent' || this.name === 'discount_amount') {
            discountMode = this.name === 'discount_percent' ? 'percent' : 'amount';
            if (discountModeField) {
                discountModeField.value = discountMode;
            }
        }
        renderPayment();
    });
    $form.on('click', '[data-remove-investigation]', function () {
        const remaining = Array.from(source.selectedOptions).map(option => option.value)
            .filter(id => id !== this.dataset.removeInvestigation);
        $(source).val(remaining).trigger('change');
        const nextRemove = $form.find('[data-remove-investigation]')[0];
        if (nextRemove) {
            nextRemove.focus();
        } else {
            $(doctorSelect?.value ? investigationPicker : doctorSelect).next('.select2-container').find('.select2-selection').trigger('focus');
        }
    });
    $form.on('click', '[data-edit-investigation]', function () {
        const row = investigationRows.get(this.dataset.editInvestigation);
        if (row) {
            row.editing = !row.editing;
            renderInvestigationItems();
            if (row.editing) {
                $form.find('[data-investigation-eye]').filter((_, select) => select.dataset.investigationEye === this.dataset.editInvestigation).trigger('focus');
            } else {
                $form.find('[data-edit-investigation]').filter((_, button) => button.dataset.editInvestigation === this.dataset.editInvestigation).trigger('focus');
            }
        }
    });
    $form.on('change', '[data-investigation-eye]', function () {
        const id = this.dataset.investigationEye;
        const row = investigationRows.get(id);
        if (!row) {
            return;
        }
        row.eye = this.value;
        state = sourceState();
        stateKey = JSON.stringify(state);
        preserveSnapshot = form.dataset.recordExists === '1' && stateKey === JSON.stringify(original);
        amount = preserveSnapshot ? (parseClinicalDecimal(form.dataset.initialAmount, 18) ?? 0n) : currentAmount();
        const $row = $(this).closest('tr');
        $row.find('[data-investigation-eye-value]').val(row.eye);
        $row.find('[data-investigation-row-rate]').text(formatClinicalDecimal(itemRate(items.find(item => item.id === id)) ?? 0n));
        renderPayment();
    });

    if (kind === 'investigation') {
        const $picker = $(investigationPicker);
        $picker.on('select2:opening select2:selecting', event => {
            if (!doctorSelect?.value || items.length >= 100) {
                event.preventDefault();
                return;
            }
            const id = event.params?.args?.data?.id;
            if (event.type === 'select2:selecting' && items.some(item => item.id === String(id))) {
                event.preventDefault();
                $form.find('[data-investigation-picker-help]').text('This test is already in the list. Select another test.');
            }
        });
        $picker.on('select2:select', event => {
            const test = event.params?.data;
            const id = String(test?.id ?? '');
            if (!doctorSelect?.value || items.length >= 100 || !/^[1-9][0-9]*$/.test(id)
                || items.some(item => item.id === id)) {
                return;
            }

            let option = Array.from(source.options).find(option => option.value === id);
            if (!option) {
                option = new Option(test.name ?? test.text, id);
                source.append(option);
            }
            option.dataset.singleRate = test.single_rate ?? '';
            option.dataset.bothRate = test.both_rate ?? '';
            option.dataset.categoryName = test.category?.name ?? '-';
            option.selected = true;
            $(source).trigger('change');
            $picker.val(null).trigger('change.select2');

            window.setTimeout(() => {
                if (investigationPicker.isConnected && !investigationPicker.disabled && doctorSelect?.value) {
                    $picker.select2('open');
                }
            }, 0);
        });
        updateInvestigationPicker();
    }
    renderPayment();
}

function initializeInvoiceEditor(editor) {
    const $editor = $(editor);
    const $items = $editor.find('[data-invoice-items]');
    const template = $editor.find('template[data-invoice-template]')[0];
    let nextIndex = 0;

    if (!$items.length || !template) {
        return;
    }

    $items.find('[name]').each(function () {
        const match = this.name.match(/^items\[(\d+)\]/);
        const index = match ? Number(match[1]) : -1;

        if (Number.isSafeInteger(index)) {
            nextIndex = Math.max(nextIndex, index + 1);
        }
    });

    function updateControls() {
        const count = $items.children('[data-invoice-item]').length;
        $editor.find('[data-invoice-add]').prop('disabled', count >= 100);
        $items.find('[data-invoice-remove]').prop('disabled', count <= 1);
    }

    $editor.on('click', '[data-invoice-add]', () => {
        if ($items.children('[data-invoice-item]').length >= 100) {
            return;
        }

        const row = template.innerHTML.replaceAll('__INDEX__', String(nextIndex++));
        $items.append(row);
        updateControls();
        $items.children('[data-invoice-item]').last().find('input').first().trigger('focus');
    });

    $editor.on('click', '[data-invoice-remove]', function () {
        if ($items.children('[data-invoice-item]').length <= 1) {
            return;
        }

        $(this).closest('[data-invoice-item]').remove();
        updateControls();
    });

    updateControls();
}

function initializePaymentProvider(select) {
    const $fields = $(select.form).find('[data-manual-payment-fields]');
    const requiredInputs = new Set($fields.find(':input[required]').toArray());

    function updateFields() {
        const manual = select.value === 'manual';
        const method = select.selectedOptions[0];
        $(select.form).find('[data-payment-extra-charge]').text(method?.dataset.extraCharge ?? '0.00');
        $(select.form).find('[data-payment-total]').text(method?.dataset.total ?? '0.00');
        $fields.prop('hidden', !manual);
        $fields.find(':input').each(function () {
            this.disabled = !manual;
            this.required = manual && requiredInputs.has(this);
        });
    }

    $(select).on('change', updateFields);
    updateFields();
}

$(document).on('click', '[data-print]', () => window.print());

$(() => {
    $('[data-datepicker]').each((_, element) => flatpickr(element, {
        wrap: true,
        dateFormat: 'Y-m-d',
        allowInput: true,
        disableMobile: true,
    }));
    $('[data-timepicker]').each((_, element) => flatpickr(element, {
        wrap: true,
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        altInput: true,
        altFormat: 'G:i K',
        time_24hr: false,
        allowInput: true,
        disableMobile: true,
        onReady(_, __, picker) {
            const { input, altInput } = picker;
            input.before(altInput);
            altInput.id = input.id;
            input.id += '_value';
            altInput.autocomplete = 'off';
            for (const attribute of ['aria-invalid', 'aria-describedby']) {
                const value = input.getAttribute(attribute);
                if (value !== null) {
                    altInput.setAttribute(attribute, value);
                }
            }
        },
    }));
    $('[data-clinical-entry]').each((_, form) => initializeClinicalEntry(form));
    $('[data-refund-form]').each((_, form) => initializeRefundForm(form));
    $('[data-due-collection-form]').each((_, form) => initializeDueCollectionForm(form));
    $('[data-invoice-editor]').each((_, editor) => initializeInvoiceEditor(editor));
    $('[data-payment-provider]').each((_, select) => initializePaymentProvider(select));
});
