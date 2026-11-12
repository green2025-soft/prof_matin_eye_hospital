@php
    $permissions = $moduleMenu['permissions'];
    $transactionsOpen = request()->routeIs('hospital.appointments.*', 'hospital.consultations.*', 'hospital.patient-investigations.*', 'hospital.delivery-investigations.*', 'hospital.patient-operations.*', 'hospital.commissions.*', 'hospital.refunds.*', 'hospital.due-collections.*');
    $peopleOpen = request()->routeIs('hospital.patients.*', 'hospital.references.*', 'hospital.doctors.*');
    $setupOpen = request()->routeIs('hospital.departments.*', 'hospital.investigation-categories.*', 'hospital.investigations.*', 'hospital.operation-categories.*', 'hospital.operations.*', 'hospital.refund-causes.*');
@endphp

<p class="app-nav-label">Hospital</p>
@if (($permissions['appointments.view'] ?? false)
    || ($permissions['consultations.view'] ?? false)
    || ($permissions['patient-investigations.view'] ?? false)
    || ($permissions['delivery-investigations.view'] ?? false)
    || ($permissions['patient-operations.view'] ?? false)
    || ($permissions['commissions.view'] ?? false)
    || ($permissions['refunds.view'] ?? false)
    || ($permissions['due-collections.view'] ?? false))
    <details class="app-nav-group" @if ($transactionsOpen) open @endif>
        <summary class="app-nav-link app-nav-group-toggle{{ $transactionsOpen ? ' active' : '' }}" title="Transactions" aria-label="Transactions">
            <span class="app-nav-icon"><i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Billing</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @if ($permissions['appointments.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Appointment', 'icon' => 'calendar-check',
                    'url' => route('hospital.appointments.index'),
                    'active' => request()->routeIs('hospital.appointments.*'),
                ])
            @endif
            @if ($permissions['consultations.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Consultation', 'icon' => 'notes-medical',
                    'url' => route('hospital.consultations.index'),
                    'active' => request()->routeIs('hospital.consultations.*'),
                ])
            @endif
            @if ($permissions['patient-investigations.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Investigation', 'icon' => 'eye',
                    'url' => route('hospital.patient-investigations.index'),
                    'active' => request()->routeIs('hospital.patient-investigations.*'),
                ])
            @endif
            @if ($permissions['delivery-investigations.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Report Delivery', 'icon' => 'file-circle-check',
                    'url' => route('hospital.delivery-investigations.index'),
                    'active' => request()->routeIs('hospital.delivery-investigations.*'),
                ])
            @endif
            @if ($permissions['patient-operations.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Operation', 'icon' => 'stethoscope',
                    'url' => route('hospital.patient-operations.index'),
                    'active' => request()->routeIs('hospital.patient-operations.*'),
                ])
            @endif
            @if ($permissions['commissions.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'PC Commission', 'icon' => 'percent',
                    'url' => route('hospital.commissions.index'),
                    'active' => request()->routeIs('hospital.commissions.*'),
                ])
            @endif
            @if ($permissions['due-collections.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Due Collection', 'icon' => 'hand-holding-dollar',
                    'url' => route('hospital.due-collections.index'),
                    'active' => request()->routeIs('hospital.due-collections.*'),
                ])
            @endif
            @if ($permissions['refunds.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Refund', 'icon' => 'money-bill-transfer',
                    'url' => route('hospital.refunds.index'),
                    'active' => request()->routeIs('hospital.refunds.*'),
                ])
            @endif
        </div>
    </details>
@endif
@if (($permissions['patients.view'] ?? false)
    || ($permissions['references.view'] ?? false)
    || ($permissions['doctors.view'] ?? false)
    || ($permissions['doctor-permissions.view'] ?? false))
    <details class="app-nav-group" @if ($peopleOpen) open @endif>
        <summary class="app-nav-link app-nav-group-toggle{{ $peopleOpen ? ' active' : '' }}" title="People" aria-label="People">
            <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Resource</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @if ($permissions['patients.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Patient', 'icon' => 'hospital-user',
                    'url' => route('hospital.patients.index'),
                    'active' => request()->routeIs('hospital.patients.*'),
                ])
            @endif
            @if ($permissions['references.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Reference', 'icon' => 'user-group',
                    'url' => route('hospital.references.index'),
                    'active' => request()->routeIs('hospital.references.*'),
                ])
            @endif
            @if ($permissions['doctors.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Doctor', 'icon' => 'user-doctor',
                    'url' => route('hospital.doctors.index'),
                    'active' => request()->routeIs('hospital.doctors.*') && ! request()->routeIs('hospital.doctors.permissions*'),
                ])
            @endif
            @if ($permissions['doctor-permissions.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Dr. Permission', 'icon' => 'key',
                    'url' => route('hospital.doctors.permissions'),
                    'active' => request()->routeIs('hospital.doctors.permissions', 'hospital.doctors.permissions-update'),
                ])
            @endif
        </div>
    </details>
@endif
@if (($permissions['departments.view'] ?? false)
    || ($permissions['investigation-categories.view'] ?? false)
    || ($permissions['investigations.view'] ?? false)
    || ($permissions['operation-categories.view'] ?? false)
    || ($permissions['operations.view'] ?? false)
    || ($permissions['refund-causes.view'] ?? false))
    <details class="app-nav-group" @if ($setupOpen) open @endif>
        <summary class="app-nav-link app-nav-group-toggle{{ $setupOpen ? ' active' : '' }}" title="Setup" aria-label="Setup">
            <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Setup</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @if ($permissions['departments.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Doctor Departments', 'icon' => 'user-doctor',
                    'url' => route('hospital.departments.index'),
                    'active' => request()->routeIs('hospital.departments.*'),
                ])
            @endif
            @if ($permissions['investigation-categories.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Investigation Categories', 'icon' => 'flask',
                    'url' => route('hospital.investigation-categories.index'),
                    'active' => request()->routeIs('hospital.investigation-categories.*'),
                ])
            @endif
            @if ($permissions['investigations.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Investigations', 'icon' => 'eye',
                    'url' => route('hospital.investigations.index'),
                    'active' => request()->routeIs('hospital.investigations.*'),
                ])
            @endif
            @if ($permissions['operation-categories.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Operation Categories', 'icon' => 'tags',
                    'url' => route('hospital.operation-categories.index'),
                    'active' => request()->routeIs('hospital.operation-categories.*'),
                ])
            @endif
            @if ($permissions['operations.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Operations', 'icon' => 'stethoscope',
                    'url' => route('hospital.operations.index'),
                    'active' => request()->routeIs('hospital.operations.*'),
                ])
            @endif
            @if ($permissions['refund-causes.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => 'Refund Causes', 'icon' => 'rotate-left',
                    'url' => route('hospital.refund-causes.index'),
                    'active' => request()->routeIs('hospital.refund-causes.*'),
                ])
            @endif
        </div>
    </details>
@endif
@if ($permissions['settings.view'] ?? false)
    @include('core::partials.navigation-link', [
        'label' => 'Settings', 'icon' => 'gear',
        'url' => route('hospital.settings.index'),
        'active' => request()->routeIs('hospital.settings.*'),
    ])
@endif

@if ($permissions['hospital-cash-report.view'] ?? false)
    @include('core::partials.navigation-link', ['label' => 'Cash & Due Balance', 'icon' => 'money-bill-wave', 'url' => route('hospital.reports.cash-balance'), 'active' => request()->routeIs('hospital.reports.cash-balance')])
@endif
