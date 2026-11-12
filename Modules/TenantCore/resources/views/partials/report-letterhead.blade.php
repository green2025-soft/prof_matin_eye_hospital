<header class="app-report-letterhead d-flex flex-column flex-sm-row align-items-center justify-content-center gap-2 gap-sm-3 text-center">
    @isset($reportBrand['logo_url'])
        <img class="app-report-logo flex-shrink-0 object-fit-contain" src="{{ $reportBrand['logo_url'] }}" width="76" height="76" alt="">
    @endisset
    <div class="app-report-brand">
        <h2>{{ $reportBrand['name'] }}</h2>
        <p>{{ $reportBrand['tagline'] }}</p>
        <p>{{ $reportBrand['address'] }}</p>
        <p>{{ $reportBrand['contact'] }}</p>
        @if (filled($reportBrand['branch_name']))
            <p class="fw-semibold">{{ $reportBrand['branch_name'] }}</p>
        @endif
    </div>
</header>
