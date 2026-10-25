@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Select a role, review its access, and save permission changes separately.'])
    <div
        data-permission-editor
        data-permission-readonly="{{ $canManagePermissions ? '0' : '1' }}"
        data-permission-total="{{ $permissionCount }}"
        data-permission-initial="{{ implode(',', $selectedPermissions) }}"
    >
        @include('core::permissions.picker', [
            'recordLabel' => 'role',
            'records' => $roles,
            'selectedRecord' => $selectedRole,
            'searchValue' => $roleSearch,
            'hasMoreRecords' => $hasMoreRoles,
            'pickerAction' => route('core.permissions.index'),
            'searchField' => 'role_search',
            'recordField' => 'role',
        ])

        @if ($selectedRole === null)
            <section class="app-card">
                @include('core::partials.empty', ['title' => 'No role selected', 'description' => 'Choose a role above to view its permissions.', 'icon' => 'key'])
            </section>
        @else
            @include('core::permissions.matrix', [
                'recordName' => $selectedRole->name,
                'formAction' => route('core.permissions.update', $selectedRole),
                'fieldName' => 'permissions',
                'markerName' => 'permissions_present',
                'isPlan' => false,
            ])
        @endif
    </div>
@endsection
