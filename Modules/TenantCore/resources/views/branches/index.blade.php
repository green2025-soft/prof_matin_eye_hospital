@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Manage branch locations and contact details.',
        'create' => ['model' => \Modules\TenantCore\Models\Branch::class, 'url' => route($routePrefix.'.create'), 'label' => 'New branch'],
    ])
    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-building text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Branch directory</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter branches">
            <div class="row g-2 align-items-end">
                <div class="col-md-8 col-xl-7">
                    <label class="form-label small" for="search">Search branches</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input
                            class="{{ inputClass('search') }}"
                            id="search"
                            name="search"
                            type="search"
                            value="{{ getValue('search', null, $filters['search'] ?? '') }}"
                            placeholder="Branch name starts with..."
                            maxlength="80"
                            {{ inputAttrs('search') }}
                        >
                    </div>
                    @error('search')
                        <div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 col-xl-5">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>
        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No branches found', 'description' => 'Try another branch name or clear the filters.', 'icon' => 'building'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Branches, addresses, and contact details</caption>
                    <thead>
                        <tr>
                            <th scope="col">Branch</th>
                            <th scope="col">Address</th>
                            <th scope="col">Contact</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $branch)
                            <tr>
                                <th scope="row">
                                    <div class="app-table-person d-flex align-items-center gap-3">
                                        @if ($branch->logo_path)
                                            <img class="app-brand-logo" src="{{ $branch->logo_url }}" alt="" width="36" height="36">
                                        @else
                                            <span class="app-table-avatar" aria-hidden="true">
                                                <i class="fa-solid fa-building"></i>
                                            </span>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $branch->name }}</div>
                                            <div class="small text-body-secondary fw-normal">
                                                {{ $branch->created_at?->format('d M Y') ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <td class="small text-body-secondary text-break">{{ $branch->address }}</td>
                                <td class="small text-break">
                                    @if ($branch->mobile_no)
                                        <div><span class="text-body-secondary">Mobile:</span> {{ $branch->mobile_no }}</div>
                                    @endif
                                    @if ($branch->telephone_no)
                                        <div><span class="text-body-secondary">Telephone:</span> {{ $branch->telephone_no }}</div>
                                    @endif
                                    @if ($branch->email)
                                        <div>{{ $branch->email }}</div>
                                    @endif
                                    @if (! $branch->mobile_no && ! $branch->telephone_no && ! $branch->email)
                                        <span class="text-body-secondary">Not provided</span>
                                    @endif
                                </td>
                                <td>
                                    @include('core::partials.row-actions', ['model' => $branch, 'routePrefix' => $routePrefix])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
