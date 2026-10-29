<form class="app-card overflow-hidden" action="{{ $formAction }}" method="POST" data-permission-form>
    @csrf
    @method('PUT')
    <input name="{{ $markerName }}" type="hidden" value="1">
    <div class="app-permission-summary app-card-heading d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 px-md-4 py-3 border-bottom">
        <div>
            <h2 class="h6 mb-1">{{ $recordName }}</h2>
            <output class="small text-body-secondary" data-permission-count aria-live="polite">{{ count($selectedPermissions) }} of {{ $permissionCount }} selected</output>
        </div>
        <label class="d-flex align-items-center gap-2 small fw-semibold">
            <input class="form-check-input mt-0" type="checkbox" data-permission-visible-toggle @disabled(! $canManagePermissions)>
            Select visible
        </label>
    </div>
    @if ($isPlan)
        <div class="px-3 px-md-4 py-3 border-bottom">
            <input name="unrestricted_route_access" type="hidden" value="0">
            <label class="form-check d-flex align-items-center gap-2 mb-2" for="unrestricted-route-access">
                <input
                    class="{{ inputClass('unrestricted_route_access', 'form-check-input') }}"
                    id="unrestricted-route-access"
                    name="unrestricted_route_access"
                    type="checkbox"
                    value="1"
                    data-permission-unrestricted
                    {{ getChecked(1, 'unrestricted_route_access', null, $unrestrictedRouteAccess) }}
                    {{ inputAttrs('unrestricted_route_access', 'unrestricted-route-access', 'plan-routes-help') }}
                    @disabled(! $canManagePermissions)
                >
                Allow all current and future routes
            </label>
            <p class="form-text mb-0" id="plan-routes-help">
                Uncheck to use only the selected actions; no selections means no optional routes. Account roles still apply.
                Dashboard, sign-in, password recovery, and Billing remain available for subscription and payment recovery.
            </p>
            @error('unrestricted_route_access')
                <div class="invalid-feedback d-block" id="unrestricted-route-access-error">{{ $message }}</div>
            @enderror
        </div>
    @endif
    <div class="px-3 px-md-4 py-3 border-bottom">
        <div class="app-permission-toolbar">
            <div>
                <label class="form-label small" for="permission-area">Area</label>
                <select class="form-select" id="permission-area" data-permission-area>
                    <option value="">All areas</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area['key'] }}">{{ $area['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label small" for="permission-group">Group</label>
                <select class="form-select" id="permission-group" data-permission-group>
                    <option value="">All groups</option>
                    @foreach ($areas as $area)
                        @foreach ($area['groups'] as $group)
                            <option value="{{ $group['key'] }}" data-area-key="{{ $area['key'] }}">{{ $area['label'] }} / {{ $group['label'] }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label small" for="permission-search">Search permissions</label>
                <div class="app-input-wrap app-search-field">
                    <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                    <input class="form-control" id="permission-search" type="search" placeholder="Search an action or permission name..." data-permission-search>
                </div>
            </div>
        </div>
        <p class="form-text mb-0 mt-2" id="permissions-help">
            {{ $canManagePermissions ? 'Choose individual actions or use the area and group checkboxes. Filters only change what is visible.' : 'You have read-only access. These permissions cannot be changed here.' }}
        </p>
        @error($fieldName)
            <div class="invalid-feedback d-block" id="{{ $fieldName }}-error">{{ $message }}</div>
        @enderror
        @error($fieldName.'.*')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        @error($markerName)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <fieldset @disabled(! $canManagePermissions) {{ inputAttrs($fieldName, null, 'permissions-help') }}>
        <legend class="visually-hidden">Permissions for {{ $recordName }}</legend>
        <div class="table-responsive" tabindex="0" role="region" aria-label="Permission matrix">
            <table class="table app-table app-permission-matrix align-middle mb-0">
                <caption class="visually-hidden">Permission areas, groups, and actions for {{ $recordName }}</caption>
                <thead>
                    <tr>
                        <th scope="col">Area</th>
                        <th scope="col">Group</th>
                        <th scope="col">Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($areas as $area)
                        @foreach ($area['groups'] as $group)
                            <tr
                                data-permission-row
                                data-permission-area-key="{{ $area['key'] }}"
                                data-permission-group-key="{{ $group['key'] }}"
                                data-permission-search-text="{{ $area['label'].' '.$group['label'].' '.implode(' ', array_column($group['permissions'], 'name')).' '.implode(' ', array_column($group['permissions'], 'label')) }}"
                            >
                                <th class="app-permission-area" scope="row">
                                    <label class="d-flex align-items-center gap-2">
                                        <input class="form-check-input mt-0" type="checkbox" data-permission-area-toggle data-area-key="{{ $area['key'] }}" aria-label="Select area {{ $area['label'] }}">
                                        <span>{{ $area['label'] }}</span>
                                    </label>
                                </th>
                                <th class="app-permission-group" scope="row">
                                    <label class="d-flex align-items-center gap-2">
                                        <input class="form-check-input mt-0" type="checkbox" data-permission-group-toggle data-group-key="{{ $group['key'] }}" aria-label="Select group {{ $group['label'] }}">
                                        <span>{{ $group['label'] }}</span>
                                    </label>
                                </th>
                                <td>
                                    <div class="app-permission-options d-flex flex-wrap gap-2">
                                        @foreach ($group['permissions'] as $permission)
                                            <label class="app-permission-option app-check-option d-flex align-items-center gap-2" for="permission-{{ $permission['id'] }}" title="{{ $permission['name'] }}">
                                                <input
                                                    class="{{ inputClass($fieldName, 'form-check-input') }}"
                                                    id="permission-{{ $permission['id'] }}"
                                                    name="{{ $fieldName }}[]"
                                                    type="checkbox"
                                                    value="{{ $permission['id'] }}"
                                                    {{ getChecked($permission['id'], $fieldName, null, $selectedPermissions) }}
                                                    {{ inputAttrs($fieldName) }}
                                                    data-permission-item
                                                    data-area-key="{{ $area['key'] }}"
                                                    data-group-key="{{ $group['key'] }}"
                                                >
                                                <span class="small">{{ $permission['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr data-permission-empty @if ($areas !== []) hidden @endif>
                        <td class="text-center small text-body-secondary py-4" colspan="3">No permissions match these filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </fieldset>
    <div class="app-permission-footer d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 px-md-4 py-3 border-top">
        <span class="app-permission-state small text-body-secondary" data-permission-state>{{ ! $canManagePermissions ? 'Read-only access' : (getValue($markerName) ? 'Unsaved changes' : 'All changes saved') }}</span>
        @if ($canManagePermissions)
            <button class="btn btn-primary" type="submit" data-permission-save>
                <i class="fa-solid fa-floppy-disk me-2" aria-hidden="true"></i>Save
            </button>
        @endif
    </div>
</form>
