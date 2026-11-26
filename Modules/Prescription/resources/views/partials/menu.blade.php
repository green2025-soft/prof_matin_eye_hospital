<p class="app-nav-label">Prescription</p>
@if (($moduleMenu['permissions']['prescriptions.view'] ?? false) && $moduleMenu['settings']['general_enabled'])
    <a class="app-nav-link{{ request()->routeIs('prescription.prescriptions.*') ? ' active' : '' }}" href="{{ route('prescription.prescriptions.index') }}">
        <span class="app-nav-icon"><i class="fa-solid fa-prescription" aria-hidden="true"></i></span>
        <span class="app-nav-copy">General Prescription</span>
    </a>
@endif
@if (($moduleMenu['permissions']['glass-prescriptions.view'] ?? false) && $moduleMenu['settings']['glass_enabled'])
    <a class="app-nav-link{{ request()->routeIs('prescription.glass-prescriptions.*') ? ' active' : '' }}" href="{{ route('prescription.glass-prescriptions.index') }}">
        <span class="app-nav-icon"><i class="fa-solid fa-glasses" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Glass Prescription</span>
    </a>
@endif
@if ($moduleMenu['permissions']['prescription-options.view'] ?? false)
    <a class="app-nav-link{{ request()->routeIs('prescription.options.*') ? ' active' : '' }}" href="{{ route('prescription.options.index') }}">
        <span class="app-nav-icon"><i class="fa-solid fa-list-check" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Prescription Content</span>
    </a>
@endif
@if ($moduleMenu['permissions']['prescription-dose.view'] ?? false)
    <a class="app-nav-link{{ request()->routeIs('prescription.prescription-dose.*') ? ' active' : '' }}"
        href="{{ route('prescription.prescription-dose.index') }}">
        <span class="app-nav-icon"><i class="fa-solid fa-pills" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Create Dose</span>
    </a>
@endif


@include('pharmacy::partials.catalog-menu', [
    'catalogType'   => 'medicine',
    'catalogTitle'  => 'Medicine Catalog',
    'catalogLabel'  => 'Medicine/Product',
    'productLabel'  => 'Medicine/Product',
    'productIcon'   => 'pills',
    'categoryLabel' => 'Generic/Category',
    'shelfLabel'    => 'Shelf List',
    'catalogParameters' => ['menu' => 'prescription'],
])

@if ($moduleMenu['permissions']['settings.view'] ?? false)
    <a class="app-nav-link{{ request()->routeIs('prescription.settings.*') ? ' active' : '' }}" href="{{ route('prescription.settings.index') }}">
        <span class="app-nav-icon"><i class="fa-solid fa-sliders" aria-hidden="true"></i></span>
        <span class="app-nav-copy">Settings</span>
    </a>
@endif

