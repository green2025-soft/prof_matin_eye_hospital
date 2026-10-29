<p class="app-nav-label">Hospital</p>
@if (($moduleMenu['permissions']['appointments.view'] ?? false)
    || ($moduleMenu['permissions']['consultations.view'] ?? false)
    || ($moduleMenu['permissions']['patient-investigations.view'] ?? false)
    || ($moduleMenu['permissions']['delivery-investigations.view'] ?? false)
    || ($moduleMenu['permissions']['patient-operations.view'] ?? false)
    || ($moduleMenu['permissions']['commissions.view'] ?? false)
    || ($moduleMenu['permissions']['refunds.view'] ?? false)
    || ($moduleMenu['permissions']['due-collections.view'] ?? false))
    <details class="app-nav-group" @if (request()->routeIs('hospital.appointments.*', 'hospital.consultations.*', 'hospital.patient-investigations.*', 'hospital.delivery-investigations.*', 'hospital.patient-operations.*', 'hospital.commissions.*', 'hospital.refunds.*', 'hospital.due-collections.*')) open @endif>
        <summary class="app-nav-link app-nav-group-toggle{{ request()->routeIs('hospital.appointments.*', 'hospital.consultations.*', 'hospital.patient-investigations.*', 'hospital.delivery-investigations.*', 'hospital.patient-operations.*', 'hospital.commissions.*', 'hospital.refunds.*', 'hospital.due-collections.*') ? ' active' : '' }}" title="Transactions" aria-label="Transactions">
            <span class="app-nav-icon"><i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Transactions</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @if ($moduleMenu['permissions']['appointments.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.appointments.*') ? ' active' : '' }}" href="{{ route('hospital.appointments.index') }}" title="Appointments" @if (request()->routeIs('hospital.appointments.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Appointments</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['consultations.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.consultations.*') ? ' active' : '' }}" href="{{ route('hospital.consultations.index') }}" title="Consultations" @if (request()->routeIs('hospital.consultations.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-notes-medical" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Consultations</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['patient-investigations.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.patient-investigations.*') ? ' active' : '' }}" href="{{ route('hospital.patient-investigations.index') }}" title="Patient Investigations" @if (request()->routeIs('hospital.patient-investigations.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-eye" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Patient Investigations</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['delivery-investigations.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.delivery-investigations.*') ? ' active' : '' }}" href="{{ route('hospital.delivery-investigations.index') }}" title="Delivery Investigations" @if (request()->routeIs('hospital.delivery-investigations.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-file-circle-check" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Delivery Investigations</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['patient-operations.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.patient-operations.*') ? ' active' : '' }}" href="{{ route('hospital.patient-operations.index') }}" title="Patient Operations" @if (request()->routeIs('hospital.patient-operations.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-stethoscope" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Patient Operations</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['commissions.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.commissions.*') ? ' active' : '' }}" href="{{ route('hospital.commissions.index') }}" title="Commission" @if (request()->routeIs('hospital.commissions.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-percent" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Commission</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['due-collections.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.due-collections.*') ? ' active' : '' }}" href="{{ route('hospital.due-collections.index') }}" title="Due Collections" @if (request()->routeIs('hospital.due-collections.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-hand-holding-dollar" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Due Collections</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['refunds.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.refunds.*') ? ' active' : '' }}" href="{{ route('hospital.refunds.index') }}" title="Refunds" @if (request()->routeIs('hospital.refunds.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-money-bill-transfer" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Refunds</span>
                </a>
            @endif
        </div>
    </details>
@endif
@if (($moduleMenu['permissions']['patients.view'] ?? false)
    || ($moduleMenu['permissions']['references.view'] ?? false)
    || ($moduleMenu['permissions']['doctors.view'] ?? false)
    || ($moduleMenu['permissions']['doctor-permissions.view'] ?? false))
    <details class="app-nav-group" @if (request()->routeIs('hospital.patients.*', 'hospital.references.*', 'hospital.doctors.*')) open @endif>
        <summary class="app-nav-link app-nav-group-toggle{{ request()->routeIs('hospital.patients.*', 'hospital.references.*', 'hospital.doctors.*') ? ' active' : '' }}" title="People" aria-label="People">
            <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
            <span class="app-nav-copy">People</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @if ($moduleMenu['permissions']['patients.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.patients.*') ? ' active' : '' }}" href="{{ route('hospital.patients.index') }}" title="Patients" @if (request()->routeIs('hospital.patients.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-hospital-user" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Patients</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['references.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.references.*') ? ' active' : '' }}" href="{{ route('hospital.references.index') }}" title="References" @if (request()->routeIs('hospital.references.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-user-group" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">References</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['doctors.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.doctors.*') && ! request()->routeIs('hospital.doctors.permissions*') ? ' active' : '' }}" href="{{ route('hospital.doctors.index') }}" title="Doctors" @if (request()->routeIs('hospital.doctors.*') && ! request()->routeIs('hospital.doctors.permissions*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-user-doctor" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Doctors</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['doctor-permissions.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.doctors.permissions', 'hospital.doctors.permissions-update') ? ' active' : '' }}" href="{{ route('hospital.doctors.permissions') }}" title="Doctor Permissions" @if (request()->routeIs('hospital.doctors.permissions', 'hospital.doctors.permissions-update')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-key" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Doctor Permissions</span>
                </a>
            @endif
        </div>
    </details>
@endif
@if (($moduleMenu['permissions']['departments.view'] ?? false)
    || ($moduleMenu['permissions']['investigation-categories.view'] ?? false)
    || ($moduleMenu['permissions']['investigations.view'] ?? false)
    || ($moduleMenu['permissions']['operation-categories.view'] ?? false)
    || ($moduleMenu['permissions']['operations.view'] ?? false)
    || ($moduleMenu['permissions']['refund-causes.view'] ?? false))
    <details class="app-nav-group" @if (request()->routeIs('hospital.departments.*', 'hospital.investigation-categories.*', 'hospital.investigations.*', 'hospital.operation-categories.*', 'hospital.operations.*', 'hospital.refund-causes.*')) open @endif>
        <summary class="app-nav-link app-nav-group-toggle{{ request()->routeIs('hospital.departments.*', 'hospital.investigation-categories.*', 'hospital.investigations.*', 'hospital.operation-categories.*', 'hospital.operations.*', 'hospital.refund-causes.*') ? ' active' : '' }}" title="Setup" aria-label="Setup">
            <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
            <span class="app-nav-copy">Setup</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @if ($moduleMenu['permissions']['departments.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.departments.*') ? ' active' : '' }}" href="{{ route('hospital.departments.index') }}" title="Doctor Departments" @if (request()->routeIs('hospital.departments.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-user-doctor" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Doctor Departments</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['investigation-categories.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.investigation-categories.*') ? ' active' : '' }}" href="{{ route('hospital.investigation-categories.index') }}" title="Investigation Categories" @if (request()->routeIs('hospital.investigation-categories.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-flask" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Investigation Categories</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['investigations.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.investigations.*') ? ' active' : '' }}" href="{{ route('hospital.investigations.index') }}" title="Investigations" @if (request()->routeIs('hospital.investigations.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-eye" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Investigations</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['operation-categories.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.operation-categories.*') ? ' active' : '' }}" href="{{ route('hospital.operation-categories.index') }}" title="Operation Categories" @if (request()->routeIs('hospital.operation-categories.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-tags" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Operation Categories</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['operations.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.operations.*') ? ' active' : '' }}" href="{{ route('hospital.operations.index') }}" title="Operations" @if (request()->routeIs('hospital.operations.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-stethoscope" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Operations</span>
                </a>
            @endif
            @if ($moduleMenu['permissions']['refund-causes.view'] ?? false)
                <a class="app-nav-link{{ request()->routeIs('hospital.refund-causes.*') ? ' active' : '' }}" href="{{ route('hospital.refund-causes.index') }}" title="Refund Causes" @if (request()->routeIs('hospital.refund-causes.*')) aria-current="page" @endif>
                    <span class="app-nav-icon"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i></span>
                    <span class="app-nav-copy">Refund Causes</span>
                </a>
            @endif
        </div>
    </details>
@endif
