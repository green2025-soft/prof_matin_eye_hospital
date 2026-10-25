@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Select a plan and choose the tenant actions included in it. Account roles still decide who can use them.'])
    <div
        data-permission-editor
        data-permission-readonly="{{ $canManagePermissions ? '0' : '1' }}"
        data-permission-total="{{ $permissionCount }}"
        data-permission-initial="{{ implode(',', $selectedPermissions) }}"
        data-permission-unrestricted-initial="{{ $unrestrictedRouteAccess ? '1' : '0' }}"
    >
        @include('core::permissions.picker', [
            'recordLabel' => 'plan',
            'records' => $plans,
            'selectedRecord' => $selectedPlan,
            'searchValue' => $planSearch,
            'hasMoreRecords' => $hasMorePlans,
            'pickerAction' => route($routePrefix.'.index'),
            'searchField' => 'plan_search',
            'recordField' => 'plan',
        ])

        @if ($selectedPlan === null)
            <section class="app-card">
                @include('core::partials.empty', ['title' => 'No plan selected', 'description' => 'Choose a plan above to view its permissions.', 'icon' => 'key'])
            </section>
        @else
            @include('core::permissions.matrix', [
                'recordName' => $selectedPlan->name,
                'formAction' => route($routePrefix.'.update', $selectedPlan),
                'fieldName' => 'route_permissions',
                'markerName' => 'route_permissions_present',
                'isPlan' => true,
            ])
        @endif
    </div>
@endsection
