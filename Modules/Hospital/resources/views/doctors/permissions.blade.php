@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage the permissions shared by all doctors.'])
    <div
        data-permission-editor
        data-permission-readonly="{{ $canManagePermissions ? '0' : '1' }}"
        data-permission-total="{{ $permissionCount }}"
        data-permission-initial="{{ implode(',', $selectedPermissions) }}"
    >
        @include('core::permissions.matrix', [
            'recordName' => 'All doctors',
            'formAction' => route('hospital.doctors.permissions-update'),
            'fieldName' => 'permissions',
            'markerName' => 'permissions_present',
            'isPlan' => false,
        ])
    </div>
@endsection
