@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <header class="app-selection-intro">
        <div>
            <h1>Choose your branch</h1>
            <p>Select a branch to continue working.</p>
        </div>
        <span class="app-selection-count"><strong>{{ number_format($records->total()) }}</strong><span>{{ $records->total() === 1 ? 'branch' : 'branches' }}</span></span>
    </header>

    <form class="app-selection-search" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Find a branch">
        <div class="app-selection-search-field">
            <label class="visually-hidden" for="branch-search">Search branches</label>
            <div class="app-input-wrap app-search-field">
                <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                <input class="{{ inputClass('search') }}" id="branch-search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="Search by branch name..." {{ inputAttrs('search', 'branch-search') }}>
            </div>
            @error('search')<div class="invalid-feedback d-block" id="branch-search-error">{{ $message }}</div>@enderror
        </div>
        @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
    </form>

    @if ($records->isEmpty())
        <section class="app-card">
            @include('core::partials.empty', ['title' => 'No matching branches', 'description' => 'Try another branch name or clear the filters.', 'icon' => 'location-dot'])
        </section>
    @else
        <div class="app-selection-grid">
            @foreach ($records as $branch)
                @php($isActive = $activeBranchId !== null && (string) $activeBranchId === (string) $branch->id)
                <form class="app-selection-choice" action="{{ route('tenantcore.branch-dashboard.store', $branch->id) }}" method="POST">
                    @csrf
                    <button class="app-selection-card{{ $isActive ? ' active' : '' }}" type="submit" aria-label="Open {{ $branch->name }} workspace" aria-describedby="branch-address-{{ $branch->id }}" @if ($isActive) aria-current="true" @endif>
                        <span class="app-selection-card-content">
                            <span class="app-selection-card-head">
                                <span class="app-selection-mark">
                                    @if ($branch->logo_path)
                                        <img src="{{ $branch->logo_url }}" width="44" height="44" alt="">
                                    @else
                                        <i class="fa-solid fa-building" aria-hidden="true"></i>
                                    @endif
                                </span>
                                <span class="app-selection-name">{{ $branch->name }}</span>
                                @if ($isActive)
                                    <span class="app-selection-active" title="Active branch"><i class="fa-solid fa-check" aria-hidden="true"></i><span class="visually-hidden">Active branch</span></span>
                                @endif
                            </span>
                            <span class="app-selection-description" id="branch-address-{{ $branch->id }}">{{ $branch->address }}</span>
                            @if ($branch->mobile_no || $branch->telephone_no || $branch->email)
                                <span class="app-selection-contacts">
                                    @if ($branch->mobile_no || $branch->telephone_no)
                                        <span><i class="fa-solid fa-phone" aria-hidden="true"></i><span>{{ $branch->mobile_no ?: $branch->telephone_no }}@if ($branch->mobile_no && $branch->telephone_no) &middot; {{ $branch->telephone_no }}@endif</span></span>
                                    @endif
                                    @if ($branch->email)
                                        <span><i class="fa-solid fa-envelope" aria-hidden="true"></i><span>{{ $branch->email }}</span></span>
                                    @endif
                                </span>
                            @endif
                        </span>
                        <span class="app-selection-open"><span>Open</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
                    </button>
                </form>
            @endforeach
        </div>
    @endif

    <div class="app-card app-selection-pagination mt-4 overflow-hidden">
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </div>
@endsection
