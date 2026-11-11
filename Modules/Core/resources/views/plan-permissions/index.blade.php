@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Select a plan and choose the tenant actions included in it. Account roles still decide who can use them.'])
    <div data-permission-editor data-permission-readonly="{{ $canManagePermissions ? '0' : '1' }}"
        data-permission-total="{{ $permissionCount }}" data-permission-initial="{{ implode(',', $selectedPermissions) }}"
        data-permission-unrestricted-initial="{{ $unrestrictedRouteAccess ? '1' : '0' }}">
        @include('core::permissions.picker', [
            'recordLabel' => 'plan',
            'recordIcon' => 'layer-group',
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
            @component('core::permissions.matrix', [
                'recordName' => $selectedPlan->name,
                'formAction' => route($routePrefix.'.update', $selectedPlan),
                'fieldName' => 'route_permissions',
                'markerName' => 'route_permissions_present',
                'canManagePermissions' => $canManagePermissions,
                'selectedPermissions' => $selectedPermissions,
                'permissionCount' => $permissionCount,
                'areas' => $areas,
            ])
                <div class="px-3 px-md-4 py-3 border-bottom">
                    <input name="unrestricted_route_access" type="hidden" value="0">
                    <label class="form-check d-flex align-items-center gap-2 mb-2" for="unrestricted-route-access">
                        <input class="{{ inputClass('unrestricted_route_access', 'form-check-input') }}"
                            id="unrestricted-route-access" name="unrestricted_route_access" type="checkbox" value="1"
                            data-permission-unrestricted @disabled(! $canManagePermissions)
                            {{ getChecked(1, 'unrestricted_route_access', null, $unrestrictedRouteAccess) }}
                            {{ inputAttrs('unrestricted_route_access', 'unrestricted-route-access', 'plan-routes-help') }}>
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
            @endcomponent
        @endif
    </div>
@endsection
