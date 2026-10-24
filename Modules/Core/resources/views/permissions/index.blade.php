@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Select a role, review its access, and save permission changes separately.'])
    <div data-permission-editor data-permission-readonly="{{ $canManagePermissions ? '0' : '1' }}" data-permission-total="{{ $permissionCount }}" data-permission-initial="{{ implode(',', $selectedPermissions) }}">
        <section class="app-card p-3 p-md-4 mb-4" aria-labelledby="permission-role-heading">
            <h2 class="h6 mb-3" id="permission-role-heading"><i class="fa-solid fa-shield-halved me-2 text-body-secondary" aria-hidden="true"></i>Choose a role</h2>
            <form action="{{ route('core.permissions.index') }}" method="GET" data-permission-role-picker>
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label" for="role-search">Find a role</label>
                        <div class="input-group">
                            <input class="{{ inputClass('role_search') }}" id="role-search" name="role_search" type="search" value="{{ getValue('role_search', null, $roleSearch) }}" placeholder="Role name starts with..." maxlength="80" {{ inputAttrs('role_search', 'role-search', 'role-search-help') }}>
                            <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass me-2" aria-hidden="true"></i>Search</button>
                        </div>
                        @error('role_search')
                            <div class="invalid-feedback d-block" id="role-search-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-5">
                        <label class="form-label" for="permission-role">Role</label>
                        <select class="{{ inputClass('role', 'form-select') }}" id="permission-role" name="role" data-permission-role-select {{ inputAttrs('role', 'permission-role') }}>
                            <option value="">Select a role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ getSelected($role->id, 'role', null, $selectedRole?->id) }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback d-block" id="permission-role-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-primary w-100" type="submit"><i class="fa-solid fa-arrow-right me-2" aria-hidden="true"></i>Load role</button>
                    </div>
                </div>
                <p class="form-text mb-0 mt-2" id="role-search-help">{{ $hasMoreRoles ? 'More roles match this search. Narrow the name to find the role you need.' : 'Search by the start of a role name, then select a role to review its permissions.' }}</p>
            </form>
        </section>

        @if ($selectedRole === null)
            <section class="app-card">
                @include('core::partials.empty', ['title' => 'No role selected', 'description' => 'Choose a role above to view its permissions.', 'icon' => 'key'])
            </section>
        @else
            <form class="app-card overflow-hidden" action="{{ route('core.permissions.update', $selectedRole) }}" method="POST" data-permission-form>
                @csrf
                @method('PUT')
                <input name="permissions_present" type="hidden" value="1">
                <div class="app-permission-summary app-card-heading d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 px-md-4 py-3 border-bottom">
                    <div>
                        <h2 class="h6 mb-1">{{ $selectedRole->name }}</h2>
                        <output class="small text-body-secondary" data-permission-count aria-live="polite">{{ count($selectedPermissions) }} of {{ $permissionCount }} selected</output>
                    </div>
                    <label class="d-flex align-items-center gap-2 small fw-semibold">
                        <input class="form-check-input mt-0" type="checkbox" data-permission-visible-toggle @disabled(! $canManagePermissions)>
                        Select visible
                    </label>
                </div>
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
                    <p class="form-text mb-0 mt-2" id="permissions-help">{{ $canManagePermissions ? 'Choose individual actions or use the area and group checkboxes. Filters only change what is visible.' : 'You have read-only access to this role. Its permissions cannot be changed here.' }}</p>
                    @error('permissions')
                        <div class="invalid-feedback d-block" id="permissions-error">{{ $message }}</div>
                    @enderror
                    @error('permissions_present')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <fieldset @disabled(! $canManagePermissions) {{ inputAttrs('permissions', null, 'permissions-help') }}>
                    <legend class="visually-hidden">Permissions for {{ $selectedRole->name }}</legend>
                    <div class="table-responsive" tabindex="0" role="region" aria-label="Role permission matrix">
                        <table class="table app-table app-permission-matrix align-middle mb-0">
                            <caption class="visually-hidden">Permission areas, groups, and actions for {{ $selectedRole->name }}</caption>
                            <thead><tr><th scope="col">Area</th><th scope="col">Group</th><th scope="col">Permissions</th></tr></thead>
                            <tbody>
                                @foreach ($areas as $area)
                                    @foreach ($area['groups'] as $group)
                                        <tr data-permission-row data-permission-area-key="{{ $area['key'] }}" data-permission-group-key="{{ $group['key'] }}" data-permission-search-text="{{ $area['label'].' '.$group['label'].' '.implode(' ', array_column($group['permissions'], 'name')).' '.implode(' ', array_column($group['permissions'], 'label')) }}">
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
                                                            <input class="{{ inputClass('permissions', 'form-check-input') }}" id="permission-{{ $permission['id'] }}" name="permissions[]" type="checkbox" value="{{ $permission['id'] }}" {{ getChecked($permission['id'], 'permissions', null, $selectedPermissions) }} {{ inputAttrs('permissions') }} data-permission-item data-area-key="{{ $area['key'] }}" data-group-key="{{ $group['key'] }}">
                                                            <span class="small">{{ $permission['label'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                <tr data-permission-empty @if ($areas !== []) hidden @endif><td class="text-center small text-body-secondary py-4" colspan="3">No permissions match these filters.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </fieldset>
                <div class="app-permission-footer d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 px-md-4 py-3 border-top">
                    <span class="app-permission-state small text-body-secondary" data-permission-state>{{ ! $canManagePermissions ? 'Read-only access' : (getValue('permissions_present') ? 'Unsaved changes' : 'All changes saved') }}</span>
                    @if ($canManagePermissions)
                        <button class="btn btn-primary" type="submit" data-permission-save><i class="fa-solid fa-floppy-disk me-2" aria-hidden="true"></i>Save permissions</button>
                    @endif
                </div>
            </form>
        @endif
    </div>
@endsection
