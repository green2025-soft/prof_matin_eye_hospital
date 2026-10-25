import { Offcanvas } from 'bootstrap';
import $ from 'jquery';
import flatpickr from 'flatpickr';

window.$ = window.jQuery = $;

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

$(document).on('submit', 'form[data-confirm]', function (event) {
    if (!window.confirm($(this).attr('data-confirm'))) {
        event.preventDefault();
    }
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

        if (slug.length < 5) {
            slug = `${slug}-tenant`.replace(/^-+/, '');
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
    const $roleSelect = $editor.find('[data-permission-role-select]');
    const originalRole = $roleSelect.val();
    const readOnly = $editor.attr('data-permission-readonly') === '1' || !$items.is(':enabled');
    const total = Number($editor.attr('data-permission-total') ?? $items.length);
    const initialValue = $editor.attr('data-permission-initial');
    const initialSelected = initialValue === undefined
        ? selectedIds()
        : new Set(initialValue.split(',').filter(id => id !== ''));
    let dirty = false;
    let submitting = false;

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

    function updateState() {
        const $visible = visibleItems();
        const selected = selectedIds();
        dirty = !readOnly && (selected.size !== initialSelected.size
            || [...selected].some(id => !initialSelected.has(id)));

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
        $editor.find('[data-permission-save]').prop('disabled', readOnly || !dirty || submitting);
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
    $editor.on('change', '[data-permission-item]', updateState);
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
        updateState();
    });

    $roleSelect.on('change', function () {
        this.form?.requestSubmit();
    });
    $editor.on('submit', '[data-permission-role-picker]', function (event) {
        if (dirty && !window.confirm('Discard unsaved permission changes?')) {
            event.preventDefault();
            $roleSelect.val(originalRole);
            return;
        }

        submitting = true;
    });
    $editor.on('submit', '[data-permission-form]', event => {
        if (readOnly) {
            event.preventDefault();
            return;
        }

        submitting = true;
        updateState();
    });
    window.addEventListener('beforeunload', event => {
        if (dirty && !submitting) {
            event.preventDefault();
            event.returnValue = '';
        }
    });

    updateGroupOptions();
    filterRows();
}

$(() => {
    $('[data-permission-editor]').each((_, editor) => initializePermissionEditor(editor));
});

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
    $('[data-invoice-editor]').each((_, editor) => initializeInvoiceEditor(editor));
    $('[data-payment-provider]').each((_, select) => initializePaymentProvider(select));
});
