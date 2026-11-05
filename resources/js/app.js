import $ from './jquery';
import 'bootstrap';
import 'flatpickr';
import attachSelect2 from 'select2';
import toastr from 'toastr';
import { formatAmount, roundRatio } from './money';
import './pharmacy';
import './prescription';

attachSelect2(window, $);

toastr.options = {
    toastClass: 'app-toast',
    positionClass: 'toast-top-right',
    escapeHtml: true,
    closeButton: true,
    closeHtml: '<button type="button" aria-label="Close notification">&times;</button>',
    progressBar: true,
    preventDuplicates: true,
    newestOnTop: true,
    timeOut: 5000,
    extendedTimeOut: 1500,
    showDuration: 150,
    hideDuration: 150,
};

window.showToast = function (message, type = 'info') {
    const text = String(message ?? '').trim();
    if (!text) return;
    const toastType = ['success', 'error', 'warning', 'info'].includes(type) ? type : 'info';

    return toastr[toastType](text);
};

window.initAjaxSelects = function (root = document) {
    $(root).find('select[data-ajax-select]').addBack('select[data-ajax-select]').each(function () {
        const $select = $(this);
        if ($select.hasClass('select2-hidden-accessible')) {
            return;
        }

        const valueField = $select.attr('data-value-field') || 'id';
        const labelField = $select.attr('data-label-field') || 'name';
        const parentId = $select.attr('data-parent-select');
        const $parent = parentId ? $('#' + $.escapeSelector(parentId)) : $();
        const parentField = $select.attr('data-parent-field');
        const $modal = $select.closest('.modal');
        $select.select2({
            width: '100%',
            dropdownParent: $modal.length ? $modal : $('body'),
            placeholder: $select.attr('data-placeholder') || 'Select',
            allowClear: $select.attr('data-allow-clear') !== 'false',
            minimumInputLength: Number($select.attr('data-minimum-input-length') || 0),
            maximumInputLength: 80,
            language: {
                noResults: () => $select.attr('data-no-results-text') || 'No results found',
            },
            ajax: {
                url: () => $select.attr('data-url'),
                dataType: 'json',
                delay: 250,
                timeout: 15000,
                data: params => {
                    const data = { search: params.term || '', page: params.page || 1 };
                    if ($parent.length && parentField) {
                        data[parentField] = $parent.val();
                    }
                    return data;
                },
                transport(params, success, failure) {
                    const parentValue = $parent.length && parentField
                        ? String(params.data?.[parentField] ?? '') : null;
                    const parentUnchanged = () => parentValue === null || $parent.val() === parentValue;
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

        if ($parent.length) {
            $select.prop('disabled', !$parent.val());
            $parent.on('change.ajaxSelect', () => {
                $select.select2('close');
                $select.find('option').not('[value=""]').remove();
                $select.val(null).prop('disabled', !$parent.val()).trigger('change');
            });
        }

        const $selection = $select.next('.select2-container').find('.select2-selection');
        for (const attribute of ['aria-invalid', 'aria-describedby']) {
            const value = $select.attr(attribute);
            if (value !== undefined) {
                $selection.attr(attribute, value);
            }
        }

        $select.on('invalid.ajaxSelect', event => {
            const firstInvalid = $select.closest('form').find(':invalid').first().get(0);
            if (firstInvalid === this) {
                event.preventDefault();
                $select.select2('open');
            }
        });
    });
};

const desktopNavigation = window.matchMedia('(min-width: 992px)');

function updateNavigationState() {
    const $sidebar = $('#appSidebar');

    if (!$sidebar.length) {
        return;
    }

    const expanded = desktopNavigation.matches
        ? !$('body').hasClass('sidebar-collapsed')
        : $sidebar.hasClass('show');

    $('[data-sidebar-toggle]').attr('aria-expanded', String(expanded))
        .attr('aria-label', expanded ? 'Collapse navigation' : 'Expand navigation');
}

function initializeNavigation() {
    try {
        $('body').toggleClass('sidebar-collapsed', localStorage.getItem('admin.sidebar.collapsed') === '1');
    } catch {
        // Navigation remains usable when browser storage is unavailable.
    }

    updateNavigationState();
    $(desktopNavigation).on('change.navigation', updateNavigationState);
    $('#appSidebar').on('shown.bs.offcanvas.navigation hidden.bs.offcanvas.navigation', updateNavigationState);

    if (!document.fullscreenEnabled) {
        $('[data-fullscreen-toggle]').remove();
    }
}

$(document).on('click.navigation', '[data-sidebar-toggle]', function () {
    const $sidebar = $('#appSidebar');
    const $body = $('body');

    if (!$sidebar.length) {
        return;
    }

    if (desktopNavigation.matches) {
        $body.toggleClass('sidebar-collapsed');

        try {
            localStorage.setItem('admin.sidebar.collapsed', $body.hasClass('sidebar-collapsed') ? '1' : '0');
        } catch {
            // The current page still remembers the chosen state.
        }

        updateNavigationState();
    } else {
        $sidebar.offcanvas('toggle');
    }
});

$(document).on('click.navigation', '#appSidebar a', function () {
    if (!desktopNavigation.matches) {
        $('#appSidebar').offcanvas('hide');
    }
});

$(document).on('click.navigation', '[data-fullscreen-toggle]', async function () {
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

$(document).on('fullscreenchange.navigation', () => {
    const active = Boolean(document.fullscreenElement);
    $('[data-fullscreen-toggle]').attr('aria-pressed', String(active))
        .attr('aria-label', active ? 'Exit fullscreen' : 'Enter fullscreen')
        .attr('title', active ? 'Exit fullscreen' : 'Enter fullscreen')
        .find('i').toggleClass('fa-expand', !active).toggleClass('fa-compress', active);
});

$(document).on('click.password', '[data-password-toggle]', function () {
    const $button = $(this);
    const inputId = $button.attr('aria-controls');
    const $input = inputId ? $('#' + $.escapeSelector(inputId)) : $();

    if (!$input.length) {
        return;
    }

    const visible = $input.prop('type') === 'password';
    $input.prop('type', visible ? 'text' : 'password');
    $button.attr('aria-pressed', String(visible))
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
        state.$buttons.prop('disabled', false);

        if (state.$button.length) {
            state.$button.removeAttr('aria-busy');

            if (state.$button.is('button')) {
                state.$button.html(state.content);
            } else {
                state.$button.val(state.value);
            }
        }
    }

    submittingForms.delete(form);
}

function startFormSubmission(form, submitter, event = null) {
    if (submittingForms.has(form)) {
        return false;
    }

    const $form = $(form);
    const confirmation = $form.attr('data-confirm');
    if (confirmation && !window.confirm(confirmation)) {
        return false;
    }

    const $buttons = $(form.elements).filter(':submit:enabled');
    const $button = $buttons.is(submitter) ? $(submitter) : $buttons.first();
    const state = { $buttons, $button, content: $button.html(), value: $button.val() };
    submittingForms.set(form, state);
    $form.attr('aria-busy', 'true');

    // Keep named button values in the native payload before changing or disabling controls.
    state.timer = window.setTimeout(() => {
        if (event?.isDefaultPrevented()) {
            resetFormSubmission(form);
            return;
        }

        state.loading = true;
        $buttons.prop('disabled', true);

        if ($button.length) {
            $button.attr('aria-busy', 'true');

            if ($button.is('button')) {
                const textClass = $button.text().trim() ? '' : ' class="visually-hidden"';
                $button.html(`<span class="d-inline-flex align-items-center gap-2 m-auto"><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span${textClass}>Loading...</span></span>`);
            } else {
                $button.val('Loading...');
            }
        }
    }, 0);

    return true;
}

$(document).on('submit.submission', 'form', function (event) {
    if (event.isDefaultPrevented() || $(this).is('[data-patient-create-form]')) {
        return;
    }

    if (!startFormSubmission(this, event.originalEvent?.submitter, event)) {
        event.preventDefault();
    }
});

$(window).on('pageshow.submission', () => {
    submittingForms.forEach((_, form) => resetFormSubmission(form));
});

function clearPatientModalErrors(form) {
    const $form = $(form);
    $form.find('[data-patient-field-error]').remove();
    $form.find('[data-patient-invalid]').each((_, field) => {
        const $field = $(field);
        const attributes = $field.data('patientErrorAttributes');
        $field.removeClass('is-invalid').removeAttr('data-patient-invalid');
        for (const attribute of ['aria-invalid', 'aria-describedby']) {
            $field.attr(attribute, attributes?.[attribute] ?? null);
            $field.next('.select2-container').find('.select2-selection').attr(attribute, attributes?.[attribute] ?? null);
        }
        $field.removeData('patientErrorAttributes');
    });
    $form.closest('.modal').find('[data-patient-modal-error]').prop('hidden', true).text('');
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

        const $field = $(form.elements).filter(function () {
            return this.name === name && $(this).is(':not([type="hidden"])')
                && ($(this).is(':visible') || $(this).hasClass('select2-hidden-accessible'));
        }).first();
        if (!$field.length) {
            messages.push(text);
            continue;
        }

        const errorId = ($field.attr('id') || 'patient-modal-' + name.replace(/[^a-z0-9_-]/gi, '-')) + '-ajax-error';
        $field.data('patientErrorAttributes', {
            'aria-invalid': $field.attr('aria-invalid'),
            'aria-describedby': $field.attr('aria-describedby'),
        }).addClass('is-invalid').attr({ 'data-patient-invalid': '', 'aria-invalid': 'true' });
        const describedBy = [$field.attr('aria-describedby'), errorId].filter(Boolean).join(' ');
        $field.attr('aria-describedby', describedBy);
        let $anchor = $field;
        if ($field.hasClass('select2-hidden-accessible')) {
            $anchor = $field.next('.select2-container');
            $anchor.find('.select2-selection').attr({ 'aria-invalid': 'true', 'aria-describedby': describedBy });
        } else if ($field.closest('.app-input-wrap').length) {
            $anchor = $field.closest('.app-input-wrap');
        }
        $('<div>', { id: errorId, class: 'invalid-feedback d-block', 'data-patient-field-error': '' }).text(text).insertAfter($anchor);
        if (!firstField && !$field.prop('disabled')) {
            firstField = $field.get(0);
        }
    }

    const $modal = $(form).closest('.modal');
    showPatientModalError($modal, ['Check the highlighted patient fields.', ...messages].join(' '));
    if (firstField) {
        if ($(firstField).hasClass('select2-hidden-accessible')) {
            $(firstField).select2('open');
        } else {
            $(firstField).trigger('focus');
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
    const $modal = $(modal);
    const form = $modal.find('[data-patient-create-form]').get(0);
    if (form) {
        resetFormSubmission(form);
        clearPatientModalErrors(form);
    }
    $modal.find('[data-patient-modal-content] select.select2-hidden-accessible').each(function () {
        $(this).select2('close').select2('destroy');
    });
    $modal.find('[data-patient-modal-content]').empty();
    $modal.find('[data-patient-modal-save]').prop('disabled', true);
}

$(document).on('click.patientModal', '[data-patient-target]', function (event) {
    event.preventDefault();
    const $button = $(this);
    const selectId = $button.attr('data-patient-target');
    const $select = selectId ? $('#' + $.escapeSelector(selectId)) : $();
    const $modal = $('#patient-create-modal');
    if (!$select.is('select[data-patient-select]') || !$modal.length) {
        return;
    }

    if ($button.attr('data-bs-toggle') !== 'modal') {
        $modal.modal('show', this);
    }
});

$(document).on('show.bs.modal.patientModal', '#patient-create-modal', function (event) {
    const modal = this;
    const $modal = $(modal);
    const selectId = $(event.relatedTarget).attr('data-patient-target');
    const $select = selectId ? $('#' + $.escapeSelector(selectId)) : $();
    if (!$select.is('select[data-patient-select]')) {
        event.preventDefault();
        return;
    }
    patientTargetSelect = $select.get(0);
    clearPatientModal(modal);
    const $content = $modal.find('[data-patient-modal-content]');
    $content.text('Loading patient form...');
    const request = $.ajax({
        url: $modal.attr('data-fields-url'),
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
        $modal.find('[data-patient-modal-save]').prop('disabled', false);
        $content.find(':input:visible').first().trigger('focus');
    }).fail((xhr, status) => {
        if (status !== 'abort') {
            $content.empty();
            window.showToast(patientRequestError(xhr.status), 'error');
        }
    }).always(() => {
        if (patientFieldsRequest === request) {
            patientFieldsRequest = null;
        }
    });
});

$(document).on('shown.bs.modal.patientModal', '#patient-create-modal', function () {
    $(this).find('[data-patient-modal-content] :input:visible').first().trigger('focus');
});

$(document).on('hide.bs.modal.patientModal', '#patient-create-modal', function (event) {
    const form = $(this).find('[data-patient-create-form]').get(0);
    if (form && submittingForms.has(form)) {
        event.preventDefault();
    }
});

$(document).on('hidden.bs.modal.patientModal', '#patient-create-modal', function () {
    clearPatientModal(this);
    patientTargetSelect = null;
});

$(document).on('submit.patientModal', '[data-patient-create-form]', function (event) {
    event.preventDefault();
    const form = this;
    const $form = $(form);
    const $modal = $form.closest('.modal');
    const select = patientTargetSelect;
    const submitter = event.originalEvent?.submitter;
    if (!select || submittingForms.has(form) || !form.reportValidity()) {
        return;
    }

    const values = $form.serializeArray();
    if (submitter?.name && !submitter.disabled) {
        values.push({ name: submitter.name, value: submitter.value });
    }
    if (!startFormSubmission(form, submitter)) {
        return;
    }
    clearPatientModalErrors(form);
    $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        data: values,
        dataType: 'json',
        headers: { Accept: 'application/json' },
        timeout: 30000,
    }).done((patient, _, xhr) => {
        const validId = (typeof patient?.id === 'string' && /^[1-9][0-9]*$/.test(patient.id))
            || (Number.isSafeInteger(patient?.id) && patient.id > 0);
        if (xhr.status !== 201 || !validId || typeof patient.name !== 'string'
            || !patient.name.trim() || typeof patient.patient_id !== 'string' || !patient.patient_id.trim()
            || typeof patient.text !== 'string' || !patient.text.trim()) {
            window.showToast('The patient response could not be read. Refresh the page before trying again.', 'error');
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
        $modal.modal('hide');
        window.showToast('Patient created.', 'success');
    }).fail((xhr, status) => {
        if (status === 'abort') return;
        if (xhr.status === 422) {
            showPatientValidationErrors(form, xhr.responseJSON?.errors);
        } else {
            window.showToast(patientRequestError(xhr.status), 'error');
        }
    }).always(() => resetFormSubmission(form));
});

$(document).on('change.pagination', '[data-page-size]', function () {
    this.form?.requestSubmit();
});

function updateDomainRows($editor) {
    const $rows = $editor.find('[data-domain-row]');

    $rows.each(function (index) {
        const $row = $(this);
        const id = `tenant-domain-${index}`;
        $row.find('input').attr('id', id);
        $row.find('[data-domain-label]').attr('for', id).text(`Domain ${index + 1}`);
        $row.find('[data-domain-remove]').prop('disabled', $rows.length === 1).attr('aria-label', `Remove domain ${index + 1}`);
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

    $name.on('input.tenantForm', updatePreview);
    $form.on('input.tenantForm', '[name="domains[]"]', function () {
        if (this === firstDomain()[0]) {
            automaticDomain = String($(this).val()).trim() === '';
            $automatic.val(automaticDomain ? '1' : '0');
        }
    });
    $form.on('click.tenantForm', '[data-domain-remove]', function () {
        automaticDomain = false;
        $automatic.val('0');
    });

    updatePreview();
}

$(document).on('click.tenantDomains', '[data-domain-add]', function () {
    const $editor = $(this).closest('[data-domain-editor]');

    if ($editor.find('[data-domain-row]').length >= 20) {
        return;
    }

    $editor.find('[data-domain-list]').append($editor.find('[data-domain-template]').html());
    updateDomainRows($editor);
    $editor.find('[data-domain-row]').last().find('input').trigger('focus');
});

$(document).on('click.tenantDomains', '[data-domain-remove]', function () {
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
        return new Set($items.filter(':checked').map((_, item) => $(item).val()).get());
    }

    function visibleItems() {
        return $rows.filter((_, row) => !$(row).prop('hidden')).find('[data-permission-item]').filter((_, item) => {
            return !$(item).closest('.app-permission-option').prop('hidden');
        });
    }

    function matchingItems($visible, attribute, key) {
        return $visible.filter((_, item) => $(item).attr(attribute) === key);
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
            updateToggle(toggle, matchingItems($visible, 'data-group-key', $(toggle).attr('data-group-key')));
        });
        $editor.find('[data-permission-area-toggle]').each((_, toggle) => {
            updateToggle(toggle, matchingItems($visible, 'data-area-key', $(toggle).attr('data-area-key')));
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
            const $row = $(row);
            const matchesScope = (!area || $row.attr('data-permission-area-key') === area)
                && (!group || $row.attr('data-permission-group-key') === group);
            const context = [
                $row.find('.app-permission-area span').text(),
                $row.find('.app-permission-group span').text(),
            ].join(' ');
            let matches = false;

            $row.find('.app-permission-option').each((_, option) => {
                const $option = $(option);
                const text = `${context} ${$option.attr('title') ?? ''} ${$option.text()}`
                    .replace(/\s+/g, ' ').trim().toLocaleLowerCase();
                const hidden = !matchesScope || (search !== '' && !text.includes(search));
                $option.prop('hidden', hidden);

                if (!hidden) {
                    matches = true;
                }
            });

            $row.prop('hidden', !matches);
            visibleRows += Number(matches);
        });

        // Filtering keeps every permission input enabled and available for submission.
        $editor.find('[data-permission-empty]').prop('hidden', visibleRows !== 0);
        updateState();
    }

    function updateGroupOptions() {
        const area = String($area.val() ?? '');
        const availableGroups = new Set($rows.filter((_, row) => !area
            || $(row).attr('data-permission-area-key') === area)
            .map((_, row) => $(row).attr('data-permission-group-key')).get());

        $group.find('option').each((_, option) => {
            const $option = $(option);
            const optionArea = $option.attr('data-area-key');
            const matchesArea = !area || !optionArea || optionArea === area;
            const value = $option.val();
            $option.prop('hidden', value !== '' && (!availableGroups.has(value) || !matchesArea));
        });
    }

    $search.on('input.permissions', filterRows);
    $search.on('keydown.permissions', event => {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });
    $group.on('change.permissions', filterRows);
    $area.on('change.permissions', () => {
        $group.val('');
        updateGroupOptions();
        filterRows();
    });
    $unrestricted.on('change.permissions', function () {
        if (readOnly) {
            return;
        }

        if ($(this).prop('checked')) {
            $items.filter(':enabled').prop('checked', true);
        }

        updateState();
    });
    $editor.on('change.permissions', '[data-permission-item]', () => updateState(true));
    $editor.on('change.permissions', '[data-permission-visible-toggle], [data-permission-group-toggle], [data-permission-area-toggle]', function () {
        if (readOnly) {
            return;
        }

        let $matching = visibleItems();

        const $toggle = $(this);
        if ($toggle.is('[data-permission-group-toggle]')) {
            $matching = matchingItems($matching, 'data-group-key', $toggle.attr('data-group-key'));
        } else if ($toggle.is('[data-permission-area-toggle]')) {
            $matching = matchingItems($matching, 'data-area-key', $toggle.attr('data-area-key'));
        }

        $matching.filter(':enabled').prop('checked', $toggle.prop('checked'));
        updateState(true);
    });

    $recordSelect.on('change.permissions', function () {
        this.form?.requestSubmit();
    });
    $editor.on('submit.permissions', '[data-permission-picker]', function (event) {
        if (dirty && !window.confirm('Discard unsaved permission changes?')) {
            event.preventDefault();
            $recordSelect.val(originalRecord);
        }
    });
    $editor.on('submit.permissions', '[data-permission-form]', event => {
        if (readOnly) {
            event.preventDefault();
        }
    });
    $(window).on('beforeunload.permissions', event => {
        const formPending = $editor.find('form').toArray().some(form => submittingForms.has(form));

        if (dirty && !formPending) {
            event.preventDefault();
            event.originalEvent.returnValue = '';
            return '';
        }
    });
    $(window).on('pageshow.permissions', () => updateState());

    updateGroupOptions();
    filterRows();
}

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

function initializeRefundForm(form) {
    const $form = $(form);
    const $invoice = $form.find('[data-refund-invoice]');
    const $service = $(form.elements.namedItem('service'));
    const invoice = $invoice.get(0);
    const $items = $form.find('[data-refund-items]');
    const $itemsSection = $form.find('[data-refund-items-section]');
    const $error = $form.find('[data-refund-error]');
    const $status = $form.find('[data-refund-status]');
    let preview = null;
    let request = null;
    let requestVersion = 0;
    let selectedInvoice = '';

    function showAmounts(credit, dueAdjustment, cash) {
        $form.find('[data-refund-credit]').val(formatAmount(credit));
        $form.find('[data-refund-due-adjustment]').val(formatAmount(dueAdjustment));
        $form.find('[data-refund-amount]').val(formatAmount(cash));
    }

    if ($form.attr('data-record-exists') === '1') {
        const cash = parseClinicalDecimal($form.attr('data-fixed-amount'), 18) ?? 0n;
        const dueAdjustment = parseClinicalDecimal($form.attr('data-fixed-due-adjustment'), 18) ?? 0n;
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
            const $choices = $items.find('[data-refund-item]');
            const checked = $choices.filter(':checked').get();
            $choices.each((_, choice) => choice.setCustomValidity(''));
            $choices.get(0)?.setCustomValidity(checked.length ? '' : 'Select at least one investigation item.');
            credit = checked.reduce((total, choice) => total + (parseClinicalDecimal($(choice).attr('data-amount'), 18) ?? 0n), 0n);
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
        const encodedInvoice = $invoice.val();
        const selectedService = $service.val() ?? '';
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
            && $invoice.val() === encodedInvoice && ($service.val() ?? '') === selectedService;
        request = $.ajax({
            url: $form.attr('data-preview-url'),
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

    $items.on('change.refund', '[data-refund-item]', updateAmounts);
    $invoice.on('change.refund', loadPreview);
    try {
        const initialPreview = JSON.parse($form.attr('data-initial-preview') ?? 'null');
        const selectedIds = JSON.parse($form.attr('data-selected-item-ids') ?? '[]');
        if ($invoice.val() && initialPreview) {
            selectedInvoice = $invoice.val();
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
    const $invoice = $form.find('[data-due-collection-invoice]');
    const $service = $(form.elements.namedItem('service'));
    const $amount = $form.find('[data-due-collection-amount]');
    const invoice = $invoice.get(0);
    const amount = $amount.get(0);
    const $currentDue = $form.find('[data-due-collection-current-due]');
    const $error = $form.find('[data-due-collection-error]');
    const $amountError = $form.find('[data-due-collection-amount-error]');
    const $status = $form.find('[data-due-collection-status]');
    let amountServerInvalid = $amount.attr('aria-invalid') === 'true';
    let invoiceServerInvalid = $invoice.attr('aria-invalid') === 'true';
    let availableDue = null;
    let selectedInvoice = '';
    let request = null;
    let requestVersion = 0;

    function updateAmountValidity() {
        const value = String($amount.val()).trim();
        const received = parseClinicalDecimal(value);
        let message = '';
        if (value && (received === null || received < 1n)) {
            message = 'Enter an amount of at least 0.01 with up to two decimal places.';
        } else if (value && availableDue === null) {
            message = 'Wait until the invoice due is available before saving.';
        } else if (received !== null && availableDue !== null && received > availableDue) {
            message = 'The amount cannot exceed the current due of ' + formatAmount(availableDue) + '.';
        }

        amount.setCustomValidity(message);
        $amount.attr('aria-invalid', String(amountServerInvalid || Boolean(message)))
            .toggleClass('is-invalid', amountServerInvalid || Boolean(message));
        $amountError.text(message);
    }

    function showError(message) {
        $error.text(message).prop('hidden', !message);
        invoice.setCustomValidity(message);
        const invalid = invoiceServerInvalid || Boolean(message);
        $invoice.attr('aria-invalid', String(invalid)).toggleClass('is-invalid', invalid)
            .next('.select2-container').find('.select2-selection').attr('aria-invalid', String(invalid));
    }

    function applyPreview(preview) {
        availableDue = parseClinicalDecimal(preview.available_due, 18);
        if (availableDue === null) {
            showError('The invoice due is unavailable. Reload the page to try again.');
        } else {
            $currentDue.val(formatAmount(availableDue));
            $amount.attr('data-max-amount', formatAmount(availableDue));
            showError('');
        }
        updateAmountValidity();
    }

    function loadPreview(clearAmount = false) {
        const encodedInvoice = $invoice.val();
        const selectedService = $service.val() ?? '';
        if (encodedInvoice && encodedInvoice === selectedInvoice) {
            return;
        }

        const version = ++requestVersion;
        request?.abort();
        selectedInvoice = encodedInvoice;
        availableDue = null;
        $amount.removeAttr('data-max-amount');
        if (clearAmount) {
            $amount.val('');
            amountServerInvalid = false;
            $form.find('#' + $.escapeSelector($amount.attr('id') + '-error')).empty();
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
            && $invoice.val() === encodedInvoice && ($service.val() ?? '') === selectedService;
        request = $.ajax({
            url: $form.attr('data-preview-url'),
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

    $amount.on('input.dueCollection change.dueCollection', () => {
        amountServerInvalid = false;
        $form.find('#' + $.escapeSelector($amount.attr('id') + '-error')).empty();
        updateAmountValidity();
    });
    try {
        const initialPreview = JSON.parse($form.attr('data-initial-preview') ?? 'null');
        if ($invoice.val() && initialPreview) {
            selectedInvoice = $invoice.val();
            applyPreview(initialPreview);
        }
    } catch {
        // A new collection can still load the selected invoice from the server.
    }

    if ($form.attr('data-record-exists') === '1') {
        if (availableDue === null) {
            showError('The invoice due is unavailable. Reload the page before saving.');
            updateAmountValidity();
        }

        return;
    }

    $invoice.on('change.dueCollection', () => {
        invoiceServerInvalid = false;
        $form.find('#' + $.escapeSelector($invoice.attr('id') + '-error')).empty();
        showError($error.text());
        loadPreview(true);
    });
    if (availableDue === null) {
        loadPreview();
    }
}

$(document).on('select2:selecting.clinicalInvoice', '[data-refund-invoice], [data-due-collection-invoice]', function (event) {
    const service = $(this.form?.elements.namedItem('service')).val();
    const invoice = event.params?.args?.data;
    if (service !== 'all' && invoice?.service !== service) {
        event.preventDefault();
    }
});

function selectedClinicalItems($select) {
    if (!$select.length) {
        return [];
    }
    const selectedData = $select.hasClass('select2-hidden-accessible') ? $select.select2('data') : [];
    const dataById = new Map(selectedData.map(item => [String(item.id), item]));

    return $select.find('option:selected').get().filter(option => $(option).val()).map(option => {
        const $option = $(option);
        const id = $option.val();
        const item = dataById.get(id) ?? {};

        return {
            id,
            name: item.name ?? $option.text(),
            categoryName: item.category?.name ?? $option.attr('data-category-name') ?? '-',
            consultationFee: item.consultation_fee ?? $option.attr('data-consultation-fee'),
            followUpFee: item.follow_up_fee ?? $option.attr('data-follow-up-fee'),
            singleRate: item.single_rate ?? $option.attr('data-single-rate'),
            bothRate: item.both_rate ?? $option.attr('data-both-rate'),
            entryRate: $option.attr('data-entry-rate'),
            entryEye: $option.attr('data-entry-eye'),
        };
    });
}

function clinicalSourceState($form, kind, $select) {
    return {
        ids: $select.find('option:selected').map((_, option) => $(option).val()).get().filter(Boolean).sort(),
        patientType: kind === 'consultation' ? ($form.find('[name="patient_type"]').val() ?? '') : '',
        eyeType: kind === 'consultation' ? '' : ($form.find('[name="eye_type"]').val() ?? ''),
    };
}

function originalClinicalSourceState($form, current) {
    let ids = current.ids;
    try {
        const originalIds = JSON.parse($form.attr('data-original-source-ids') ?? 'null');
        if (Array.isArray(originalIds)) {
            ids = originalIds.filter(id => id !== null && id !== '').map(String).sort();
        }
    } catch {
        // Missing snapshot metadata keeps the current selection as the edit baseline.
    }

    return {
        ids,
        patientType: $form.attr('data-clinical-entry') === 'consultation' ? ($form.attr('data-original-patient-type') ?? current.patientType) : '',
        eyeType: $form.attr('data-original-eye-type') ?? current.eyeType,
    };
}

function initializeClinicalEntry(form) {
    const $form = $(form);
    const kind = $form.attr('data-clinical-entry');
    if (!['consultation', 'investigation', 'operation'].includes(kind)) {
        return;
    }

    const sourceSelectors = {
        consultation: 'select[name="doctor_id"]',
        investigation: '[data-investigation-items]',
        operation: 'select[name="operation_id"]',
    };
    const $source = $form.find(sourceSelectors[kind]);
    const $doctorSelect = $form.find('select[name="doctor_id"]');
    const $investigationPicker = $form.find('[data-investigation-picker]');
    const $discountPercent = $form.find('[name="discount_percent"]');
    const $discountAmount = $form.find('[name="discount_amount"]');
    const $discountModeField = $form.find('[name="discount_mode"]');
    const $extraDiscountPercent = $form.find('[name="extra_discount_percent"]');
    const $paidAmount = $form.find('[name="paid_amount"]');
    const recordExists = $form.attr('data-record-exists') === '1';
    const initialAmount = $form.attr('data-initial-amount');
    const originalEyeType = $form.attr('data-original-eye-type') || 'left';
    let discountMode = kind === 'operation' || $discountModeField.val() === 'amount' ? 'amount' : 'percent';
    let items = selectedClinicalItems($source);
    const investigationRows = new Map();
    const original = originalClinicalSourceState($form, clinicalSourceState($form, kind, $source));
    const originalIds = new Set(original.ids);
    let presetEyes = {};
    let originalInvestigationEyes = {};
    let originalInvestigationRates = {};
    if (kind === 'investigation') {
        try {
            presetEyes = JSON.parse($form.attr('data-investigation-eyes') ?? '{}') ?? {};
            originalInvestigationEyes = JSON.parse($form.attr('data-original-investigation-eyes') ?? '{}') ?? {};
            originalInvestigationRates = JSON.parse($form.attr('data-original-investigation-rates') ?? '{}') ?? {};
        } catch {
            presetEyes = {};
            originalInvestigationEyes = {};
            originalInvestigationRates = {};
        }
    }

    function syncInvestigationRows(initial = false) {
        const selected = selectedClinicalItems($source);
        const selectedById = new Map(selected.map(item => [item.id, item]));
        for (const id of investigationRows.keys()) {
            if (!selectedById.has(id)) {
                investigationRows.delete(id);
            }
        }
        for (const item of selected) {
            if (!investigationRows.has(item.id)) {
                const defaultEye = $form.find('[name="eye_type"]').val() || 'left';
                const eye = initial ? (presetEyes[item.id] ?? item.entryEye ?? originalInvestigationEyes[item.id] ?? defaultEye) : defaultEye;
                investigationRows.set(item.id, { eye, editing: false });
            }
        }
        items = Array.from(investigationRows.keys()).map(id => selectedById.get(id));
        for (const item of items) {
            const $option = $source.find('option').filter((_, option) => $(option).val() === item.id);
            if ($option.length) {
                $source.append($option);
            }
        }
    }

    function sourceState() {
        const current = clinicalSourceState($form, kind, $source);
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

            return [id, originalInvestigationEyes[id] || item?.entryEye || originalEyeType];
        });
    }
    let state = sourceState();
    let stateKey = JSON.stringify(state);
    let preserveSnapshot = recordExists && stateKey === JSON.stringify(original);

    function itemRate(item) {
        const eye = kind === 'investigation' ? investigationRows.get(item.id)?.eye : state.eyeType;
        const savedEye = originalInvestigationEyes[item.id] || item.entryEye || originalEyeType;
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
    let amount = preserveSnapshot ? (parseClinicalDecimal(initialAmount, 18) ?? 0n) : currentAmount();

    function updateInvestigationPicker() {
        const hasDoctor = Boolean($doctorSelect.val());
        $investigationPicker.prop('disabled', !hasDoctor || items.length >= 100);
        $investigationPicker.get(0).setCustomValidity(items.length === 0 ? 'Select at least one investigation.' : '');
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
        const doctorName = $doctorSelect.val() ? $doctorSelect.find('option:selected').text().trim() : '-';
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
                .append($('<td>', { class: 'text-end text-nowrap', 'data-investigation-row-rate': '' }).text(formatAmount(itemRate(item) ?? 0n)))
                .append($('<td>', { class: 'text-end text-nowrap' }).append($edit, ' ', $remove)));
        }
    }

    function renderPayment() {
        $form.find('[data-clinical-amount]').val(formatAmount(amount));
        $form.find('[data-clinical-payable], [data-clinical-due]').val('0.00');
        let discount;

        if (discountMode === 'percent') {
            const percent = parseClinicalPercent($discountPercent.val());
            if (percent === null) {
                return;
            }
            discount = roundRatio(amount * percent, 10000n);
            $discountAmount.val(formatAmount(discount));
        } else {
            discount = parseClinicalDecimal($discountAmount.val());
            if (discount === null) {
                return;
            }
            $discountPercent.val(formatAmount(roundRatio(discount * 10000n, amount)));
        }
        if (discount > amount) {
            return;
        }

        let payable = amount - discount;
        if (kind === 'investigation') {
            const extraPercent = parseClinicalPercent($extraDiscountPercent.val());
            if (extraPercent === null) {
                return;
            }
            payable -= roundRatio(payable * extraPercent, 10000n);
        }
        $form.find('[data-clinical-payable]').val(formatAmount(payable));
        const paid = parseClinicalDecimal($paidAmount.val());
        if (paid !== null && paid <= payable) {
            $form.find('[data-clinical-due]').val(formatAmount(payable - paid));
        }
    }

    $form.on('change.clinicalEntry', '[name="doctor_id"], [name="patient_type"], [name="eye_type"], [name="investigation_ids[]"], [name="operation_id"]', function () {
        if (kind === 'investigation') {
            if (this.name === 'doctor_id') {
                updateInvestigationPicker();
                return;
            }
            if (this.name === 'eye_type') {
                return;
            }
            const currentIds = new Set(items.map(item => item.id));
            if (!$doctorSelect.val() && $source.find('option:selected').get().some(option => !currentIds.has($(option).val()))) {
                $source.val(items.map(item => item.id)).trigger('change.select2');
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
        preserveSnapshot = recordExists && stateKey === JSON.stringify(original);
        if (kind !== 'investigation') {
            items = selectedClinicalItems($source);
        }
        amount = preserveSnapshot ? (parseClinicalDecimal(initialAmount, 18) ?? 0n) : currentAmount();
        if (kind === 'investigation') {
            updateInvestigationPicker();
        }
        renderPayment();
    });
    $form.on('input.clinicalEntry change.clinicalEntry', '[name="discount_percent"], [name="discount_amount"], [name="extra_discount_percent"], [name="paid_amount"]', function () {
        if (this.name === 'discount_percent' || this.name === 'discount_amount') {
            discountMode = this.name === 'discount_percent' ? 'percent' : 'amount';
            $discountModeField.val(discountMode);
        }
        renderPayment();
    });
    $form.on('click.clinicalEntry', '[data-remove-investigation]', function () {
        const removedId = $(this).attr('data-remove-investigation');
        const remaining = $source.find('option:selected').map((_, option) => $(option).val()).get()
            .filter(id => id !== removedId);
        $source.val(remaining).trigger('change');
        const $nextRemove = $form.find('[data-remove-investigation]').first();
        if ($nextRemove.length) {
            $nextRemove.trigger('focus');
        } else {
            const $nextSelect = $doctorSelect.val() ? $investigationPicker : $doctorSelect;
            $nextSelect.next('.select2-container').find('.select2-selection').trigger('focus');
        }
    });
    $form.on('click.clinicalEntry', '[data-edit-investigation]', function () {
        const id = $(this).attr('data-edit-investigation');
        const row = investigationRows.get(id);
        if (row) {
            row.editing = !row.editing;
            renderInvestigationItems();
            if (row.editing) {
                $form.find('[data-investigation-eye]').filter((_, select) => $(select).attr('data-investigation-eye') === id).trigger('focus');
            } else {
                $form.find('[data-edit-investigation]').filter((_, button) => $(button).attr('data-edit-investigation') === id).trigger('focus');
            }
        }
    });
    $form.on('change.clinicalEntry', '[data-investigation-eye]', function () {
        const id = $(this).attr('data-investigation-eye');
        const row = investigationRows.get(id);
        if (!row) {
            return;
        }
        row.eye = $(this).val();
        state = sourceState();
        stateKey = JSON.stringify(state);
        preserveSnapshot = recordExists && stateKey === JSON.stringify(original);
        amount = preserveSnapshot ? (parseClinicalDecimal(initialAmount, 18) ?? 0n) : currentAmount();
        const $row = $(this).closest('tr');
        $row.find('[data-investigation-eye-value]').val(row.eye);
        $row.find('[data-investigation-row-rate]').text(formatAmount(itemRate(items.find(item => item.id === id)) ?? 0n));
        renderPayment();
    });

    if (kind === 'investigation') {
        $investigationPicker.on('select2:opening.clinicalEntry select2:selecting.clinicalEntry', event => {
            if (!$doctorSelect.val() || items.length >= 100) {
                event.preventDefault();
                return;
            }
            const id = event.params?.args?.data?.id;
            if (event.type === 'select2:selecting' && items.some(item => item.id === String(id))) {
                event.preventDefault();
                $form.find('[data-investigation-picker-help]').text('This test is already in the list. Select another test.');
            }
        });
        $investigationPicker.on('select2:select.clinicalEntry', event => {
            const test = event.params?.data;
            const id = String(test?.id ?? '');
            if (!$doctorSelect.val() || items.length >= 100 || !/^[1-9][0-9]*$/.test(id)
                || items.some(item => item.id === id)) {
                return;
            }

            let $option = $source.find('option').filter((_, option) => $(option).val() === id);
            if (!$option.length) {
                $option = $('<option>', { value: id }).text(test.name ?? test.text).appendTo($source);
            }
            $option.attr({
                'data-single-rate': test.single_rate ?? '',
                'data-both-rate': test.both_rate ?? '',
                'data-category-name': test.category?.name ?? '-',
            }).prop('selected', true);
            $source.trigger('change');
            $investigationPicker.val(null).trigger('change.select2');

            window.setTimeout(() => {
                if ($investigationPicker.prop('isConnected') && !$investigationPicker.prop('disabled') && $doctorSelect.val()) {
                    $investigationPicker.select2('open');
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

    $editor.on('click.invoiceEditor', '[data-invoice-add]', () => {
        if ($items.children('[data-invoice-item]').length >= 100) {
            return;
        }

        const row = template.innerHTML.replaceAll('__INDEX__', String(nextIndex++));
        $items.append(row);
        updateControls();
        $items.children('[data-invoice-item]').last().find('input').first().trigger('focus');
    });

    $editor.on('click.invoiceEditor', '[data-invoice-remove]', function () {
        if ($items.children('[data-invoice-item]').length <= 1) {
            return;
        }

        $(this).closest('[data-invoice-item]').remove();
        updateControls();
    });

    updateControls();
}

function initializePaymentProvider(select) {
    const $select = $(select);
    const $form = $(select.form);
    const $fields = $form.find('[data-manual-payment-fields]');
    const requiredInputs = new Set($fields.find(':input[required]').toArray());

    function updateFields() {
        const manual = $select.val() === 'manual';
        const $method = $select.find('option:selected');
        $form.find('[data-payment-extra-charge]').text($method.attr('data-extra-charge') ?? '0.00');
        $form.find('[data-payment-total]').text($method.attr('data-total') ?? '0.00');
        $fields.prop('hidden', !manual);
        $fields.find(':input').each(function () {
            $(this).prop({ disabled: !manual, required: manual && requiredInputs.has(this) });
        });
    }

    $select.on('change.paymentProvider', updateFields);
    updateFields();
}

$(document).on('click.print', '[data-print]', () => window.print());

function prepareTimeInput(_, __, picker) {
    const $input = $(picker.input);
    const $altInput = $(picker.altInput);
    $input.before($altInput);
    $altInput.attr({ id: $input.attr('id'), autocomplete: 'off' });
    $input.attr('id', $input.attr('id') + '_value');
    for (const attribute of ['aria-invalid', 'aria-describedby']) {
        const value = $input.attr(attribute);
        if (value !== undefined) $altInput.attr(attribute, value);
    }
}

window.initDateTimePickers = function (root = document) {
    const selector = '[data-datepicker], [data-timepicker]';
    const timeOptions = {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        altInput: true,
        altFormat: 'G:i K',
        time_24hr: false,
        onReady: prepareTimeInput,
    };

    $(root).find(selector).addBack(selector).each(function () {
        const $picker = $(this);
        if (this._flatpickr || $picker.find('[data-input]').get(0)?._flatpickr) return;

        $picker.flatpickr({
            wrap: true,
            allowInput: true,
            disableMobile: true,
            ...($picker.is('[data-timepicker]') ? timeOptions : { dateFormat: 'Y-m-d' }),
        });
    });
};

$(() => {
    $('[data-toast-message]').each(function () {
        const $message = $(this);
        window.showToast($message.attr('data-toast-message'), $message.attr('data-toast-type'));
        $message.remove();
    });
    window.initAjaxSelects();
    initializeNavigation();
    $('[data-domain-editor]').each((_, editor) => updateDomainRows($(editor)));
    $('[data-tenant-form]').each((_, form) => initializeTenantForm(form));
    $('[data-permission-editor]').each((_, editor) => initializePermissionEditor(editor));

    const $saleInvoicePaper = $('#pharmacy-sale-invoice-paper');
    const $salePosWidth = $('[data-pharmacy-pos-width]');
    if ($saleInvoicePaper.length && $salePosWidth.length) {
        const updatePosWidth = () => {
            $salePosWidth.prop('hidden', $saleInvoicePaper.val() !== 'pos' && !$salePosWidth.find('.is-invalid').length);
        };
        $saleInvoicePaper.on('change.pharmacySettings', updatePosWidth);
        updatePosWidth();
    }
    window.initDateTimePickers();
    $('[data-clinical-entry]').each((_, form) => initializeClinicalEntry(form));
    $('[data-refund-form]').each((_, form) => initializeRefundForm(form));
    $('[data-due-collection-form]').each((_, form) => initializeDueCollectionForm(form));
    $('[data-invoice-editor]').each((_, editor) => initializeInvoiceEditor(editor));
    $('[data-payment-provider]').each((_, select) => initializePaymentProvider(select));
});
